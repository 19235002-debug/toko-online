<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Halaman rekap laporan penjualan.
     */
    public function index(Request $request)
    {
        // Default rentang tanggal: awal bulan ini s/d hari ini
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tglSelesai = $request->input('tgl_selesai', Carbon::now()->toDateString());
        $status = $request->input('status', 'all');
        $metode = $request->input('metode', 'all');

        $query = Order::with('items.barang')
            ->whereDate('created_at', '>=', $tglMulai)
            ->whereDate('created_at', '<=', $tglSelesai)
            ->orderBy('id_order', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($metode !== 'all') {
            $query->where('metode_pembayaran', $metode);
        }

        $orders = $query->get();

        // Statistik Ringkasan
        $totalPesanan = $orders->count();
        $ordersPaid = $orders->whereIn('status', ['diproses', 'dikirim', 'selesai']);
        $totalOmset = $ordersPaid->sum('total_harga');
        $totalPending = $orders->where('status', 'pending')->count();
        $totalBatal = $orders->where('status', 'dibatalkan')->count();

        // Total pcs produk terjual dari pesanan yang tidak dibatalkan
        $totalProdukTerjual = $orders->where('status', '!=', 'dibatalkan')->flatMap->items->sum('qty');

        // Ringkasan per metode pembayaran
        $metodeSummary = [
            'midtrans' => $ordersPaid->where('metode_pembayaran', 'midtrans')->sum('total_harga'),
            'transfer' => $ordersPaid->where('metode_pembayaran', 'transfer')->sum('total_harga'),
            'cod' => $ordersPaid->where('metode_pembayaran', 'cod')->sum('total_harga'),
        ];

        return view('laporan.laporan', compact(
            'orders',
            'tglMulai',
            'tglSelesai',
            'status',
            'metode',
            'totalPesanan',
            'totalOmset',
            'totalPending',
            'totalBatal',
            'totalProdukTerjual',
            'metodeSummary'
        ));
    }

    /**
     * Cetak rekap laporan penjualan.
     */
    public function print(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tglSelesai = $request->input('tgl_selesai', Carbon::now()->toDateString());
        $status = $request->input('status', 'all');
        $metode = $request->input('metode', 'all');

        $query = Order::with('items')
            ->whereDate('created_at', '>=', $tglMulai)
            ->whereDate('created_at', '<=', $tglSelesai)
            ->orderBy('id_order', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($metode !== 'all') {
            $query->where('metode_pembayaran', $metode);
        }

        $orders = $query->get();
        $ordersPaid = $orders->whereIn('status', ['diproses', 'dikirim', 'selesai']);
        $totalOmset = $ordersPaid->sum('total_harga');
        $totalProdukTerjual = $orders->where('status', '!=', 'dibatalkan')->flatMap->items->sum('qty');

        return view('laporan.print-laporan', compact(
            'orders',
            'tglMulai',
            'tglSelesai',
            'status',
            'metode',
            'totalOmset',
            'totalProdukTerjual'
        ));
    }

    /**
     * Export rekap laporan penjualan ke file Excel (CSV UTF-8).
     */
    public function exportCsv(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tglSelesai = $request->input('tgl_selesai', Carbon::now()->toDateString());
        $status = $request->input('status', 'all');
        $metode = $request->input('metode', 'all');

        $query = Order::with('items')
            ->whereDate('created_at', '>=', $tglMulai)
            ->whereDate('created_at', '<=', $tglSelesai)
            ->orderBy('id_order', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($metode !== 'all') {
            $query->where('metode_pembayaran', $metode);
        }

        $orders = $query->get();
        $filename = 'laporan-penjualan-' . $tglMulai . '-sd-' . $tglSelesai . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'Kode Order',
                'Tanggal',
                'Pelanggan',
                'No HP',
                'Metode Pembayaran',
                'Kurir',
                'Ongkir',
                'Diskon Voucher',
                'Total Harga',
                'Status',
            ]);

            foreach ($orders as $o) {
                fputcsv($file, [
                    $o->kode_order,
                    $o->created_at ? $o->created_at->format('Y-m-d H:i') : '',
                    $o->nama_customer,
                    $o->no_hp,
                    strtoupper($o->metode_pembayaran),
                    $o->kurir ?? $o->ekspedisi ?? '-',
                    $o->ongkir ?? 0,
                    $o->diskon ?? 0,
                    $o->total_harga,
                    strtoupper($o->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
