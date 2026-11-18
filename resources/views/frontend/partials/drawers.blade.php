	<!-- Cart Sidebar -->
	<div class="wrap-header-cart js-panel-cart">
		<div class="s-full js-hide-cart"></div>

		<div class="header-cart cart-drawer-modern">
			<div class="cart-drawer-header">
				<div class="d-flex align-items-center" style="gap: 10px;">
					<span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: #eef2ff; color: #717fe0;">
						<i class="zmdi zmdi-shopping-cart" style="font-size: 18px;"></i>
					</span>
					<div>
						<div class="font-weight-bold text-dark" style="font-size: 15px; line-height: 1.2;">Keranjang Belanja</div>
						<small class="text-muted" id="cart-drawer-count" style="font-size: 11.5px;">0 produk</small>
					</div>
				</div>

				<button type="button" class="btn btn-sm btn-light rounded-circle d-flex align-items-center justify-content-center js-hide-cart" style="width: 34px; height: 34px; border: 1px solid #e2e8f0;">
					<i class="zmdi zmdi-close text-dark" style="font-size: 18px;"></i>
				</button>
			</div>

			<div class="cart-drawer-body">
				<ul class="header-cart-wrapitem w-full p-0 m-0" id="cart-items-list" style="list-style: none;">
					<!-- Dinamis dari JavaScript -->
				</ul>
			</div>

			<div class="cart-drawer-footer">
				<div class="d-flex justify-content-between align-items-center mb-1">
					<span class="text-muted" style="font-size: 13px;">Ongkos Kirim</span>
					<span class="badge badge-success px-2 py-1" style="font-size: 11px;">Gratis Ongkir</span>
				</div>
				<div class="d-flex justify-content-between align-items-center mb-3">
					<span class="font-weight-bold text-dark" style="font-size: 15px;">Total Belanja</span>
					<span id="cart-total-price" style="font-size: 1.25rem; font-weight: 700; color: #e65540;">Rp 0</span>
				</div>

				<div class="d-flex align-items-center" style="gap: 10px;">
					<button type="button" onclick="clearCart()" class="btn-cart-clear" title="Kosongkan Keranjang">
						<i class="fa fa-trash-alt mr-1"></i> Kosongkan
					</button>
					<button type="button" onclick="checkoutCart()" class="btn-cart-checkout">
						<span>Checkout Sekarang</span>
						<i class="fa fa-arrow-right"></i>
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Wishlist Sidebar (Love) -->
	<div class="wrap-header-cart js-panel-wishlist">
		<div class="s-full js-hide-wishlist"></div>

		<div class="header-cart cart-drawer-modern">
			<div class="cart-drawer-header">
				<div class="d-flex align-items-center" style="gap: 10px;">
					<span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: #fff1f2; color: #e11d48;">
						<i class="zmdi zmdi-favorite" style="font-size: 18px;"></i>
					</span>
					<div>
						<div class="font-weight-bold text-dark" style="font-size: 15px; line-height: 1.2;">Wishlist Saya</div>
						<small class="text-muted" id="wishlist-drawer-count" style="font-size: 11.5px;">0 produk</small>
					</div>
				</div>

				<button type="button" class="btn btn-sm btn-light rounded-circle d-flex align-items-center justify-content-center js-hide-wishlist" style="width: 34px; height: 34px; border: 1px solid #e2e8f0;">
					<i class="zmdi zmdi-close text-dark" style="font-size: 18px;"></i>
				</button>
			</div>

			<div class="cart-drawer-body">
				<ul class="header-cart-wrapitem w-full p-0 m-0" id="wishlist-items-list" style="list-style: none;">
					<!-- Dinamis dari JavaScript -->
				</ul>
			</div>

			<div class="cart-drawer-footer">
				<div class="d-flex align-items-center" style="gap: 10px;">
					<button type="button" onclick="clearWishlist()" class="btn-cart-clear" title="Kosongkan Wishlist">
						<i class="fa fa-trash-alt mr-1"></i> Kosongkan
					</button>
					<button type="button" onclick="$('.js-hide-wishlist').first().click(); $('.js-show-cart').first().click();" class="btn-cart-checkout">
						<i class="zmdi zmdi-shopping-cart"></i>
						<span>Buka Keranjang</span>
					</button>
				</div>
			</div>
		</div>
	</div>

