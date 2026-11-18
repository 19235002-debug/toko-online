<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Banner;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Otomatis sinkronkan transaksi Midtrans yang masih pending agar ringkasan statistik akurat
        OrderController::syncPendingMidtransOrders(10);

        $barang = Barang::count();
        $user = User::count();
        $banner = Banner::count();
        $order = Order::count();
        $order_pending = Order::where('status', 'pending')->count();
        $pendapatan = Order::whereIn('status', ['diproses', 'dikirim', 'selesai'])->sum('total_harga');
        $recent_orders = Order::with('items')->latest('id_order')->take(5)->get();

        // Data Grafik Penjualan 7 Hari Terakhir
        $salesChartLabels = [];
        $salesChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $salesChartLabels[] = $date->format('d M');
            $dailyTotal = Order::whereIn('status', ['diproses', 'dikirim', 'selesai'])
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_harga');
            $salesChartData[] = intval($dailyTotal);
        }

        // Top 5 Produk Terlaris
        $topProducts = OrderItem::select('nama_barang', 'barang_id', DB::raw('SUM(qty) as total_terjual'), DB::raw('SUM(subtotal) as total_omzet'))
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['diproses', 'dikirim', 'selesai']);
            })
            ->groupBy('nama_barang', 'barang_id')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        // Peringatan Stok Menipis (<= 5 unit)
        $lowStockProducts = Barang::whereRaw('CAST(stok AS SIGNED) <= 5')
            ->orderByRaw('CAST(stok AS SIGNED) ASC')
            ->take(6)
            ->get();

        return view('dashboard.dashboard', compact(
            'barang',
            'user',
            'banner',
            'order',
            'order_pending',
            'pendapatan',
            'recent_orders',
            'salesChartLabels',
            'salesChartData',
            'topProducts',
            'lowStockProducts'
        ));
    }
}
