<style>
	/* Shared Simple Navbar & User Dropdown Styling */
	.header-v4 .container-menu-desktop {
		height: auto !important;
	}
	.header-v4 .wrap-menu-desktop {
		position: sticky !important;
		top: 0 !important;
		height: 76px !important;
		background-color: #ffffff !important;
		z-index: 1000 !important;
	}
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
		box-shadow: 0 10px 30px rgba(15, 23, 42, 0.14) !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 12px !important;
		min-width: 255px !important;
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
</style>

@php $activeMenu = $activeMenu ?? ''; @endphp

<header class="header-v4">
	<!-- Header desktop -->
	<div class="container-menu-desktop">
		<div class="wrap-menu-desktop how-shadow1">
			<nav class="limiter-menu-desktop container">
				<!-- Logo desktop -->
				<a href="/" class="logo">
					<img src="{{ asset('frontend-assets/images/icons/logo-01.png') }}" alt="IMG-LOGO">
				</a>

				<!-- Menu desktop -->
				<div class="menu-desktop">
					<ul class="main-menu">
						<li class="{{ $activeMenu === 'home' ? 'active-menu' : '' }}">
							<a href="/">Home</a>
						</li>
						<li class="{{ $activeMenu === 'shop' ? 'active-menu' : '' }}">
							<a href="/shop">Shop</a>
						</li>
						<li class="{{ $activeMenu === 'track' ? 'active-menu' : '' }}">
							<a href="/lacak-pesanan">Lacak Pesanan</a>
						</li>
						<li>
							<a href="{{ store_wa_url('Halo Admin, saya ingin bertanya') }}" target="_blank">Hubungi Kami</a>
						</li>
					</ul>
				</div>

				<!-- Icon header -->
				<div class="wrap-icon-header flex-r-m">
					@auth
					<div class="js-user-dropdown">
						<a href="javascript:void(0);" class="js-user-dropdown-toggle dis-flex align-items-center cl2 hov-cl1 trans-04 p-l-22 p-r-11 pointer" style="color: #333 !important; text-decoration: none;">
							@if(auth()->user()->user_image)
								<img src="{{ asset('storage/' . auth()->user()->user_image) }}" alt="{{ auth()->user()->nama }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #cbd5e1;">
							@else
								<i class="zmdi zmdi-account" style="color: #333 !important; font-size: 22px;"></i>
							@endif
							<span class="m-l-6 font-weight-bold" style="font-size: 14px; color: #333 !important;">{{ auth()->user()->nama }}</span>
							<i class="fa fa-angle-down m-l-4 text-muted" style="font-size: 12px;"></i>
						</a>
						<div class="user-dropdown-menu shadow border-0 py-2">
							<div class="px-3 py-2 border-bottom mb-2 bg-light rounded-top d-flex align-items-center" style="gap: 12px;">
								@if(auth()->user()->user_image)
									<img src="{{ asset('storage/' . auth()->user()->user_image) }}" alt="{{ auth()->user()->nama }}" class="rounded-circle border shadow-sm flex-shrink-0" style="width: 50px; height: 50px; object-fit: cover;">
								@else
									<div class="rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold flex-shrink-0" style="width: 50px; height: 50px; background: linear-gradient(135deg, #4f46e5, #717fe0); font-size: 18px;">
										{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
									</div>
								@endif
								<div style="min-width: 0; flex: 1; text-align: left;">
									<div class="font-weight-bold text-dark text-truncate" style="font-size: 14px; line-height: 1.25;">{{ auth()->user()->nama }}</div>
									<small class="text-muted d-block text-truncate" style="font-size: 12px;">{{ auth()->user()->email }}</small>
									<span class="badge {{ auth()->user()->role === 'admin' ? 'badge-danger' : 'badge-primary' }} mt-1 font-weight-normal" style="font-size: 10.5px;">
										{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Member' }}
									</span>
								</div>
							</div>
							@if(in_array(auth()->user()->role, ['admin', 'superadmin']))
							<a class="dropdown-item" href="/dashboard">
								<i class="fa-solid fa-gauge-high mr-2 text-secondary" style="width: 18px;"></i> Dashboard Admin
							</a>
							<a class="dropdown-item {{ $activeMenu === 'profile' ? 'font-weight-bold text-primary' : '' }}" href="{{ route('profile.edit') }}">
								<i class="fa-solid fa-user-gear mr-2 {{ $activeMenu === 'profile' ? '' : 'text-secondary' }}" style="width: 18px;"></i> Profil Admin
							</a>
							@else
							<a class="dropdown-item {{ $activeMenu === 'pesanan' ? 'font-weight-bold text-primary' : '' }}" href="/pesanan-saya">
								<i class="fa-solid fa-shopping-bag mr-2 {{ $activeMenu === 'pesanan' ? '' : 'text-secondary' }}" style="width: 18px;"></i> Pesanan Saya
							</a>
							<a class="dropdown-item {{ $activeMenu === 'profile' ? 'font-weight-bold text-primary' : '' }}" href="{{ route('profile.edit') }}">
								<i class="fa-solid fa-user-gear mr-2 {{ $activeMenu === 'profile' ? '' : 'text-secondary' }}" style="width: 18px;"></i> Profil Saya
							</a>
							@endif
							<div class="dropdown-divider my-1"></div>
							<a class="dropdown-item text-danger font-weight-bold" href="javascript:void(0);" onclick="document.getElementById('logout-form-header').submit();" style="color: #dc3545 !important;">
								<i class="fa-solid fa-right-from-bracket mr-2 text-danger" style="width: 18px;"></i> Logout
							</a>
							<form id="logout-form-header" action="/logout" method="POST" style="display: none;">@csrf</form>
						</div>
					</div>
					@else
					<a href="/login" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 pointer" title="Masuk / Daftar" style="color: #333 !important;">
						<i class="zmdi zmdi-account-o" style="color: #333 !important; font-size: 22px;"></i>
					</a>
					@endauth
				</div>
			</nav>
		</div>
	</div>

	<!-- Header Mobile -->
	<div class="wrap-header-mobile">
		<div class="logo-mobile">
			<a href="/"><img src="{{ asset('frontend-assets/images/icons/logo-01.png') }}" alt="IMG-LOGO"></a>
		</div>
		<div class="btn-show-menu-mobile hamburger hamburger--squeeze" onclick="var m = document.querySelector('.menu-mobile'); if(m){ m.style.display = (m.style.display === 'block' ? 'none' : 'block'); }">
			<span class="hamburger-box">
				<span class="hamburger-inner"></span>
			</span>
		</div>
	</div>

	<!-- Menu Mobile -->
	<div class="menu-mobile">
		<ul class="main-menu-m">
			<li><a href="/">Home</a></li>
			<li><a href="/shop">Shop</a></li>
			<li><a href="/lacak-pesanan">Lacak Pesanan</a></li>
			@auth
				@if(in_array(auth()->user()->role, ['admin', 'superadmin']))
					<li><a href="/dashboard">Dashboard Admin</a></li>
				@else
					<li><a href="/pesanan-saya">Pesanan Saya</a></li>
				@endif
				<li><a href="{{ route('profile.edit') }}">Profil Saya</a></li>
				<li>
					<a href="javascript:void(0);" onclick="document.getElementById('logout-form-header').submit();" class="text-danger">Logout</a>
				</li>
			@else
				<li><a href="/login">Masuk / Daftar</a></li>
			@endauth
			<li><a href="{{ store_wa_url('Halo Admin, saya ingin bertanya') }}" target="_blank">Hubungi Kami</a></li>
		</ul>
	</div>
</header>

<script>
	if (!window.__simpleNavbarBound) {
		window.__simpleNavbarBound = true;
		document.addEventListener('click', function(e) {
			var toggle = e.target.closest('.js-user-dropdown-toggle');
			if (toggle) {
				e.preventDefault();
				e.stopPropagation();
				var dd = toggle.closest('.js-user-dropdown');
				if (dd) dd.classList.toggle('show');
				return;
			}
			if (!e.target.closest('.js-user-dropdown')) {
				document.querySelectorAll('.js-user-dropdown.show').forEach(function(el) {
					el.classList.remove('show');
				});
			}
		});
	}
</script>

