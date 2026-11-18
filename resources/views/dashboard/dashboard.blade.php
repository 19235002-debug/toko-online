@extends('template.main')
@section('title', 'Dashboard')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-gauge-high mr-2"></i>@yield('title')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <!-- Box Pesanan Total -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary shadow-sm">
                        <div class="inner">
                            <h3>{{ $order }}</h3>
                            <p>Total Pesanan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ route('order.index') }}" class="small-box-footer">Semua Pesanan <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Box Pesanan Pending -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner">
                            <h3 class="text-white">{{ $order_pending }}</h3>
                            <p class="text-white">Pesanan Pending</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ route('order.index', ['status' => 'pending']) }}" class="small-box-footer" style="color: rgba(255,255,255,0.9);">Proses Sekarang <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Box Total Pendapatan -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success shadow-sm">
                        <div class="inner">
                            <h3 style="font-size: 1.5rem;">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                            <p>Pendapatan Toko</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <a href="{{ route('order.index', ['status' => 'selesai']) }}" class="small-box-footer">Pesanan Selesai <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Box Produk -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3>{{ $barang }}</h3>
                            <p>Total Produk</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <a href="/barang" class="small-box-footer">Kelola Produk <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Box Banner -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-purple shadow-sm" style="background-color: #6f42c1; color: #fff;">
                        <div class="inner">
                            <h3>{{ $banner }}</h3>
                            <p>Banner Slider</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-panorama"></i>
                        </div>
                        <a href="/banner" class="small-box-footer" style="color: rgba(255,255,255,0.85);">Kelola Banner <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Box Users -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary shadow-sm">
                        <div class="inner">
                            <h3>{{ $user }}</h3>
                            <p>Pengguna Terdaftar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="/user" class="small-box-footer">Kelola User <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Grafik Penjualan & Ringkasan Stok -->
            <div class="row mt-2">
                <div class="col-lg-8 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-header border-transparent">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-chart-area text-primary mr-1"></i> Grafik Penjualan (7 Hari Terakhir)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div style="height: 250px;">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <!-- Produk Terlaris -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header border-transparent py-2">
                            <h3 class="card-title font-weight-bold" style="font-size: 0.95rem;">
                                <i class="fas fa-fire text-danger mr-1"></i> Produk Terlaris
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($topProducts as $tp)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                    <div class="text-truncate pr-2" style="max-width: 190px; font-size: 0.85rem; font-weight: 600;">
                                        {{ $tp->nama_barang }}
                                    </div>
                                    <span class="badge badge-primary badge-pill">{{ $tp->total_terjual }} terjual</span>
                                </li>
                                @empty
                                <li class="list-group-item text-center text-muted small py-3">Belum ada data penjualan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Peringatan Stok Menipis -->
                    <div class="card shadow-sm">
                        <div class="card-header border-transparent py-2">
                            <h3 class="card-title font-weight-bold" style="font-size: 0.95rem;">
                                <i class="fas fa-exclamation-triangle text-warning mr-1"></i> Stok Menipis (&le; 5)
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($lowStockProducts as $ls)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                    <a href="{{ route('barang.edit', $ls->id_barang) }}" class="text-dark text-truncate pr-2" style="max-width: 190px; font-size: 0.85rem; font-weight: 600;">
                                        {{ $ls->nama }}
                                    </a>
                                    <span class="badge {{ intval($ls->stok) <= 0 ? 'badge-danger' : 'badge-warning' }} badge-pill">
                                        {{ intval($ls->stok) <= 0 ? 'Habis' : 'Sisa ' . $ls->stok }}
                                    </span>
                                </li>
                                @empty
                                <li class="list-group-item text-center text-success small py-3">
                                    <i class="fas fa-check-circle mr-1"></i> Semua stok produk aman.
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Terbaru Table -->
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header border-transparent">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-receipt mr-1"></i> Pesanan Terbaru Masuk
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('order.index') }}" class="btn btn-tool text-primary">
                                    Lihat Semua Pesanan <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0 table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Pelanggan</th>
                                            <th>Waktu</th>
                                            <th>Total</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recent_orders as $o)
                                        <tr>
                                            <td class="font-weight-bold">
                                                <a href="{{ route('order.show', $o->id_order) }}">{{ $o->kode_order }}</a>
                                            </td>
                                            <td>{{ $o->nama_customer }}</td>
                                            <td>{{ $o->created_at->diffForHumans() }}</td>
                                            <td class="font-weight-bold text-success">
                                                Rp {{ number_format($o->total_harga, 0, ',', '.') }}
                                            </td>
                                            <td><span class="badge badge-light border text-uppercase">{{ $o->metode_pembayaran }}</span></td>
                                            <td>
                                                @if($o->status == 'pending')
                                                    <span class="badge badge-warning text-white">Pending</span>
                                                @elseif($o->status == 'diproses')
                                                    <span class="badge badge-info">Diproses</span>
                                                @elseif($o->status == 'dikirim')
                                                    <span class="badge badge-primary">Dikirim</span>
                                                @elseif($o->status == 'selesai')
                                                    <span class="badge badge-success">Selesai</span>
                                                @else
                                                    <span class="badge badge-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('order.show', $o->id_order) }}" class="btn btn-sm btn-info btn-action text-white" title="Lihat">
                                                    <i class="fa fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-3x mb-2 d-block"></i>
                                                Belum ada pesanan masuk.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('salesChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesChartLabels) !!},
                datasets: [{
                    label: 'Pendapatan Harian (Rp)',
                    data: {!! json_encode($salesChartData) !!},
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.12)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + (context.raw || 0).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
