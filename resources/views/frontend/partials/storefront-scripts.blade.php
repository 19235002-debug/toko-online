		function getCart() {
			if (!window.isLoggedIn || !CART_KEY) return [];
			try {
				var data = localStorage.getItem(CART_KEY);
				return data ? JSON.parse(data) : [];
			} catch (e) {
				return [];
			}
		}

		var _cartSyncTimer = null;
		function saveCart(cart) {
			if (!window.isLoggedIn || !CART_KEY) return;
			localStorage.setItem(CART_KEY, JSON.stringify(cart));
			clearTimeout(_cartSyncTimer);
			_cartSyncTimer = setTimeout(function() {
				$.post('{{ route("frontend.cart.sync") }}', {
					_token: '{{ csrf_token() }}',
					cart_items: JSON.stringify(cart)
				});
			}, 250);
		}

		function getWishlist() {
			if (!window.isLoggedIn || !WISHLIST_KEY) return [];
			try {
				var data = localStorage.getItem(WISHLIST_KEY);
				return data ? JSON.parse(data) : [];
			} catch (e) {
				return [];
			}
		}

		var _wishlistSyncTimer = null;
		function saveWishlist(wishlist) {
			if (!window.isLoggedIn || !WISHLIST_KEY) return;
			localStorage.setItem(WISHLIST_KEY, JSON.stringify(wishlist));
			clearTimeout(_wishlistSyncTimer);
			_wishlistSyncTimer = setTimeout(function() {
				$.post('{{ route("frontend.wishlist.sync") }}', {
					_token: '{{ csrf_token() }}',
					wishlist_items: JSON.stringify(wishlist)
				});
			}, 250);
		}

		// ==================== CART ACTIONS ====================
		function addToCart(product, qty) {
			if (!window.isLoggedIn) {
				requireLoginPrompt("menambahkan produk ke keranjang");
				return false;
			}
			qty = parseInt(qty, 10);
			if (isNaN(qty) || qty < 1) {
				swal("Jumlah Minimal 1", "Minimal masukkan 1 produk ke dalam keranjang.", "warning");
				return false;
			}
			var cart = getCart();
			var found = false;
			var cartKey = String(product.id) + (product.variasi_id ? ('_v' + product.variasi_id) : '');
			var maxStock = (typeof product.max_stok !== 'undefined' && product.max_stok !== null) ? parseInt(product.max_stok, 10) : null;

			for (var i = 0; i < cart.length; i++) {
				var itemKey = cart[i].cart_key || String(cart[i].id);
				if (itemKey === cartKey) {
					var effectiveMax = (maxStock !== null && !isNaN(maxStock)) ? maxStock : (cart[i].max_stok ? parseInt(cart[i].max_stok, 10) : null);
					if (effectiveMax !== null && !isNaN(effectiveMax) && (cart[i].qty + qty) > effectiveMax) {
						var sisa = Math.max(0, effectiveMax - cart[i].qty);
						swal("Stok Terbatas", "Di keranjang sudah ada " + cart[i].qty + " unit. Sisa stok yang bisa ditambahkan hanya " + sisa + " unit (maksimal " + effectiveMax + " unit).", "warning");
						return false;
					}
					cart[i].qty += qty;
					if (effectiveMax !== null && !isNaN(effectiveMax)) {
						cart[i].max_stok = effectiveMax;
					}
					found = true;
					break;
				}
			}

			if (!found) {
				if (maxStock !== null && !isNaN(maxStock) && qty > maxStock) {
					swal("Stok Terbatas", "Jumlah melebihi stok yang tersedia (" + maxStock + " unit).", "warning");
					return false;
				}
				cart.push({
					id: String(product.id),
					cart_key: cartKey,
					variasi_id: product.variasi_id || null,
					variasi: product.variasi || null,
					nama: String(product.nama),
					harga: parseInt(product.harga) || 0,
					gambar: String(product.gambar),
					qty: qty,
					max_stok: (maxStock !== null && !isNaN(maxStock)) ? maxStock : null
				});
			}

			saveCart(cart);
			updateCartUI();
			return true;
		}

		function removeFromCart(cartKey) {
			var cart = getCart();
			cart = cart.filter(function(item) {
				var k = item.cart_key || String(item.id);
				return String(k) !== String(cartKey);
			});
			saveCart(cart);
			updateCartUI();
		}

		function clearCart() {
			if (getCart().length === 0) {
				swal("Keranjang Kosong", "Keranjang belanja Anda sudah kosong.", "info");
				return;
			}
			saveCart([]);
			updateCartUI();
			swal("Keranjang Dikosongkan", "Semua produk dalam keranjang telah dihapus.", "success");
		}

		function updateCartItemQty(cartKey, delta) {
			var cart = getCart();
			var idx = cart.findIndex(function(item) {
				var k = item.cart_key || String(item.id);
				return String(k) === String(cartKey);
			});
			if (idx === -1) return;

			var nextQty = parseInt(cart[idx].qty, 10) + delta;
			if (nextQty < 1) {
				swal("Jumlah Minimal 1", "Minimal 1 produk. Klik ikon hapus untuk mengeluarkan produk.", "warning");
				return;
			}
			if (delta > 0) {
				var maxStock = (typeof cart[idx].max_stok !== 'undefined' && cart[idx].max_stok !== null) ? parseInt(cart[idx].max_stok, 10) : null;
				if (maxStock !== null && !isNaN(maxStock)) {
					if (nextQty > maxStock) {
						swal("Stok Terbatas", "Jumlah melebihi stok yang tersedia (" + maxStock + " unit).", "warning");
						return;
					}
				} else if (window.isLoggedIn) {
					var checkItem = Object.assign({}, cart[idx], { qty: nextQty });
					$.ajax({
						url: '{{ route("frontend.checkout.validateStock") }}',
						type: 'POST',
						data: {
							_token: '{{ csrf_token() }}',
							cart_items: JSON.stringify([checkItem])
						},
						success: function(res) {
							if (res.stock_map && typeof res.stock_map[cartKey] !== 'undefined') {
								cart[idx].max_stok = res.stock_map[cartKey];
							}
							cart[idx].qty = nextQty;
							saveCart(cart);
							updateCartUI();
						},
						error: function(xhr) {
							var res = xhr.responseJSON || {};
							if (res.stock_map && typeof res.stock_map[cartKey] !== 'undefined') {
								cart[idx].max_stok = res.stock_map[cartKey];
								saveCart(cart);
							}
							swal("Stok Terbatas", res.message || "Jumlah melebihi stok yang tersedia.", "warning");
						}
					});
					return;
				}
			}
			cart[idx].qty = nextQty;
			saveCart(cart);
			updateCartUI();
		}

		function updateCartUI() {
			var cart = getCart();
			var totalProductCount = cart.length;
			var totalPrice = 0;
			var $list = $('#cart-items-list');
			$list.empty();

			if (cart.length === 0) {
				$list.html(
					'<li class="text-center py-5 px-3">' +
						'<div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #eef2ff; color: #717fe0;">' +
							'<i class="zmdi zmdi-shopping-cart-plus" style="font-size: 32px;"></i>' +
						'</div>' +
						'<h6 class="font-weight-bold text-dark mb-1">Keranjang masih kosong</h6>' +
						'<p class="text-muted small mb-3">Yuk temukan produk fashion favoritmu sekarang!</p>' +
						'<button type="button" class="btn btn-sm btn-primary rounded-pill px-4 js-hide-cart" style="background: #717fe0; border-color: #717fe0;">Mulai Belanja</button>' +
					'</li>'
				);
			} else {
				cart.forEach(function(item) {
					var subtotal = item.qty * item.harga;
					totalPrice += subtotal;
					var cartKey = item.cart_key || String(item.id);
					var varBadge = item.variasi
						? '<div class="mb-2"><span class="badge badge-light border text-primary font-weight-normal" style="font-size: 11px; padding: 3px 8px; border-radius: 6px; background: #f5f7ff;"><i class="fa fa-tag mr-1"></i>' + escapeHtml(item.variasi) + '</span></div>'
						: '';

					var itemHtml =
						'<li class="cart-item-card">' +
							'<img src="' + escapeHtml(item.gambar) + '" alt="' + escapeHtml(item.nama) + '" class="cart-item-thumb">' +
							'<div style="flex: 1; min-width: 0;">' +
								'<div class="d-flex justify-content-between align-items-start" style="gap: 8px;">' +
									'<div class="font-weight-bold text-dark text-truncate" style="font-size: 14px;" title="' + escapeHtml(item.nama) + '">' +
										escapeHtml(item.nama) +
									'</div>' +
									'<button type="button" class="btn-cart-trash" title="Hapus produk" onclick="removeFromCart(\'' + cartKey + '\')">' +
										'<i class="fa fa-trash-alt" style="font-size: 12px;"></i>' +
									'</button>' +
								'</div>' +
								varBadge +
								'<div class="d-flex justify-content-between align-items-center mt-2">' +
									'<div class="cart-qty-stepper">' +
										'<button type="button" class="cart-qty-btn" onclick="updateCartItemQty(\'' + cartKey + '\', -1)" title="Kurangi">-</button>' +
										'<span class="cart-qty-val">' + item.qty + '</span>' +
										'<button type="button" class="cart-qty-btn" onclick="updateCartItemQty(\'' + cartKey + '\', 1)" title="Tambah">+</button>' +
									'</div>' +
									'<div class="text-right">' +
										'<div style="font-size: 13.5px; color: #e65540; font-weight: 700;">' + formatRupiah(subtotal) + '</div>' +
										'<div class="text-muted" style="font-size: 11px;">@ ' + formatRupiah(item.harga) + '</div>' +
									'</div>' +
								'</div>' +
							'</div>' +
						'</li>';
					$list.append(itemHtml);
				});
			}

			$('.js-cart-badge').attr('data-notify', totalProductCount);
			$('#cart-drawer-count').text(totalProductCount + ' produk');
			$('#cart-total-price').text(formatRupiah(totalPrice));
		}

		var isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

		function checkoutCart() {
			var cart = getCart();
			if (cart.length === 0) {
				swal("Keranjang Kosong", "Silakan tambahkan produk ke keranjang terlebih dahulu sebelum checkout.", "warning");
				return;
			}

			if (!isLoggedIn) {
				$('.js-panel-cart').removeClass('show-header-cart');
				swal({
					title: "Login Diperlukan",
					text: "Silakan masuk atau daftar akun terlebih dahulu untuk melanjutkan checkout. Keranjang belanja Anda tetap tersimpan.",
					icon: "info",
					buttons: {
						cancel: "Nanti Saja",
						confirm: {
							text: "Masuk / Daftar",
							className: "btn btn-primary"
						}
					}
				}).then(function(willLogin) {
					if (willLogin) {
						window.location.href = '/login';
					}
				});
				return;
			}

			var $checkoutBtn = $('.btn-cart-checkout');
			var origBtnHtml = $checkoutBtn.html();
			$checkoutBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Memeriksa Stok...');

			$.ajax({
				url: '{{ route("frontend.checkout.validateStock") }}',
				type: 'POST',
				data: {
					_token: '{{ csrf_token() }}',
					cart_items: JSON.stringify(cart)
				},
				success: function(res) {
					$checkoutBtn.prop('disabled', false).html(origBtnHtml);
					if (res.stock_map) {
						cart.forEach(function(it) {
							var ck = it.cart_key || String(it.id);
							if (typeof res.stock_map[ck] !== 'undefined') {
								it.max_stok = res.stock_map[ck];
							}
						});
						saveCart(cart);
					}
					openCheckoutModalWithCart(cart);
				},
				error: function(xhr) {
					$checkoutBtn.prop('disabled', false).html(origBtnHtml);
					var res = xhr.responseJSON || {};
					if (res.stock_map) {
						cart.forEach(function(it) {
							var ck = it.cart_key || String(it.id);
							if (typeof res.stock_map[ck] !== 'undefined') {
								it.max_stok = res.stock_map[ck];
							}
						});
						saveCart(cart);
					}
					swal("Stok Tidak Mencukupi", res.message || "Ada produk di keranjang yang melebihi stok tersedia. Silakan kurangi jumlahnya terlebih dahulu.", "warning");
				}
			});
		}

		@php
			$jsShipSetting = \App\Models\Setting::getSettings();
		@endphp
		window.SHIPPING_RATES = {
			reguler: {{ intval($jsShipSetting->ongkir_reguler ?? 15000) }},
			express: {{ intval($jsShipSetting->ongkir_express ?? 30000) }},
			minFree: {{ intval($jsShipSetting->min_gratis_ongkir ?? 300000) }}
		};

		function recalculateCheckoutTotals() {
			var subtotal = window.currentCheckoutSubtotal || 0;
			var discount = window.appliedVoucherDiscount || 0;
			var kurir = $('input[name="kurir"]:checked').val() || 'reguler';
			var rates = window.SHIPPING_RATES || { reguler: 15000, express: 30000, minFree: 300000 };
			var ongkir = 0;

			if (kurir === 'express') {
				ongkir = rates.express;
			} else {
				ongkir = subtotal >= rates.minFree ? 0 : rates.reguler;
			}

			if ($('#kurir-reguler-price-label').length) {
				$('#kurir-reguler-price-label').text(
					subtotal >= rates.minFree
						? 'Gratis Ongkir'
						: (formatRupiah(rates.reguler) + ' (Gratis ≥ ' + formatRupiah(rates.minFree) + ')')
				);
			}

			if ($('#checkout-summary-ongkir').length) {
				if (ongkir === 0) {
					$('#checkout-summary-ongkir').html('<span class="badge badge-success px-2 py-1" style="font-size: 10.5px;">Gratis Ongkir</span>');
				} else {
					$('#checkout-summary-ongkir').text(formatRupiah(ongkir));
				}
			}

			var totalAkhir = Math.max(0, subtotal - discount) + ongkir;
			$('#checkout-summary-total-price').text(formatRupiah(totalAkhir));
		}

		function openCheckoutModalWithCart(cart) {
			var $summary = $('#checkout-items-summary');
			$summary.empty();
			var totalQty = 0;
			var totalPrice = 0;

			cart.forEach(function(item) {
				var sub = item.qty * item.harga;
				totalQty += item.qty;
				totalPrice += sub;
				var varText = item.variasi ? '<div class="small text-primary font-weight-bold" style="font-size: 0.75rem;"><i class="fa fa-tag mr-1"></i>' + escapeHtml(item.variasi) + '</div>' : '';

				var rowHtml =
					'<div class="d-flex align-items-center m-b-10 pb-2 border-bottom">' +
						'<img src="' + escapeHtml(item.gambar) + '" alt="' + escapeHtml(item.nama) + '" style="width: 42px; height: 42px; object-fit: cover; border-radius: 4px; margin-right: 10px;">' +
						'<div style="flex: 1; min-width: 0;">' +
							'<div class="font-weight-bold text-truncate" style="font-size: 0.85rem;" title="' + escapeHtml(item.nama) + '">' + escapeHtml(item.nama) + '</div>' +
							varText +
							'<div class="text-muted" style="font-size: 0.78rem;">' + item.qty + 'x @ ' + formatRupiah(item.harga) + '</div>' +
						'</div>' +
						'<div class="font-weight-bold text-dark text-right" style="font-size: 0.85rem; min-width: 75px;">' +
							formatRupiah(sub) +
						'</div>' +
					'</div>';
				$summary.append(rowHtml);
			});

			window.currentCheckoutSubtotal = totalPrice;
			window.appliedVoucherCode = null;
			window.appliedVoucherDiscount = 0;

			$('#checkout_kode_voucher').val('');
			$('#voucher-feedback').hide().text('');
			$('#checkout-summary-discount-row').attr('style', 'display: none !important;');

			$('#checkout-summary-total-qty').text(totalQty + ' pcs');
			$('#checkout-summary-subtotal').text(formatRupiah(totalPrice));
			recalculateCheckoutTotals();

			$('.js-panel-cart').removeClass('show-header-cart');
			$('#modalCheckout').modal('show');
		}

		function copyVoucherCode(code) {
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(code);
			}
			swal("Kode Voucher Disalin!", "Kode \"" + code + "\" siap digunakan saat checkout.", "success");
		}

		function applyVoucher(codeOpt) {
			if (codeOpt) {
				$('#checkout_kode_voucher').val(codeOpt);
			}
			var kode = ($('#checkout_kode_voucher').val() || '').trim().toUpperCase();
			if (!kode) {
				$('#voucher-feedback').removeClass('text-success').addClass('text-danger').text('Masukkan kode voucher terlebih dahulu.').show();
				return;
			}

			var $btn = $('#btn-apply-voucher');
			$btn.prop('disabled', true).text('...');

			$.ajax({
				url: '{{ route("frontend.voucher.check") }}',
				type: 'POST',
				data: {
					_token: '{{ csrf_token() }}',
					kode_voucher: kode,
					subtotal: window.currentCheckoutSubtotal || 0
				},
				success: function(res) {
					$btn.prop('disabled', false).text('Pakai');
					if (res.success) {
						window.appliedVoucherCode = res.kode_voucher;
						window.appliedVoucherDiscount = res.diskon;
						$('#checkout-applied-code').text(res.kode_voucher);
						$('#checkout-summary-discount').text('- ' + res.diskon_formatted);
						$('#checkout-summary-discount-row').attr('style', 'display: flex !important;');
						recalculateCheckoutTotals();
						$('#voucher-feedback').removeClass('text-danger').addClass('text-success').html('<i class="fa fa-check-circle mr-1"></i>' + res.message).show();
					}
				},
				error: function(xhr) {
					$btn.prop('disabled', false).text('Pakai');
					var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Voucher tidak valid.';
					removeVoucher(true);
					$('#voucher-feedback').removeClass('text-success').addClass('text-danger').html('<i class="fa fa-times-circle mr-1"></i>' + msg).show();
				}
			});
		}

		function removeVoucher(keepFeedback) {
			window.appliedVoucherCode = null;
			window.appliedVoucherDiscount = 0;
			if (!keepFeedback) {
				$('#checkout_kode_voucher').val('');
				$('#voucher-feedback').hide().text('');
			}
			$('#checkout-summary-discount-row').attr('style', 'display: none !important;');
			recalculateCheckoutTotals();
		}

		function submitCheckoutOrder(e) {
			e.preventDefault();
			var cart = getCart();
			if (cart.length === 0) {
				swal("Keranjang Kosong", "Silakan tambahkan produk ke keranjang terlebih dahulu.", "warning");
				$('#modalCheckout').modal('hide');
				return;
			}

			var noHp = ($('#checkout_no_hp').val() || '').trim();
			if (!/^[0-9]{10,15}$/.test(noHp)) {
				swal("Nomor HP Tidak Valid", "Nomor WhatsApp/HP hanya boleh berupa angka dengan panjang 10 sampai 15 digit (contoh: 081234567890).", "warning");
				$('#checkout_no_hp').focus();
				return;
			}

			var $btn = $('#btn-submit-checkout');
			var originalBtnHtml = $btn.html();
			$btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin m-r-5"></i> Memproses...');

			var formData = {
				_token: $('input[name="_token"]').val() || '{{ csrf_token() }}',
				nama_customer: $('#checkout_nama').val(),
				no_hp: $('#checkout_no_hp').val(),
				alamat: $('#checkout_alamat').val(),
				metode_pembayaran: $('input[name="metode_pembayaran"]:checked').val(),
				kurir: $('input[name="kurir"]:checked').val() || 'reguler',
				catatan: $('#checkout_catatan').val(),
				kode_voucher: window.appliedVoucherCode || '',
				cart_items: JSON.stringify(cart)
			};

			$.ajax({
				url: '/checkout',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(res) {
					$btn.prop('disabled', false).html(originalBtnHtml);
					if (res.success) {
						saveCart([]);
						updateCartUI();
						$('#modalCheckout').modal('hide');
						$('#formCheckout')[0].reset();

						if (res.metode_pembayaran === 'midtrans' && res.snap_token && typeof window.snap !== 'undefined') {
							window.snap.pay(res.snap_token, {
								onSuccess: function(result) {
									$.post('/midtrans/sync/' + res.kode_order);

									swal({
										title: "Pembayaran Berhasil!",
										text: "Terima kasih, pembayaran pesanan " + res.kode_order + " telah diterima secara otomatis.",
										icon: "success",
										timer: 3000,
										buttons: {
											cancel: {
												text: "Tutup",
												value: false,
												visible: true,
												className: "btn btn-secondary btn-sm"
											},
											confirm: {
												text: "WhatsApp Admin (Opsional)",
												value: true,
												visible: true,
												className: "btn btn-success btn-sm"
											}
										}
									}).then(function(openWa) {
										if (openWa === true && res.wa_url) {
											window.open(res.wa_url, '_blank');
										}
									});
								},
								onPending: function(result) {
									swal({
										title: "Menunggu Pembayaran",
										text: "Pesanan " + res.kode_order + " telah dibuat. Silakan selesaikan pembayaran sesuai instruksi Midtrans.",
										icon: "info",
										buttons: {
											cancel: "Tutup",
											confirm: {
												text: "Konfirmasi WhatsApp",
												className: "btn btn-success"
											}
										}
									}).then(function(openWa) {
										if (openWa && res.wa_url) {
											window.open(res.wa_url, '_blank');
										}
									});
								},
								onError: function(result) {
									swal({
										title: "Pembayaran Gagal",
										text: "Pembayaran untuk pesanan " + res.kode_order + " gagal diproses.",
										icon: "error"
									});
								},
								onClose: function() {
									$.post('/midtrans/sync/' + res.kode_order);

									swal({
										title: "Pesanan Tercatat",
										text: "Pop-up pembayaran ditutup. Pesanan " + res.kode_order + " tetap tercatat dan sistem otomatis memantau status pembayarannya.",
										icon: "warning",
										buttons: {
											cancel: "Tutup",
											confirm: {
												text: "Hubungi Admin",
												className: "btn btn-success"
											}
										}
									}).then(function(openWa) {
										if (openWa && res.wa_url) {
											window.open(res.wa_url, '_blank');
										}
									});
								}
							});
						} else {
							swal({
								title: "Pesanan Berhasil Dibuat!",
								text: "Nomor Pesanan: " + res.kode_order + "\nTotal: " + res.total_formatted,
								icon: "success",
								timer: 3000,
								buttons: {
									cancel: {
										text: "Tutup",
										value: false,
										visible: true,
										className: "btn btn-secondary btn-sm"
									},
									confirm: {
										text: "WhatsApp Admin (Opsional)",
										value: true,
										visible: true,
										className: "btn btn-success btn-sm"
									}
								}
							}).then(function(openWa) {
								if (openWa === true && res.wa_url) {
									window.open(res.wa_url, '_blank');
								}
							});
						}
					} else {
						swal("Gagal Memproses", res.message || "Terjadi kesalahan saat memproses pesanan.", "error");
					}
				},
				error: function(xhr) {
					$btn.prop('disabled', false).html(originalBtnHtml);
					if (xhr.status === 401) {
						swal({
							title: "Sesi Login Diperlukan",
							text: "Silakan masuk atau daftar akun terlebih dahulu untuk melanjutkan checkout.",
							icon: "warning",
							buttons: {
								cancel: "Tutup",
								confirm: { text: "Masuk / Daftar", className: "btn btn-primary" }
							}
						}).then(function(ok) {
							if (ok) window.location.href = '/login';
						});
						return;
					}
					var msg = "Gagal memproses pesanan. Silakan periksa kembali data Anda.";
					if (xhr.responseJSON && xhr.responseJSON.message) {
						msg = xhr.responseJSON.message;
					}
					swal("Perhatian", msg, "error");
				}
			});
		}

		// ==================== WISHLIST (LOVE) ACTIONS ====================
		function toggleWishlist(product) {
			var wishlist = getWishlist();
			var index = -1;

			for (var i = 0; i < wishlist.length; i++) {
				if (String(wishlist[i].id) === String(product.id)) {
					index = i;
					break;
				}
			}

			if (index > -1) {
				wishlist.splice(index, 1);
				saveWishlist(wishlist);
				updateWishlistUI();
				swal(product.nama, "Dihapus dari wishlist!", "info");
			} else {
				wishlist.push({
					id: String(product.id),
					nama: String(product.nama),
					harga: parseInt(product.harga) || 0,
					gambar: String(product.gambar)
				});
				saveWishlist(wishlist);
				updateWishlistUI();
				swal(product.nama, "Berhasil ditambahkan ke wishlist!", "success");
			}
		}

		function removeFromWishlist(id) {
			var wishlist = getWishlist();
			wishlist = wishlist.filter(function(item) {
				return String(item.id) !== String(id);
			});
			saveWishlist(wishlist);
			updateWishlistUI();
		}

		function clearWishlist() {
			if (getWishlist().length === 0) {
				swal("Wishlist Kosong", "Wishlist Anda sudah kosong.", "info");
				return;
			}
			saveWishlist([]);
			updateWishlistUI();
			swal("Wishlist Dikosongkan", "Semua produk di wishlist telah dihapus.", "success");
		}

		function moveWishlistToCart(id) {
			var wishlist = getWishlist();
			var item = wishlist.find(function(w) { return String(w.id) === String(id); });
			if (item) {
				if (addToCart(item, 1)) {
					swal(item.nama, "Berhasil ditambahkan ke keranjang belanja!", "success");
				}
			}
		}

		function updateWishlistUI() {
			var wishlist = getWishlist();
			var totalCount = wishlist.length;
			var $list = $('#wishlist-items-list');
			$list.empty();

			if (wishlist.length === 0) {
				$list.html(
					'<li class="text-center py-5 px-3">' +
						'<div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #fff1f2; color: #e11d48;">' +
							'<i class="zmdi zmdi-favorite-outline" style="font-size: 32px;"></i>' +
						'</div>' +
						'<h6 class="font-weight-bold text-dark mb-1">Wishlist masih kosong</h6>' +
						'<p class="text-muted small mb-3">Simpan produk favoritmu di sini untuk dibeli nanti!</p>' +
						'<button type="button" class="btn btn-sm btn-primary rounded-pill px-4 js-hide-wishlist" style="background: #717fe0; border-color: #717fe0;">Jelajahi Produk</button>' +
					'</li>'
				);
			} else {
				wishlist.forEach(function(item) {
					var itemHtml =
						'<li class="cart-item-card">' +
							'<img src="' + escapeHtml(item.gambar) + '" alt="' + escapeHtml(item.nama) + '" class="cart-item-thumb">' +
							'<div style="flex: 1; min-width: 0;">' +
								'<div class="d-flex justify-content-between align-items-start" style="gap: 8px;">' +
									'<div class="font-weight-bold text-dark text-truncate" style="font-size: 14px;" title="' + escapeHtml(item.nama) + '">' +
										escapeHtml(item.nama) +
									'</div>' +
									'<button type="button" class="btn-cart-trash" title="Hapus dari wishlist" onclick="removeFromWishlist(\'' + item.id + '\')">' +
										'<i class="fa fa-trash-alt" style="font-size: 12px;"></i>' +
									'</button>' +
								'</div>' +
								'<div class="mt-1 mb-2" style="color: #e65540; font-weight: 700; font-size: 14px;">' +
									formatRupiah(item.harga) +
								'</div>' +
								'<div>' +
									'<button type="button" class="btn btn-sm text-white font-weight-bold" onclick="moveWishlistToCart(\'' + item.id + '\')" style="font-size: 12px; padding: 5px 14px; border-radius: 20px; background: linear-gradient(135deg, #717fe0 0%, #5865d6 100%); border: none; box-shadow: 0 3px 10px rgba(113, 127, 224, 0.25);">' +
										'<i class="zmdi zmdi-shopping-cart-plus mr-1"></i> + Keranjang' +
									'</button>' +
								'</div>' +
							'</div>' +
						'</li>';
					$list.append(itemHtml);
				});
			}

			$('.js-wishlist-badge').attr('data-notify', totalCount);
			$('#wishlist-drawer-count').text(totalCount + ' produk');

			$('.js-toggle-wishlist').each(function() {
				var pid = String($(this).data('id'));
				var isSaved = wishlist.some(function(w) { return String(w.id) === pid; });
				if (isSaved) {
					$(this).addClass('js-addedwish-b2');
				} else {
					$(this).removeClass('js-addedwish-b2');
				}
			});
		}

		// ==================== MODAL QUICK VIEW & HANDLERS ====================
		var currentModalProduct = null;
		var currentProductVariants = [];
		var selectedColor = null;
		var selectedSize = null;
		var currentSelectedVariant = null;

		$(document).on('click', '.js-show-product-modal', function(e) {
			e.preventDefault();
			currentModalProduct = {
				id: String($(this).data('id')),
				nama: String($(this).data('nama')),
				kategori: String($(this).data('kategori')),
				supplier: String($(this).data('supplier')),
				stok: parseInt($(this).data('stok')) || 0,
				harga: parseInt($(this).data('rawprice')) || 0,
				gambar: String($(this).data('gambar'))
			};

			selectedColor = null;
			selectedSize = null;
			currentSelectedVariant = null;
			currentProductVariants = [];

			$('#modal-product-name').text(currentModalProduct.nama);
			$('#modal-product-price').text(formatRupiah(currentModalProduct.harga));
			$('#modal-product-desc').html(
				'<div class="mb-2"><span class="badge badge-info">' + escapeHtml(currentModalProduct.kategori) + '</span></div>' +
				'<strong>Supplier:</strong> ' + escapeHtml(currentModalProduct.supplier) + '<br>' +
				'<strong>Total Stok:</strong> <span id="modal-base-stock">' + currentModalProduct.stok + '</span> unit'
			);
			$('#modal-product-img').attr('src', currentModalProduct.gambar);
			$('#modal-product-qty').val(1);
			$('#modal-gallery-thumbnails').empty().hide();
			$('#modal-variants-section').hide();

			$('#review_barang_id').val(currentModalProduct.id);
			$('#modal-write-review-box').hide();
			$('#modal-reviews-list').html('<div class="text-center text-muted small py-2"><i class="fa fa-spinner fa-spin mr-1"></i> Memuat ulasan...</div>');

			$.get('/frontend/product/' + currentModalProduct.id, function(res) {
				if (typeof res.stok !== 'undefined') {
					currentModalProduct.stok = parseInt(res.stok, 10) || 0;
					$('#modal-base-stock').text(currentModalProduct.stok);
				}
				if (res.is_promo_active) {
					currentModalProduct.harga = parseInt(res.harga, 10) || 0;
					$('#modal-product-price').html(
						formatRupiah(res.harga) +
						' <del class="text-muted font-weight-normal ml-2" style="font-size: 0.95rem;">' + formatRupiah(res.harga_asli) + '</del>' +
						' <span class="badge badge-danger ml-1" style="font-size: 0.75rem; vertical-align: middle;">-' + res.diskon_persen + '%</span>'
					);
					if (res.promo_selesai) {
						$('#modal-product-desc').append('<br><span class="badge badge-light border text-danger mt-1"><i class="fa fa-clock mr-1"></i> Promo berakhir: ' + escapeHtml(res.promo_selesai) + '</span>');
					}
				}

				if (res.images && res.images.length > 1) {
					var $thumbContainer = $('#modal-gallery-thumbnails');
					$thumbContainer.empty().css('display', 'flex');
					res.images.forEach(function(imgUrl, idx) {
						var $thumb = $('<div class="modal-thumb-item ' + (idx === 0 ? 'active' : '') + '"><img src="' + escapeHtml(imgUrl) + '" alt="Thumb"></div>');
						$thumb.on('click', function() {
							$('.modal-thumb-item').removeClass('active');
							$(this).addClass('active');
							$('#modal-product-img').css('opacity', 0.3);
							setTimeout(function() {
								$('#modal-product-img').attr('src', imgUrl).css('opacity', 1);
							}, 80);
						});
						$thumbContainer.append($thumb);
					});
				} else {
					$('#modal-gallery-thumbnails').empty().hide();
				}

				currentProductVariants = res.variasis || [];
				if (res.has_variants && currentProductVariants.length > 0) {
					$('#modal-variants-section').show();

					if (res.warna_list && res.warna_list.length > 0) {
						$('#modal-color-wrapper').show();
						$('#selected-color-label').text('Pilih warna');
						var $colOpts = $('#modal-color-options');
						$colOpts.empty();
						res.warna_list.forEach(function(col) {
							var $btn = $('<button type="button" class="var-pill js-color-pill">' + escapeHtml(col) + '</button>');
							$btn.on('click', function() {
								$('.js-color-pill').removeClass('active');
								$(this).addClass('active');
								selectedColor = col;
								$('#selected-color-label').text(col);
								onVariantSelectionChanged();
							});
							$colOpts.append($btn);
						});
					} else {
						$('#modal-color-wrapper').hide();
					}

					if (res.ukuran_list && res.ukuran_list.length > 0) {
						$('#modal-size-wrapper').show();
						$('#selected-size-label').text('Pilih ukuran');
						var $szOpts = $('#modal-size-options');
						$szOpts.empty();
						res.ukuran_list.forEach(function(sz) {
							var $btn = $('<button type="button" class="var-pill js-size-pill">' + escapeHtml(sz) + '</button>');
							$btn.on('click', function() {
								$('.js-size-pill').removeClass('active');
								$(this).addClass('active');
								selectedSize = sz;
								$('#selected-size-label').text(sz);
								onVariantSelectionChanged();
							});
							$szOpts.append($btn);
						});
					} else {
						$('#modal-size-wrapper').hide();
					}

					$('#modal-variant-info').html('<span class="text-info"><i class="fa fa-info-circle mr-1"></i> Silakan pilih variasi warna/ukuran di atas</span>').show();
				} else {
					$('#modal-variants-section').hide();
				}

				var avg = parseFloat(res.avg_rating) || 0;
				var total = parseInt(res.total_reviews) || 0;
				var starHtml = '';
				for (var s = 1; s <= 5; s++) {
					if (avg >= s) {
						starHtml += '<i class="fa fa-star text-warning"></i> ';
					} else if (avg >= (s - 0.5)) {
						starHtml += '<i class="fa fa-star-half-o text-warning"></i> ';
					} else {
						starHtml += '<i class="fa fa-star-o text-muted"></i> ';
					}
				}
				$('#modal-star-display').html(starHtml);
				$('#modal-review-count').text(avg > 0 ? (avg + ' / 5.0 (' + total + ' ulasan)') : 'Belum ada ulasan');
				$('#modal-reviews-tab-count').text(total);

				window.currentProductCanReview = !!res.can_review;
				window.currentProductReviewMsg = res.review_message || '';
				renderReviewList(res.reviews);
			});

			$('.js-modal1').addClass('show-modal1');
		});

		function onVariantSelectionChanged() {
			if (!currentProductVariants || currentProductVariants.length === 0) return;

			var hasColorAttr = $('#modal-color-wrapper').is(':visible');
			var hasSizeAttr = $('#modal-size-wrapper').is(':visible');

			if (hasColorAttr && !selectedColor) {
				$('#modal-variant-info').html('<span class="text-muted"><i class="fa fa-info-circle mr-1"></i> Silakan pilih warna</span>').show();
				currentSelectedVariant = null;
				return;
			}
			if (hasSizeAttr && !selectedSize) {
				$('#modal-variant-info').html('<span class="text-muted"><i class="fa fa-info-circle mr-1"></i> Silakan pilih ukuran</span>').show();
				currentSelectedVariant = null;
				return;
			}

			var matched = currentProductVariants.find(function(v) {
				var matchC = !hasColorAttr || (v.warna === selectedColor);
				var matchS = !hasSizeAttr || (v.ukuran === selectedSize);
				return matchC && matchS;
			});

			if (matched) {
				currentSelectedVariant = matched;
				var vPrice = matched.harga ? matched.harga : currentModalProduct.harga;
				$('#modal-product-price').text(formatRupiah(vPrice));

				if (matched.stok > 0) {
					$('#modal-variant-info').html('<span class="text-success font-weight-bold"><i class="fa fa-check mr-1"></i> Stok Varian: ' + matched.stok + ' unit tersedia</span>').show();
				} else {
					$('#modal-variant-info').html('<span class="text-danger font-weight-bold"><i class="fa fa-times-circle mr-1"></i> Maaf, varian ini sedang habis</span>').show();
				}
			} else {
				currentSelectedVariant = null;
				$('#modal-variant-info').html('<span class="text-warning font-weight-bold"><i class="fa fa-exclamation-triangle mr-1"></i> Kombinasi variasi ini tidak tersedia</span>').show();
			}
		}

		function renderReviewList(reviews) {
			var $list = $('#modal-reviews-list');
			$list.empty();
			if (!reviews || reviews.length === 0) {
				$list.html('<div class="text-muted small text-center py-2 font-italic">Belum ada ulasan untuk produk ini.</div>');
				return;
			}

			reviews.forEach(function(r) {
				var stars = '';
				for (var i = 1; i <= 5; i++) {
					stars += '<i class="fa ' + (i <= r.rating ? 'fa-star text-warning' : 'fa-star-o text-muted') + '" style="font-size: 0.75rem;"></i>';
				}

				var fotoHtml = '';
				if (r.foto) {
					fotoHtml = '<div class="mt-2"><a href="' + escapeHtml(r.foto) + '" target="_blank"><img src="' + escapeHtml(r.foto) + '" alt="Foto Ulasan" style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;"></a></div>';
				}

				var html =
					'<div class="p-b-10 m-b-10 border-bottom" style="font-size: 0.83rem;">' +
						'<div class="d-flex justify-content-between align-items-center mb-1">' +
							'<div><strong class="text-dark">' + escapeHtml(r.nama) + '</strong><div class="text-warning mt-1">' + stars + '</div></div>' +
						'</div>' +
						'<p class="text-secondary m-0" style="line-height: 1.45;">' + escapeHtml(r.komentar) + '</p>' +
						fotoHtml +
					'</div>';
				$list.append(html);
			});
		}

		var compressedReviewBlob = null;

		function handleReviewPhotoSelect(input) {
			compressedReviewBlob = null;
			if (!input.files || !input.files[0]) {
				$('#review-foto-preview-wrap').hide();
				return;
			}
			var file = input.files[0];
			var reader = new FileReader();
			reader.onload = function(ev) {
				var img = new Image();
				img.onload = function() {
					var maxDim = 800;
					var w = img.width;
					var h = img.height;
					if (w > maxDim || h > maxDim) {
						var ratio = Math.min(maxDim / w, maxDim / h);
						w = Math.round(w * ratio);
						h = Math.round(h * ratio);
					}
					var canvas = document.createElement('canvas');
					canvas.width = w;
					canvas.height = h;
					var ctx = canvas.getContext('2d');
					ctx.fillStyle = '#ffffff';
					ctx.fillRect(0, 0, w, h);
					ctx.drawImage(img, 0, 0, w, h);
					canvas.toBlob(function(blob) {
						compressedReviewBlob = blob;
						$('#review-foto-preview').attr('src', canvas.toDataURL('image/jpeg', 0.72));
						$('#review-foto-preview-wrap').css('display', 'flex');
					}, 'image/jpeg', 0.72);
				};
				img.src = ev.target.result;
			};
			reader.readAsDataURL(file);
		}

		function clearReviewPhoto() {
			compressedReviewBlob = null;
			$('#review_foto').val('');
			$('#review-foto-preview').attr('src', '');
			$('#review-foto-preview-wrap').hide();
		}

		function toggleReviewForm() {
			if (!window.isLoggedIn) {
				requireLoginPrompt("menulis ulasan produk");
				return;
			}
			if (!$('#modal-write-review-box').is(':visible') && window.currentProductCanReview === false) {
				swal("Belum Dapat Mengulas", window.currentProductReviewMsg || "Ulasan hanya dapat ditulis setelah Anda membeli produk ini dan pesanan berstatus Selesai.", "info");
				return;
			}
			$('#modal-write-review-box').slideToggle(200);
		}

		function selectStarRating(val) {
			$('#review_rating_input').val(val);
			var labels = ['', '1 Bintang (Sangat Buruk)', '2 Bintang (Kurang)', '3 Bintang (Cukup)', '4 Bintang (Bagus)', '5 Bintang (Sangat Puas)'];
			$('#star-selected-text').text(labels[val] || (val + ' Bintang'));
			$('.js-star-select').each(function() {
				var r = parseInt($(this).data('rating'));
				if (r <= val) {
					$(this).removeClass('fa-star-o text-muted').addClass('fa-star text-warning');
				} else {
					$(this).removeClass('fa-star text-warning').addClass('fa-star-o text-muted');
				}
			});
		}

		function submitReview(e) {
			e.preventDefault();
			var $btn = $('#btn-submit-review');
			var originalText = $btn.html();
			$btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Mengirim...');

			var formData = new FormData();
			formData.append('_token', $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}');
			formData.append('barang_id', $('#review_barang_id').val());
			formData.append('rating', $('#review_rating_input').val());
			formData.append('nama_reviewer', $('#review_nama').val());
			formData.append('komentar', $('#review_komentar').val());

			if (compressedReviewBlob) {
				formData.append('foto', compressedReviewBlob, 'ulasan.jpg');
			} else if ($('#review_foto')[0] && $('#review_foto')[0].files[0]) {
				formData.append('foto', $('#review_foto')[0].files[0]);
			}

			$.ajax({
				url: '/review/store',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(res) {
					$btn.prop('disabled', false).html(originalText);
					swal("Ulasan Terkirim!", res.message, "success");
					$('#formSubmitReview')[0].reset();
					clearReviewPhoto();
					selectStarRating(5);
					$('#modal-write-review-box').slideUp(200);

					if (currentModalProduct && currentModalProduct.id) {
						$.get('/frontend/product/' + currentModalProduct.id, function(p) {
							window.currentProductCanReview = !!p.can_review;
							window.currentProductReviewMsg = p.review_message || '';
							renderReviewList(p.reviews);
							$('#modal-review-count').text(p.avg_rating > 0 ? (p.avg_rating + ' / 5.0 (' + p.total_reviews + ' ulasan)') : 'Belum ada ulasan');
							$('#modal-reviews-tab-count').text(p.total_reviews);
						});
					}
				},
				error: function(xhr) {
					$btn.prop('disabled', false).html(originalText);
					var msg = "Gagal mengirim ulasan. Silakan periksa formulir Anda.";
					if (xhr.responseJSON && xhr.responseJSON.message) {
						msg = xhr.responseJSON.message;
					}
					swal("Gagal Mengirim Ulasan", msg, "warning");
				}
			});
		}

		function changeModalQty(delta) {
			var $qty = $('#modal-product-qty');
			var current = parseInt($qty.val(), 10);
			if (isNaN(current) || current < 1) current = 1;
			var val = current + delta;
			if (val < 1) {
				swal("Jumlah Minimal 1", "Minimal masukkan 1 produk ke dalam keranjang.", "warning");
				$qty.val(1);
				return;
			}
			var maxStock = (currentSelectedVariant && typeof currentSelectedVariant.stok !== 'undefined') ? parseInt(currentSelectedVariant.stok, 10) : (currentModalProduct ? parseInt(currentModalProduct.stok, 10) : 0);
			if (isNaN(maxStock) || maxStock <= 0) {
				swal("Stok Habis", "Maaf, stok produk ini sedang habis.", "error");
				$qty.val(1);
				return;
			}
			if (val > maxStock) {
				swal("Stok Terbatas", "Jumlah melebihi stok yang tersedia (" + maxStock + " unit)", "warning");
				val = maxStock;
			}
			$qty.val(val);
		}

		function addModalProductToCart() {
			if (!currentModalProduct) return;
			if (!window.isLoggedIn) {
				requireLoginPrompt("menambahkan produk ke keranjang");
				return;
			}

			var hasVariants = $('#modal-variants-section').is(':visible');
			if (hasVariants) {
				var hasColorAttr = $('#modal-color-wrapper').is(':visible');
				var hasSizeAttr = $('#modal-size-wrapper').is(':visible');

				if (hasColorAttr && !selectedColor) {
					swal("Pilih Warna", "Silakan pilih warna produk terlebih dahulu.", "warning");
					return;
				}
				if (hasSizeAttr && !selectedSize) {
					swal("Pilih Ukuran", "Silakan pilih ukuran produk terlebih dahulu.", "warning");
					return;
				}
				if (!currentSelectedVariant) {
					swal("Variasi Tidak Tersedia", "Kombinasi warna dan ukuran yang Anda pilih tidak tersedia.", "warning");
					return;
				}
				if (currentSelectedVariant.stok <= 0) {
					swal("Stok Habis", "Maaf, stok variasi yang Anda pilih sedang habis.", "error");
					return;
				}
			}

			var qty = parseInt($('#modal-product-qty').val(), 10);
			if (isNaN(qty) || qty < 1) {
				swal("Jumlah Minimal 1", "Minimal masukkan 1 produk ke dalam keranjang.", "warning");
				$('#modal-product-qty').val(1);
				return;
			}

			var maxStock = (currentSelectedVariant && typeof currentSelectedVariant.stok !== 'undefined') ? parseInt(currentSelectedVariant.stok, 10) : parseInt(currentModalProduct.stok, 10);
			if (isNaN(maxStock) || maxStock <= 0) {
				swal("Stok Habis", "Maaf, stok produk yang Anda pilih sedang habis.", "error");
				return;
			}
			if (qty > maxStock) {
				swal("Stok Terbatas", "Jumlah melebihi stok yang tersedia (" + maxStock + " unit).", "warning");
				return;
			}

			var productToAdd = {
				id: currentModalProduct.id,
				nama: currentModalProduct.nama,
				gambar: currentModalProduct.gambar,
				harga: (currentSelectedVariant && currentSelectedVariant.harga) ? currentSelectedVariant.harga : currentModalProduct.harga,
				variasi_id: currentSelectedVariant ? currentSelectedVariant.id_variasi : null,
				variasi: currentSelectedVariant ? currentSelectedVariant.label : null,
				max_stok: maxStock
			};

			if (!addToCart(productToAdd, qty)) {
				return;
			}
			$('.js-modal1').removeClass('show-modal1');

			var descMsg = productToAdd.variasi ? (' (' + productToAdd.variasi + ')') : '';
			swal(currentModalProduct.nama, "Berhasil ditambahkan ke keranjang" + descMsg + " (" + qty + " unit)!", "success");
		}

		$(document).on('click', '.js-toggle-wishlist', function(e) {
			e.preventDefault();
			if (!window.isLoggedIn) {
				requireLoginPrompt("menyimpan produk ke wishlist");
				return;
			}
			var product = {
				id: String($(this).data('id')),
				nama: String($(this).data('nama')),
				kategori: String($(this).data('kategori')),
				harga: parseInt($(this).data('rawprice')) || 0,
				gambar: String($(this).data('gambar'))
			};
			toggleWishlist(product);
		});

		$(document).on('click', '.js-show-wishlist', function(e) {
			e.preventDefault();
			if (!window.isLoggedIn) {
				requireLoginPrompt("melihat wishlist Anda");
				return;
			}
			$('.js-panel-cart').removeClass('show-header-cart');
			$('.js-panel-wishlist').addClass('show-header-cart');
		});

		$(document).on('click', '.js-hide-wishlist', function(e) {
			e.preventDefault();
			$('.js-panel-wishlist').removeClass('show-header-cart');
		});

		$(document).on('click', '.js-show-cart', function(e) {
			if (!window.isLoggedIn) {
				e.preventDefault();
				requireLoginPrompt("melihat keranjang belanja");
				return;
			}
			$('.js-panel-wishlist').removeClass('show-header-cart');
		});

		function copyProductShareLink() {
			if (!currentModalProduct || !currentModalProduct.id) return;
			var url = window.location.origin + '/shop?produk=' + currentModalProduct.id;
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url);
			}
			swal("Link Produk Disalin!", "Link produk siap dibagikan: " + url, "success");
		}

		// Initialize state on load + Sync Cart & Wishlist from Database when logged in + Deep-link ?produk={id}
		$(document).ready(function() {
			updateCartUI();
			updateWishlistUI();

			var urlParams = new URLSearchParams(window.location.search);
			var sharedProdukId = urlParams.get('produk');
			if (sharedProdukId) {
				var $trigger = $('.js-show-product-modal[data-id="' + sharedProdukId + '"]').first();
				if ($trigger.length) {
					$trigger.trigger('click');
				} else {
					$.get('/frontend/product/' + sharedProdukId, function(res) {
						if (!res || !res.id_barang) return;
						currentModalProduct = {
							id: String(res.id_barang),
							nama: String(res.nama),
							kategori: String(res.kategori || ''),
							supplier: String(res.supplier || ''),
							stok: parseInt(res.stok, 10) || 0,
							harga: parseInt(res.harga, 10) || 0,
							gambar: String(res.gambar || '')
						};
						$('#modal-product-name').text(currentModalProduct.nama);
						$('#modal-product-price').text(formatRupiah(currentModalProduct.harga));
						$('#modal-product-desc').html(
							'<div class="mb-2"><span class="badge badge-info">' + escapeHtml(currentModalProduct.kategori) + '</span></div>' +
							'<strong>Supplier:</strong> ' + escapeHtml(currentModalProduct.supplier) + '<br>' +
							'<strong>Total Stok:</strong> <span id="modal-base-stock">' + currentModalProduct.stok + '</span> unit'
						);
						$('#modal-product-img').attr('src', currentModalProduct.gambar);
						$('#modal-product-qty').val(1);
						$('#review_barang_id').val(currentModalProduct.id);
						if (res.is_promo_active) {
							$('#modal-product-price').html(
								formatRupiah(res.harga) +
								' <del class="text-muted font-weight-normal ml-2" style="font-size: 0.95rem;">' + formatRupiah(res.harga_asli) + '</del>' +
								' <span class="badge badge-danger ml-1" style="font-size: 0.75rem; vertical-align: middle;">-' + res.diskon_persen + '%</span>'
							);
						}
						window.currentProductCanReview = !!res.can_review;
						window.currentProductReviewMsg = res.review_message || '';
						renderReviewList(res.reviews);
						$('.js-modal1').addClass('show-modal1');
					});
				}
			}

			if (window.isLoggedIn) {
				$.get('{{ route("frontend.sync.state") }}', function(res) {
					if (!res || !res.success) return;

					var localCart = getCart();
					if (res.cart && res.cart.length > 0) {
						localStorage.setItem(CART_KEY, JSON.stringify(res.cart));
					} else if (localCart.length > 0) {
						saveCart(localCart);
					}

					var localWishlist = getWishlist();
					if (res.wishlist && res.wishlist.length > 0) {
						localStorage.setItem(WISHLIST_KEY, JSON.stringify(res.wishlist));
					} else if (localWishlist.length > 0) {
						saveWishlist(localWishlist);
					}

					updateCartUI();
					updateWishlistUI();
				});
			}
		});

