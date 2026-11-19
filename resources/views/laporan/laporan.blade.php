@extends('template.main')
@section('title', 'Laporan Penjualan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-chart-line mr-2 text-primary"></i>@yield('title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Card Filter Tanggal & Kriteria -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fa-solid fa-filter mr-1 text-primary"></i> Filter Periode &amp; Kriteria Laporan
                    </h3>
                    <div class="card-tools d-flex flex-wrap align-items-center" style="gap: 8px;">
                        <a href="{{ route('laporan.index', ['tgl_mulai' => now()->toDateString(), 'tgl_selesai' => now()->toDateString()]) }}" class="btn btn-light border btn-sm btn-action">
                            Hari Ini
                        </a>
                        <a href="{{ route('laporan.index', ['tgl_mulai' => now()->subDays(6)->toDateString(), 'tgl_selesai' => now()->toDateString()]) }}" class="btn btn-light border btn-sm btn-action">
                            7 Hari
                        </a>
                        <a href="{{ route('laporan.index', ['tgl_mulai' => now()->startOfMonth()->toDateString(), 'tgl_selesai' => now()->toDateString()]) }}" class="btn btn-light border btn-sm btn-action">
                            Bulan Ini
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.index') }}" method="GET" class="row align-items-end" style="row-gap: 12px;">
                        <div class="col-lg-3 col-sm-6">
                            <label class="mb-1">Mulai Tanggal</label>
                            <input type="date" name="tgl_mulai" class="form-control" value="{{ $tglMulai }}" required>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <label class="mb-1">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" class="form-control" value="{{ $tglSelesai }}" required>
                        </div>
                        <div class="col-lg-2 col-sm-6">
                            <label class="mb-1">Status Pesanan</label>
                            <select name="status" class="form-control">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses (Lunas)</option>
                                <option value="dikirim" {{ $status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-6">
                            <label class="mb-1">Metode Bayar</label>
                            <select name="metode" class="form-control">
                                <option value="all" {{ $metode == 'all' ? 'selected' : '' }}>Semua Metode</option>
                                <option value="midtrans" {{ $metode == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                                <option value="transfer" {{ $metode == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="cod" {{ $metode == 'cod' ? 'selected' : '' }}>COD</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-12 d-flex" style="gap: 8px;">
                            <button type="submit" class="btn btn-primary font-weight-bold w-100" style="height: 42px; border-radius: 10px;">
                                <i class="fa-solid fa-magnifying-glass mr-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kartu Statistik Ringkasan (TailAdmin KPI Cards) -->
            <div class="row mb-3">
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div style="min-width: 0;">
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Total Pendapatan</div>
                                <div class="h4 font-weight-bold mb-1 mt-1 text-success">Rp {{ number_format($totalOmset, 0, ',', '.') }}</div>
                                <div class="text-muted" style="font-size: 11.5px;">Omset periode terpilih</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center shrink-0" style="width: 48px; height: 48px; border-radius: 14px; background: #ecfdf5; color: #10b981;">
                                <i class="fas fa-money-bill-wave fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Total Transaksi</div>
                                <div class="h4 font-weight-bold mb-1 mt-1">{{ $totalPesanan }}</div>
                                <div class="text-muted" style="font-size: 11.5px;">Pesanan tercatat</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center shrink-0" style="width: 48px; height: 48px; border-radius: 14px; background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-shopping-bag fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Produk Terjual</div>
                                <div class="h4 font-weight-bold mb-1 mt-1">{{ $totalProdukTerjual }} <span class="text-muted font-weight-normal" style="font-size: 14px;">pcs</span></div>
                                <div class="text-muted" style="font-size: 11.5px;">Total kuantitas item</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center shrink-0" style="width: 48px; height: 48px; border-radius: 14px; background: #eef2ff; color: #465fff;">
                                <i class="fas fa-box-open fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Menunggu Bayar</div>
                                <div class="h4 font-weight-bold mb-1 mt-1" style="color: #d97706;">{{ $totalPending }}</div>
                                <div class="text-muted" style="font-size: 11.5px;">Pesanan status pending</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center shrink-0" style="width: 48px; height: 48px; border-radius: 14px; background: #fef3c7; color: #d97706;">
                                <i class="fas fa-clock fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Laporan -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fa-solid fa-list mr-1 text-primary"></i> Rincian Transaksi ({{ date('d M Y', strtotime($tglMulai)) }} - {{ date('d M Y', strtotime($tglSelesai)) }})
                    </h3>
                    <div class="card-tools d-flex flex-wrap align-items-center" style="gap: 8px;">
                        <a href="{{ route('laporan.exportCsv', request()->all()) }}" class="btn btn-info btn-sm btn-action" style="gap: 5px;">
                            <i class="fa-solid fa-file-csv"></i> <span>Export CSV</span>
                        </a>
                        <a href="{{ route('laporan.print', request()->all()) }}" target="_blank" class="btn btn-success btn-sm btn-action" style="gap: 5px;">
                            <i class="fa-solid fa-print"></i> <span>Cetak Laporan</span>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 45px;" class="text-center">#</th>
                                    <th>Invoice &amp; Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Item Produk</th>
                                    <th class="text-center">Metode &amp; Status</th>
                                    <th class="text-right">Total Omset</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="text-center text-muted font-weight-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('order.show', $order->id_order) }}" class="font-weight-bold text-primary font-monospace d-block" style="font-size: 13.5px;">
                                            {{ $order->kode_order }}
                                        </a>
                                        <small class="text-muted">
                                            <i class="fa-regular fa-clock mr-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $order->nama_customer }}</div>
                                        <small class="text-muted"><i class="fab fa-whatsapp text-success mr-1"></i>{{ $order->no_hp }}</small>
                                    </td>
                                    <td>
                                        <ul class="pl-3 mb-0" style="font-size: 13px;">
                                            @foreach($order->items as $it)
                                                <li>{{ $it->nama_barang }} <span class="text-muted font-weight-bold">(x{{ $it->qty }})</span></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center" style="gap: 4px;">
                                            <span class="badge rounded-pill px-2 py-0.5 text-uppercase" style="background: #eef2ff; color: #465fff; font-size: 10.5px;">
                                                {{ $order->metode_pembayaran }}
                                            </span>
                                            @if($order->status == 'pending')
                                                <span class="badge rounded-pill px-2.5 py-1" style="background: #fef3c7; color: #b45309; font-size: 11px;">Pending</span>
                                            @elseif($order->status == 'diproses')
                                                <span class="badge rounded-pill px-2.5 py-1" style="background: #e0f2fe; color: #0369a1; font-size: 11px;">Diproses</span>
                                            @elseif($order->status == 'dikirim')
                                                <span class="badge rounded-pill px-2.5 py-1" style="background: #ede9fe; color: #6d28d9; font-size: 11px;">Dikirim</span>
                                            @elseif($order->status == 'selesai')
                                                <span class="badge rounded-pill px-2.5 py-1" style="background: #dcfce7; color: #15803d; font-size: 11px;">Selesai</span>
                                            @else
                                                <span class="badge rounded-pill px-2.5 py-1" style="background: #fee2e2; color: #b91c1c; font-size: 11px;">Dibatalkan</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="font-weight-bold text-right text-success" style="font-size: 14px;">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data transaksi pada rentang filter ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background: #f8fafc;">
                                    <td colspan="5" class="text-right font-weight-bold">TOTAL PENDAPATAN (OMSET):</td>
                                    <td class="text-right font-weight-bold text-success" style="font-size: 16px;">
                                        Rp {{ number_format($totalOmset, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
