<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Barang;
use App\Models\BarangVariasi;
use App\Models\Voucher;
use App\Models\Setting;
use App\Services\BiteshipService;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of orders in admin panel.
     */
    public function index(Request $request)
    {
        // Batalkan pesanan belum dibayar yang melewati batas 3 hari
        self::cancelUnpaidOrders(3);

        // Throttle sinkronisasi otomatis Midtrans & Biteship/resi (15 menit) agar perpindahan halaman termuat instan (<50ms)
        if (!Cache::has('auto_sync_external_orders_throttle')) {
            Cache::put('auto_sync_external_orders_throttle', true, now()->addMinutes(15));
            self::syncPendingMidtransOrders(5);
            self::syncShippedOrdersWithRajaOngkir(5);
        }

        $status = $request->query('status');
        $search = trim((string) $request->query('search', ''));
        $metode = $request->query('metode', 'all');
        $periode = $request->query('periode');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Jika user melakukan pencarian kata kunci dan tidak menentukan periode, default ke 'all'
        // Sebaliknya jika membuka halaman pesanan pertama kali, default ke 'today' (Hari Ini)
        // sesuai SOP toko: pengiriman diusahakan 1 hari full, maksimal 2 hari saat pesanan ramai.
        if (empty($periode)) {
            $periode = ($search !== '') ? 'all' : 'today';
        }

        $now = Carbon::now();
        $dateRange = null;

        if ($periode === 'today') {
            $dateRange = [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
        } elseif ($periode === '2days') {
            // 2 Hari Terakhir (Kemarin & Hari ini) - batas toleransi maksimal pengiriman saat ramai
            $dateRange = [$now->copy()->subDays(1)->startOfDay(), $now->copy()->endOfDay()];
        } elseif ($periode === 'week') {
            $dateRange = [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()];
        } elseif ($periode === 'month') {
            $dateRange = [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
        } elseif ($periode === 'custom' && $startDate && $endDate) {
            try {
                $dateRange = [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()];
            } catch (Exception $e) {
                $dateRange = null;
            }
        }

        $query = Order::with('items');

        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        if ($status && $status !== 'all') {
            if ($status === 'perlu_tindakan' || $status === 'unfulfilled') {
                $query->whereIn('status', ['diproses', 'pending']);
            } elseif ($status === 'belum_kirim') {
                $query->where('status', 'diproses');
            } elseif ($status === 'belum_bayar') {
                $query->where('status', 'pending');
            } else {
                $query->where('status', $status);
            }
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

        // Prioritas Tampilan Utama:
        // 1. 'diproses' (Barang siap dikemas & belum dikirim - prioritas operasional packing/kirim)
        // 2. 'pending' (Pesanan belum dibayar customer - prioritas monitoring pembayaran)
        // 3. 'dikirim' (Pesanan sedang dalam perjalanan kurir)
        // 4. 'selesai' (Pesanan telah diterima customer)
        // 5. 'dibatalkan' (Pesanan dibatalkan / kedaluwarsa)
        // Dalam setiap kelompok status, pesanan diurutkan dari yang terbaru (id_order DESC)
        $query->orderByRaw("
            CASE 
                WHEN status = 'diproses' THEN 1
                WHEN status = 'pending' THEN 2
                WHEN status = 'dikirim' THEN 3
                WHEN status = 'selesai' THEN 4
                WHEN status = 'dibatalkan' THEN 5
                ELSE 6
            END ASC
        ")->orderBy('id_order', 'desc');

        $orders = $query->get();

        // Hitung total pesanan pada masing-masing periode untuk badge tabs
        $periodCounts = [
            'today' => Order::whereBetween('created_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])->count(),
            '2days' => Order::whereBetween('created_at', [$now->copy()->subDays(1)->startOfDay(), $now->copy()->endOfDay()])->count(),
            'week' => Order::whereBetween('created_at', [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()])->count(),
            'month' => Order::whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->count(),
            'all' => Order::count(),
        ];

        // Sinkronkan status KPI card dengan filter periode & metode yang aktif
        $baseStatusQuery = Order::query();
        if ($dateRange) {
            $baseStatusQuery->whereBetween('created_at', $dateRange);
        }
        if ($metode && $metode !== 'all') {
            $baseStatusQuery->where('metode_pembayaran', $metode);
        }

        $counts = [
            'all' => (clone $baseStatusQuery)->count(),
            'perlu_tindakan' => (clone $baseStatusQuery)->whereIn('status', ['diproses', 'pending'])->count(),
            'diproses' => (clone $baseStatusQuery)->where('status', 'diproses')->count(),
            'pending' => (clone $baseStatusQuery)->where('status', 'pending')->count(),
            'dikirim' => (clone $baseStatusQuery)->where('status', 'dikirim')->count(),
            'selesai' => (clone $baseStatusQuery)->where('status', 'selesai')->count(),
            'dibatalkan' => (clone $baseStatusQuery)->where('status', 'dibatalkan')->count(),
        ];

        $biteshipWallet = BiteshipService::getAccountBalance();

        return view('order.order', compact(
            'orders',
            'counts',
            'status',
            'search',
            'metode',
            'periode',
            'periodCounts',
            'startDate',
            'endDate',
            'biteshipWallet'
        ));
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

        // Otomatis selesaikan pesanan jika resi RajaOngkir sudah berstatus delivered
        if ($order->status === 'dikirim' && !empty($order->no_resi)) {
            self::syncOrderWithRajaOngkir($order);
            $order->refresh();
        }

        return view('order.order-detail', compact('order'));
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items')->where('id_order', $id)->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai,dibatalkan',
            'ekspedisi' => 'nullable|string|max:50',
            'no_resi' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->input('status');
        $noResi = trim((string) $request->input('no_resi', ''));
        $ekspedisi = trim((string) $request->input('ekspedisi', '')) ?: $order->ekspedisi;

        // 1. Validasi alur status (tidak boleh lompat / mundur)
        if (!$order->canTransitionTo($newStatus)) {
            Alert::error('Status Tidak Valid', 'Status "' . ucfirst($oldStatus) . '" tidak dapat diubah menjadi "' . ucfirst($newStatus) . '".');
            return redirect()->route('order.show', $id);
        }

        // 2. Pesanan Midtrans wajib lunas sebelum diproses
        if ($newStatus === 'diproses' && $oldStatus === 'pending' && $order->metode_pembayaran === 'midtrans' && !$order->paid_at) {
            self::syncOrderStatusWithMidtrans($order);
            $order->refresh();
            if ($order->status !== 'diproses') {
                Alert::warning('Belum Dibayar', 'Pesanan ' . $order->kode_order . ' belum dibayar melalui Midtrans, sehingga belum dapat diproses.');
                return redirect()->route('order.show', $id);
            }
            Alert::success('Sukses', 'Pembayaran terverifikasi Midtrans. Pesanan kini berstatus Diproses.');
            return redirect()->route('order.show', $id);
        }

        // 3. Pengiriman wajib menyertakan nomor resi
        if ($newStatus === 'dikirim' && $noResi === '') {
            Alert::warning('Nomor Resi Wajib', 'Masukkan nomor resi pengiriman sebelum mengubah status menjadi Dikirim.');
            return redirect()->route('order.show', $id);
        }

        $order->ekspedisi = $ekspedisi;
        if ($noResi !== '' || in_array($newStatus, ['dikirim', 'selesai'], true)) {
            $order->no_resi = $noResi !== '' ? $noResi : $order->no_resi;
        }

        if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
            self::restoreOrderStockAndVoucher($order);
            $order->cancelled_at = now();
        }
        if ($newStatus === 'dikirim' && $oldStatus !== 'dikirim') {
            $order->shipped_at = now();
        }
        if ($newStatus === 'selesai' && $oldStatus !== 'selesai') {
            $order->completed_at = now();
            if ($order->metode_pembayaran === 'cod' && !$order->paid_at) {
                $order->paid_at = now(); // COD dianggap lunas saat paket diterima
            }
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
     * Batalkan otomatis pesanan yang belum dibayar dalam batas waktu tertentu (default 3 hari).
     * Mengembalikan stok produk, variasi, dan kuota voucher.
     */
    public static function cancelUnpaidOrders(int $days = 3, bool $includeCod = false): int
    {
        try {
            $expiredThreshold = now()->subDays($days);
            $query = Order::with('items')
                ->where('status', 'pending')
                ->whereNull('paid_at')
                ->where('created_at', '<=', $expiredThreshold);

            if (!$includeCod) {
                $query->where(function ($q) {
                    $q->whereNull('metode_pembayaran')
                      ->orWhere('metode_pembayaran', '!=', 'cod');
                });
            }

            $expiredOrders = $query->get();
            $cancelledCount = 0;

            foreach ($expiredOrders as $order) {
                \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                    $order->status = 'dibatalkan';
                    $order->cancelled_at = now();
                    $note = ' [Dibatalkan otomatis: tidak ada pembayaran dalam 3 hari]';
                    $order->catatan = trim(($order->catatan ?? '') . $note);
                    $order->save();

                    self::restoreOrderStockAndVoucher($order);
                });

                if ($order->metode_pembayaran === 'midtrans' && !empty($order->snap_token)) {
                    try {
                        \Midtrans\Config::$serverKey = config('midtrans.server_key');
                        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production', false);
                        \Midtrans\Transaction::cancel($order->midtrans_order_id ?: $order->kode_order);
                    } catch (\Throwable $e) {
                        // Abaikan jika order belum terdaftar atau sudah dibatalkan
                    }
                }

                $cancelledCount++;
            }

            return $cancelledCount;
        } catch (\Throwable $e) {
            return 0;
        }
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
            $midtransTarget = $order->midtrans_order_id ?: $order->kode_order;
            $status = \Midtrans\Transaction::status($midtransTarget);
            $get = fn($key) => is_object($status) ? ($status->{$key} ?? null) : ($status[$key] ?? null);

            return self::applyMidtransStatus($order, $get('transaction_status'), $get('fraud_status'), $get('payment_type'));
        } catch (Exception $e) {
            // Abaikan jika order belum terdaftar di Midtrans
        }

        return false;
    }

    /**
     * Terapkan status transaksi Midtrans ke pesanan (dipakai webhook & sinkronisasi manual).
     * Mengembalikan true jika status pesanan berubah.
     */
    public static function applyMidtransStatus(Order $order, ?string $transactionStatus, ?string $fraudStatus = null, ?string $paymentType = null): bool
    {
        // Pesanan yang sudah lewat tahap pembayaran tidak boleh diubah oleh notifikasi Midtrans yang terlambat
        if (!in_array($order->status, ['pending'], true)) {
            return false;
        }

        $oldStatus = $order->status;
        $paid = ($transactionStatus === 'settlement')
            || ($transactionStatus === 'capture' && $fraudStatus !== 'challenge');

        if ($paid) {
            $order->status = 'diproses';
            $order->paid_at = $order->paid_at ?: now();
            if ($paymentType) {
                $order->payment_type = $paymentType;
            }
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'], true)) {
            $order->status = 'dibatalkan';
            $order->cancelled_at = now();
            self::restoreOrderStockAndVoucher($order);
        }

        if ($order->status !== $oldStatus) {
            $order->save();
            return true;
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
     * Request Pick-up otomatis & terbitkan resi resmi via Biteship API.
     */
    public function requestBiteshipPickup($id)
    {
        $order = Order::with(['items.barang', 'user'])->where('id_order', $id)->firstOrFail();

        if ($order->status === 'dibatalkan') {
            Alert::error('Gagal', 'Pesanan yang telah dibatalkan tidak dapat di-request pick-up.');
            return redirect()->back();
        }

        if ($order->metode_pembayaran === 'midtrans' && !$order->paid_at) {
            // Jika pesanan masih berstatus pending, cek dulu ke Midtrans apakah sudah lunas
            if ($order->status === 'pending') {
                self::syncOrderStatusWithMidtrans($order);
                $order->refresh();
            }

            // Jika status pesanan sudah 'diproses' (atau admin telah memvalidasinya), tandai lunas otomatis
            if (in_array($order->status, ['diproses', 'dikirim', 'selesai'], true)) {
                if (!$order->paid_at) {
                    $order->paid_at = now();
                    $order->save();
                }
            } elseif (!$order->paid_at) {
                Alert::warning('Belum Lunas', 'Pesanan belum dibayar melalui Midtrans. Menunggu pembayaran pembeli.');
                return redirect()->back();
            }
        }

        $res = BiteshipService::createOrder($order);
        if ($res['success']) {
            $order->refresh();
            if ($order->status !== 'dikirim' && $order->status !== 'selesai') {
                $order->status = 'dikirim';
                $order->shipped_at = $order->shipped_at ?: now();
                $order->save();
            }
            Alert::success('Pick-up Berhasil', $res['message']);
        } else {
            Alert::error('Gagal Request Pick-up', $res['message'] ?? 'Terjadi kesalahan saat memproses pick-up Biteship.');
        }

        return redirect()->back();
    }

    /**
     * Cetak label pengiriman resmi (Thermal / Standard Shipping Label) berbarcode kurir.
     */
    public function printShippingLabel($id)
    {
        $order = Order::with(['items.barang', 'user'])->where('id_order', $id)->firstOrFail();
        $setting = Setting::first();
        return view('order.shipping-label', compact('order', 'setting'));
    }

    /**
     * Webhook Real-time dari Biteship (POST /api/biteship/webhook).
     */
    public function biteshipWebhook(Request $request)
    {
        $result = BiteshipService::handleWebhook($request->all());
        return response()->json($result);
    }

    /**
     * Sinkronkan otomatis pesanan berstatus 'dikirim' dengan status pelacakan resi Biteship / kurir.
     * Jika kurir melaporkan paket sudah diterima (delivered), ubah status menjadi 'selesai'.
     */
    public static function syncShippedOrdersWithBiteship($limit = 10): int
    {
        $completedCount = 0;
        try {
            $shippedOrders = Order::where('status', 'dikirim')
                ->whereNotNull('no_resi')
                ->where('no_resi', '!=', '')
                ->latest('id_order')
                ->take($limit)
                ->get();

            foreach ($shippedOrders as $order) {
                if (self::syncOrderWithBiteship($order)) {
                    $completedCount++;
                }
            }
        } catch (Exception $e) {
            // Abaikan agar halaman tetap dimuat lancar saat limit API tercapai / offline
        }

        return $completedCount;
    }

    public static function syncShippedOrdersWithRajaOngkir($limit = 10): int
    {
        return self::syncShippedOrdersWithBiteship($limit);
    }

    public static function syncOrderWithBiteship(Order $order, ?array $tracking = null): bool
    {
        if ($order->status !== 'dikirim' || empty($order->no_resi)) {
            return false;
        }

        $courier = $order->kurir_kode ?: $order->ekspedisi;
        if (empty($courier)) {
            return false;
        }

        $tracking = $tracking ?? BiteshipService::trackWaybill($order->no_resi, $courier);
        if (!$tracking) {
            $tracking = RajaOngkirService::trackWaybill($order->no_resi, $courier);
        }

        if (!$tracking) {
            return false;
        }

        $isDelivered = !empty($tracking['delivered'])
            || strtoupper((string) ($tracking['status'] ?? '')) === 'DELIVERED';

        if (!$isDelivered) {
            return false;
        }

        $order->status = 'selesai';
        $order->completed_at = $order->completed_at ?: now();
        if ($order->metode_pembayaran === 'cod' && !$order->paid_at) {
            $order->paid_at = now();
        }
        $order->save();

        return true;
    }

    public static function syncOrderWithRajaOngkir(Order $order, ?array $tracking = null): bool
    {
        return self::syncOrderWithBiteship($order, $tracking);
    }

    /**
     * Tombol sinkronisasi massal resi kurir dari halaman daftar pesanan admin.
     */
    public function syncAllShippedOrders()
    {
        $updated = self::syncShippedOrdersWithBiteship(20);
        if ($updated > 0) {
            Alert::success('Sinkronisasi Selesai', $updated . ' pesanan otomatis diperbarui ke status Selesai berdasarkan resi Biteship.');
        } else {
            Alert::info('Info Resi', 'Belum ada paket berstatus Delivered baru dari kurir (atau data pelacakan belum terupdate).');
        }

        return redirect()->route('order.index');
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
