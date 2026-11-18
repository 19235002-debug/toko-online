@extends('template.main')
@section('title', 'Laporan Penjualan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-chart-line mr-2"></i>@yield('title')</h1>
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
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-filter mr-1"></i> Filter Periode & Kriteria Laporan
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('laporan.index') }}" method="GET" class="form-row align-items-end">
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label class="font-weight-bold text-muted small">Mulai Tanggal:</label>
                                    <input type="date" name="tgl_mulai" class="form-control" value="{{ $tglMulai }}" required>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label class="font-weight-bold text-muted small">Sampai Tanggal:</label>
                                    <input type="date" name="tgl_selesai" class="form-control" value="{{ $tglSelesai }}" required>
                                </div>
                                <div class="col-md-2 col-sm-6 mb-2">
                                    <label class="font-weight-bold text-muted small">Status Pesanan:</label>
                                    <select name="status" class="form-control">
                                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses (Lunas)</option>
                                        <option value="dikirim" {{ $status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6 mb-2">
                                    <label class="font-weight-bold text-muted small">Metode Bayar:</label>
                                    <select name="metode" class="form-control">
                                        <option value="all" {{ $metode == 'all' ? 'selected' : '' }}>Semua Metode</option>
                                        <option value="midtrans" {{ $metode == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                                        <option value="transfer" {{ $metode == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="cod" {{ $metode == 'cod' ? 'selected' : '' }}>COD</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-12 mb-2 d-flex">
                                    <button type="submit" class="btn btn-primary btn-block mr-1">
                                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Filter
                                    </button>
                                    <a href="{{ route('laporan.print', request()->all()) }}" target="_blank" class="btn btn-success mr-1" title="Cetak Rekap Laporan">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <a href="{{ route('laporan.exportCsv', request()->all()) }}" class="btn btn-info" title="Export Excel (CSV)">
                                        <i class="fa-solid fa-file-csv"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Statistik Ringkasan -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success shadow-sm">
                        <div class="inner">
                            <h3>Rp {{ number_format($totalOmset, 0, ',', '.') }}</h3>
                            <p>Total Pendapatan (Omset)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3>{{ $totalPesanan }}</h3>
                            <p>Total Transaksi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary shadow-sm">
                        <div class="inner">
                            <h3>{{ $totalProdukTerjual }} <span style="font-size: 16px;">pcs</span></h3>
                            <p>Produk Terjual</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner">
                            <h3 class="text-white">{{ $totalPending }}</h3>
                            <p class="text-white">Pesanan Pending / Belum Bayar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Laporan -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-list mr-1"></i> Rincian Transaksi ({{ date('d M Y', strtotime($tglMulai)) }} - {{ date('d M Y', strtotime($tglSelesai)) }})
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('laporan.print', request()->all()) }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-print mr-1"></i> Cetak Laporan
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>No. WhatsApp</th>
                                            <th>Item Produk</th>
                                            <th>Metode Bayar</th>
                                            <th>Status</th>
                                            <th>Total Omset</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="font-weight-bold text-left">
                                                <a href="{{ route('order.show', $order->id_order) }}" class="text-primary font-monospace">
                                                    {{ $order->kode_order }}
                                                </a>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-left font-weight-bold">{{ $order->nama_customer }}</td>
                                            <td>{{ $order->no_hp }}</td>
                                            <td class="text-left">
                                                <ul class="pl-3 mb-0" style="font-size: 13px;">
                                                    @foreach($order->items as $it)
                                                        <li>{{ $it->nama_barang }} (x{{ $it->qty }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                <span class="badge badge-info text-uppercase font-weight-normal px-2 py-1">
                                                    {{ $order->metode_pembayaran }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($order->status == 'pending')
                                                    <span class="badge badge-warning text-white px-2 py-1">Pending</span>
                                                @elseif($order->status == 'diproses')
                                                    <span class="badge badge-info px-2 py-1">Diproses</span>
                                                @elseif($order->status == 'dikirim')
                                                    <span class="badge badge-primary px-2 py-1">Dikirim</span>
                                                @elseif($order->status == 'selesai')
                                                    <span class="badge badge-success px-2 py-1">Selesai</span>
                                                @else
                                                    <span class="badge badge-danger px-2 py-1">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td class="font-weight-bold text-right text-success">
                                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-muted py-4">Tidak ada data transaksi pada rentang filter ini.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light font-weight-bold">
                                            <td colspan="8" class="text-right">TOTAL PENDAPATAN (OMSET):</td>
                                            <td class="text-right text-success" style="font-size: 16px;">
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
    </div>
</div>

@endsection

