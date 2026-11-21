	@include('frontend.partials.footer')

	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

	@include('frontend.partials.modals')

	@if(config('midtrans.is_production'))
		<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	@else
		<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	@endif

	<script src="{{ asset('frontend-assets/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/select2/select2.min.js') }}"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		});
	</script>
	<script src="{{ asset('frontend-assets/vendor/daterangepicker/moment.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/slick/slick.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/js/slick-custom.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/parallax100/parallax100.js') }}"></script>
	<script>$('.parallax100').parallax100();</script>
	<script src="{{ asset('frontend-assets/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
	<script>
		$('.gallery-lb').each(function() {
			$(this).magnificPopup({
				delegate: 'a',
				type: 'image',
				gallery: { enabled: true },
				mainClass: 'mfp-fade'
			});
		});
	</script>
	<script src="{{ asset('frontend-assets/vendor/isotope/isotope.pkgd.min.js') }}"></script>
	@include('frontend.partials.toast-scripts')
	<script src="{{ asset('frontend-assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
	<script>
		$('.js-pscroll').each(function(){
			$(this).css('position','relative');
			$(this).css('overflow','hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});
			$(window).on('resize', function(){ ps.update(); });
		});
	</script>
	<script>
		window.isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
		window.currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
	</script>
	<script src="{{ asset('frontend-assets/js/main.js') }}"></script>

	<script>
		function formatRupiah(amount) {
			var num = parseInt(amount) || 0;
			return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		}

		function escapeHtml(str) {
			if (!str) return '';
			return String(str)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}

		$(document).on('click', '.js-user-dropdown-toggle', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $dropdown = $(this).closest('.js-user-dropdown');
			$('.js-user-dropdown').not($dropdown).removeClass('show');
			$dropdown.toggleClass('show');
		});

		$(document).on('click', function(e) {
			if (!$(e.target).closest('.js-user-dropdown').length) {
				$('.js-user-dropdown').removeClass('show');
			}
		});

		// SweetAlert Confirmation for Frontend Logout (Consistent with Backend)
		$(document).on('click', '.js-frontend-logout-btn', function(e) {
			e.preventDefault();
			var formId = $(this).data('form-id') || 'logout-form-desktop';
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
		});

		var CART_KEY = window.isLoggedIn ? ('cozastore_cart_u_' + window.currentUserId) : null;
		var WISHLIST_KEY = window.isLoggedIn ? ('cozastore_wishlist_u_' + window.currentUserId) : null;

		function requireLoginPrompt(actionLabel) {
			swal({
				title: "Login Diperlukan",
				text: "Silakan masuk atau daftar akun terlebih dahulu untuk " + (actionLabel || "melanjutkan") + ".",
				icon: "info",
				buttons: {
					cancel: "Nanti Saja",
					confirm: { text: "Masuk / Daftar", className: "btn btn-primary" }
				}
			}).then(function(willLogin) {
				if (willLogin) window.location.href = '/login';
			});
		}

		@include('frontend.partials.storefront-scripts')

		@if(!empty($enableHomeInteractions))
		$('input[name="search-product"]').on('keyup', function() {
			var keyword = $(this).val().toLowerCase();
			$('.isotope-item').each(function() {
				var title = $(this).find('.product-item-title').text().toLowerCase();
				$(this).toggle(title.indexOf(keyword) > -1);
			});
			if (window.jQuery && jQuery.fn.isotope) {
				$('.isotope-grid').isotope('layout');
			}
		});

		$(document).on('click', '.js-scroll-katalog', function(e) {
			var targetEl = document.getElementById('section-produk');
			if (targetEl) {
				e.preventDefault();
				targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		});

		$(document).on('click', '.item-slick1', function(e) {
			var link = $(this).data('link') || '#section-produk';
			if (link.indexOf('#product-grid') > -1 || link.indexOf('#section-produk') > -1) {
				e.preventDefault();
				var targetEl = document.getElementById('section-produk') || document.getElementById('product-grid');
				if (targetEl) {
					targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
					return;
				}
			}
			window.location.href = link;
		});

		$(document).on('click', '.js-banner-btn', function(e) {
			e.stopPropagation();
			var href = $(this).attr('href');
			if (href && (href.indexOf('#product-grid') > -1 || href.indexOf('#section-produk') > -1)) {
				e.preventDefault();
				var targetEl = document.getElementById('section-produk') || document.getElementById('product-grid');
				if (targetEl) targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		});
		@endif
	</script>
	<!-- Instant.page for lightning fast page transitions (<50ms) -->
	<script src="{{ asset('frontend-assets/vendor/instantpage/instantpage.min.js') }}" type="module"></script>
</body>
</html>

