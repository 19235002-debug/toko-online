@extends('template.main')
@section('title', 'Data Pesanan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-receipt mr-2"></i>@yield('title')</h1>
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
            <!-- Filter Status Nav Pills -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-outline card-primary shadow-sm mb-0">
                        <div class="card-body p-2">
                            <ul class="nav nav-pills flex-wrap">
                                <li class="nav-item">
                                    <a class="nav-link {{ empty($status) || $status == 'all' ? 'active' : '' }}" href="{{ route('order.index') }}">
                                        Semua <span class="badge badge-light ml-1">{{ $counts['all'] }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" href="{{ route('order.index', ['status' => 'pending']) }}">
                                        Menunggu Proses <span class="badge badge-warning text-white ml-1">{{ $counts['pending'] }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $status == 'diproses' ? 'active' : '' }}" href="{{ route('order.index', ['status' => 'diproses']) }}">
                                        Diproses <span class="badge badge-info ml-1">{{ $counts['diproses'] }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $status == 'dikirim' ? 'active' : '' }}" href="{{ route('order.index', ['status' => 'dikirim']) }}">
                                        Dikirim <span class="badge badge-primary ml-1">{{ $counts['dikirim'] }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $status == 'selesai' ? 'active' : '' }}" href="{{ route('order.index', ['status' => 'selesai']) }}">
                                        Selesai <span class="badge badge-success ml-1">{{ $counts['selesai'] }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $status == 'dibatalkan' ? 'active' : '' }}" href="{{ route('order.index', ['status' => 'dibatalkan']) }}">
                                        Dibatalkan <span class="badge badge-danger ml-1">{{ $counts['dibatalkan'] }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Pesanan -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-list-check mr-1"></i> Daftar Transaksi Toko
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('order.index') }}" class="btn btn-secondary btn-sm btn-action">
                                    <i class="fa-solid fa-arrows-rotate mr-1"></i> Reset
                                </a>
                            </div>
                        </div>
                        <div class="card-body border-bottom bg-light py-2">
                            <form action="{{ route('order.index') }}" method="GET" class="row align-items-center g-2">
                                @if(!empty($status))
                                    <input type="hidden" name="status" value="{{ $status }}">
                                @endif
                                <div class="col-md-5 mb-2 mb-md-0">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="search" class="form-control" value="{{ $search ?? '' }}"
                                            placeholder="Cari kode order, nama pembeli, atau no. HP...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa-solid fa-magnifying-glass"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <select name="metode" class="form-control form-control-sm" onchange="this.form.submit()">
                                        <option value="all" {{ ($metode ?? 'all') === 'all' ? 'selected' : '' }}>Semua Metode Bayar</option>
                                        <option value="midtrans" {{ ($metode ?? '') === 'midtrans' ? 'selected' : '' }}>Midtrans (Online)</option>
                                        <option value="cod" {{ ($metode ?? '') === 'cod' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                                    </select>
                                </div>
                            </form>
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
                                            <th>WhatsApp / No HP</th>
                                            <th>Total Bayar</th>
                                            <th>Metode Bayar</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="font-weight-bold text-left">
                                                <a href="{{ route('order.show', $data->id_order) }}" class="text-primary font-monospace">
                                                    {{ $data->kode_order }}
                                                </a>
                                            </td>
                                            <td>{{ $data->created_at->format('d M Y, H:i') }}</td>
                                            <td class="font-weight-bold text-left">{{ $data->nama_customer }}</td>
                                            <td>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->no_hp) }}" target="_blank" class="badge badge-light border text-success">
                                                    <i class="fab fa-whatsapp mr-1"></i> {{ $data->no_hp }}
                                                </a>
                                            </td>
                                            <td class="font-weight-bold text-success">
                                                Rp {{ number_format($data->total_harga, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($data->metode_pembayaran == 'midtrans')
                                                    <span class="badge badge-primary px-2 py-1"><i class="fa fa-credit-card mr-1"></i> Midtrans</span>
                                                @else
                                                    <span class="badge badge-info text-uppercase font-weight-normal px-2 py-1">
                                                        {{ $data->metode_pembayaran }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->status == 'pending')
                                                    <span class="badge badge-warning text-white px-2 py-1"><i class="fas fa-clock mr-1"></i> Pending</span>
                                                @elseif($data->status == 'diproses')
                                                    <span class="badge badge-info px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Diproses</span>
                                                @elseif($data->status == 'dikirim')
                                                    <span class="badge badge-primary px-2 py-1"><i class="fas fa-truck mr-1"></i> Dikirim</span>
                                                    @if($data->no_resi)
                                                        <small class="d-block text-muted font-monospace mt-1" title="{{ $data->ekspedisi }}">{{ $data->no_resi }}</small>
                                                    @endif
                                                @elseif($data->status == 'selesai')
                                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Selesai</span>
                                                @else
                                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-action-group d-flex justify-content-center align-items-center flex-nowrap" style="gap: 5px;">
                                                    <a href="{{ route('order.show', $data->id_order) }}" class="btn btn-info btn-sm btn-action text-white" title="Lihat Detail Pesanan">
                                                        <i class="fa-solid fa-eye mr-1"></i> <span class="d-none d-md-inline">Detail</span>
                                                    </a>
                                                    <a href="{{ route('order.print', $data->id_order) }}" target="_blank" class="btn btn-success btn-sm btn-action" title="Cetak Invoice">
                                                        <i class="fa-solid fa-print"></i>
                                                    </a>
                                                    <form action="{{ route('order.destroy', $data->id_order) }}" method="POST" class="d-inline form-delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm btn-action btn-delete" title="Hapus Pesanan">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
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

