<!DOCTYPE html>
<html lang="id">
<head>
	<title>Pesanan Saya | Coza Store</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/png" href="{{ store_favicon_url() }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/iconic/css/material-design-iconic-font.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/linearicons-v1.0.0/icon-font.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/main.css') }}">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
	<style>
		body {
			background-color: #f8fafc;
			font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
			color: #1e293b;
		}

		/* Modern Hero Header */
		.orders-hero-modern {
			background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 60%, #e2e8f0 100%);
			border-bottom: 1px solid #e2e8f0;
			padding: 32px 0 28px;
			margin-bottom: 24px;
		}
		.orders-breadcrumb {
			font-size: 13px;
			color: #64748b;
			margin-bottom: 8px;
			display: flex;
			align-items: center;
			gap: 6px;
		}
		.orders-breadcrumb a {
			color: #64748b;
			text-decoration: none;
			transition: color 0.2s;
		}
		.orders-breadcrumb a:hover {
			color: #4f46e5;
		}

		/* KPI Quick Stats Cards */
		.kpi-stat-card {
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 14px;
			padding: 12px 16px;
			box-shadow: 0 4px 12px -4px rgba(15, 23, 42, 0.05);
			display: flex;
			align-items: center;
			gap: 12px;
			transition: all 0.2s ease;
		}
		.kpi-stat-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 18px -4px rgba(15, 23, 42, 0.08);
		}
		.kpi-icon-wrap {
			width: 40px;
			height: 40px;
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 17px;
			flex-shrink: 0;
		}
		.kpi-val {
			font-size: 18px;
			font-weight: 800;
			color: #0f172a;
			line-height: 1.1;
		}
		.kpi-lbl {
			font-size: 11.5px;
			font-weight: 600;
			color: #64748b;
		}

		/* Status Filter Pills */
		.order-filter-wrapper {
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 12px;
			margin-bottom: 20px;
		}
		.order-filter-pills {
			display: flex;
			align-items: center;
			gap: 8px;
			overflow-x: auto;
			padding-bottom: 4px;
			-webkit-overflow-scrolling: touch;
		}
		.order-filter-pills::-webkit-scrollbar {
			height: 4px;
		}
		.order-filter-pills::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 4px;
		}
		.order-filter-pill {
			border: 1px solid #e2e8f0;
			background: #ffffff;
			color: #475569;
			font-size: 12.5px;
			font-weight: 700;
			padding: 7px 16px;
			border-radius: 99px;
			cursor: pointer;
			transition: all 0.2s ease;
			white-space: nowrap;
			display: inline-flex;
			align-items: center;
			gap: 6px;
		}
		.order-filter-pill:hover {
			border-color: #c7d2fe;
			color: #4f46e5;
			background: #f8fafc;
		}
		.order-filter-pill.active {
			background: #4f46e5;
			border-color: #4f46e5;
			color: #ffffff;
			box-shadow: 0 4px 14px -3px rgba(79, 70, 229, 0.4);
		}
		.order-filter-pill .count-badge {
			font-size: 11px;
			padding: 1px 6px;
			border-radius: 99px;
			background: rgba(0, 0, 0, 0.08);
		}
		.order-filter-pill.active .count-badge {
			background: rgba(255, 255, 255, 0.25);
			color: #ffffff;
		}

		/* Search Box */
		.order-search-box {
			position: relative;
			min-width: 260px;
			flex: 1;
			max-width: 360px;
		}
		.order-search-box input {
			border-radius: 99px;
			border: 1px solid #e2e8f0;
			padding: 8px 16px 8px 38px;
			font-size: 13px;
			width: 100%;
			background: #ffffff;
			transition: all 0.2s ease;
		}
		.order-search-box input:focus {
			border-color: #4f46e5;
			box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
			outline: none;
		}
		.order-search-box i {
			position: absolute;
			left: 14px;
			top: 50%;
			transform: translateY(-50%);
			color: #94a3b8;
			font-size: 14px;
		}

		/* Modern Order Card */
		.order-card-modern {
			background: #ffffff;
			border-radius: 20px;
			border: 1px solid #e2e8f0;
			box-shadow: 0 4px 20px -6px rgba(15, 23, 42, 0.05);
			margin-bottom: 22px;
			overflow: hidden;
			transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
		}
		.order-card-modern:hover {
			box-shadow: 0 12px 30px -8px rgba(15, 23, 42, 0.1);
			border-color: #cbd5e1;
		}
		.order-header {
			background: #f8fafc;
			padding: 14px 22px;
			border-bottom: 1px solid #e2e8f0;
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;
			align-items: center;
			gap: 12px;
		}
		.order-body {
			padding: 18px 22px;
		}
		.order-footer {
			background: #ffffff;
			padding: 14px 22px;
			border-top: 1px solid #f1f5f9;
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;
			align-items: center;
			gap: 14px;
		}

		/* Status Badges */
		.status-badge {
			padding: 5px 12px;
			border-radius: 99px;
			font-size: 12px;
			font-weight: 700;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			letter-spacing: 0.2px;
		}
		.status-pending { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
		.status-diproses { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
		.status-dikirim { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
		.status-selesai { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
		.status-dibatalkan { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

		/* Item Row */
		.item-row {
			display: flex;
			align-items: center;
			gap: 16px;
			padding: 12px 0;
			border-bottom: 1px solid #f1f5f9;
		}
		.item-row:last-child { border-bottom: none; }
		.item-img {
			width: 68px;
			height: 68px;
			border-radius: 12px;
			object-fit: cover;
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			flex-shrink: 0;
			transition: transform 0.2s;
		}
		.item-img:hover {
			transform: scale(1.04);
		}
		.variant-pill {
			display: inline-flex;
			align-items: center;
			background: #f1f5f9;
			color: #475569;
			padding: 2.5px 8px;
			border-radius: 6px;
			font-size: 11px;
			font-weight: 700;
			margin-top: 4px;
			border: 1px solid #e2e8f0;
		}

		/* Logistics Shipping Strip (Modern & Tanpa Redundansi) */
		.shipping-strip-modern {
			background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
			border-radius: 14px;
			border: 1px solid #e2e8f0;
			border-left: 4px solid #4f46e5;
			padding: 12px 16px;
			margin-top: 14px;
		}
		.courier-avatar-mini {
			width: 38px;
			height: 38px;
			border-radius: 10px;
			background: #ffffff;
			border: 1px solid #e2e8f0;
			color: #4f46e5;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 16px;
			flex-shrink: 0;
			box-shadow: 0 2px 6px rgba(0,0,0,0.04);
		}
		.waybill-code-pill {
			font-family: 'Space Mono', monospace, monospace;
			font-size: 13px;
			font-weight: 700;
			color: #4f46e5;
			letter-spacing: 0.5px;
			background: #ffffff;
			padding: 2px 8px;
			border-radius: 6px;
			border: 1px solid #e2e8f0;
		}
		.btn-copy-mini {
			background: transparent;
			border: none;
			color: #64748b;
			font-size: 11.5px;
			font-weight: 600;
			padding: 2px 6px;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 4px;
			border-radius: 4px;
			transition: all 0.15s;
		}
		.btn-copy-mini:hover {
			background: #e2e8f0;
			color: #0f172a;
		}

		/* Buttons */
		.btn-modern-primary {
			background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
			color: #ffffff !important;
			border: none;
			border-radius: 10px;
			font-weight: 700;
			box-shadow: 0 4px 14px -3px rgba(79, 70, 229, 0.4);
			transition: all 0.2s ease;
		}
		.btn-modern-primary:hover {
			opacity: 0.94;
			transform: translateY(-1px);
			color: #ffffff !important;
		}
		.btn-action-base {
			padding: 8px 15px;
			border-radius: 10px;
			font-size: 12.5px;
			font-weight: 700;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			transition: all 0.2s ease;
			text-decoration: none !important;
		}
		.btn-action-base:hover {
			transform: translateY(-1px);
		}
	</style>
	@include('frontend.partials.responsive-styles')
</head>
<body>
	@include('sweetalert::alert')

	@include('frontend.partials.simple-navbar', ['activeMenu' => 'pesanan'])

	<!-- Modern Page Header -->
	<section class="orders-hero-modern">
		<div class="container">
			<div class="orders-breadcrumb">
				<a href="/"><i class="fa-solid fa-house mr-1"></i>Home</a>
				<span>/</span>
				<span class="text-dark font-weight-bold">Pesanan Saya</span>
			</div>
			
			<div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 14px;">
				<div>
					<h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.75rem; letter-spacing: -0.02em;">Riwayat Pesanan Anda</h2>
					<p class="text-muted mb-0" style="font-size: 13.5px;">Pantau proses pengiriman, konfirmasi penerimaan, dan akses invoice pesanan Anda.</p>
				</div>
				<a href="/shop" class="btn btn-modern-primary btn-sm px-4 py-2">
					<i class="fa-solid fa-bag-shopping mr-1.5"></i> Belanja Lagi
				</a>
			</div>

			@if(!$orders->isEmpty())
			<!-- Quick Stats Row -->
			<div class="row pt-2" style="margin-right: -6px; margin-left: -6px;">
				<div class="col-6 col-md-3 mb-2 px-1.5">
					<div class="kpi-stat-card">
						<div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
							<i class="fa-solid fa-receipt"></i>
						</div>
						<div>
							<div class="kpi-val" id="stat-all-count">{{ $orders->total() }}</div>
							<div class="kpi-lbl">Total Pesanan</div>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-3 mb-2 px-1.5">
					<div class="kpi-stat-card">
						<div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
							<i class="fa-solid fa-clock-rotate-left"></i>
						</div>
						<div>
							<div class="kpi-val" id="stat-pending-count">-</div>
							<div class="kpi-lbl">Menunggu Bayar</div>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-3 mb-2 px-1.5">
					<div class="kpi-stat-card">
						<div class="kpi-icon-wrap" style="background: #eff6ff; color: #2563eb;">
							<i class="fa-solid fa-truck-fast"></i>
						</div>
						<div>
							<div class="kpi-val" id="stat-active-count">-</div>
							<div class="kpi-lbl">Dalam Pengiriman</div>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-3 mb-2 px-1.5">
					<div class="kpi-stat-card">
						<div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
							<i class="fa-solid fa-circle-check"></i>
						</div>
						<div>
							<div class="kpi-val" id="stat-done-count">-</div>
							<div class="kpi-lbl">Pesanan Selesai</div>
						</div>
					</div>
				</div>
			</div>
			@endif
		</div>
	</section>

	<!-- Content Area -->
	<div class="container pb-5">
		<div class="row justify-content-center">
			<div class="col-lg-10">

				@if(!$orders->isEmpty())
				<!-- Filter Status & Pencarian -->
				<div class="order-filter-wrapper">
					<div class="order-filter-pills">
						<button type="button" class="order-filter-pill active" onclick="filterOrders('all', this)">
							Semua <span class="count-badge" data-filter-count="all">({{ $orders->total() }})</span>
						</button>
						<button type="button" class="order-filter-pill" onclick="filterOrders('pending', this)">
							Menunggu Pembayaran <span class="count-badge" data-filter-count="pending">(0)</span>
						</button>
						<button type="button" class="order-filter-pill" onclick="filterOrders('diproses', this)">
							Sedang Dikemas <span class="count-badge" data-filter-count="diproses">(0)</span>
						</button>
						<button type="button" class="order-filter-pill" onclick="filterOrders('dikirim', this)">
							Dikirim <span class="count-badge" data-filter-count="dikirim">(0)</span>
						</button>
						<button type="button" class="order-filter-pill" onclick="filterOrders('selesai', this)">
							Selesai <span class="count-badge" data-filter-count="selesai">(0)</span>
						</button>
						<button type="button" class="order-filter-pill" onclick="filterOrders('dibatalkan', this)">
							Dibatalkan <span class="count-badge" data-filter-count="dibatalkan">(0)</span>
						</button>
					</div>

					<div class="order-search-box">
						<i class="fa-solid fa-magnifying-glass"></i>
						<input type="text" id="orderSearchInput" placeholder="Cari invoice atau nama produk..." onkeyup="searchOrders(this.value)">
					</div>
				</div>
				@endif

				@if($orders->isEmpty())
				<div class="bg-white p-5 border text-center" style="border-radius: 20px; border-color: #e2e8f0 !important; box-shadow: 0 4px 20px -6px rgba(15, 23, 42, 0.05);">
					<div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 76px; height: 76px; background: #eef2ff; color: #4f46e5;">
						<i class="fa-solid fa-bag-shopping" style="font-size: 32px;"></i>
					</div>
					<h5 class="font-weight-bold text-dark mb-1">Belum Ada Riwayat Pesanan</h5>
					<p class="text-muted mb-4" style="font-size: 13.5px;">Anda belum melakukan pemesanan barang di toko kami.</p>
					<a href="/shop" class="btn btn-modern-primary px-4 py-2">
						Mulai Belanja Sekarang
					</a>
				</div>
				@else
				@foreach($orders as $o)
				@php
					// Metode Pembayaran Bersahabat
					$methodLabel = match($o->metode_pembayaran) {
						'midtrans' => 'Pembayaran Online (Instan)',
						'cod' => 'COD (Bayar di Tempat)',
						'transfer' => 'Transfer Bank',
						default => strtoupper($o->metode_pembayaran ?: 'Online')
					};
				@endphp
				<div class="order-card-modern js-order-card" data-status="{{ $o->status }}" data-order-code="{{ $o->kode_order }}" data-resi="{{ $o->no_resi }}">
					<!-- Order Header -->
					<div class="order-header">
						<div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
							<span class="text-muted small">No. Invoice:</span>
							<strong class="text-dark font-monospace" style="letter-spacing: 0.4px;">{{ $o->kode_order }}</strong>
							<button type="button" class="btn-copy-mini js-copy-btn" data-copy="{{ $o->kode_order }}" title="Salin Nomor Invoice">
								<i class="fa-regular fa-copy"></i> Salin
							</button>
							<span class="text-muted mx-1">•</span>
							<span class="text-muted small"><i class="fa-regular fa-calendar-days mr-1"></i> {{ $o->created_at->format('d M Y, H:i') }} WIB</span>
						</div>
						<div>
							@if($o->status === 'pending')
								<span class="status-badge status-pending">
									<i class="fa-regular fa-clock"></i> Menunggu Pembayaran
								</span>
							@elseif($o->status === 'diproses')
								<span class="status-badge status-diproses">
									<i class="fa-solid fa-box-open"></i> Sedang Dikemas
								</span>
							@elseif($o->status === 'dikirim')
								<span class="status-badge status-dikirim">
									<i class="fa-solid fa-truck-fast"></i> Sedang Dikirim
								</span>
							@elseif($o->status === 'selesai')
								<span class="status-badge status-selesai">
									<i class="fa-solid fa-circle-check"></i> Selesai
								</span>
							@else
								<span class="status-badge status-dibatalkan">
									<i class="fa-solid fa-circle-xmark"></i> Dibatalkan
								</span>
							@endif
						</div>
					</div>

					<!-- Order Body -->
					<div class="order-body">
						@foreach($o->items as $item)
						<div class="item-row js-item-row" data-item-name="{{ strtolower($item->nama_barang) }}">
							@if($item->barang && $item->barang->gambar)
							<img src="{{ asset('storage/' . $item->barang->gambar) }}" class="item-img" alt="{{ $item->nama_barang }}">
							@else
							<div class="item-img d-flex align-items-center justify-content-center text-muted">
								<i class="fa-regular fa-image fa-lg"></i>
							</div>
							@endif
							<div class="flex-grow-1" style="min-width: 0;">
								<div class="font-weight-bold text-dark text-truncate" style="font-size: 14.5px;">{{ $item->nama_barang }}</div>
								@if(!empty($item->variasi))
								<span class="variant-pill">
									<i class="fa-solid fa-tag mr-1 text-primary"></i> {{ $item->variasi }}
								</span>
								@endif
								<div class="text-muted small mt-1">
									{{ $item->qty }} barang × Rp {{ number_format($item->harga, 0, ',', '.') }}
								</div>
							</div>
							<div class="text-right">
								<span class="font-weight-bold text-dark" style="font-size: 14.5px;">
									Rp {{ number_format($item->subtotal, 0, ',', '.') }}
								</span>
							</div>
						</div>
						@endforeach

						@if($o->diskon > 0)
						<div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top text-success small font-weight-bold">
							<span><i class="fa-solid fa-ticket mr-1"></i> Diskon Promo ({{ $o->kode_voucher }})</span>
							<span>- Rp {{ number_format($o->diskon, 0, ',', '.') }}</span>
						</div>
						@endif

						<div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top text-muted small">
							<span><i class="fa-solid fa-truck mr-1 text-primary"></i> Ongkos Kirim ({{ $o->kurir ?? $o->ekspedisi ?? 'Reguler' }})</span>
							<span class="font-weight-bold {{ ($o->ongkir ?? 0) > 0 ? 'text-dark' : 'text-success' }}">
								{{ ($o->ongkir ?? 0) > 0 ? 'Rp ' . number_format($o->ongkir, 0, ',', '.') : 'Gratis Ongkir' }}
							</span>
						</div>

						<!-- Info Pengiriman jika sudah ada resi (Clean Strip Tanpa Tombol Dobel) -->
						@if($o->no_resi)
						<div class="shipping-strip-modern">
							<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
								<div class="d-flex align-items-center" style="gap: 10px;">
									<div class="courier-avatar-mini">
										<i class="fa-solid fa-truck-fast"></i>
									</div>
									<div>
										<div class="text-muted small" style="font-size: 11px; line-height: 1.2;">Ekspedisi &amp; Nomor Resi</div>
										<div class="d-flex align-items-center flex-wrap" style="gap: 6px; margin-top: 2px;">
											<strong class="text-dark" style="font-size: 13px;">{{ $o->ekspedisi ?? 'Kurir' }}</strong>
											<span class="waybill-code-pill">{{ $o->no_resi }}</span>
											<button type="button" class="btn-copy-mini js-copy-btn" data-copy="{{ $o->no_resi }}" title="Salin Nomor Resi">
												<i class="fa-regular fa-copy"></i> Salin Resi
											</button>
										</div>
									</div>
								</div>
								<div>
									@if($o->status === 'dikirim')
										<span class="badge badge-light border px-2.5 py-1" style="color: #4338ca; background: #e0e7ff; font-weight: 700; border-radius: 99px; font-size: 11px;">
											<i class="fa-solid fa-paper-plane mr-1"></i> Sedang Dalam Perjalanan
										</span>
									@elseif($o->status === 'selesai')
										<span class="badge badge-light border px-2.5 py-1" style="color: #065f46; background: #d1fae5; font-weight: 700; border-radius: 99px; font-size: 11px;">
											<i class="fa-solid fa-circle-check mr-1"></i> Paket Telah Sampai
										</span>
									@endif
								</div>
							</div>
						</div>
						@endif
					</div>

					<!-- Order Footer (Aksi Terpadu & Tombol Lacak Tunggal) -->
					<div class="order-footer">
						<div>
							<span class="text-muted small">Metode Bayar:</span>
							<strong class="ml-1" style="color: #4f46e5; font-size: 12.5px;">{{ $methodLabel }}</strong>
						</div>
						<div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
							<div class="mr-2">
								<span class="text-muted small mr-1">Total:</span>
								<span class="font-weight-bold" style="font-size: 16px; color: #0f172a;">
									Rp {{ number_format($o->total_harga, 0, ',', '.') }}
								</span>
							</div>

							<!-- Actions Row -->
							@if($o->status === 'pending')
								<form action="{{ route('frontend.order.cancel', $o->kode_order) }}" method="POST" class="js-form-cancel-order" data-code="{{ $o->kode_order }}" style="display: inline-block;">
									@csrf
									<button type="button" class="btn-action-base btn btn-sm btn-outline-danger js-btn-cancel-order">
										<i class="fa-solid fa-xmark"></i> Batalkan
									</button>
								</form>
							@endif

							@if($o->status === 'pending' && $o->metode_pembayaran === 'midtrans' && !empty($o->snap_token))
								<button type="button" class="btn-action-base btn btn-sm btn-success font-weight-bold" onclick="paySnap('{{ $o->snap_token }}', '{{ $o->kode_order }}')">
									<i class="fa-solid fa-credit-card"></i> Bayar Sekarang
								</button>
							@endif

							@if($o->status === 'dikirim')
								<form action="{{ route('frontend.track.confirm', $o->kode_order) }}" method="POST" class="js-form-confirm-order" data-code="{{ $o->kode_order }}" style="display: inline-block;">
									@csrf
									<button type="button" class="btn-action-base btn btn-sm btn-success js-btn-confirm-order" style="background: #059669; border-color: #059669; color: #fff;">
										<i class="fa-solid fa-check"></i> Konfirmasi Diterima
									</button>
								</form>
							@endif

							<a href="{{ route('frontend.order.invoice', $o->kode_order) }}" target="_blank" class="btn-action-base btn btn-sm btn-outline-secondary" title="Cetak atau Unduh Faktur Pesanan">
								<i class="fa-solid fa-print"></i> Cetak Invoice
							</a>

							<!-- SATU-SATUNYA TOMBOL LACAK (Konsisten & Rapi) -->
							<a href="/lacak-pesanan?order={{ $o->kode_order }}" class="btn-action-base btn btn-sm btn-outline-primary" title="Lacak posisi pengiriman paket real-time">
								<i class="fa-solid fa-location-dot"></i> Lacak Paket
							</a>
						</div>
					</div>
				</div>
				@endforeach

				<div id="order-filter-empty" class="bg-white p-5 border text-center" style="display: none; border-radius: 20px; border-color: #e2e8f0 !important; box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.05);">
					<i class="fa-solid fa-box-archive text-muted mb-2" style="font-size: 32px;"></i>
					<p class="text-muted mb-0 font-weight-bold">Tidak ada pesanan yang sesuai dengan filter atau kata kunci pencarian.</p>
				</div>

				<!-- Pagination -->
				<div class="d-flex justify-content-center mt-4">
					{{ $orders->links('vendor.pagination.modern') }}
				</div>
				@endif
			</div>
		</div>
	</div>

	@include('frontend.partials.footer')

	<!-- Scripts -->
	<script src="{{ asset('frontend-assets/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	@if(config('midtrans.is_production'))
		<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	@else
		<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	@endif
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		var currentFilter = 'all';

		// Hitung statistik pesanan secara dinamis
		function updateFilterCounts() {
			var counts = { all: 0, pending: 0, diproses: 0, dikirim: 0, selesai: 0, dibatalkan: 0 };
			$('.js-order-card').each(function() {
				counts.all++;
				var st = $(this).data('status');
				if (counts[st] !== undefined) counts[st]++;
			});

			$('[data-filter-count]').each(function() {
				var key = $(this).data('filter-count');
				if (counts[key] !== undefined) {
					$(this).text('(' + counts[key] + ')');
				}
			});

			$('#stat-pending-count').text(counts.pending);
			$('#stat-active-count').text(counts.dikirim);
			$('#stat-done-count').text(counts.selesai);
		}

		// Filter Tab Status Pesanan
		function filterOrders(status, btn) {
			currentFilter = status;
			$('.order-filter-pill').removeClass('active');
			$(btn).addClass('active');
			applyFilters();
		}

		// Pencarian Live Order
		function searchOrders(keyword) {
			applyFilters();
		}

		function applyFilters() {
			var keyword = ($('#orderSearchInput').val() || '').toLowerCase().trim();
			var visibleCount = 0;

			$('.js-order-card').each(function() {
				var $card = $(this);
				var statusMatch = (currentFilter === 'all' || $card.data('status') === currentFilter);
				
				var searchMatch = true;
				if (keyword !== '') {
					var orderCode = String($card.data('order-code') || '').toLowerCase();
					var resi = String($card.data('resi') || '').toLowerCase();
					var itemNames = '';
					$card.find('.js-item-row').each(function() {
						itemNames += ' ' + ($(this).data('item-name') || '');
					});

					searchMatch = orderCode.includes(keyword) || resi.includes(keyword) || itemNames.includes(keyword);
				}

				if (statusMatch && searchMatch) {
					$card.show();
					visibleCount++;
				} else {
					$card.hide();
				}
			});

			if (visibleCount === 0) {
				$('#order-filter-empty').show();
			} else {
				$('#order-filter-empty').hide();
			}
		}

		// Tombol Salin Cepat
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
					timer: 1600
				}).fire({
					icon: 'success',
					title: 'Tersalin: ' + text
				});
			}
		});

		// Midtrans Pay
		function paySnap(snapToken, kodeOrder) {
			if (!snapToken) return;
			try {
				if (window.snap && typeof window.snap.hide === 'function') {
					window.snap.hide();
				}
			} catch (e) {}
			$('#snap-midtrans, #snap-container').remove();

			try {
				snap.pay(snapToken, {
					onSuccess: function(result) {
						syncAndReload(kodeOrder);
					},
					onPending: function(result) {
						syncAndReload(kodeOrder);
					},
					onError: function(result) {
						Swal.fire("Pembayaran Gagal", "Silakan coba metode pembayaran lain.", "error");
					},
					onClose: function() {
						syncAndReload(kodeOrder);
					}
				});
			} catch (err) {
				console.error('Snap error:', err);
				Swal.fire("Gagal Membuka Pembayaran", "Tidak dapat membuka modul pembayaran.", "error");
			}
		}

		function syncAndReload(kodeOrder) {
			$.ajax({
				url: '/midtrans/sync/' + kodeOrder,
				type: 'POST',
				data: { _token: '{{ csrf_token() }}' },
				complete: function() {
					window.location.reload();
				}
			});
		}

		// SweetAlert2 Batalkan Pesanan
		$(document).on('click', '.js-btn-cancel-order', function(e) {
			e.preventDefault();
			var form = $(this).closest('form');
			var kode = form.data('code') || '';
			Swal.fire({
				title: 'Batalkan Pesanan?',
				text: 'Apakah Anda yakin ingin membatalkan pesanan ' + kode + '? Stok produk akan otomatis dikembalikan.',
				icon: 'warning',
				iconColor: '#ef4444',
				showCancelButton: true,
				confirmButtonColor: '#ef4444',
				cancelButtonColor: '#94a3b8',
				confirmButtonText: '<i class="fa-solid fa-xmark mr-1"></i> Ya, Batalkan',
				cancelButtonText: 'Kembali',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});

		// SweetAlert2 Konfirmasi Pesanan Diterima
		$(document).on('click', '.js-btn-confirm-order', function(e) {
			e.preventDefault();
			var form = $(this).closest('form');
			Swal.fire({
				title: 'Konfirmasi Pesanan Diterima?',
				text: 'Apakah paket pesanan Anda sudah sampai dan diterima dengan baik?',
				icon: 'question',
				iconColor: '#10b981',
				showCancelButton: true,
				confirmButtonColor: '#10b981',
				cancelButtonColor: '#94a3b8',
				confirmButtonText: '<i class="fa-solid fa-check mr-1"></i> Ya, Sudah Diterima',
				cancelButtonText: 'Belum',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});

		// Inisialisasi awal
		$(document).ready(function() {
			updateFilterCounts();
		});
	</script>
</body>
</html>
