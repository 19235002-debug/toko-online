@extends('template.main')
@section('title', 'Data Pesanan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header pb-2">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center" style="gap: 12px;">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                              style="width: 44px; height: 44px; background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); color: #4f46e5; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);">
                            <i class="fa-solid fa-receipt" style="font-size: 20px;"></i>
                        </span>
                        <div>
                            <h1 class="m-0 font-weight-bold text-dark" style="font-size: 24px; font-family: 'Outfit', sans-serif;">@yield('title')</h1>
                            <p class="text-muted small mb-0">Kelola riwayat transaksi, verifikasi pembayaran, dan pantau pengiriman kurir</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0 font-weight-500" style="font-size: 13px;">
                        <li class="breadcrumb-item"><a href="/dashboard" class="text-muted"><i class="fa-solid fa-house mr-1"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Modern KPI Status Filter Cards -->
            @php
                $statusTabs = [
                    [
                        'key' => 'all',
                        'label' => 'Semua Pesanan',
                        'sub' => 'Total Transaksi',
                        'count' => $counts['all'],
                        'icon' => 'fa-solid fa-layer-group',
                        'iconBg' => '#eef2ff',
                        'iconColor' => '#4f46e5',
                        'activeBorder' => '#4f46e5',
                        'activeBg' => '#f8faff',
                    ],
                    [
                        'key' => 'pending',
                        'label' => 'Menunggu Bayar',
                        'sub' => 'Pending Midtrans/COD',
                        'count' => $counts['pending'],
                        'icon' => 'fa-solid fa-clock-rotate-left',
                        'iconBg' => '#fffbeb',
                        'iconColor' => '#d97706',
                        'activeBorder' => '#f59e0b',
                        'activeBg' => '#fffdfa',
                    ],
                    [
                        'key' => 'diproses',
                        'label' => 'Siap Dikemas',
                        'sub' => 'Perlu Request Pick-up',
                        'count' => $counts['diproses'],
                        'icon' => 'fa-solid fa-box-open',
                        'iconBg' => '#e0f2fe',
                        'iconColor' => '#0284c7',
                        'activeBorder' => '#0284c7',
                        'activeBg' => '#f0f9ff',
                    ],
                    [
                        'key' => 'dikirim',
                        'label' => 'Dalam Pengiriman',
                        'sub' => 'Resi Aktif Kurir',
                        'count' => $counts['dikirim'],
                        'icon' => 'fa-solid fa-truck-fast',
                        'iconBg' => '#f5f3ff',
                        'iconColor' => '#7c3aed',
                        'activeBorder' => '#7c3aed',
                        'activeBg' => '#faf5ff',
                    ],
                    [
                        'key' => 'selesai',
                        'label' => 'Pesanan Selesai',
                        'sub' => 'Diterima Pelanggan',
                        'count' => $counts['selesai'],
                        'icon' => 'fa-solid fa-circle-check',
                        'iconBg' => '#ecfdf5',
                        'iconColor' => '#059669',
                        'activeBorder' => '#10b981',
                        'activeBg' => '#f0fdf4',
                    ],
                    [
                        'key' => 'dibatalkan',
                        'label' => 'Dibatalkan',
                        'sub' => 'Batal / Kedaluwarsa',
                        'count' => $counts['dibatalkan'],
                        'icon' => 'fa-solid fa-circle-xmark',
                        'iconBg' => '#fef2f2',
                        'iconColor' => '#dc2626',
                        'activeBorder' => '#ef4444',
                        'activeBg' => '#fff5f5',
                    ],
                ];
            @endphp

            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 mb-3" style="margin-left: -6px; margin-right: -6px;">
                @foreach($statusTabs as $tab)
                    @php
                        $isActive = ($tab['key'] === 'all' && (empty($status) || $status === 'all')) || ($status === $tab['key']);
                        $filterParams = array_filter([
                            'status' => $tab['key'] === 'all' ? null : $tab['key'],
                            'search' => $search ?: null,
                            'metode' => ($metode && $metode !== 'all') ? $metode : null,
                        ]);
                    @endphp
                    <div class="col px-1.5 mb-2">
                        <a href="{{ route('order.index', $filterParams) }}"
                           class="card h-100 text-decoration-none transition-all order-kpi-card {{ $isActive ? 'active shadow-sm' : '' }}"
                           style="border-radius: 14px; border: 1.5px solid {{ $isActive ? $tab['activeBorder'] : '#e2e8f0' }}; background: {{ $isActive ? $tab['activeBg'] : '#ffffff' }}; transition: all 0.2s ease;">
                            <div class="card-body p-2.5 d-flex align-items-center justify-content-between">
                                <div class="overflow-hidden pr-1">
                                    <span class="d-block text-muted text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">{{ $tab['label'] }}</span>
                                    <h4 class="font-weight-bold mb-0 mt-0.5 text-dark" style="font-size: 20px; font-family: 'Outfit', sans-serif;">{{ number_format($tab['count']) }}</h4>
                                    <small class="text-muted d-block text-truncate" style="font-size: 10px;">{{ $tab['sub'] }}</small>
                                </div>
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                      style="width: 36px; height: 36px; background: {{ $tab['iconBg'] }}; color: {{ $tab['iconColor'] }}; font-size: 15px;">
                                    <i class="{{ $tab['icon'] }}"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Tabel Data Pesanan Card -->
            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <!-- Card Header -->
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap align-items-center justify-content-between" style="row-gap: 12px;">
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                              style="width: 36px; height: 36px; background: #eef2ff; color: #4f46e5;">
                            <i class="fa-solid fa-list-check" style="font-size: 16px;"></i>
                        </span>
                        <div>
                            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 16px;">
                                Daftar Transaksi Toko
                            </h5>
                            <span class="badge badge-light border text-muted px-2 py-0.5 ml-1" style="font-size: 11px;">
                                {{ count($orders) }} pesanan ditemukan
                            </span>
                        </div>
                    </div>

                    <!-- Tools: Saldo Biteship & Actions -->
                    <div class="card-tools d-flex flex-wrap align-items-center" style="gap: 8px;">
                        <!-- Saldo Pengiriman -->
                        <div class="d-inline-flex align-items-center px-3 py-1.5 rounded-pill"
                             style="background: #ecfdf5; border: 1px solid #a7f3d0; gap: 8px;"
                             title="Saldo akun logistik pengiriman untuk request pick-up kurir instan/ekspedisi">
                            <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);"></span>
                            <span class="small text-muted" style="font-size: 12px;"><i class="fa-solid fa-wallet text-success mr-1"></i> Saldo Pengiriman:</span>
                            <strong class="text-success font-monospace" style="font-size: 13px;">{{ $biteshipWallet['formatted'] ?? 'Rp 250.000' }}</strong>
                            @if(!empty($biteshipWallet['is_demo']))
                                <span class="badge badge-light border text-muted px-1.5 py-0.5" style="font-size: 9.5px;">Sandbox</span>
                            @endif
                        </div>

                        @if(($counts['dikirim'] ?? 0) > 0)
                        <form action="{{ route('order.syncShipping') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill font-weight-bold px-3 py-1.5"
                                    title="Perbarui otomatis status resi pesanan yang sedang diantar kurir">
                                <i class="fa-solid fa-truck-fast mr-1"></i> Sinkron Status Resi
                            </button>
                        </form>
                        @endif

                        @if($status || $search || ($metode && $metode !== 'all'))
                        <a href="{{ route('order.index') }}" class="btn btn-light border btn-sm rounded-pill font-weight-bold px-3 py-1.5 text-muted"
                           title="Bersihkan semua kata kunci dan filter">
                            <i class="fa-solid fa-arrows-rotate mr-1"></i> Reset Filter
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Unified Search & Metode Filter Toolbar -->
                <div class="card-body border-bottom py-3 px-4" style="background: #f8fafc;">
                    <form action="{{ route('order.index') }}" method="GET" class="row align-items-center" style="row-gap: 10px;">
                        @if(!empty($status) && $status !== 'all')
                            <input type="hidden" name="status" value="{{ $status }}">
                        @endif
                        <div class="col-md-7 col-lg-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #cbd5e1;">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                </div>
                                <input type="text" name="search" class="form-control border-left-0" value="{{ $search ?? '' }}"
                                       placeholder="Cari kode order (ORD-...), nama pembeli, atau no. HP..."
                                       style="border-radius: 0; border-color: #cbd5e1; font-size: 13.5px;">
                                <div class="input-group-append">
                                    @if(!empty($search))
                                        <a href="{{ route('order.index', array_filter(['status' => $status, 'metode' => $metode])) }}"
                                           class="btn btn-light border border-left-0 text-muted d-flex align-items-center"
                                           style="border-color: #cbd5e1;" title="Hapus pencarian">
                                            <i class="fa-solid fa-xmark"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-primary px-4 font-weight-bold" type="submit"
                                            style="border-radius: 0 12px 12px 0; background: #4f46e5; border-color: #4f46e5; font-size: 13.5px;">
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #cbd5e1;">
                                        <i class="fa-solid fa-credit-card"></i>
                                    </span>
                                </div>
                                <select name="metode" class="form-control custom-select border-left-0" onchange="this.form.submit()"
                                        style="border-radius: 0 12px 12px 0; border-color: #cbd5e1; font-size: 13.5px;">
                                    <option value="all" {{ ($metode ?? 'all') === 'all' ? 'selected' : '' }}>Semua Metode Pembayaran</option>
                                    <option value="midtrans" {{ ($metode ?? '') === 'midtrans' ? 'selected' : '' }}>Pembayaran Online (Instan)</option>
                                    <option value="cod" {{ ($metode ?? '') === 'cod' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Content -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="example1" data-dt-searching="false" class="table table-hover align-middle mb-0" style="width: 100%;">
                            <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                <tr>
                                    <th style="width: 45px;" class="text-center text-muted font-weight-bold">#</th>
                                    <th style="min-width: 180px;" class="text-muted font-weight-bold">INVOICE &amp; PENGIRIMAN</th>
                                    <th style="min-width: 190px;" class="text-muted font-weight-bold">PELANGGAN &amp; TUJUAN</th>
                                    <th style="min-width: 170px;" class="text-muted font-weight-bold">TOTAL &amp; METODE</th>
                                    <th style="min-width: 140px;" class="text-center text-muted font-weight-bold">STATUS</th>
                                    <th style="min-width: 190px;" class="text-center text-muted font-weight-bold">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $data)
                                <tr>
                                    <!-- # Index -->
                                    <td class="text-center align-middle">
                                        <span class="d-inline-flex align-items-center justify-content-center text-muted font-weight-bold small" style="width: 26px; height: 26px;">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <!-- Invoice, Date & Courier -->
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center" style="gap: 6px;">
                                            <a href="{{ route('order.show', $data->id_order) }}"
                                               class="font-weight-bold text-primary font-monospace"
                                               style="font-size: 13.5px;" title="Lihat detail pesanan {{ $data->kode_order }}">
                                                {{ $data->kode_order }}
                                            </a>
                                            <button type="button" class="btn btn-xs btn-link text-muted p-0 js-copy-btn"
                                                    data-copy="{{ $data->kode_order }}" title="Salin Kode Pesanan"
                                                    style="font-size: 11px;">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                        <div class="text-muted mt-0.5" style="font-size: 11.5px;">
                                            <i class="fa-regular fa-clock mr-1"></i>{{ $data->created_at->format('d M Y, H:i') }}
                                        </div>
                                        <div class="mt-1">
                                            <span class="badge badge-light border text-dark font-weight-500 px-2 py-0.5 d-inline-flex align-items-center"
                                                  style="font-size: 11px; gap: 4px; border-radius: 6px;">
                                                <i class="fa-solid fa-truck-fast text-primary"></i>
                                                {{ $data->kurir ?: ($data->ekspedisi . ' ' . $data->layanan_kurir) }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Customer & Destination -->
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center" style="gap: 8px;">
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle font-weight-bold text-white flex-shrink-0"
                                                  style="width: 28px; height: 28px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); font-size: 11px;">
                                                {{ strtoupper(substr($data->nama_customer ?: 'U', 0, 1)) }}
                                            </span>
                                            <div class="overflow-hidden">
                                                <div class="font-weight-bold text-dark text-truncate" style="font-size: 13.5px; max-width: 170px;" title="{{ $data->nama_customer }}">
                                                    {{ $data->nama_customer }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-1">
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->no_hp) }}" target="_blank"
                                               class="badge rounded-pill px-2 py-0.5 d-inline-flex align-items-center"
                                               style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; gap: 4px; font-size: 11px;"
                                               title="Hubungi pembeli via WhatsApp">
                                                <i class="fa-brands fa-whatsapp"></i> {{ $data->no_hp }}
                                            </a>
                                        </div>
                                        @if($data->destination_label)
                                        <div class="text-muted text-truncate mt-1" style="font-size: 11px; max-width: 210px;" title="{{ $data->destination_label }}">
                                            <i class="fa-solid fa-location-dot text-danger mr-1"></i>{{ Str::limit($data->destination_label, 30) }}
                                        </div>
                                        @endif
                                    </td>

                                    <!-- Total & Payment Method -->
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-dark" style="font-size: 14.5px;">
                                            Rp {{ number_format($data->total_harga, 0, ',', '.') }}
                                        </div>
                                        <div class="text-muted" style="font-size: 11px;">
                                            {{ $data->items->sum('jumlah') }} item barang
                                        </div>
                                        <div class="mt-1">
                                            @if($data->metode_pembayaran === 'midtrans' || $data->metode_pembayaran === 'online')
                                                <span class="badge rounded-pill px-2.5 py-1 d-inline-flex align-items-center font-weight-500"
                                                      style="background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 11px; gap: 4px;">
                                                    <i class="fa-solid fa-credit-card text-primary"></i> Online (Instan)
                                                </span>
                                            @else
                                                <span class="badge rounded-pill px-2.5 py-1 d-inline-flex align-items-center font-weight-500"
                                                      style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; font-size: 11px; gap: 4px;">
                                                    <i class="fa-solid fa-hand-holding-dollar text-success"></i> COD (Bayar di Tempat)
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Order Status -->
                                    <td class="text-center align-middle">
                                        @if($data->status == 'pending')
                                            <span class="badge rounded-pill px-3 py-1.5 font-weight-bold d-inline-flex align-items-center"
                                                  style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 11.5px; gap: 5px;">
                                                <i class="fa-solid fa-clock"></i> Pending
                                            </span>
                                        @elseif($data->status == 'diproses')
                                            <span class="badge rounded-pill px-3 py-1.5 font-weight-bold d-inline-flex align-items-center"
                                                  style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 11.5px; gap: 5px;">
                                                <i class="fa-solid fa-box-open"></i> Diproses
                                            </span>
                                        @elseif($data->status == 'dikirim')
                                            <span class="badge rounded-pill px-3 py-1.5 font-weight-bold d-inline-flex align-items-center"
                                                  style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; font-size: 11.5px; gap: 5px;">
                                                <i class="fa-solid fa-truck-fast"></i> Dikirim
                                            </span>
                                            @if($data->no_resi)
                                                <div class="mt-1.5 d-inline-flex align-items-center font-monospace bg-light border px-2 py-0.5 rounded"
                                                     style="font-size: 10.5px; gap: 4px;" title="Nomor Resi Resmi Kurir">
                                                    <span class="text-dark font-weight-bold">{{ $data->no_resi }}</span>
                                                    <button type="button" class="btn btn-xs btn-link text-muted p-0 js-copy-btn"
                                                            data-copy="{{ $data->no_resi }}" title="Salin Resi">
                                                        <i class="fa-regular fa-copy"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        @elseif($data->status == 'selesai')
                                            <span class="badge rounded-pill px-3 py-1.5 font-weight-bold d-inline-flex align-items-center"
                                                  style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 11.5px; gap: 5px;">
                                                <i class="fa-solid fa-circle-check"></i> Selesai
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-3 py-1.5 font-weight-bold d-inline-flex align-items-center"
                                                  style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-size: 11.5px; gap: 5px;">
                                                <i class="fa-solid fa-circle-xmark"></i> Dibatalkan
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="text-center align-middle">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 5px;">
                                            <!-- Tombol Detail -->
                                            <a href="{{ route('order.show', $data->id_order) }}"
                                               class="btn btn-sm btn-primary font-weight-bold px-2.5 py-1 text-white"
                                               style="border-radius: 8px; font-size: 12px; background: #4f46e5; border-color: #4f46e5;"
                                               title="Lihat Detail Pesanan Lengkap">
                                                <i class="fa-solid fa-eye mr-1"></i> Detail
                                            </a>

                                            <!-- Tombol Logistik / Cek Status -->
                                            @if($data->status === 'dikirim' && $data->no_resi)
                                                <a href="{{ route('order.shippingLabel', $data->id_order) }}" target="_blank"
                                                   class="btn btn-sm btn-dark px-2 py-1 font-weight-bold"
                                                   style="border-radius: 8px; font-size: 12px;"
                                                   title="Cetak Label Resi Barcode Thermal">
                                                    <i class="fa-solid fa-barcode mr-1"></i> Label
                                                </a>
                                            @elseif($data->status === 'diproses' || ($data->status === 'pending' && $data->metode_pembayaran === 'cod'))
                                                <form action="{{ route('order.requestPickup', $data->id_order) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning text-dark font-weight-bold px-2 py-1"
                                                            style="border-radius: 8px; font-size: 12px; background: #f59e0b; border-color: #f59e0b;"
                                                            title="Request Pick-up Kurir Ekspedisi Otomatis">
                                                        <i class="fa-solid fa-truck-pickup mr-1"></i> Pick-up
                                                    </button>
                                                </form>
                                            @elseif($data->status === 'pending' && $data->metode_pembayaran === 'midtrans')
                                                <a href="{{ route('order.checkMidtrans', $data->id_order) }}"
                                                   class="btn btn-sm btn-outline-info font-weight-bold px-2 py-1"
                                                   style="border-radius: 8px; font-size: 12px;"
                                                   title="Sinkronkan Status Pembayaran Terkini">
                                                    <i class="fa-solid fa-rotate mr-1"></i> Cek Bayar
                                                </a>
                                            @endif

                                            <!-- Tombol Cetak Invoice -->
                                            <a href="{{ route('order.print', $data->id_order) }}" target="_blank"
                                               class="btn btn-sm btn-outline-secondary px-2 py-1"
                                               style="border-radius: 8px; font-size: 12px;"
                                               title="Cetak Invoice Pesanan">
                                                <i class="fa-solid fa-print"></i>
                                            </a>

                                            <!-- Tombol Hapus Pesanan -->
                                            <form action="{{ route('order.destroy', $data->id_order) }}" method="POST" class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1 btn-delete"
                                                        style="border-radius: 8px; font-size: 12px;"
                                                        title="Hapus Data Pesanan">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                                  style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8; font-size: 24px;">
                                                <i class="fa-solid fa-inbox"></i>
                                            </span>
                                            <h6 class="font-weight-bold text-dark mb-1">Tidak Ada Pesanan Ditemukan</h6>
                                            <p class="text-muted small mb-3">Tidak ada data transaksi yang cocok dengan kriteria filter saat ini.</p>
                                            <a href="{{ route('order.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 font-weight-bold">
                                                <i class="fa-solid fa-arrows-rotate mr-1"></i> Reset Semua Filter
                                            </a>
                                        </div>
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
</div>

<style>
.order-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06) !important;
}
.order-kpi-card.active {
    box-shadow: 0 6px 20px rgba(79, 70, 229, 0.12) !important;
}
.js-copy-btn:hover {
    color: #4f46e5 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1-Click Copy helper
    $(document).on('click', '.js-copy-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var text = $(this).data('copy');
        if (!text) return;
        
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text);
        } else {
            var temp = $('<input>');
            $('body').append(temp);
            temp.val(text).select();
            document.execCommand('copy');
            temp.remove();
        }

        if (typeof Swal !== 'undefined' && Swal.mixin) {
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1800
            }).fire({
                icon: 'success',
                title: 'Tersalin ke clipboard: ' + text
            });
        }
    });
});
</script>

@endsection
