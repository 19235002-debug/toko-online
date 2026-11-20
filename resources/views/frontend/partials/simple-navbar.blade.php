@include('frontend.partials.user-dropdown-styles')

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
</style>

@php $activeMenu = $activeMenu ?? ''; @endphp

<header class="header-v4">
	<!-- Header desktop -->
	<div class="container-menu-desktop">
		<div class="wrap-menu-desktop how-shadow1">
			<nav class="limiter-menu-desktop container">
				<!-- Logo desktop -->
				<a href="/" class="logo" style="display: inline-flex; align-items: center; text-decoration: none;">
					@include('frontend.partials.brand-logo', ['maxHeight' => '38px', 'fontSize' => '1.25rem'])
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
					@include('frontend.partials.user-dropdown', ['idSuffix' => 'simple', 'activeMenu' => $activeMenu])
				</div>
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
					<a href="javascript:void(0);" class="js-frontend-logout-btn text-danger" data-form-id="logout-form-simple">
						<i class="fa-solid fa-arrow-right-from-bracket mr-2 text-danger"></i> Logout
					</a>
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

			var logoutBtn = e.target.closest('.js-frontend-logout-btn');
			if (logoutBtn) {
				e.preventDefault();
				var formId = logoutBtn.getAttribute('data-form-id') || 'logout-form-simple';
				var form = document.getElementById(formId);

				if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
					Swal.fire({
						title: 'Apakah Anda yakin?',
						text: "Tindakan ini tidak dapat dibatalkan!",
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#465fff',
						cancelButtonColor: '#6c757d',
						confirmButtonText: 'Ya, Logout',
						cancelButtonText: 'Batal'
					}).then(function(result) {
						if (result.isConfirmed && form) {
							form.submit();
						}
					});
				} else if (typeof swal === 'function') {
					swal({
						title: "Apakah Anda yakin?",
						text: "Tindakan ini tidak dapat dibatalkan!",
						icon: "warning",
						buttons: ["Batal", "Ya, Logout"],
						dangerMode: true,
					}).then(function(willLogout) {
						if (willLogout && form) {
							form.submit();
						}
					});
				} else if (form) {
					form.submit();
				}
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

