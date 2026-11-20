<!DOCTYPE html>
<html lang="id">
<head>
	<title>Lacak Pesanan | Coza Store</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{ store_favicon_url() }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/iconic/css/material-design-iconic-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/linearicons-v1.0.0/icon-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/animate/animate.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/main.css') }}">
<!--===============================================================================================-->
	<style>
		/* Header & User Dropdown Styling */
		.js-user-dropdown {
			position: relative !important;
			display: inline-flex !important;
			align-items: center !important;
		}
		.js-user-dropdown .js-user-dropdown-toggle {
			display: inline-flex !important;
			align-items: center !important;
			cursor: pointer;
			user-select: none;
		}
		.js-user-dropdown .user-dropdown-menu {
			display: none;
			position: absolute !important;
			top: calc(100% + 14px) !important;
			right: 0 !important;
			left: auto !important;
			transform: none !important;
			box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
			border: 1px solid #e9ecef !important;
			border-radius: 10px !important;
			min-width: 220px !important;
			background-color: #ffffff !important;
			z-index: 1200 !important;
			margin: 0 !important;
			padding: 8px 0;
		}
		.js-user-dropdown.show > .user-dropdown-menu {
			display: block !important;
			animation: fadeInMenu 0.15s ease-out;
		}
		@keyframes fadeInMenu {
			from { opacity: 0; transform: translateY(-4px); }
			to { opacity: 1; transform: translateY(0); }
		}

		/* Modern Track Order Page */
		.track-hero-modern {
			background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 55%, #f5f3ff 100%);
			border-bottom: 1px solid #e2e8f0;
			padding: 112px 0 64px 0;
		}
		@media (max-width: 991px) {
			.track-hero-modern {
				padding: 90px 0 56px 0;
			}
		}
		.track-breadcrumb {
			font-size: 13px;
			color: #64748b;
			margin-bottom: 10px;
		}
		.track-breadcrumb a {
			color: #64748b;
			text-decoration: none;
		}
		.track-breadcrumb a:hover {
			color: #4f46e5;
		}
		.track-hero-badge {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			background: #e0e7ff;
			color: #4338ca;
			font-size: 12px;
			font-weight: 700;
			padding: 5px 12px;
			border-radius: 99px;
			margin-bottom: 12px;
		}
		.track-hero-title {
			font-size: 2rem;
			font-weight: 800;
			color: #0f172a;
			letter-spacing: -0.02em;
			margin-bottom: 8px;
		}
		.track-hero-subtitle {
			color: #475569;
			font-size: 15px;
			max-width: 580px;
			margin: 0 auto;
		}

		/* Stepper / Timeline */
		.order-stepper {
			display: flex;
			justify-content: space-between;
			position: relative;
			margin-bottom: 10px;
		}
		.order-stepper::before {
			content: '';
			position: absolute;
			top: 24px;
			left: 40px;
			right: 40px;
			height: 4px;
			background-color: #e2e8f0;
			border-radius: 99px;
			z-index: 1;
		}
		.step-item {
			position: relative;
			z-index: 2;
			text-align: center;
			flex: 1;
		}
		.step-icon {
			width: 48px;
			height: 48px;
			border-radius: 50%;
			background-color: #f1f5f9;
			color: #94a3b8;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 19px;
			margin-bottom: 8px;
			transition: all 0.3s ease;
			border: 4px solid #ffffff;
			box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
		}
		.step-item.active .step-icon {
			background: linear-gradient(135deg, #4f46e5, #717fe0);
			color: #ffffff;
			box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.16);
		}
		.step-item.completed .step-icon {
			background: linear-gradient(135deg, #10b981, #059669);
			color: #ffffff;
		}
		.step-title {
			font-size: 13px;
			font-weight: 700;
			color: #1e293b;
			margin-bottom: 2px;
		}
		.step-desc {
			font-size: 11.5px;
			color: #64748b;
		}

		@media (max-width: 576px) {
			.track-hero-title {
				font-size: 1.55rem;
			}
			.order-stepper::before {
				top: 18px;
				left: 20px;
				right: 20px;
			}
			.step-icon {
				width: 36px;
				height: 36px;
				font-size: 15px;
			}
			.step-title {
				font-size: 11px;
			}
			.step-desc {
				display: none;
			}
		}

		/* Search tracking form */
		.tracking-search-card {
			background: #ffffff;
			border-radius: 18px;
			box-shadow: 0 18px 40px -12px rgba(15, 23, 42, 0.12);
			border: 1px solid #e2e8f0;
			padding: 24px 26px;
			margin-top: -38px;
			position: relative;
			z-index: 10;
		}
		.tracking-input-group {
			display: flex;
			align-items: center;
			background: #f8fafc;
			border: 1.5px solid #cbd5e1;
			border-radius: 14px;
			padding: 5px 5px 5px 16px;
			gap: 10px;
			transition: all 0.2s ease;
		}
		.tracking-input-group:focus-within {
			background: #ffffff;
			border-color: #4f46e5;
			box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
		}
		.tracking-input {
			flex: 1;
			height: 44px;
			border: none !important;
			background: transparent !important;
			box-shadow: none !important;
			padding: 0 6px !important;
			font-size: 14.5px;
			color: #0f172a;
		}
		.tracking-input::placeholder {
			color: #94a3b8;
		}
		.tracking-btn {
			height: 44px;
			border-radius: 10px;
			padding: 0 22px;
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff;
			border: none;
			font-weight: 700;
			font-size: 14px;
			white-space: nowrap;
			box-shadow: 0 6px 16px -4px rgba(79, 70, 229, 0.4);
			transition: all 0.2s ease;
			cursor: pointer;
		}
		.tracking-btn:hover {
			transform: translateY(-1px);
			box-shadow: 0 10px 20px -4px rgba(79, 70, 229, 0.5);
			color: #ffffff;
		}
		.track-guide-card {
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 16px;
			padding: 22px 18px;
			height: 100%;
			transition: all 0.2s ease;
		}
		.track-guide-card:hover {
			transform: translateY(-3px);
			box-shadow: 0 12px 24px -8px rgba(15, 23, 42, 0.08);
			border-color: #c7d2fe;
		}
		.track-guide-icon {
			width: 52px;
			height: 52px;
			border-radius: 14px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 22px;
			margin-bottom: 12px;
		}
		.track-result-card {
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 18px;
			box-shadow: 0 14px 34px -12px rgba(15, 23, 42, 0.08);
			overflow: hidden;
		}
		.track-info-panel {
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			border-radius: 14px;
			padding: 20px;
			height: 100%;
		}
		@media (max-width: 576px) {
			.tracking-search-card {
				padding: 18px 16px;
			}
			.tracking-input-group {
				flex-direction: column;
				align-items: stretch;
				padding: 10px;
				gap: 8px;
			}
			.tracking-btn {
				width: 100%;
			}
		}

		/* --- Clean Single-Line Desktop Header Navbar --- */
		.limiter-menu-desktop {
			display: flex !important;
			align-items: center !important;
			justify-content: space-between !important;
			flex-wrap: nowrap !important;
			height: 100%;
		}
		.limiter-menu-desktop .logo {
			flex-shrink: 0;
			margin-right: 32px !important;
		}
		.menu-desktop {
			display: flex;
			align-items: center;
			flex: 1 1 auto;
			min-width: 0;
		}
		.menu-desktop .main-menu {
			display: flex !important;
			align-items: center !important;
			flex-wrap: nowrap !important;
			margin: 0;
			padding: 0;
		}
		.menu-desktop .main-menu > li {
			margin: 0 12px 0 0 !important;
			padding: 12px 6px !important;
			flex-shrink: 0;
		}
		.menu-desktop .main-menu > li > a {
			white-space: nowrap !important;
			display: inline-flex;
			align-items: center;
			gap: 5px;
		}
		.wrap-icon-header {
			display: flex !important;
			align-items: center !important;
			flex-wrap: nowrap !important;
			flex-shrink: 0 !important;
			margin-left: auto;
		}
		.wrap-icon-header .icon-header-item,
		.wrap-icon-header .js-user-dropdown {
			flex-shrink: 0;
			white-space: nowrap;
		}
	</style>

	<!-- Midtrans Snap JS (Sandbox Demo) -->
	@if(config('midtrans.is_production'))
		<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	@else
		<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	@endif
	@include('frontend.partials.responsive-styles')
</head>
<body class="animsition">
	
	@include('frontend.partials.simple-navbar', ['activeMenu' => 'track'])

	<!-- Modern Header Hero -->
	<section class="track-hero-modern txt-center p-lr-15">
		<div class="container">
			<div class="track-breadcrumb">
				<a href="/">Home</a> <span class="mx-1">/</span> <span class="text-dark font-weight-bold">Lacak Pesanan</span>
			</div>
			<div class="track-hero-badge">
				<i class="fa fa-truck-fast"></i> Pelacakan Pesanan Real-Time
			</div>
			<h2 class="track-hero-title">
				Lacak Status Pesanan Anda
			</h2>
			<p class="track-hero-subtitle">
				Pantau riwayat proses pesanan, status pembayaran, dan nomor resi kurir secara langsung dalam satu halaman.
			</p>
		</div>
	</section>

	<!-- Content page -->
	<section class="p-b-80" style="background: #f8fafc; min-height: 60vh;">
		<div class="container">
			
			<!-- Card Pencarian Lacak Pesanan -->
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="tracking-search-card">
						<form action="{{ route('frontend.track') }}" method="GET">
							<div class="tracking-input-group">
								<i class="zmdi zmdi-search text-muted" style="font-size: 19px;"></i>
								<input type="text" name="order" class="form-control tracking-input" placeholder="Masukkan Nomor Invoice (cth: ORD-20261119-0001), Nomor Resi, atau No. WhatsApp" value="{{ $keyword }}" required autofocus>
								<button type="submit" class="tracking-btn">
									<i class="zmdi zmdi-search mr-1"></i> Lacak Pesanan
								</button>
							</div>
							<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 px-1" style="gap: 8px;">
								<small class="text-muted" style="font-size: 12px;">
									<i class="fa fa-info-circle text-primary mr-1"></i> Cari menggunakan <strong>Kode Invoice</strong>, <strong>Nomor Resi</strong>, atau <strong>Nomor WhatsApp</strong> yang digunakan saat checkout.
								</small>
								@if($searched)
								<a href="{{ route('frontend.track') }}" class="text-danger font-weight-bold" style="font-size: 12px;">
									<i class="fa fa-times-circle mr-1"></i> Reset Pencarian
								</a>
								@endif
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- Hasil Pencarian -->
			<div class="row justify-content-center m-t-35">
				<div class="col-lg-10">

					@if($searched && !$order)
						<!-- Pesanan Tidak Ditemukan -->
						<div class="track-result-card text-center p-all-40">
							<div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #fef3c7; color: #d97706;">
								<i class="zmdi zmdi-search-for" style="font-size: 36px;"></i>
							</div>
							<h4 class="font-weight-bold text-dark m-b-8">Pesanan Tidak Ditemukan</h4>
							<p class="text-muted m-b-20" style="max-width: 500px; margin-left: auto; margin-right: auto;">
								Tidak ditemukan transaksi dengan nomor atau kontak "<strong>{{ $keyword }}</strong>". Mohon periksa kembali nomor invoice atau hubungi layanan pelanggan kami.
							</p>
							<div>
								<a href="{{ store_wa_url('Halo Admin, saya ingin menanyakan status pesanan saya: ' . $keyword) }}" target="_blank" class="btn btn-success px-4 font-weight-bold" style="border-radius: 10px;">
									<i class="fab fa-whatsapp mr-1"></i> Tanya Admin via WhatsApp
								</a>
							</div>
						</div>

					@elseif($order)
						<!-- Kartu Detail Pesanan Ditemukan -->
						<div class="track-result-card m-b-30">
							<!-- Header Kartu -->
							<div class="card-header bg-white p-all-25 border-bottom d-flex flex-wrap justify-content-between align-items-center">
								<div>
									<div class="text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Pesanan</div>
									<h4 class="font-weight-bold text-dark font-monospace m-t-2" style="font-size: 1.35rem;">
										{{ $order->kode_order }}
									</h4>
									<div class="text-muted" style="font-size: 13px;">
										<i class="zmdi zmdi-calendar mr-1"></i> {{ $order->created_at->format('d F Y, H:i') }} WIB
									</div>
								</div>
								<div class="text-md-right mt-3 mt-md-0">
									<div class="text-muted" style="font-size: 12px; text-transform: uppercase;">Status Saat Ini</div>
									<div class="mt-1">
										@if($order->status == 'pending')
											<span class="badge badge-warning text-white px-3 py-2" style="font-size: 14px;"><i class="fas fa-clock mr-1"></i> {{ $order->status_label }}</span>
										@elseif($order->status == 'diproses')
											<span class="badge badge-info px-3 py-2" style="font-size: 14px;"><i class="fas fa-box mr-1"></i> Sedang Dikemas (Packing)</span>
										@elseif($order->status == 'dikirim')
											<span class="badge badge-primary px-3 py-2" style="font-size: 14px;"><i class="fas fa-truck mr-1"></i> Sedang Dikirim</span>
										@elseif($order->status == 'selesai')
											<span class="badge badge-success px-3 py-2" style="font-size: 14px;"><i class="fas fa-check-circle mr-1"></i> Selesai / Diterima</span>
										@else
											<span class="badge badge-danger px-3 py-2" style="font-size: 14px;"><i class="fas fa-times-circle mr-1"></i> Dibatalkan</span>
										@endif
									</div>
								</div>
							</div>

							<!-- Body: Stepper Progress -->
							<div class="card-body p-all-30 bg-light border-bottom">
								@if($order->status == 'dibatalkan')
									<div class="alert alert-danger mb-0 text-center" style="border-radius: 8px;">
										<i class="fa fa-exclamation-triangle mr-1"></i> Pesanan ini telah dibatalkan. Silakan hubungi admin jika ada kendala.
									</div>
								@else
									@php
										// Hitung status stepper
										$steps = [
											'created' => true,
											'processed' => in_array($order->status, ['diproses', 'dikirim', 'selesai']),
											'shipping' => in_array($order->status, ['dikirim', 'selesai']),
											'completed' => $order->status === 'selesai',
										];
									@endphp
									<div class="order-stepper">
										<!-- Step 1 -->
										<div class="step-item {{ $steps['created'] ? 'completed' : '' }}">
											<div class="step-icon">
												<i class="zmdi zmdi-receipt"></i>
											</div>
											<div class="step-title">Pesanan Dibuat</div>
											<div class="step-desc">{{ $order->created_at->format('d M H:i') }}</div>
										</div>

										<!-- Step 2 -->
										<div class="step-item {{ $steps['shipping'] || $steps['completed'] ? 'completed' : ($order->status == 'diproses' ? 'active' : '') }}">
											<div class="step-icon">
												<i class="zmdi zmdi-archive"></i>
											</div>
											<div class="step-title">Diproses & Dikemas</div>
											<div class="step-desc">Penyiapan Produk</div>
										</div>

										<!-- Step 3 -->
										<div class="step-item {{ $steps['completed'] ? 'completed' : ($order->status == 'dikirim' ? 'active' : '') }}">
											<div class="step-icon">
												<i class="zmdi zmdi-truck"></i>
											</div>
											<div class="step-title">Dalam Pengiriman</div>
											<div class="step-desc">{{ $order->ekspedisi ?? 'Kurir' }}</div>
										</div>

										<!-- Step 4 -->
										<div class="step-item {{ $steps['completed'] ? 'completed' : '' }}">
											<div class="step-icon">
												<i class="zmdi zmdi-check-all"></i>
											</div>
											<div class="step-title">Pesanan Tiba</div>
											<div class="step-desc">Diterima Pembeli</div>
										</div>
									</div>
								@endif
							</div>

							<!-- Informasi Pengiriman & Resi -->
							<div class="card-body p-all-30">
								<div class="row">
									<!-- Resi & Ekspedisi Highlight Card -->
									<div class="col-md-6 m-b-20">
										<div class="track-info-panel">
											<h6 class="font-weight-bold text-dark m-b-15">
												<i class="fa fa-truck text-primary mr-1"></i> Informasi Ekspedisi & Resi
											</h6>

											<div class="d-flex justify-content-between py-2 border-bottom">
												<span class="text-muted">Kurir / Ekspedisi:</span>
												<strong class="text-dark">
													{{ $order->kurir ?? $order->ekspedisi ?: 'Menunggu Penyerahan ke Kurir' }}
													@if($order->estimasi)
														<small class="text-muted font-weight-normal">({{ $order->estimasi }})</small>
													@endif
												</strong>
											</div>

											<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
												<span class="text-muted">Nomor Resi:</span>
												<div>
													@if($order->no_resi)
														<span class="badge badge-success px-2 py-1 font-monospace" id="resi-badge" style="font-size: 13px;">
															{{ $order->no_resi }}
														</span>
														<button type="button" class="btn btn-xs btn-outline-secondary ml-1" onclick="navigator.clipboard.writeText('{{ $order->no_resi }}'); alert('Nomor resi berhasil disalin!');" title="Salin Resi">
															<i class="fa fa-copy"></i>
														</button>
													@else
														<span class="text-muted font-italic" style="font-size: 13px;">Belum diterbitkan</span>
													@endif
												</div>
											</div>

											<div class="d-flex justify-content-between py-2 border-bottom">
												<span class="text-muted">Metode Pembayaran:</span>
												<span class="font-weight-bold" style="color: #717fe0;">
													{{ $order->metode_pembayaran == 'midtrans' ? 'Pembayaran Online (Instan)' : strtoupper($order->metode_pembayaran) }}
												</span>
											</div>

											<div class="d-flex justify-content-between py-2">
												<span class="text-muted">Total Tagihan:</span>
												<strong class="text-danger" style="font-size: 16px;">
													Rp {{ number_format($order->total_harga, 0, ',', '.') }}
												</strong>
											</div>
										</div>
									</div>

									<!-- Alamat Penerima -->
									<div class="col-md-6 m-b-20">
										<div class="track-info-panel">
											<h6 class="font-weight-bold text-dark m-b-15">
												<i class="zmdi zmdi-pin text-primary mr-1"></i> Tujuan Pengiriman
											</h6>

											<div class="mb-2">
												<span class="text-muted d-block" style="font-size: 12px;">Penerima:</span>
												<strong class="text-dark">{{ $order->nama_customer }}</strong>
											</div>

											<div class="mb-2">
												<span class="text-muted d-block" style="font-size: 12px;">Nomor Telepon / WhatsApp:</span>
												<span class="text-dark font-weight-bold">{{ $order->no_hp }}</span>
											</div>

											<div>
												<span class="text-muted d-block" style="font-size: 12px;">Alamat Lengkap:</span>
												<p class="text-dark mb-0" style="font-size: 14px; line-height: 1.5;">
													{{ $order->alamat }}
												</p>
												@if($order->destination_label)
												<small class="text-primary font-weight-bold d-block mt-1">
													<i class="zmdi zmdi-pin mr-1"></i> {{ $order->destination_label }}
												</small>
												@endif
											</div>

											@if($order->catatan)
											<div class="mt-2 pt-2 border-top">
												<small class="text-muted d-block">Catatan: <em>"{{ $order->catatan }}"</em></small>
											</div>
											@endif
										</div>
									</div>
								</div>

								@if(!empty($tracking) && !empty($tracking['manifest']))
								<!-- Riwayat Pelacakan Resi RajaOngkir -->
								<div class="track-info-panel m-b-20" style="background: #f8fafc; border-left: 4px solid #717fe0;">
									<div class="d-flex justify-content-between align-items-center mb-3">
										<h6 class="font-weight-bold text-dark mb-0">
											<i class="fa fa-route text-primary mr-1"></i> Riwayat Perjalanan Paket ({{ $order->ekspedisi ?? 'Kurir' }})
										</h6>
										@if(!empty($tracking['status']))
											<span class="badge badge-{{ !empty($tracking['delivered']) ? 'success' : 'info' }} px-2 py-1">{{ $tracking['status'] }}</span>
										@endif
									</div>
									<div class="d-flex flex-column" style="gap: 8px; max-height: 220px; overflow-y: auto;">
										@foreach($tracking['manifest'] as $m)
										<div class="d-flex justify-content-between align-items-start pb-2 border-bottom" style="font-size: 13px;">
											<div>
												<div class="text-dark font-weight-bold">{{ $m['description'] }}</div>
												@if(!empty($m['city']))
													<small class="text-muted"><i class="fa fa-map-marker mr-1"></i>{{ $m['city'] }}</small>
												@endif
											</div>
											<small class="text-muted text-nowrap ml-2">{{ $m['date'] }}</small>
										</div>
										@endforeach
									</div>
								</div>
								@endif

								<!-- Rincian Produk -->
								<h6 class="font-weight-bold text-dark m-t-15 m-b-15">
									<i class="zmdi zmdi-shopping-basket text-info mr-1"></i> Produk yang Dipesan
								</h6>

								<div class="table-responsive">
									<table class="table table-bordered table-hover mb-0">
										<thead class="bg-light text-center" style="font-size: 13px;">
											<tr>
												<th style="width: 50px;">#</th>
												<th style="width: 70px;">Foto</th>
												<th class="text-left">Nama Produk</th>
												<th style="width: 140px;">Harga Satuan</th>
												<th style="width: 80px;">Qty</th>
												<th style="width: 150px;">Subtotal</th>
											</tr>
										</thead>
										<tbody style="font-size: 14px;">
											@foreach($order->items as $item)
											<tr>
												<td class="text-center align-middle">{{ $loop->iteration }}</td>
												<td class="text-center align-middle p-1">
													@if($item->barang && $item->barang->gambar)
														<img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->nama_barang }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
													@else
														<div class="bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px; border-radius: 6px;">
															<i class="fa fa-box"></i>
														</div>
													@endif
												</td>
												<td class="align-middle">
													<div class="font-weight-bold">{{ $item->nama_barang }}</div>
													@if($item->variasi)
														<span class="badge badge-light border text-primary" style="font-size: 11px;">
															<i class="fa fa-tag mr-1"></i> {{ $item->variasi }}
														</span>
													@endif
													@if($order->status == 'selesai' && $item->barang_id)
														@php
															$hasReviewed = \App\Models\Review::where('order_id', $order->id_order)->where('barang_id', $item->barang_id)->exists();
														@endphp
														@if($hasReviewed)
															<span class="badge badge-light border text-success mt-1" style="font-size: 11px;">
																<i class="fa fa-check mr-1"></i> Sudah Diulas
															</span>
														@else
															<button type="button" class="btn btn-xs btn-outline-warning mt-1 font-weight-bold" onclick="openReviewModal('{{ $item->barang_id }}', '{{ addslashes($item->nama_barang) }}', '{{ $order->kode_order }}', '{{ addslashes($order->nama_customer) }}')" style="font-size: 11px; padding: 2px 8px; border-radius: 4px;">
																<i class="fa fa-star text-warning mr-1"></i> Tulis Ulasan
															</button>
														@endif
													@endif
												</td>
												<td class="text-center align-middle">
													Rp {{ number_format($item->harga, 0, ',', '.') }}
												</td>
												<td class="text-center align-middle font-weight-bold">
													{{ $item->qty }} pcs
												</td>
												<td class="text-right align-middle font-weight-bold text-dark">
													Rp {{ number_format($item->subtotal, 0, ',', '.') }}
												</td>
											</tr>
											@endforeach
										</tbody>
										<tfoot>
											@if(($order->diskon ?? 0) > 0)
											<tr>
												<td colspan="5" class="text-right align-middle text-success font-weight-bold">Diskon Voucher ({{ $order->kode_voucher }}):</td>
												<td class="text-right align-middle text-success font-weight-bold">- Rp {{ number_format($order->diskon, 0, ',', '.') }}</td>
											</tr>
											@endif
											<tr>
												<td colspan="5" class="text-right align-middle text-muted">Ongkos Kirim ({{ $order->kurir ?? $order->ekspedisi ?? 'Reguler' }}):</td>
												<td class="text-right align-middle font-weight-bold {{ ($order->ongkir ?? 0) > 0 ? 'text-dark' : 'text-success' }}">
													{{ ($order->ongkir ?? 0) > 0 ? 'Rp ' . number_format($order->ongkir, 0, ',', '.') : 'Gratis' }}
												</td>
											</tr>
											<tr class="bg-light font-weight-bold">
												<td colspan="5" class="text-right align-middle">TOTAL PEMBAYARAN:</td>
												<td class="text-right align-middle text-danger font-weight-bold" style="font-size: 16px;">
													Rp {{ number_format($order->total_harga, 0, ',', '.') }}
												</td>
											</tr>
										</tfoot>
									</table>
								</div>

								<!-- Action Bar Bawah -->
								<div class="d-flex flex-wrap justify-content-between align-items-center m-t-25 pt-3 border-top">
									<div>
										<a href="/frontend#product-grid" class="btn btn-outline-secondary btn-sm" style="border-radius: 6px;">
											<i class="fa fa-arrow-left mr-1"></i> Kembali Belanja
										</a>
									</div>
									<div class="d-flex gap-2 mt-2 mt-md-0">
										@if($order->status == 'dikirim')
										<form action="{{ route('frontend.track.confirm', $order->kode_order) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('Apakah paket pesanan Anda sudah sampai dan diterima dengan baik? Status pesanan akan diubah menjadi Selesai.')">
											@csrf
											<button type="submit" class="btn btn-success btn-sm font-weight-bold px-3" style="border-radius: 6px;">
												<i class="fa fa-check-circle mr-1"></i> Pesanan Sudah Diterima
											</button>
										</form>
										@endif

										@if($order->status == 'pending' && $order->metode_pembayaran == 'midtrans' && $order->snap_token)
										<button type="button" class="btn btn-primary btn-sm font-weight-bold px-3 mr-2" onclick="payWithSnap('{{ $order->snap_token }}', '{{ $order->kode_order }}')" style="background-color: #717fe0; border: none; border-radius: 6px;">
											<i class="fa fa-credit-card mr-1"></i> Bayar Sekarang (Online)
										</button>
										<button type="button" class="btn btn-outline-warning btn-sm font-weight-bold px-3 mr-2" onclick="promptTrackSwitchPayment('{{ $order->kode_order }}')" style="border-radius: 6px;">
											<i class="zmdi zmdi-refresh mr-1"></i> Ganti Metode Bayar
										</button>
										@endif

										@php
											$waInquiry = "Halo Admin Toko, saya ingin menanyakan pesanan saya dengan nomor *" . $order->kode_order . "* (Status: " . ucfirst($order->status) . ").";
											$waInquiryUrl = store_wa_url($waInquiry);
										@endphp
										<a href="{{ $waInquiryUrl }}" target="_blank" class="btn btn-success btn-sm font-weight-bold px-3" style="border-radius: 6px;">
											<i class="fab fa-whatsapp mr-1"></i> Hubungi Admin Toko
										</a>
									</div>
								</div>
							</div>
						</div>

					@else
						<!-- Panduan Awal (Belum Melakukan Pencarian) -->
						<div class="track-result-card p-all-35 text-center">
							<h5 class="font-weight-bold text-dark m-b-6">Tahapan Alur Pesanan Anda</h5>
							<p class="text-muted small m-b-25">Setiap pesanan melalui 4 tahapan transparan hingga tiba di alamat Anda</p>
							<div class="row">
								<div class="col-md-3 col-6 m-b-20">
									<div class="track-guide-card">
										<div class="track-guide-icon" style="background: #eef2ff; color: #4f46e5;"><i class="zmdi zmdi-receipt"></i></div>
										<h6 class="font-weight-bold text-dark" style="font-size: 14px;">1. Pesanan Masuk</h6>
										<small class="text-muted">Data pesanan tercatat di sistem toko.</small>
									</div>
								</div>
								<div class="col-md-3 col-6 m-b-20">
									<div class="track-guide-card">
										<div class="track-guide-icon" style="background: #e0f2fe; color: #0284c7;"><i class="zmdi zmdi-archive"></i></div>
										<h6 class="font-weight-bold text-dark" style="font-size: 14px;">2. Pengemasan</h6>
										<small class="text-muted">Barang disiapkan & dikemas rapi.</small>
									</div>
								</div>
								<div class="col-md-3 col-6 m-b-20">
									<div class="track-guide-card">
										<div class="track-guide-icon" style="background: #fef3c7; color: #d97706;"><i class="zmdi zmdi-truck"></i></div>
										<h6 class="font-weight-bold text-dark" style="font-size: 14px;">3. Dikirim Kurir</h6>
										<small class="text-muted">Resi diterbitkan & paket dalam jalan.</small>
									</div>
								</div>
								<div class="col-md-3 col-6 m-b-20">
									<div class="track-guide-card">
										<div class="track-guide-icon" style="background: #d1fae5; color: #059669;"><i class="zmdi zmdi-check-circle"></i></div>
										<h6 class="font-weight-bold" style="font-size: 14px;">4. Diterima</h6>
										<small class="text-muted">Paket tiba di alamat Anda dengan selamat.</small>
									</div>
								</div>
							</div>
						</div>
					@endif

				</div>
			</div>

		</div>
	</section>

	@include('frontend.partials.footer')

