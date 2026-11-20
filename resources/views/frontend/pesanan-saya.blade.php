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
	<style>
		body {
			background-color: #f8fafc;
		}

		/* Modern Hero Header */
		.orders-hero-modern {
			background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 55%, #f5f3ff 100%);
			border-bottom: 1px solid #e2e8f0;
			padding: 28px 0;
			margin-bottom: 28px;
		}
		.orders-breadcrumb {
			font-size: 13px;
			color: #64748b;
			margin-bottom: 6px;
		}
		.orders-breadcrumb a {
			color: #64748b;
			text-decoration: none;
		}
		.orders-breadcrumb a:hover {
			color: #4f46e5;
		}

		/* Sidebar Card */
		.account-sidebar-card {
			background: #ffffff;
			border-radius: 18px;
			border: 1px solid #e2e8f0;
			box-shadow: 0 8px 24px -10px rgba(15, 23, 42, 0.06);
			padding: 24px 18px;
		}
		.account-avatar-fallback {
			width: 76px;
			height: 76px;
			border-radius: 50%;
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff;
			font-size: 26px;
			font-weight: 800;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 8px 18px -4px rgba(79, 70, 229, 0.4);
		}
		.account-nav-link {
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 10px 14px;
			border-radius: 12px;
			font-size: 13.5px;
			font-weight: 600;
			color: #475569;
			text-decoration: none !important;
			transition: all 0.2s ease;
			margin-bottom: 4px;
		}
		.account-nav-link:hover {
			background: #f1f5f9;
			color: #0f172a;
		}
		.account-nav-link.active {
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff;
			box-shadow: 0 6px 14px -4px rgba(79, 70, 229, 0.4);
		}

		/* Status Filter Pills */
		.order-filter-pills {
			display: flex;
			flex-wrap: wrap;
			gap: 8px;
			margin-bottom: 18px;
		}
		.order-filter-pill {
			border: 1px solid #e2e8f0;
			background: #ffffff;
			color: #475569;
			font-size: 12.5px;
			font-weight: 600;
			padding: 6px 14px;
			border-radius: 99px;
			cursor: pointer;
			transition: all 0.2s ease;
		}
		.order-filter-pill:hover {
			border-color: #c7d2fe;
			color: #4f46e5;
		}
		.order-filter-pill.active {
			background: #4f46e5;
			border-color: #4f46e5;
			color: #ffffff;
			box-shadow: 0 4px 12px -3px rgba(79, 70, 229, 0.35);
		}

		/* Modern Order Card */
		.order-card-modern {
			background: #ffffff;
			border-radius: 18px;
			border: 1px solid #e2e8f0;
			box-shadow: 0 8px 24px -12px rgba(15, 23, 42, 0.08);
			margin-bottom: 20px;
			overflow: hidden;
			transition: all 0.2s ease;
		}
		.order-card-modern:hover {
			box-shadow: 0 14px 32px -12px rgba(15, 23, 42, 0.12);
		}
		.order-header {
			background: #f8fafc;
			padding: 14px 22px;
			border-bottom: 1px solid #e2e8f0;
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;
			align-items: center;
			gap: 10px;
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
			gap: 12px;
		}
		.status-badge {
			padding: 5px 12px;
			border-radius: 99px;
			font-size: 12px;
			font-weight: 700;
			display: inline-flex;
			align-items: center;
			gap: 6px;
		}
		.status-pending { background: #fef3c7; color: #b45309; }
		.status-diproses { background: #e0f2fe; color: #0369a1; }
		.status-dikirim { background: #e0e7ff; color: #4338ca; }
		.status-selesai { background: #d1fae5; color: #047857; }
		.status-dibatalkan { background: #fee2e2; color: #b91c1c; }

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
		}
		.variant-pill {
			display: inline-block;
			background: #eef2ff;
			color: #4f46e5;
			font-size: 11px;
			font-weight: 600;
			padding: 2px 8px;
			border-radius: 6px;
			margin-top: 4px;
		}
		.btn-modern-primary {
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff !important;
			border: none;
			border-radius: 10px;
			font-weight: 600;
			box-shadow: 0 4px 12px -3px rgba(79, 70, 229, 0.4);
		}
		.btn-modern-primary:hover {
			opacity: 0.94;
			color: #ffffff !important;
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
				<a href="/">Home</a> <span class="mx-1">/</span> <span class="text-dark font-weight-bold">Pesanan Saya</span>
			</div>
			<div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 12px;">
				<div>
					<h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.75rem; letter-spacing: -0.02em;">Riwayat Pesanan Anda</h2>
					<p class="text-muted mb-0" style="font-size: 14px;">Kelola, pantau status pengiriman, cetak invoice, dan selesaikan pembayaran pesanan Anda.</p>
				</div>
				<a href="/shop" class="btn btn-modern-primary btn-sm px-4 py-2">
					<i class="fa fa-shopping-bag mr-1"></i> Belanja Lagi
				</a>
			</div>
		</div>
	</section>

	<!-- Content Area -->
	<div class="container pb-5">
		<div class="row justify-content-center">
			<!-- Daftar Pesanan (Full-Width Centered) -->
			<div class="col-lg-10">
				@if(!$orders->isEmpty())
				<!-- Filter Status Cepat -->
				<div class="order-filter-pills">
					<button type="button" class="order-filter-pill active" onclick="filterOrders('all', this)">Semua</button>
					<button type="button" class="order-filter-pill" onclick="filterOrders('pending', this)">Menunggu Pembayaran</button>
					<button type="button" class="order-filter-pill" onclick="filterOrders('diproses', this)">Sedang Dikemas</button>
					<button type="button" class="order-filter-pill" onclick="filterOrders('dikirim', this)">Dikirim</button>
					<button type="button" class="order-filter-pill" onclick="filterOrders('selesai', this)">Selesai</button>
					<button type="button" class="order-filter-pill" onclick="filterOrders('dibatalkan', this)">Dibatalkan</button>
				</div>
				@endif

				@if($orders->isEmpty())
				<div class="bg-white p-5 border text-center" style="border-radius: 18px; border-color: #e2e8f0 !important;">
					<div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #eef2ff; color: #4f46e5;">
						<i class="zmdi zmdi-shopping-cart-plus" style="font-size: 34px;"></i>
					</div>
					<h5 class="font-weight-bold text-dark">Belum Ada Riwayat Pesanan</h5>
					<p class="text-muted mb-4">Anda belum pernah melakukan pemesanan barang di toko kami.</p>
					<a href="/shop" class="btn btn-modern-primary px-4 py-2">
						Mulai Belanja Sekarang
					</a>
				</div>
				@else
				@foreach($orders as $o)
				<div class="order-card-modern js-order-card" data-status="{{ $o->status }}">
					<!-- Order Header -->
					<div class="order-header">
						<div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
							<span class="text-muted small">Kode Order:</span>
							<strong class="text-dark" style="letter-spacing: 0.4px;">{{ $o->kode_order }}</strong>
							<span class="text-muted small"><i class="fa fa-calendar-alt mr-1"></i> {{ $o->created_at->format('d M Y, H:i') }} WIB</span>
						</div>
						<div>
							@if($o->status === 'pending')
								<span class="status-badge status-pending">
									<i class="fa fa-clock-o"></i> Menunggu Pembayaran
								</span>
							@elseif($o->status === 'diproses')
								<span class="status-badge status-diproses">
									<i class="fa fa-box"></i> Sedang Dikemas
								</span>
							@elseif($o->status === 'dikirim')
								<span class="status-badge status-dikirim">
									<i class="fa fa-truck"></i> Sedang Dikirim
								</span>
							@elseif($o->status === 'selesai')
								<span class="status-badge status-selesai">
									<i class="fa fa-check-circle"></i> Selesai
								</span>
							@else
								<span class="status-badge status-dibatalkan">
									<i class="fa fa-times-circle"></i> Dibatalkan
								</span>
							@endif
						</div>
					</div>

					<!-- Order Body -->
					<div class="order-body">
						@foreach($o->items as $item)
						<div class="item-row">
							@if($item->barang && $item->barang->gambar)
							<img src="{{ asset('storage/' . $item->barang->gambar) }}" class="item-img" alt="{{ $item->nama_barang }}">
							@else
							<div class="item-img d-flex align-items-center justify-content-center text-muted">
								<i class="fa fa-image fa-lg"></i>
							</div>
							@endif
							<div class="flex-grow-1" style="min-width: 0;">
								<div class="font-weight-bold text-dark text-truncate" style="font-size: 15px;">{{ $item->nama_barang }}</div>
								@if(!empty($item->variasi))
								<span class="variant-pill">
									<i class="fa fa-tag mr-1"></i> {{ $item->variasi }}
								</span>
								@endif
								<div class="text-muted small mt-1">
									{{ $item->qty }} barang × Rp {{ number_format($item->harga, 0, ',', '.') }}
								</div>
							</div>
							<div class="text-right">
								<span class="font-weight-bold text-dark">
									Rp {{ number_format($item->subtotal, 0, ',', '.') }}
								</span>
							</div>
						</div>
						@endforeach

						@if($o->diskon > 0)
						<div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top text-success small font-weight-bold">
							<span><i class="fa fa-ticket mr-1"></i> Voucher Promo ({{ $o->kode_voucher }})</span>
							<span>- Rp {{ number_format($o->diskon, 0, ',', '.') }}</span>
						</div>
						@endif

						<div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top text-muted small">
							<span><i class="fa fa-truck mr-1 text-primary"></i> Ongkos Kirim ({{ $o->kurir ?? $o->ekspedisi ?? 'Reguler' }})</span>
							<span class="font-weight-bold {{ ($o->ongkir ?? 0) > 0 ? 'text-dark' : 'text-success' }}">
								{{ ($o->ongkir ?? 0) > 0 ? 'Rp ' . number_format($o->ongkir, 0, ',', '.') : 'Gratis Ongkir' }}
							</span>
						</div>

						<!-- Info Pengiriman jika ada resi -->
						@if($o->no_resi)
						<div class="mt-3 p-3" style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; border-left: 4px solid #4f46e5;">
							<div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 10px;">
								<div>
									<span class="text-muted small">Ekspedisi & Nomor Resi:</span>
									<div class="font-weight-bold text-dark">
										<i class="fa fa-truck mr-1 text-primary"></i> {{ $o->ekspedisi ?? 'Kurir' }} -
										<span style="color: #4f46e5;">{{ $o->no_resi }}</span>
									</div>
								</div>
								<a href="/lacak-pesanan?order={{ $o->kode_order }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
									<i class="fa fa-search mr-1"></i> Lacak Paket
								</a>
							</div>
						</div>
						@endif
					</div>

					<!-- Order Footer -->
					<div class="order-footer">
						<div>
							<span class="text-muted small">Metode Pembayaran:</span>
							<strong class="text-uppercase ml-1" style="color: #4f46e5; font-size: 13px;">{{ $o->metode_pembayaran }}</strong>
						</div>
						<div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
							<div class="mr-2">
								<span class="text-muted small mr-1">Total Pesanan:</span>
								<span class="font-weight-bold" style="font-size: 16px; color: #0f172a;">
									Rp {{ number_format($o->total_harga, 0, ',', '.') }}
								</span>
							</div>

							<!-- Actions -->
							@if($o->status === 'pending' && $o->metode_pembayaran === 'midtrans' && !empty($o->snap_token))
							<button type="button" class="btn btn-sm btn-success px-3 font-weight-bold" style="border-radius: 10px;" onclick="paySnap('{{ $o->snap_token }}', '{{ $o->kode_order }}')">
								<i class="fa fa-credit-card mr-1"></i> Bayar Sekarang
							</button>
							@endif

							@if($o->status === 'pending')
							<form action="{{ route('frontend.order.cancel', $o->kode_order) }}" method="POST" class="js-form-cancel-order" data-code="{{ $o->kode_order }}" style="display: inline-block;">
								@csrf
								<button type="button" class="btn btn-sm btn-outline-danger px-3 js-btn-cancel-order" style="border-radius: 10px;">
									<i class="fa fa-times mr-1"></i> Batalkan
								</button>
							</form>
							@endif

							@if($o->status === 'dikirim')
							<form action="{{ route('frontend.track.confirm', $o->kode_order) }}" method="POST" class="js-form-confirm-order" data-code="{{ $o->kode_order }}" style="display: inline-block;">
								@csrf
								<button type="button" class="btn btn-sm btn-modern-primary px-3 js-btn-confirm-order">
									<i class="fa fa-check mr-1"></i> Konfirmasi Diterima
								</button>
							</form>
							@endif

							<a href="{{ route('frontend.order.invoice', $o->kode_order) }}" target="_blank" class="btn btn-sm btn-outline-primary px-3" style="border-radius: 10px;">
								<i class="fa fa-print mr-1"></i> Cetak Invoice
							</a>

							<a href="/lacak-pesanan?order={{ $o->kode_order }}" class="btn btn-sm btn-light border px-3" style="border-radius: 10px;">
								<i class="fa fa-truck mr-1"></i> Lacak
							</a>
						</div>
					</div>
				</div>
				@endforeach

				<div id="order-filter-empty" class="bg-white p-5 border text-center" style="display: none; border-radius: 18px; border-color: #e2e8f0 !important;">
					<p class="text-muted mb-0">Tidak ada pesanan dengan status tersebut.</p>
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
		function filterOrders(status, btn) {
			$('.order-filter-pill').removeClass('active');
			$(btn).addClass('active');

			var visibleCount = 0;
			$('.js-order-card').each(function() {
				var st = $(this).data('status');
				if (status === 'all' || st === status) {
					$(this).show();
					visibleCount++;
				} else {
					$(this).hide();
				}
			});

			if (visibleCount === 0) {
				$('#order-filter-empty').show();
			} else {
				$('#order-filter-empty').hide();
			}
		}

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
				confirmButtonText: '<i class="fa fa-times mr-1"></i> Ya, Batalkan',
				cancelButtonText: 'Kembali',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});

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
				confirmButtonText: '<i class="fa fa-check mr-1"></i> Ya, Sudah Diterima',
				cancelButtonText: 'Belum',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});
	</script>
</body>
</html>
