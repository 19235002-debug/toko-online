@php
	$footerSetting = \App\Models\Setting::getSettings();
	$footerCats = \App\Models\Kategori::main()->get();
@endphp

<style>
	.footer-modern {
		background: #0f172a;
		color: #cbd5e1;
		border-top: 1px solid #1e293b;
		padding: 56px 0 28px 0;
		margin-top: 40px;
	}
	.footer-brand-title {
		font-size: 1.25rem;
		font-weight: 800;
		color: #ffffff;
		letter-spacing: -0.02em;
		margin-bottom: 12px;
	}
	.footer-brand-title span {
		color: #818cf8;
	}
	.footer-heading {
		font-size: 12.5px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.06em;
		color: #ffffff;
		margin-bottom: 16px;
	}
	.footer-link-list {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.footer-link-list li {
		margin-bottom: 10px;
	}
	.footer-link-list a {
		color: #94a3b8;
		font-size: 13.5px;
		text-decoration: none;
		transition: color 0.2s ease, transform 0.2s ease;
		display: inline-flex;
		align-items: center;
		gap: 6px;
	}
	.footer-link-list a:hover {
		color: #ffffff;
		transform: translateX(3px);
	}
	.footer-contact-item {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		font-size: 13.5px;
		color: #94a3b8;
		margin-bottom: 10px;
		line-height: 1.5;
	}
	.footer-contact-item i {
		color: #818cf8;
		margin-top: 3px;
		width: 16px;
		text-align: center;
		flex-shrink: 0;
	}
	.footer-pay-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		background: #1e293b;
		border: 1px solid #334155;
		color: #e2e8f0;
		font-size: 11.5px;
		font-weight: 600;
		padding: 5px 11px;
		border-radius: 8px;
	}
	.footer-wa-btn {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		background: #10b981;
		color: #ffffff !important;
		font-size: 12.5px;
		font-weight: 700;
		padding: 8px 16px;
		border-radius: 10px;
		text-decoration: none !important;
		margin-top: 6px;
		transition: all 0.2s ease;
	}
	.footer-wa-btn:hover {
		background: #059669;
		transform: translateY(-1px);
	}
	.footer-bottom-bar {
		border-top: 1px solid #1e293b;
		margin-top: 36px;
		padding-top: 20px;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		align-items: center;
		gap: 12px;
		font-size: 12.5px;
		color: #64748b;
	}
</style>

<footer id="footer-contact" class="footer-modern">
	<div class="container">
		<div class="row">
			<!-- Col 1: Brand & Deskripsi (dari Setting Admin) -->
			<div class="col-md-6 col-lg-4 mb-4">
				<div class="footer-brand-title">
					{{ $footerSetting->nama_toko ?? 'Coza Store' }}
				</div>
				<p style="font-size: 13.5px; line-height: 1.65; color: #94a3b8; margin-bottom: 16px;">
					{{ $footerSetting->deskripsi_toko ?? 'Toko online busana dan aksesoris modern dengan produk berkualitas tinggi.' }}
				</p>
				<div class="d-flex flex-wrap" style="gap: 8px;">
					<span class="footer-pay-pill">
						<i class="fa fa-shield-alt text-info"></i> Pembayaran Online (QRIS &amp; VA)
					</span>
					<span class="footer-pay-pill">
						<i class="fa fa-money-bill-wave text-success"></i> COD (Bayar di Tempat)
					</span>
				</div>
			</div>

			<!-- Col 2: Kategori Produk (dari Admin Kategori) -->
			<div class="col-6 col-md-3 col-lg-2 mb-4">
				<h6 class="footer-heading">Kategori</h6>
				<ul class="footer-link-list">
					@forelse($footerCats as $fCat)
						<li>
							<a href="{{ route('frontend.shop', ['kategori' => $fCat->nama_kategori]) }}">
								{{ $fCat->nama_kategori }}
							</a>
						</li>
					@empty
						<li><a href="/shop">Semua Produk</a></li>
					@endforelse
				</ul>
			</div>

			<!-- Col 3: Navigasi & Layanan -->
			<div class="col-6 col-md-3 col-lg-3 mb-4">
				<h6 class="footer-heading">Navigasi</h6>
				<ul class="footer-link-list">
					<li><a href="/">Beranda</a></li>
					<li><a href="/shop">Katalog Produk</a></li>
					<li><a href="/lacak-pesanan">Lacak Pesanan</a></li>
					@auth
						@if(in_array(auth()->user()->role, ['admin', 'superadmin']))
							<li><a href="/dashboard">Dashboard Admin</a></li>
						@else
							<li><a href="/pesanan-saya">Pesanan Saya</a></li>
							<li><a href="{{ route('profile.edit') }}">Profil Saya</a></li>
						@endif
					@else
						<li><a href="/login">Masuk / Daftar</a></li>
					@endauth
				</ul>
			</div>

			<!-- Col 4: Hubungi Kami (dari Setting Admin) -->
			<div class="col-md-12 col-lg-3 mb-4">
				<h6 class="footer-heading">Hubungi Kami</h6>
				@if(!empty($footerSetting->alamat_toko))
				<div class="footer-contact-item">
					<i class="fa fa-map-marker-alt"></i>
					<span>{{ $footerSetting->alamat_toko }}</span>
				</div>
				@endif
				@if(!empty($footerSetting->email_toko))
				<div class="footer-contact-item">
					<i class="fa fa-envelope"></i>
					<span>{{ $footerSetting->email_toko }}</span>
				</div>
				@endif
				@if(!empty($footerSetting->no_whatsapp))
				<div class="footer-contact-item">
					<i class="fa fa-phone"></i>
					<span>{{ $footerSetting->no_whatsapp }}</span>
				</div>
				@endif
				<a href="{{ store_wa_url('Halo Admin ' . ($footerSetting->nama_toko ?? 'Coza Store') . ', saya ingin bertanya.') }}" target="_blank" class="footer-wa-btn">
					<i class="fab fa-whatsapp" style="font-size: 15px;"></i> Chat WhatsApp Admin
				</a>
			</div>
		</div>

		<div class="footer-bottom-bar">
			<div>
				&copy; {{ date('Y') }} <strong class="text-white">{{ $footerSetting->nama_toko ?? 'Coza Store' }}</strong>. All rights reserved.
			</div>
			<div>
				Belanja aman, cepat, &amp; terpercaya.
			</div>
		</div>
	</div>
</footer>