<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	@include('frontend.partials.toast-scripts')
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/js/main.js') }}"></script>

	<!-- Modal Tulis Ulasan dari Tracking -->
	<div class="modal fade" id="modalTrackReview" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
				<div class="modal-header bg-dark text-white p-lr-25 p-tb-15" style="background-color: #222 !important;">
					<h5 class="modal-title font-weight-bold text-white" style="font-size: 1.1rem;">
						<i class="fa fa-star text-warning mr-1"></i> Tulis Ulasan Produk
					</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="formTrackReview" onsubmit="submitTrackReview(event)">
					@csrf
					<input type="hidden" name="barang_id" id="track_review_barang_id" value="">
					<input type="hidden" name="order_ref" id="track_review_order_ref" value="">
					<div class="modal-body p-all-25" style="background: #fafafa;">
						<div class="mb-3 p-2 bg-white rounded border">
							<small class="text-muted d-block">Produk yang Diulas:</small>
							<strong class="text-dark" id="track_review_product_name">Nama Produk</strong>
						</div>

						<div class="form-group mb-3">
							<label class="small font-weight-bold text-dark mb-1">Rating Kepuasan <span class="text-danger">*</span>:</label>
							<div class="d-flex align-items-center" style="font-size: 1.5rem; color: #ffc107; cursor: pointer;">
								<i class="fa fa-star js-tstar" data-rating="1" onclick="selectTrackStar(1)"></i>
								<i class="fa fa-star js-tstar" data-rating="2" onclick="selectTrackStar(2)"></i>
								<i class="fa fa-star js-tstar" data-rating="3" onclick="selectTrackStar(3)"></i>
								<i class="fa fa-star js-tstar" data-rating="4" onclick="selectTrackStar(4)"></i>
								<i class="fa fa-star js-tstar" data-rating="5" onclick="selectTrackStar(5)"></i>
								<span class="small text-muted font-weight-bold ml-2" id="track_star_text">5 Bintang (Sangat Puas)</span>
							</div>
							<input type="hidden" name="rating" id="track_rating_val" value="5">
						</div>

						<div class="form-group mb-3">
							<label class="small font-weight-bold text-dark mb-1">Nama Lengkap Anda <span class="text-danger">*</span>:</label>
							<input type="text" name="nama_reviewer" id="track_review_nama" class="form-control" required style="border-radius: 6px;">
						</div>

						<div class="form-group mb-3">
							<label class="small font-weight-bold text-dark mb-1">Pengalaman / Ulasan Produk <span class="text-danger">*</span>:</label>
							<textarea name="komentar" id="track_review_komentar" rows="3" class="form-control" placeholder="Ceritakan kepuasan Anda mengenai produk ini..." required style="border-radius: 6px;"></textarea>
						</div>
					</div>
					<div class="modal-footer bg-light p-lr-25 p-tb-12">
						<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary btn-sm font-weight-bold" id="btn-submit-track-review">
							<i class="fa fa-paper-plane mr-1"></i> Kirim Ulasan
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function openReviewModal(barangId, namaBarang, kodeOrder, namaCustomer) {
			$('#track_review_barang_id').val(barangId);
			$('#track_review_order_ref').val(kodeOrder);
			$('#track_review_product_name').text(namaBarang);
			$('#track_review_nama').val(namaCustomer);
			$('#track_review_komentar').val('');
			selectTrackStar(5);
			$('#modalTrackReview').modal('show');
		}

		function selectTrackStar(val) {
			$('#track_rating_val').val(val);
			var labels = ['', '1 Bintang (Sangat Buruk)', '2 Bintang (Kurang)', '3 Bintang (Cukup)', '4 Bintang (Bagus)', '5 Bintang (Sangat Puas)'];
			$('#track_star_text').text(labels[val] || (val + ' Bintang'));
			$('.js-tstar').each(function() {
				var r = parseInt($(this).data('rating'));
				if (r <= val) {
					$(this).removeClass('fa-star-o text-muted').addClass('fa-star text-warning');
				} else {
					$(this).removeClass('fa-star text-warning').addClass('fa-star-o text-muted');
				}
			});
		}

		function submitTrackReview(e) {
			e.preventDefault();
			var $btn = $('#btn-submit-track-review');
			var orig = $btn.html();
			$btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Memverifikasi...');

			var data = {
				_token: '{{ csrf_token() }}',
				barang_id: $('#track_review_barang_id').val(),
				order_ref: $('#track_review_order_ref').val(),
				nama_reviewer: $('#track_review_nama').val(),
				rating: $('#track_rating_val').val(),
				komentar: $('#track_review_komentar').val()
			};

			$.ajax({
				url: '/review/store',
				type: 'POST',
				data: data,
				success: function(res) {
					$btn.prop('disabled', false).html(orig);
					$('#modalTrackReview').modal('hide');
					swal("Ulasan Berhasil!", res.message, "success").then(function() {
						location.reload();
					});
				},
				error: function(xhr) {
					$btn.prop('disabled', false).html(orig);
					var msg = "Gagal mengirim ulasan.";
					if (xhr.responseJSON && xhr.responseJSON.message) {
						msg = xhr.responseJSON.message;
					}
					swal("Verifikasi Ulasan", msg, "warning");
				}
			});
		}

		// Midtrans pay handler & auto-sync jika pesanan pending
		@if(isset($order) && $order && $order->status === 'pending' && $order->metode_pembayaran === 'midtrans')
		(function() {
			var trackKode = '{{ $order->kode_order }}';
			setInterval(function() {
				$.post('/midtrans/sync/' + trackKode, { _token: '{{ csrf_token() }}' }, function(res) {
					if (res && res.status && res.status !== 'pending') {
						try {
							if (window.snap && typeof window.snap.hide === 'function') {
								window.snap.hide();
							}
						} catch (e) {}
						location.reload();
					}
				});
			}, 4000);
		})();
		@endif

		function payWithSnap(snapToken, kodeOrder) {
			if (!snapToken) return;
			if (typeof window.snap === 'undefined') {
				alert('Modul pembayaran sedang dimuat, silakan coba sesaat lagi.');
				return;
			}

			try {
				if (window.snap && typeof window.snap.hide === 'function') {
					window.snap.hide();
				}
			} catch (e) {}
			$('#snap-midtrans, #snap-container').remove();

			try {
				window.snap.pay(snapToken, {
					onSuccess: function(result) {
						$.post('/midtrans/sync/' + kodeOrder, { _token: '{{ csrf_token() }}' }, function() {
							location.reload();
						});
					},
					onPending: function(result) {
						$.post('/midtrans/sync/' + kodeOrder, { _token: '{{ csrf_token() }}' }, function() {
							location.reload();
						});
					},
					onError: function(result) {
						swal("Pembayaran Gagal", "Pembayaran gagal diproses.", "error");
					},
					onClose: function() {
						$.post('/midtrans/sync/' + kodeOrder, { _token: '{{ csrf_token() }}' }, function() {
							location.reload();
						});
					}
				});
			} catch (err) {
				console.error('Snap pay error:', err);
				swal("Gagal Membuka Pembayaran", "Tidak dapat membuka modul pembayaran: " + (err.message || err), "error");
			}
		}

		function promptTrackSwitchPayment(kodeOrder) {
			swal({
				title: "Ganti Metode Pembayaran",
				text: "Ingin mengganti metode pembayaran?\n\n• Pilih Channel Online Lain: Reset dan buka kembali pilihan QRIS / VA Bank.\n• Ubah ke COD: Bayar tunai saat kurir tiba di alamat.",
				buttons: {
					cancel: {
						text: "Batal",
						visible: true,
						className: "btn btn-light"
					},
					cod: {
						text: "Ubah ke COD",
						value: "cod",
						className: "btn btn-success"
					},
					reset: {
						text: "Pilih Channel Online Lain",
						value: "reset_online",
						className: "btn btn-primary"
					}
				}
			}).then(function(val) {
				if (!val) return;

				swal({
					title: "Memproses...",
					text: "Sedang memperbarui metode pembayaran...",
					buttons: false,
					closeOnClickOutside: false
				});

				$.post('/pesanan/switch-payment/' + encodeURIComponent(kodeOrder), {
					_token: '{{ csrf_token() }}',
					target: val
				}, function(resp) {
					if (typeof swal !== 'undefined' && typeof swal.close === 'function') {
						try { swal.close(); } catch (e) {}
					}
					if (resp && resp.success) {
						if (resp.mode === 'cod') {
							swal("Metode Berhasil Diubah!", resp.message, "success").then(function() {
								location.reload();
							});
						} else if (resp.mode === 'online' && resp.snap_token) {
							$('#snap-midtrans, #snap-container').remove();
							setTimeout(function() {
								payWithSnap(resp.snap_token, kodeOrder);
							}, 250);
						}
					} else {
						swal("Gagal", resp.message || "Gagal mengubah metode pembayaran.", "error");
					}
				}).fail(function(xhr) {
					if (typeof swal !== 'undefined' && typeof swal.close === 'function') {
						try { swal.close(); } catch (e) {}
					}
					var err = xhr.responseJSON ? xhr.responseJSON.message : "Terjadi kesalahan pada server.";
					swal("Gagal", err, "error");
				});
			});
		}
	</script>
</body>
</html>

