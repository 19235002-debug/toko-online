<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Barang;
use App\Models\BarangVariasi;
use App\Models\Voucher;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of orders in admin panel.
     */
    public function index(Request $request)
    {
        // Otomatis sinkronkan pesanan Midtrans yang pending di latar belakang tanpa perlu klik manual
        self::syncPendingMidtransOrders(15);

        $status = $request->query('status');
        $search = trim((string) $request->query('search', ''));
        $metode = $request->query('metode', 'all');

        $query = Order::with('items')->orderBy('id_order', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($metode && $metode !== 'all') {
            $query->where('metode_pembayaran', $metode);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                  ->orWhere('nama_customer', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $orders = $query->get();

        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'diproses' => Order::where('status', 'diproses')->count(),
            'dikirim' => Order::where('status', 'dikirim')->count(),
            'selesai' => Order::where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('status', 'dibatalkan')->count(),
        ];

        return view('order.order', compact('orders', 'counts', 'status', 'search', 'metode'));
    }

    /**
     * Display the specified order detail.
     */
    public function show($id)
    {
        $order = Order::with('items.barang')->where('id_order', $id)->firstOrFail();

        // Otomatis sinkronkan status dengan Midtrans jika status masih pending
        if ($order->status === 'pending' && $order->metode_pembayaran === 'midtrans') {
            self::syncOrderStatusWithMidtrans($order);
            $order->refresh();
        }

        return view('order.order-detail', compact('order'));
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('id_order', $id)->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai,dibatalkan',
            'ekspedisi' => 'nullable|string|max:50',
            'no_resi' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        // Simpan data ekspedisi & nomor resi pengiriman
        $order->ekspedisi = $request->input('ekspedisi');
        $order->no_resi = $request->input('no_resi');

        // Jika pesanan dibatalkan dari status non-dibatalkan, kembalikan stok barang, variasi, & kuota voucher
        if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
            self::restoreOrderStockAndVoucher($order);
        }

        $order->status = $newStatus;
        $order->save();

        $msg = 'Status pesanan ' . $order->kode_order . ' berhasil diperbarui menjadi: ' . ucfirst($newStatus);
        if ($newStatus === 'dikirim' && $order->no_resi) {
            $msg .= ' (Resi: ' . $order->no_resi . ' - ' . ($order->ekspedisi ?? 'Ekspedisi') . ')';
        }

        Alert::success('Sukses', $msg);
        return redirect()->route('order.show', $id);
    }

    /**
     * Print invoice.
     */
    public function printInvoice($id)
    {
        $order = Order::with('items.barang')->where('id_order', $id)->firstOrFail();

        return view('order.print-invoice', compact('order'));
    }

    /**
     * Otomatis sinkronkan pesanan Midtrans yang pending (batch).
     */
    public static function syncPendingMidtransOrders($limit = 15)
    {
        try {
            $pendingOrders = Order::with('items')
                ->where('status', 'pending')
                ->where('metode_pembayaran', 'midtrans')
                ->where('created_at', '>=', now()->subDays(3))
                ->latest('id_order')
                ->take($limit)
                ->get();

            foreach ($pendingOrders as $order) {
                self::syncOrderStatusWithMidtrans($order);
            }
        } catch (Exception $e) {
            // Abaikan agar halaman tetap dapat dimuat dengan lancar
        }
    }

    /**
     * Helper untuk menyelaraskan status 1 order dengan Midtrans API.
     */
    public static function syncOrderStatusWithMidtrans(Order $order)
    {
        if ($order->metode_pembayaran !== 'midtrans' || !in_array($order->status, ['pending'])) {
            return false;
        }

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production', false);

        try {
            $status = \Midtrans\Transaction::status($order->kode_order);
            $transactionStatus = is_object($status) ? ($status->transaction_status ?? null) : ($status['transaction_status'] ?? null);
            $fraudStatus = is_object($status) ? ($status->fraud_status ?? null) : ($status['fraud_status'] ?? null);

            $oldStatus = $order->status;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $order->status = 'diproses';
                }
            } else if ($transactionStatus == 'settlement') {
                $order->status = 'diproses';
            } else if ($transactionStatus == 'pending') {
                $order->status = 'pending';
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->status = 'dibatalkan';
                if ($oldStatus !== 'dibatalkan') {
                    self::restoreOrderStockAndVoucher($order);
                }
            }

            if ($order->status !== $oldStatus) {
                $order->save();
                return true;
            }
        } catch (Exception $e) {
            // Abaikan jika order belum terdaftar di Midtrans
        }

        return false;
    }

    /**
     * Cek status transaksi langsung ke API Midtrans secara manual (opsional).
     */
    public function checkMidtransStatus($id)
    {
        $order = Order::with('items')->where('id_order', $id)->firstOrFail();
        $updated = self::syncOrderStatusWithMidtrans($order);
        $order->refresh();

        if ($updated) {
            Alert::success('Berhasil', 'Status pesanan ' . $order->kode_order . ' telah berhasil disinkronkan menjadi: ' . ucfirst($order->status));
        } else {
            Alert::info('Info Status', 'Status pesanan saat ini masih: ' . ucfirst($order->status));
        }

        return redirect()->route('order.show', $id);
    }

    /**
     * Remove the specified order.
     */
    public function destroy($id)
    {
        try {
            $order = Order::where('id_order', $id)->firstOrFail();
            $kode = $order->kode_order;
            $order->delete();

            Alert::success('Sukses', 'Pesanan ' . $kode . ' berhasil dihapus!');
            return redirect('/order');
        } catch (Exception $e) {
            Alert::error('Gagal', 'Pesanan gagal dihapus: ' . $e->getMessage());
            return redirect('/order');
        }
    }

    /**
     * Kembalikan stok produk induk, stok variasi spesifik, dan kuota voucher saat pesanan dibatalkan.
     */
    public static function restoreOrderStockAndVoucher(Order $order)
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            $qty = intval($item->qty);
            if ($qty <= 0) continue;

            if ($item->barang_id) {
                $barang = Barang::find($item->barang_id);
                if ($barang) {
                    $barang->stok = intval($barang->stok) + $qty;
                    $barang->save();
                }
            }

            if (!empty($item->variasi_id)) {
                $variasi = BarangVariasi::find($item->variasi_id);
                if ($variasi) {
                    $variasi->stok = intval($variasi->stok) + $qty;
                    $variasi->save();
                }
            }
        }

        if (!empty($order->kode_voucher)) {
            $voucher = Voucher::where('kode_voucher', $order->kode_voucher)->first();
            if ($voucher && intval($voucher->digunakan) > 0) {
                $voucher->digunakan = intval($voucher->digunakan) - 1;
                $voucher->save();
            }
        }
    }
}
