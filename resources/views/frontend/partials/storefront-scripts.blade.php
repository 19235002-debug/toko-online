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

		function hasSelectedCourier() {
			return !!($('#checkout_destination_id').val() && $('#checkout_courier_code').val() && $('#checkout_courier_service').val());
		}

		function recalculateCheckoutTotals() {
			var subtotal = window.currentCheckoutSubtotal || 0;
			var discount = window.appliedVoucherDiscount || 0;
			var selected = hasSelectedCourier();
			var ongkir = selected ? (parseInt($('#checkout_ongkir_preview').val(), 10) || 0) : 0;

			var $ongkirEl = $('#checkout-summary-ongkir');
			if (!selected) {
				$ongkirEl.removeClass('text-dark').addClass('text-muted').text('Pilih kurir');
				$('#checkout-summary-kurir').text('');
			} else {
				$ongkirEl.removeClass('text-muted').addClass('text-dark').text(formatRupiah(ongkir));
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
			window.currentCheckoutTotalQty = totalQty;
			window.appliedVoucherCode = null;
			window.appliedVoucherDiscount = 0;

			$('#checkout_kode_voucher').val('');
			$('#voucher-feedback').hide().text('');
			$('#checkout-summary-discount-row').attr('style', 'display: none !important;');

			resetRajaOngkirSelection(true);

			$('#checkout-summary-total-qty').text(totalQty + ' pcs');
			$('#checkout-summary-subtotal').text(formatRupiah(totalPrice));
			recalculateCheckoutTotals();
			loadAvailableVouchers(totalPrice);

			$('.js-panel-cart').removeClass('show-header-cart');
			$('#modalCheckout').modal('show');
		}

		function loadAvailableVouchers(subtotal) {
			var $wrap = $('#checkout-available-vouchers-wrap');
			var $list = $('#checkout-available-vouchers-list');
			$list.html('<div class="text-muted small py-1"><i class="fa fa-spinner fa-spin mr-1"></i> Memuat voucher...</div>');
			$wrap.show();

			$.get('{{ route("frontend.voucher.available") }}', { subtotal: subtotal }, function(res) {
				if (!res || !res.vouchers || res.vouchers.length === 0) {
					$wrap.hide();
					return;
				}
				var html = '';
				res.vouchers.forEach(function(v) {
					var minInfo = v.minimal_belanja > 0 ? 'Min. belanja ' + formatRupiah(v.minimal_belanja) : 'Tanpa minimal belanja';
					var expInfo = v.tanggal_selesai ? ' • s/d ' + escapeHtml(v.tanggal_selesai) : '';
					var actionBtn = v.eligible
						? '<button type="button" onclick="applyVoucher(\'' + escapeHtml(v.kode_voucher) + '\')" class="btn btn-xs btn-primary font-weight-bold px-2 py-1" style="border-radius: 6px; font-size: 11px; white-space: nowrap;">Pakai</button>'
						: '<span class="badge badge-light border text-muted" style="font-size: 10px; white-space: nowrap;">Tambah ' + formatRupiah(v.kurang) + '</span>';

					html +=
						'<div class="d-flex justify-content-between align-items-center p-2 border rounded" style="background: #f8fafc;">' +
							'<div style="min-width: 0; padding-right: 8px;">' +
								'<div class="d-flex align-items-center" style="gap: 6px;">' +
									'<span class="badge badge-primary" style="font-size: 10.5px;">' + escapeHtml(v.kode_voucher) + '</span>' +
									'<strong class="text-dark" style="font-size: 11.5px;">' + escapeHtml(v.label_diskon) + '</strong>' +
								'</div>' +
								'<div class="text-muted text-truncate" style="font-size: 10.5px; margin-top: 2px;">' + minInfo + expInfo + '</div>' +
							'</div>' +
							actionBtn +
						'</div>';
				});
				$list.html(html);
				$wrap.show();
			}).fail(function() {
				$wrap.hide();
			});
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

		var receiptPollTimer = null;
		var receiptAutoCloseTimer = null;

		function stopReceiptPolling() {
			if (receiptPollTimer) {
				clearInterval(receiptPollTimer);
				receiptPollTimer = null;
			}
		}

		function stopReceiptAutoClose() {
			if (receiptAutoCloseTimer) {
				clearTimeout(receiptAutoCloseTimer);
				receiptAutoCloseTimer = null;
			}
			$('#receipt-progress-wrap').hide();
			$('#receipt-progress-bar').css({ transition: 'none', width: '100%' });
		}

		function startReceiptAutoClose() {
			stopReceiptAutoClose();
			var $wrap = $('#receipt-progress-wrap');
			var $bar = $('#receipt-progress-bar');
			$bar.css({ transition: 'none', width: '100%' });
			$wrap.show();
			// Trigger reflow agar animasi transisi 3 detik berjalan mulus dari 100% ke 0%
			if ($bar[0]) void $bar[0].offsetWidth;
			$bar.css({ transition: 'width 3s linear', width: '0%' });

			receiptAutoCloseTimer = setTimeout(function() {
				$('#modalOrderReceipt').modal('hide');
			}, 3000);
		}

		function startSnapLivePolling(res) {
			stopReceiptPolling();
			if (!res || !res.kode_order) return;
			receiptPollTimer = setInterval(function() {
				$.post('/midtrans/sync/' + res.kode_order, { _token: '{{ csrf_token() }}' }, function(syncRes) {
					if (syncRes && syncRes.status === 'diproses') {
						stopReceiptPolling();
						try {
							if (window.snap && typeof window.snap.hide === 'function') {
								window.snap.hide();
							}
						} catch (e) {}
						showModernOrderReceipt(res, 'paid');
					}
				});
			}, 3500);
		}

		function manualCheckReceiptStatus() {
			var res = window.lastCheckoutRes;
			if (!res || !res.kode_order) return;
			var $btn = $('#receipt-recheck-btn');
			$btn.prop('disabled', true).text('Mengecek...');
			$.post('/midtrans/sync/' + res.kode_order, { _token: '{{ csrf_token() }}' }, function(syncRes) {
				$btn.prop('disabled', false).text('Cek Status');
				if (syncRes && syncRes.status === 'diproses') {
					stopReceiptPolling();
					showModernOrderReceipt(res, 'paid');
				} else if (syncRes && syncRes.status === 'dibatalkan') {
					stopReceiptPolling();
					$('#modalOrderReceipt').modal('hide');
					swal("Pesanan Dibatalkan", "Transaksi dibatalkan atau kedaluwarsa.", "warning");
				}
			}).fail(function() {
				$btn.prop('disabled', false).text('Cek Status');
			});
		}

		function reopenReceiptSnap() {
			var res = window.lastCheckoutRes;
			if (!res || !res.snap_token || typeof window.snap === 'undefined') return;
			$('#modalOrderReceipt').modal('hide');
			openSnapPayment(res);
		}

		function openSnapPayment(res) {
			startSnapLivePolling(res);
			window.snap.pay(res.snap_token, {
				onSuccess: function(result) {
					stopReceiptPolling();
					$.post('/midtrans/sync/' + res.kode_order, { _token: '{{ csrf_token() }}' });
					showModernOrderReceipt(res, 'paid');
				},
				onPending: function(result) {
					// Tunggu sinkronisasi selesai sebelum menentukan status modal
					$.post('/midtrans/sync/' + res.kode_order, { _token: '{{ csrf_token() }}' }, function(syncRes) {
						showModernOrderReceipt(res, (syncRes && syncRes.status === 'diproses') ? 'paid' : 'pending');
					}).fail(function() {
						showModernOrderReceipt(res, 'pending');
					});
				},
				onError: function(result) {
					stopReceiptPolling();
					swal({
						title: "Pembayaran Gagal",
						text: "Pembayaran untuk pesanan " + res.kode_order + " gagal diproses.",
						icon: "error"
					});
				},
				onClose: function() {
					// Jika sudah terbayar di simulator/mobile banking, langsung tampilkan sukses
					$.post('/midtrans/sync/' + res.kode_order, { _token: '{{ csrf_token() }}' }, function(syncRes) {
						showModernOrderReceipt(res, (syncRes && syncRes.status === 'diproses') ? 'paid' : 'pending');
					}).fail(function() {
						showModernOrderReceipt(res, 'pending');
					});
				}
			});
		}

		function showModernOrderReceipt(res, state) {
			window.lastCheckoutRes = res;
			var $hero = $('#receipt-hero');
			var $iconWrap = $('#receipt-icon-wrap');
			var $icon = $('#receipt-icon');
			var $badge = $('#receipt-badge');
			var $sub = $('#receipt-subtitle');
			var $pendingBar = $('#receipt-pending-bar');
			var $reopenBtn = $('#receipt-reopen-snap-btn');

			if (state === 'paid') {
				stopReceiptPolling();
				$pendingBar.hide();
				$reopenBtn.hide();
				$hero.css('background', 'linear-gradient(135deg, #ecfdf5 0%, #eef2ff 100%)');
				$iconWrap.css({ background: '#10b981', boxShadow: '0 12px 28px rgba(16, 185, 129, 0.3)' });
				$icon.attr('class', 'fa fa-check');
				$badge.html('<span class="badge badge-success px-3 py-1" style="border-radius: 999px; font-size: 11.5px;">Pembayaran Berhasil</span>');
				$sub.text('Pembayaran diterima. Pesanan Anda segera dikemas & dikirim.');
				startReceiptAutoClose();
			} else if (state === 'cod') {
				stopReceiptPolling();
				$pendingBar.hide();
				$reopenBtn.hide();
				$hero.css('background', 'linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%)');
				$iconWrap.css({ background: '#4f46e5', boxShadow: '0 12px 28px rgba(79, 70, 229, 0.28)' });
				$icon.attr('class', 'fa fa-check');
				$badge.html('<span class="badge badge-primary px-3 py-1" style="border-radius: 999px; font-size: 11.5px;">Pesanan COD Dibuat</span>');
				$sub.text('Bayar tunai saat paket tiba. Menunggu konfirmasi penjual.');
				startReceiptAutoClose();
			} else {
				stopReceiptAutoClose();
				$pendingBar.show();
				$reopenBtn.toggle(!!res.snap_token);
				$hero.css('background', 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)');
				$iconWrap.css({ background: '#f59e0b', boxShadow: '0 12px 28px rgba(245, 158, 11, 0.28)' });
				$icon.attr('class', 'fa fa-clock-o');
				$badge.html('<span class="badge badge-warning text-white px-3 py-1" style="border-radius: 999px; font-size: 11.5px;">Menunggu Pembayaran</span>');
				$sub.text('Selesaikan pembayaran dalam 24 jam. Status diperbarui otomatis.');
				startSnapLivePolling(res);
			}

			$('#receipt-amount').text(res.total_formatted || 'Rp 0');
			$('#receipt-kode').text(res.kode_order || '-');
			$('#receipt-metode').text((res.metode_pembayaran || '-').toUpperCase());
			$('#receipt-kurir').text(res.kurir || '-');
			$('#receipt-track-btn').attr('href', res.track_url || ('/lacak-pesanan?order=' + encodeURIComponent(res.kode_order || '')));
			$('#receipt-invoice-btn').attr('href', res.invoice_url || ('/pesanan-saya/' + encodeURIComponent(res.kode_order || '') + '/invoice'));
			$('#receipt-wa-btn').attr('href', res.wa_url || '#');

			$('#modalOrderReceipt').modal('show');
		}

		$(document).on('hidden.bs.modal', '#modalOrderReceipt', function() {
			stopReceiptPolling();
			stopReceiptAutoClose();
		});

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

			if (!hasSelectedCourier()) {
				swal("Pengiriman Belum Dipilih", "Silakan pilih kecamatan / kota tujuan lalu pilih layanan kurir pengiriman terlebih dahulu.", "warning");
				$('#ro-destination-input').focus();
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
				destination_id: $('#checkout_destination_id').val(),
				destination_label: $('#checkout_destination_label').val(),
				courier_code: $('#checkout_courier_code').val(),
				courier_service: $('#checkout_courier_service').val(),
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
							window.lastCheckoutRes = res;
							openSnapPayment(res);
						} else {
							showModernOrderReceipt(res, 'cod');
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
			$('#modal-promo-countdown-wrap').hide();
			$('#modal-promo-countdown').attr('data-promo-end', '');

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
					if (res.promo_selesai_iso) {
						$('#modal-promo-countdown').attr('data-promo-end', res.promo_selesai_iso);
						$('#modal-promo-countdown-wrap').show();
						updatePromoCountdowns();
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
							var $btn = $('<button type="button" class="var-pill js-color-pill"></button>').attr('data-val', col).text(col);
							$btn.on('click', function() {
								if ($(this).prop('disabled')) return;
								$('.js-color-pill').removeClass('active');
								$(this).addClass('active');
								selectedColor = col;
								$('#selected-color-label').text(col);
								syncVariantPillStates('color');
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
							var $btn = $('<button type="button" class="var-pill js-size-pill"></button>').attr('data-val', sz).text(sz);
							$btn.on('click', function() {
								if ($(this).prop('disabled')) return;
								$('.js-size-pill').removeClass('active');
								$(this).addClass('active');
								selectedSize = sz;
								$('#selected-size-label').text(sz);
								syncVariantPillStates('size');
								onVariantSelectionChanged();
							});
							$szOpts.append($btn);
						});
					} else {
						$('#modal-size-wrapper').hide();
					}

					syncVariantPillStates(false);
					$('#modal-variant-info').html('<span class="text-info"><i class="fa fa-info-circle mr-1"></i> Silakan pilih variasi warna/ukuran yang tersedia</span>').show();
					setCartButtonAvailability(currentModalProduct.stok > 0);
				} else {
					$('#modal-variants-section').hide();
					setCartButtonAvailability(currentModalProduct.stok > 0);
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

		function setCartButtonAvailability(isAvailable, labelText) {
			var $cartBtn = $('.quickview-btn-cart');
			if (isAvailable) {
				$cartBtn.prop('disabled', false).html('<i class="zmdi zmdi-shopping-cart fs-18"></i> ' + (labelText || 'Tambah ke Keranjang'));
			} else {
				$cartBtn.prop('disabled', true).html('<i class="fa fa-ban mr-1"></i> ' + (labelText || 'Stok Habis'));
			}
		}

		function syncVariantPillStates(changedAxis) {
			if (!currentProductVariants || currentProductVariants.length === 0) return;

			var firstValidColor = null;
			var selectedColorStillValid = false;

			$('.js-color-pill').each(function() {
				var col = $(this).attr('data-val');
				var hasStock = currentProductVariants.some(function(v) {
					var matchSize = (changedAxis === 'size' && selectedSize) ? (v.ukuran === selectedSize) : true;
					return matchSize && v.warna === col && parseInt(v.stok, 10) > 0;
				});
				$(this).prop('disabled', !hasStock).toggleClass('is-unavailable', !hasStock);
				if (hasStock) {
					if (!firstValidColor) firstValidColor = col;
					if (selectedColor === col) selectedColorStillValid = true;
				}
			});

			if (changedAxis === 'size') {
				if (selectedColor && !selectedColorStillValid) {
					selectedColor = null;
					$('.js-color-pill').removeClass('active');
					$('#selected-color-label').text('Pilih warna');
				}
				if (!selectedColor && firstValidColor) {
					selectedColor = firstValidColor;
					$('.js-color-pill').each(function() {
						if ($(this).attr('data-val') === firstValidColor) {
							$(this).addClass('active');
						}
					});
					$('#selected-color-label').text(firstValidColor);
				}
			}

			var firstValidSize = null;
			var selectedSizeStillValid = false;

			$('.js-size-pill').each(function() {
				var sz = $(this).attr('data-val');
				var hasStock = currentProductVariants.some(function(v) {
					var matchCol = (changedAxis === 'color' && selectedColor) ? (v.warna === selectedColor) : true;
					return matchCol && v.ukuran === sz && parseInt(v.stok, 10) > 0;
				});
				$(this).prop('disabled', !hasStock).toggleClass('is-unavailable', !hasStock);
				if (hasStock) {
					if (!firstValidSize) firstValidSize = sz;
					if (selectedSize === sz) selectedSizeStillValid = true;
				}
			});

			if (changedAxis === 'color') {
				if (selectedSize && !selectedSizeStillValid) {
					selectedSize = null;
					$('.js-size-pill').removeClass('active');
					$('#selected-size-label').text('Pilih ukuran');
				}
				if (!selectedSize && firstValidSize) {
					selectedSize = firstValidSize;
					$('.js-size-pill').each(function() {
						if ($(this).attr('data-val') === firstValidSize) {
							$(this).addClass('active');
						}
					});
					$('#selected-size-label').text(firstValidSize);
				}
			}
		}

		function onVariantSelectionChanged() {
			if (!currentProductVariants || currentProductVariants.length === 0) return;

			var hasColorAttr = $('#modal-color-wrapper').is(':visible');
			var hasSizeAttr = $('#modal-size-wrapper').is(':visible');

			if (hasColorAttr && !selectedColor) {
				$('#modal-variant-info').html('<span class="text-muted"><i class="fa fa-info-circle mr-1"></i> Silakan pilih warna</span>').show();
				currentSelectedVariant = null;
				setCartButtonAvailability(currentModalProduct.stok > 0);
				return;
			}
			if (hasSizeAttr && !selectedSize) {
				$('#modal-variant-info').html('<span class="text-muted"><i class="fa fa-info-circle mr-1"></i> Silakan pilih ukuran</span>').show();
				currentSelectedVariant = null;
				setCartButtonAvailability(currentModalProduct.stok > 0);
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
					setCartButtonAvailability(true);
				} else {
					$('#modal-variant-info').html('<span class="text-danger font-weight-bold"><i class="fa fa-times-circle mr-1"></i> Maaf, varian ini sedang habis</span>').show();
					setCartButtonAvailability(false, 'Stok Habis');
				}
			} else {
				currentSelectedVariant = null;
				$('#modal-variant-info').html('<span class="text-muted"><i class="fa fa-info-circle mr-1"></i> Kombinasi variasi tidak tersedia</span>').show();
				setCartButtonAvailability(false, 'Tidak Tersedia');
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
							if (res.promo_selesai_iso) {
								$('#modal-promo-countdown').attr('data-promo-end', res.promo_selesai_iso);
								$('#modal-promo-countdown-wrap').show();
								updatePromoCountdowns();
							}
						} else {
							$('#modal-promo-countdown-wrap').hide();
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

			updatePromoCountdowns();
			setInterval(updatePromoCountdowns, 1000);
			initRajaOngkirCheckout();
		});

		var roSearchTimer = null;
		function initRajaOngkirCheckout() {
			$(document).on('input', '#ro-destination-input', function() {
				var q = ($(this).val() || '').trim();
				var $dd = $('#ro-destination-dropdown');
				if (roSearchTimer) clearTimeout(roSearchTimer);
				if (q.length === 0) {
					$dd.hide().empty();
					return;
				}
				if (q.length < 3) {
					$dd.html('<div class="list-group-item small text-muted py-2">Ketik minimal 3 huruf (kelurahan, kecamatan, atau kode pos)...</div>').show();
					return;
				}
				roSearchTimer = setTimeout(function() {
					$dd.html('<div class="list-group-item small text-muted py-2">Mencari wilayah...</div>').show();
					$.get('{{ route("frontend.shipping.destinations") }}', { q: q }, function(res) {
						if (!res || !res.destinations || res.destinations.length === 0) {
							$dd.html('<div class="list-group-item small text-muted py-2">Wilayah tidak ditemukan. Coba nama kelurahan, kecamatan, atau kode pos lain.</div>');
							return;
						}
						var html = '';
						res.destinations.forEach(function(d) {
							html += '<a href="javascript:void(0);" class="list-group-item list-group-item-action py-2 px-3 small" onclick="selectRajaOngkirDestination(' + parseInt(d.id, 10) + ', \'' + escapeHtml(d.label).replace(/'/g, "\\'") + '\')">' +
								escapeHtml(d.label) +
							'</a>';
						});
						$dd.html(html).show();
					}).fail(function() {
						$dd.hide();
					});
				}, 300);
			});

			$(document).on('click', function(e) {
				if (!$(e.target).closest('#ro-destination-input, #ro-destination-dropdown').length) {
					$('#ro-destination-dropdown').hide();
				}
			});
		}

		function resetRajaOngkirSelection(clearInput) {
			if (clearInput) {
				$('#ro-destination-input').val('');
				$('#ro-selected-dest-badge').hide().text('');
				$('#checkout_destination_id').val('');
				$('#checkout_destination_label').val('');
			}
			$('#ro-destination-dropdown').hide().empty();
			$('#ro-courier-options-wrap').hide();
			$('#ro-courier-options-list').empty();
			$('#checkout_courier_code').val('');
			$('#checkout_courier_service').val('');
			$('#checkout_ongkir_preview').val('0');
			$('#checkout-summary-kurir').text('');
			$('#ro-shipping-hint').attr('style', 'display: flex !important; background: #f8fafc; border: 1px dashed #cbd5e1; gap: 8px; font-size: 11.5px; color: #64748b;').addClass('p-2 rounded');
			recalculateCheckoutTotals();
		}

		function selectRajaOngkirDestination(destId, destLabel) {
			resetRajaOngkirSelection(false);
			$('#ro-destination-input').val(destLabel);
			$('#checkout_destination_id').val(destId);
			$('#checkout_destination_label').val(destLabel);
			$('#ro-selected-dest-badge').text('Tujuan dipilih: ' + destLabel).show();
			$('#ro-shipping-hint').attr('style', 'display: none !important;');

			var $wrap = $('#ro-courier-options-wrap');
			var $list = $('#ro-courier-options-list');
			$list.html('<div class="text-muted small py-2">Menghitung tarif kurir...</div>');
			$wrap.show();

			$.post('{{ route("frontend.shipping.cost") }}', {
				_token: $('input[name="_token"]').val() || '{{ csrf_token() }}',
				destination_id: destId,
				total_qty: window.currentCheckoutTotalQty || 1
			}, function(res) {
				if (!res || !res.options || res.options.length === 0) {
					$list.html('<div class="text-danger small py-1">Layanan kurir tidak tersedia untuk tujuan ini. Coba wilayah lain.</div>');
					return;
				}
				var beratKg = ((res.weight_grams || 0) / 1000).toFixed(2).replace(/\.?0+$/, '');
				var html = '<div class="text-muted mb-1" style="font-size: 10.5px;">Estimasi berat paket: ' + beratKg + ' kg — pilih layanan:</div>';
				res.options.forEach(function(opt) {
					html +=
						'<label class="ro-courier-option d-flex justify-content-between align-items-center p-2 border rounded mb-0" style="background: #f8fafc; cursor: pointer;">' +
							'<div class="d-flex align-items-center" style="gap: 8px;">' +
								'<input type="radio" name="ro_courier" ' +
									'data-code="' + escapeHtml(opt.code) + '" ' +
									'data-service="' + escapeHtml(opt.service) + '" ' +
									'data-cost="' + parseInt(opt.cost, 10) + '" ' +
									'data-label="' + escapeHtml(opt.label + ' (' + opt.etd + ')') + '">' +
								'<div>' +
									'<strong class="text-dark d-block" style="font-size: 12px;">' + escapeHtml(opt.label) + ' <span class="text-muted font-weight-normal">(' + escapeHtml(opt.description || opt.courier_name) + ')</span></strong>' +
									'<small class="text-muted" style="font-size: 10.5px;">Estimasi ' + escapeHtml(opt.etd) + '</small>' +
								'</div>' +
							'</div>' +
							'<strong class="text-primary" style="font-size: 12.5px; white-space: nowrap;">' + formatRupiah(opt.cost) + '</strong>' +
						'</label>';
				});
				$list.html(html);
				// Pilih otomatis layanan termurah (urutan pertama) agar pembeli langsung melihat total
				$list.find('input[name="ro_courier"]').first().prop('checked', true).trigger('change');
			}).fail(function() {
				$list.html('<div class="text-danger small py-1">Gagal menghitung ongkir. Periksa koneksi lalu pilih ulang tujuan.</div>');
			});
		}

		$(document).on('change', 'input[name="ro_courier"]', function() {
			var $r = $(this);
			$('.ro-courier-option').css({ background: '#f8fafc', borderColor: '#dee2e6' });
			$r.closest('.ro-courier-option').css({ background: '#eef2ff', borderColor: '#717fe0' });
			$('#checkout_courier_code').val($r.data('code'));
			$('#checkout_courier_service').val($r.data('service'));
			$('#checkout_ongkir_preview').val($r.data('cost'));
			$('#checkout-summary-kurir').text($r.data('label'));
			recalculateCheckoutTotals();
		});

		// Jika pembeli mengetik ulang tujuan, batalkan pilihan kurir sebelumnya
		$(document).on('input', '#ro-destination-input', function() {
			if ($('#checkout_destination_id').val() && $(this).val() !== $('#checkout_destination_label').val()) {
				$('#checkout_destination_id').val('');
				$('#checkout_destination_label').val('');
				$('#ro-selected-dest-badge').hide().text('');
				$('#ro-courier-options-wrap').hide();
				$('#ro-courier-options-list').empty();
				$('#checkout_courier_code').val('');
				$('#checkout_courier_service').val('');
				$('#checkout_ongkir_preview').val('0');
				recalculateCheckoutTotals();
			}
		});

		function updatePromoCountdowns() {
			var now = Date.now();
			$('.js-promo-countdown').each(function() {
				var endAttr = $(this).attr('data-promo-end');
				if (!endAttr) return;
				var endMs = Date.parse(endAttr);
				if (isNaN(endMs)) return;

				var diff = Math.floor((endMs - now) / 1000);
				if (diff <= 0) {
					$(this).text('Berakhir');
					return;
				}
				var days = Math.floor(diff / 86400);
				var hours = Math.floor((diff % 86400) / 3600);
				var mins = Math.floor((diff % 3600) / 60);
				var secs = diff % 60;
				var pad = function(n) { return n < 10 ? '0' + n : String(n); };
				var label = (days > 0 ? days + ' Hari ' : '') + pad(hours) + ':' + pad(mins) + ':' + pad(secs);
				$(this).html('<i class="fa fa-clock-o mr-1"></i>' + label);
			});
		}

