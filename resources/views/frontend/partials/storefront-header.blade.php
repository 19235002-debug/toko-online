	<!-- Header -->
	<header class="{{ $headerClass ?? '' }}">
		<!-- Header desktop -->
		<div class="container-menu-desktop">
			<div class="wrap-menu-desktop">
				<nav class="limiter-menu-desktop container">
					<!-- Logo desktop -->
					<a href="/" class="logo" style="display: inline-flex; align-items: center; text-decoration: none;">
						@include('frontend.partials.brand-logo', ['maxHeight' => '38px', 'fontSize' => '1.25rem'])
					</a>

					<!-- Menu desktop -->
					<div class="menu-desktop">
						<ul class="main-menu">
							<li class="{{ request()->is('/') || request()->is('frontend') ? 'active-menu' : '' }}">
								<a href="/">Home</a>
							</li>
							<li class="{{ request()->is('shop*') ? 'active-menu' : '' }}">
								<a href="/shop">Shop</a>
							</li>
							<li class="{{ request()->is('lacak-pesanan*') ? 'active-menu' : '' }}">
								<a href="/lacak-pesanan">Lacak Pesanan</a>
							</li>
							<li>
								<a href="#footer-contact">Hubungi Kami</a>
							</li>
						</ul>
					</div>

					<!-- Icon header -->
					<div class="wrap-icon-header flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
							<i class="zmdi zmdi-search"></i>
						</div>

						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart js-cart-badge pointer" data-notify="0" title="Keranjang Belanja">
							<i class="zmdi zmdi-shopping-cart"></i>
						</div>

						<a href="javascript:void(0);" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-wishlist js-wishlist-badge pointer" data-notify="0" title="Wishlist">
							<i class="zmdi zmdi-favorite-outline"></i>
						</a>

						@include('frontend.partials.user-dropdown', ['idSuffix' => 'desktop', 'extraClass' => 'p-l-18'])
					</div>
				</nav>
			</div>
		</div>

		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<div class="logo-mobile">
				<a href="/" style="display: inline-flex; align-items: center; text-decoration: none;">
					@include('frontend.partials.brand-logo', ['maxHeight' => '30px', 'fontSize' => '1.05rem'])
				</a>
			</div>

			<div class="wrap-icon-header flex-w flex-r-m m-r-15">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
					<i class="zmdi zmdi-search"></i>
				</div>

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart js-cart-badge pointer" data-notify="0" title="Keranjang Belanja">
					<i class="zmdi zmdi-shopping-cart"></i>
				</div>

				<a href="javascript:void(0);" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-wishlist js-wishlist-badge pointer" data-notify="0" title="Wishlist">
					<i class="zmdi zmdi-favorite-outline"></i>
				</a>

				@include('frontend.partials.user-dropdown', ['idSuffix' => 'mobile', 'extraClass' => 'm-l-5'])
			</div>

			<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
		</div>

		<!-- Menu Mobile -->
		<div class="menu-mobile">
			<ul class="topbar-mobile">
				<li>
					<div class="left-top-bar">
						Ongkir Real-Time &amp; Resi Otomatis &bull; Pembayaran Online Instan
					</div>
				</li>
				<li>
					<div class="right-top-bar flex-w h-full">
						@auth
							@if(in_array(auth()->user()->role, ['admin', 'superadmin']))
							<a href="/dashboard" class="flex-c-m p-lr-10 trans-04 font-weight-bold" style="color: #717fe0;">
								<i class="fa fa-dashboard mr-1"></i> Admin Panel
							</a>
							@else
							<a href="/pesanan-saya" class="flex-c-m p-lr-10 trans-04 font-weight-bold text-primary">
								<i class="fa fa-shopping-bag mr-1"></i> Pesanan Saya
							</a>
							@endif
							<a href="javascript:void(0)" class="js-frontend-logout-btn flex-c-m p-lr-10 trans-04 text-danger font-weight-bold" data-form-id="logout-form-mobile">
								<i class="fa fa-sign-out mr-1"></i> Logout
							</a>
							<form id="logout-form-mobile" action="/logout" method="POST" style="display: none;">@csrf</form>
						@else
							<a href="/login" class="flex-c-m p-lr-10 trans-04">
								<i class="fa fa-user mr-1"></i> Masuk / Daftar
							</a>
						@endauth
					</div>
				</li>
			</ul>

			<ul class="main-menu-m">
				<li><a href="/">Home</a></li>
				<li><a href="/shop">Shop / Katalog</a></li>
				@auth
					@if(auth()->user()->role === 'user')
					<li><a href="/pesanan-saya"><i class="fa fa-shopping-bag mr-2 text-primary"></i> Pesanan Saya</a></li>
					@elseif(in_array(auth()->user()->role, ['admin', 'superadmin']))
					<li><a href="/dashboard"><i class="fa fa-gauge-high mr-2 text-danger"></i> Panel Admin</a></li>
					@endif
				@endauth
				<li><a href="/lacak-pesanan"><i class="fa fa-truck mr-2 text-primary"></i> Lacak Pesanan</a></li>
				<li><a href="javascript:void(0);" onclick="$('.btn-show-menu-mobile').click(); $('.js-show-cart').first().click();"><i class="zmdi zmdi-shopping-cart mr-2"></i> Keranjang Belanja</a></li>
				<li><a href="javascript:void(0);" onclick="$('.btn-show-menu-mobile').click(); $('.js-show-wishlist').first().click();"><i class="zmdi zmdi-favorite-outline mr-2 text-danger"></i> Wishlist Saya</a></li>
				<li><a href="{{ store_wa_url('Halo Admin, saya ingin bertanya tentang produk.') }}" target="_blank"><i class="fab fa-whatsapp mr-2 text-success"></i> Hubungi Kami (WhatsApp)</a></li>
			</ul>
		</div>

		<!-- Modal Search -->
		<div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
			<div class="container-search-header">
				<button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
					<img src="{{ asset('frontend-assets/images/icons/icon-close2.png') }}" alt="CLOSE">
				</button>

				<form action="{{ route('frontend.shop') }}" method="GET" class="wrap-search-header flex-w p-l-15">
					<button type="submit" class="flex-c-m trans-04">
						<i class="zmdi zmdi-search"></i>
					</button>
					<input class="plh3" type="text" name="search" placeholder="Cari produk, kategori, atau brand..." value="{{ request('search') }}">
				</form>
			</div>
		</div>
	</header>

	@include('frontend.partials.drawers')

