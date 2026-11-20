@extends('template.main')
@section('title', 'Data Pesanan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-receipt mr-2 text-primary"></i>@yield('title')</h1>
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
            <!-- Filter Status Pills -->
            <div class="card mb-3">
                <div class="card-body py-3">
                    @php
                        $tabs = [
                            ['key' => 'all', 'label' => 'Semua', 'count' => $counts['all'], 'badgeBg' => '#e0e7ff', 'badgeColor' => '#3730a3'],
                            ['key' => 'pending', 'label' => 'Menunggu Proses', 'count' => $counts['pending'], 'badgeBg' => '#fef3c7', 'badgeColor' => '#b45309'],
                            ['key' => 'diproses', 'label' => 'Diproses', 'count' => $counts['diproses'], 'badgeBg' => '#e0f2fe', 'badgeColor' => '#0369a1'],
                            ['key' => 'dikirim', 'label' => 'Dikirim', 'count' => $counts['dikirim'], 'badgeBg' => '#ede9fe', 'badgeColor' => '#5b21b6'],
                            ['key' => 'selesai', 'label' => 'Selesai', 'count' => $counts['selesai'], 'badgeBg' => '#dcfce7', 'badgeColor' => '#15803d'],
                            ['key' => 'dibatalkan', 'label' => 'Dibatalkan', 'count' => $counts['dibatalkan'], 'badgeBg' => '#fee2e2', 'badgeColor' => '#b91c1c'],
                        ];
                    @endphp
                    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                        @foreach($tabs as $t)
                            @php
                                $isActive = ($t['key'] === 'all' && (empty($status) || $status === 'all')) || ($status === $t['key']);
                                $urlParams = array_filter([
                                    'status' => $t['key'] === 'all' ? null : $t['key'],
                                    'search' => $search ?: null,
                                    'metode' => ($metode && $metode !== 'all') ? $metode : null,
                                ]);
                            @endphp
                            <a href="{{ route('order.index', $urlParams) }}"
                               class="btn btn-sm rounded-pill px-3 py-1.5 font-weight-bold d-inline-flex align-items-center {{ $isActive ? 'btn-primary text-white shadow-sm' : 'btn-light border text-dark' }}"
                               style="gap: 7px; font-size: 13px;">
                                <span>{{ $t['label'] }}</span>
                                <span class="badge rounded-pill px-2 py-0.5"
                                      style="background: {{ $isActive ? 'rgba(255,255,255,0.22)' : $t['badgeBg'] }}; color: {{ $isActive ? '#ffffff' : $t['badgeColor'] }}; font-size: 11px;">
                                    {{ $t['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tabel Data Pesanan -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fa-solid fa-list-check mr-1 text-primary"></i> Daftar Transaksi Toko
                    </h3>
                    <div class="card-tools d-flex flex-wrap align-items-center" style="gap: 8px;">
                        <span class="badge rounded-pill px-3 py-1.5 d-inline-flex align-items-center" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 12px; gap: 6px;">
                            <i class="fa fa-wallet"></i> Saldo Pengiriman: <strong>{{ $biteshipWallet['formatted'] ?? 'Rp 250.000' }}</strong>
                            @if(!empty($biteshipWallet['is_demo']))
                                <span class="badge badge-light border text-muted" style="font-size: 9.5px;">Sandbox</span>
                            @endif
                        </span>
                        @if(($counts['dikirim'] ?? 0) > 0)
                        <form action="{{ route('order.syncShipping') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm btn-action" title="Cek status resi ekspedisi terkini">
                                <i class="fa-solid fa-truck-fast mr-1"></i> Sinkron Status Resi
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('order.index') }}" class="btn btn-light border btn-sm btn-action" style="gap: 5px;">
                            <i class="fa-solid fa-arrows-rotate"></i> <span>Reset Filter</span>
                        </a>
                    </div>
                </div>

                <!-- Unified Search & Metode Filter -->
                <div class="card-body border-bottom py-3" style="background: #f8fafc;">
                    <form action="{{ route('order.index') }}" method="GET" class="row align-items-center" style="row-gap: 10px;">
                        @if(!empty($status))
                            <input type="hidden" name="status" value="{{ $status }}">
                        @endif
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" value="{{ $search ?? '' }}"
                                    placeholder="Cari kode order, nama pembeli, atau no. HP..." style="border-radius: 10px 0 0 10px !important;">
                                <div class="input-group-append">
                                    <button class="btn btn-primary px-3 font-weight-bold" type="submit" style="border-radius: 0 10px 10px 0 !important;">
                                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <select name="metode" class="form-control" onchange="this.form.submit()">
                                <option value="all" {{ ($metode ?? 'all') === 'all' ? 'selected' : '' }}>Semua Metode Bayar</option>
                                <option value="midtrans" {{ ($metode ?? '') === 'midtrans' ? 'selected' : '' }}>Pembayaran Online</option>
                                <option value="cod" {{ ($metode ?? '') === 'cod' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" data-dt-searching="false" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 45px;" class="text-center">#</th>
                                    <th>Invoice &amp; Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total &amp; Metode</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 175px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $data)
                                <tr>
                                    <td class="text-center text-muted font-weight-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('order.show', $data->id_order) }}" class="font-weight-bold text-primary font-monospace d-block" style="font-size: 13.5px;">
                                            {{ $data->kode_order }}
                                        </a>
                                        <small class="text-muted">
                                            <i class="fa-regular fa-clock mr-1"></i>{{ $data->created_at->format('d M Y, H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $data->nama_customer }}</div>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->no_hp) }}" target="_blank"
                                           class="badge rounded-pill px-2 py-0.5 mt-1 d-inline-flex align-items-center"
                                           style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; gap: 4px; font-size: 11px;">
                                            <i class="fab fa-whatsapp"></i> {{ $data->no_hp }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-success" style="font-size: 14px;">
                                            Rp {{ number_format($data->total_harga, 0, ',', '.') }}
                                        </div>
                                        @if($data->metode_pembayaran == 'midtrans')
                                            <span class="badge rounded-pill px-2 py-0.5 mt-1" style="background: #eef2ff; color: #465fff; font-size: 11px;">
                                                <i class="fa fa-credit-card mr-1"></i> Online (Instan)
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-2 py-0.5 mt-1 text-uppercase" style="background: #e0f2fe; color: #0284c7; font-size: 11px;">
                                                <i class="fa fa-truck mr-1"></i> {{ $data->metode_pembayaran }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($data->status == 'pending')
                                            <span class="badge rounded-pill px-3 py-1.5" style="background: #fef3c7; color: #b45309; font-size: 11.5px;">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @elseif($data->status == 'diproses')
                                            <span class="badge rounded-pill px-3 py-1.5" style="background: #e0f2fe; color: #0369a1; font-size: 11.5px;">
                                                <i class="fas fa-spinner fa-spin mr-1"></i> Diproses
                                            </span>
                                        @elseif($data->status == 'dikirim')
                                            <span class="badge rounded-pill px-3 py-1.5" style="background: #ede9fe; color: #6d28d9; font-size: 11.5px;">
                                                <i class="fas fa-truck mr-1"></i> Dikirim
                                            </span>
                                            @if($data->no_resi)
                                                <small class="d-block text-muted font-monospace mt-1" title="{{ $data->ekspedisi }}">{{ $data->no_resi }}</small>
                                            @endif
                                        @elseif($data->status == 'selesai')
                                            <span class="badge rounded-pill px-3 py-1.5" style="background: #dcfce7; color: #15803d; font-size: 11.5px;">
                                                <i class="fas fa-check-circle mr-1"></i> Selesai
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-3 py-1.5" style="background: #fee2e2; color: #b91c1c; font-size: 11.5px;">
                                                <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-action-group justify-content-center">
                                            <a href="{{ route('order.show', $data->id_order) }}" class="btn btn-info btn-sm btn-action text-white" style="gap: 5px;" title="Lihat Detail Pesanan">
                                                <i class="fa-solid fa-eye"></i> <span>Detail</span>
                                            </a>
                                            @if($data->no_resi)
                                                <a href="{{ route('order.shippingLabel', $data->id_order) }}" target="_blank" class="btn btn-dark btn-sm btn-action" title="Cetak Label Resi">
                                                    <i class="fa-solid fa-barcode"></i>
                                                </a>
                                            @elseif(in_array($data->status, ['diproses', 'pending']))
                                                <form action="{{ route('order.requestPickup', $data->id_order) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm btn-action" title="Request Pick-up Kurir">
                                                        <i class="fa-solid fa-truck-pickup"></i>
                                                    </button>
                                                </form>
                                            @endif
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

@endsection
