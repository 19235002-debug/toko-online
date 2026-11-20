	<!-- Modal1 (Quick View Modern) -->
	<div class="wrap-modal1 js-modal1">
		<div class="overlay-modal1 js-hide-modal1"></div>

		<div class="container" style="max-width: 980px; width: 100%; position: relative; z-index: 2;">
			<div class="quickview-card-modern">
				<button type="button" class="quickview-close-btn js-hide-modal1" aria-label="Tutup">
					<i class="zmdi zmdi-close"></i>
				</button>

				<div class="row no-gutters">
					<!-- Left: Gallery Showcase -->
					<div class="col-md-6">
						<div class="quickview-gallery-pane">
							<div class="modal-img-wrapper">
								<img id="modal-product-img" src="{{ asset('frontend-assets/images/product-detail-01.jpg') }}" alt="IMG-PRODUCT">
							</div>
							<!-- Gallery Thumbnails Strip -->
							<div id="modal-gallery-thumbnails" class="d-flex justify-content-center flex-wrap mt-3" style="display: none !important; gap: 8px;">
								<!-- Dynamic Thumbnails inserted here -->
							</div>
						</div>
					</div>
					
					<!-- Right: Product Info, Variations & Reviews -->
					<div class="col-md-6">
						<div class="quickview-detail-pane">
							<h4 id="modal-product-name">
								Nama Produk
							</h4>

							<div class="d-flex align-items-center mb-2" id="modal-rating-summary">
								<span id="modal-star-display" class="text-warning mr-2" style="font-size: 0.9rem;">
									<i class="fa fa-star text-warning"></i>
									<i class="fa fa-star text-warning"></i>
									<i class="fa fa-star text-warning"></i>
									<i class="fa fa-star text-warning"></i>
									<i class="fa fa-star text-warning"></i>
								</span>
								<span id="modal-review-count" class="text-muted small font-weight-bold">0 ulasan</span>
							</div>

							<span id="modal-product-price">
								Rp 0
							</span>

							<div id="modal-promo-countdown-wrap" class="mb-2" style="display: none;">
								<span class="badge badge-danger px-2 py-1" style="font-size: 0.78rem;">
									Promo berakhir dalam
									<span id="modal-promo-countdown" class="js-promo-countdown font-weight-bold ml-1" data-promo-end="">--:--:--</span>
								</span>
							</div>

							<div class="quickview-desc-box">
								<p id="modal-product-desc">
									Deskripsi produk
								</p>
							</div>

							<!-- Variations Section (Warna & Ukuran) -->
							<div id="modal-variants-section" class="pb-3 mb-2 border-bottom" style="display: none;">
								<!-- Warna -->
								<div id="modal-color-wrapper" class="mb-3" style="display: none;">
									<label class="d-block mb-2" style="font-size: 0.8rem; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.03em;">
										Pilih Warna: <span id="selected-color-label" class="text-primary font-weight-normal text-capitalize">Pilih warna</span>
									</label>
									<div id="modal-color-options" class="d-flex flex-wrap" style="gap: 8px;"></div>
								</div>

								<!-- Ukuran -->
								<div id="modal-size-wrapper" class="mb-2" style="display: none;">
									<label class="d-block mb-2" style="font-size: 0.8rem; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.03em;">
										Pilih Ukuran: <span id="selected-size-label" class="text-primary font-weight-normal text-capitalize">Pilih ukuran</span>
									</label>
									<div id="modal-size-options" class="d-flex flex-wrap" style="gap: 8px;"></div>
								</div>

								<!-- Selected Variant Info & Stock Alert -->
								<div id="modal-variant-info" class="small mt-2" style="display: none;"></div>
							</div>
							
							<!-- Qty & Action CTA -->
							<div class="modal-action-wrapper">
								<div class="quickview-action-row">
									<div class="wrap-num-product quickview-qty-stepper">
										<div class="btn-num-product-down flex-c-m" onclick="changeModalQty(-1)" style="cursor: pointer;">
											<i class="fs-16 zmdi zmdi-minus"></i>
										</div>

										<input class="txt-center num-product" type="number" id="modal-product-qty" value="1" min="1" readonly>

										<div class="btn-num-product-up flex-c-m" onclick="changeModalQty(1)" style="cursor: pointer;">
											<i class="fs-16 zmdi zmdi-plus"></i>
										</div>
									</div>

									<button type="button" class="quickview-btn-cart" onclick="addModalProductToCart()">
										<i class="zmdi zmdi-shopping-cart fs-18"></i> Tambah ke Keranjang
									</button>
								</div>
								<button type="button" class="quickview-btn-admin" onclick="copyProductShareLink()">
									<i class="fa fa-share-alt"></i> Salin Link Produk
								</button>
							</div>

							<!-- Reviews Section -->
							<div class="quickview-reviews-section">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<strong class="text-dark" style="font-size: 0.88rem;"><i class="fa fa-comments text-primary mr-1"></i> Ulasan Pembeli (<span id="modal-reviews-tab-count">0</span>)</strong>
									<button type="button" class="quickview-review-toggle-btn" id="btn-toggle-review-form" onclick="toggleReviewForm()">
										<i class="fa fa-pencil mr-1"></i> Tulis Ulasan
									</button>
								</div>

								<!-- Write Review Form (Collapsible) -->
								<div id="modal-write-review-box" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 12px;">
									<h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.85rem;">Beri Ulasan Produk</h6>
									<form id="formSubmitReview" onsubmit="submitReview(event)" enctype="multipart/form-data">
										<input type="hidden" id="review_barang_id" name="barang_id" value="">

										<div class="form-group mb-2">
											<label class="small font-weight-bold mb-1">Rating Bintang <span class="text-danger">*</span>:</label>
											<div class="star-rating-select d-flex align-items-center" style="cursor: pointer; font-size: 1.2rem; color: #ffc107;">
												<i class="fa fa-star js-star-select" data-rating="1" onclick="selectStarRating(1)"></i>
												<i class="fa fa-star js-star-select" data-rating="2" onclick="selectStarRating(2)"></i>
												<i class="fa fa-star js-star-select" data-rating="3" onclick="selectStarRating(3)"></i>
												<i class="fa fa-star js-star-select" data-rating="4" onclick="selectStarRating(4)"></i>
												<i class="fa fa-star js-star-select" data-rating="5" onclick="selectStarRating(5)"></i>
												<span class="small text-muted font-weight-bold ml-2" id="star-selected-text">5 Bintang</span>
											</div>
											<input type="hidden" name="rating" id="review_rating_input" value="5">
										</div>

										<div class="form-group mb-2">
											<input type="text" name="nama_reviewer" id="review_nama" class="form-control form-control-sm" placeholder="Nama Pelanggan *" value="{{ auth()->check() ? auth()->user()->nama : '' }}" required style="font-size: 0.82rem; border-radius: 8px;">
										</div>

										<div class="form-group mb-2">
											<textarea name="komentar" id="review_komentar" rows="2" class="form-control form-control-sm" placeholder="Tuliskan pengalaman Anda memakai produk ini..." required style="font-size: 0.82rem; border-radius: 8px;"></textarea>
										</div>

										<div class="form-group mb-2">
											<label class="small font-weight-bold mb-1 d-block">Foto Produk (Opsional):</label>
											<input type="file" name="foto" id="review_foto" accept="image/*" class="form-control-file" style="font-size: 0.78rem;" onchange="handleReviewPhotoSelect(this)">
											<div id="review-foto-preview-wrap" class="mt-2 align-items-center" style="display: none; gap: 8px;">
												<img id="review-foto-preview" src="" alt="Preview" style="width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
												<button type="button" class="btn btn-xs btn-outline-danger" onclick="clearReviewPhoto()" style="font-size: 0.72rem; border-radius: 6px;">
													<i class="fa fa-times"></i> Hapus Foto
												</button>
											</div>
										</div>

										<div class="d-flex justify-content-end" style="gap: 8px;">
											<button type="button" class="btn btn-sm btn-light border" onclick="toggleReviewForm()" style="font-size: 0.78rem; border-radius: 8px;">Batal</button>
											<button type="submit" class="btn btn-sm btn-primary" id="btn-submit-review" style="font-size: 0.78rem; border-radius: 8px; background: #4f46e5; border-color: #4f46e5;">
												<i class="fa fa-paper-plane mr-1"></i> Kirim Ulasan
											</button>
										</div>
									</form>
								</div>

								<!-- Reviews List -->
								<div id="modal-reviews-list" style="max-height: 190px; overflow-y: auto; padding-right: 4px;">
									<div class="text-center text-muted small py-2">Memuat ulasan...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Checkout Pesanan -->
	<div class="modal fade" id="modalCheckout" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header bg-white px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
					<div class="d-flex align-items-center" style="gap: 12px;">
						<span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: #eef2ff; color: #717fe0;">
							<i class="zmdi zmdi-card" style="font-size: 20px;"></i>
						</span>
						<div>
							<h5 class="modal-title font-weight-bold text-dark mb-0" style="font-size: 1.08rem;">Checkout & Pembayaran</h5>
							<small class="text-muted" style="font-size: 12px;">Lengkapi data pengiriman dan pilih metode pembayaran</small>
						</div>
					</div>
					<button type="button" class="btn btn-sm btn-light rounded-circle d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close" style="width: 34px; height: 34px; border: 1px solid #e2e8f0;">
						<i class="zmdi zmdi-close text-dark" style="font-size: 18px;"></i>
					</button>
				</div>
				<form id="formCheckout" onsubmit="submitCheckoutOrder(event)">
					@csrf
					<div class="modal-body">
						<div class="row">
							<!-- Kolom Kiri: Form Data Pembeli -->
							<div class="col-md-7 pr-md-4 mb-3 mb-md-0">
								<div class="checkout-card-box">
									<h6 class="font-weight-bold text-dark mb-3 pb-2 border-bottom" style="font-size: 14px;">
										<i class="fa fa-user-circle text-primary mr-2"></i> Informasi Pembeli & Pengiriman
									</h6>

									@if(auth()->check() && (empty(auth()->user()->no_hp) || empty(auth()->user()->alamat)))
									<div class="alert alert-warning py-2 px-3 mb-3" style="font-size: 0.78rem; border-radius: 10px;">
										<i class="fa fa-info-circle mr-1"></i> Lengkapi <strong>Nomor WhatsApp & Alamat</strong> Anda — data ini akan otomatis tersimpan ke profil akun Anda untuk pesanan selanjutnya.
									</div>
									@endif

									<div class="form-group mb-3">
										<label class="small font-weight-bold text-dark mb-1">Nama Lengkap <span class="text-danger">*</span></label>
										<input type="text" name="nama_customer" id="checkout_nama" class="form-control checkout-input-modern" placeholder="Contoh: Budi Santoso" value="{{ auth()->check() ? auth()->user()->nama : '' }}" required>
									</div>

									<div class="form-group mb-3">
										<label class="small font-weight-bold text-dark mb-1">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
										<input type="tel" name="no_hp" id="checkout_no_hp" class="form-control checkout-input-modern" placeholder="Contoh: 081234567890" value="{{ auth()->check() ? auth()->user()->no_hp : '' }}" required inputmode="numeric" pattern="[0-9]{10,15}" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
										<small class="text-muted" style="font-size: 11px;">Hanya angka (10 - 15 digit) untuk konfirmasi pesanan & resi.</small>
									</div>

									<div class="form-group mb-3">
										<div class="d-flex justify-content-between align-items-center mb-1">
											<label class="small font-weight-bold text-dark mb-0">Kelurahan / Kecamatan / Kota / Kode Pos Tujuan <span class="text-danger">*</span></label>
											<span class="badge badge-light border text-primary" style="font-size: 10.5px;"><i class="fa fa-bolt text-warning mr-1"></i>Biteship Otomatis</span>
										</div>
										<div class="position-relative">
											<input type="text" id="ro-destination-input" class="form-control checkout-input-modern" placeholder="Ketik kelurahan, kecamatan, kota, atau kode pos (min. 3 huruf)..." autocomplete="off">
											<div id="ro-destination-dropdown" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1050; max-height: 210px; overflow-y: auto; display: none; border-radius: 10px;"></div>
										</div>
										<small class="text-muted d-block mt-1" style="font-size: 11px;">Tarif kurir dihitung hingga tingkat kelurahan/desa &amp; kode pos.</small>
										<div id="ro-selected-dest-badge" class="small text-success font-weight-bold mt-1" style="display: none; font-size: 11.5px;"></div>
									</div>

									<div class="form-group mb-3">
										<label class="small font-weight-bold text-dark mb-1">Detail Alamat (Jalan, No. Rumah, RT / RW) <span class="text-danger">*</span></label>
										<textarea name="alamat" id="checkout_alamat" rows="2" class="form-control checkout-input-modern" placeholder="Contoh: Jl. Melati No. 12, RT 03 / RW 05" required>{{ auth()->check() ? auth()->user()->alamat : '' }}</textarea>
									</div>

									<div class="form-group mb-3">
										<label class="small font-weight-bold text-dark mb-2 d-block">Metode Pembayaran <span class="text-danger">*</span></label>
										<div class="row" style="margin-left: -6px; margin-right: -6px;">
											<div class="col-6 px-1">
												<label class="checkout-pay-card">
													<input type="radio" name="metode_pembayaran" value="midtrans" checked>
													<span class="checkout-pay-check"><i class="fa fa-check"></i></span>
													<span class="font-weight-bold text-dark" style="font-size: 13px;">Midtrans (Online)</span>
													<small class="text-muted text-center" style="font-size: 10.5px;">VA Bank, QRIS, E-Wallet</small>
												</label>
											</div>
											<div class="col-6 px-1">
												<label class="checkout-pay-card">
													<input type="radio" name="metode_pembayaran" value="cod">
													<span class="checkout-pay-check"><i class="fa fa-check"></i></span>
													<span class="font-weight-bold text-dark" style="font-size: 13px;">COD (Bayar di Tempat)</span>
													<small class="text-muted text-center" style="font-size: 10.5px;">Tunai saat barang sampai</small>
												</label>
											</div>
										</div>
									</div>

									<div class="form-group mb-3">
										<label class="small font-weight-bold text-dark mb-2 d-block">Layanan Kurir Pengiriman <span class="text-danger">*</span></label>

										<!-- Daftar Layanan Kurir RajaOngkir Dinamis -->
										<div id="ro-courier-options-wrap" class="mb-2" style="display: none;">
											<div id="ro-courier-options-list" class="d-flex flex-column" style="gap: 6px; max-height: 175px; overflow-y: auto;"></div>
										</div>

										<input type="hidden" id="checkout_destination_id" value="">
										<input type="hidden" id="checkout_destination_postal_code" value="">
										<input type="hidden" id="checkout_destination_label" value="">
										<input type="hidden" id="checkout_courier_code" value="">
										<input type="hidden" id="checkout_courier_service" value="">
										<input type="hidden" id="checkout_ongkir_preview" value="0">

										<!-- Petunjuk sebelum tujuan dipilih -->
										<div id="ro-shipping-hint" class="d-flex align-items-center p-2 rounded" style="background: #f8fafc; border: 1px dashed #cbd5e1; font-size: 11.5px; color: #64748b;">
											<span>Pilih <strong>kelurahan / kecamatan tujuan</strong> di atas untuk melihat daftar tarif kurir.</span>
										</div>
									</div>

									<div class="form-group mb-0">
										<label class="small font-weight-bold text-dark mb-1">Catatan untuk Penjual (Opsional)</label>
										<input type="text" name="catatan" id="checkout_catatan" class="form-control checkout-input-modern" placeholder="Contoh: Packing bubble wrap ekstra">
									</div>
								</div>
							</div>

							<!-- Kolom Kanan: Ringkasan Belanja -->
							<div class="col-md-5">
								<div class="checkout-card-box mb-3">
									<h6 class="font-weight-bold text-dark mb-2 pb-2 border-bottom" style="font-size: 14px;">
										<i class="fa fa-shopping-bag text-primary mr-2"></i> Ringkasan Pesanan
									</h6>
									<div id="checkout-items-summary" style="max-height: 175px; overflow-y: auto;">
										<!-- Dynamic items via JS -->
									</div>
								</div>

								<!-- Input Voucher Promo -->
								<div class="checkout-card-box mb-3">
									<label class="small font-weight-bold text-dark mb-2 d-block">
										<i class="fa fa-ticket text-primary mr-1"></i> Punya Kode Voucher?
									</label>
									<div class="input-group input-group-sm">
										<input type="text" id="checkout_kode_voucher" class="form-control text-uppercase" placeholder="Kode promo" style="border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0; height: 36px;">
										<div class="input-group-append">
											<button type="button" id="btn-apply-voucher" onclick="applyVoucher()" class="btn btn-primary font-weight-bold px-3" style="background: linear-gradient(135deg, #717fe0 0%, #5865d6 100%); border: none; border-radius: 0 10px 10px 0;">
												Pakai
											</button>
										</div>
									</div>
									<div id="voucher-feedback" class="mt-2 small" style="display: none;"></div>

									<div id="checkout-available-vouchers-wrap" class="mt-2 pt-2 border-top" style="display: none;">
										<small class="text-muted d-block mb-2 font-weight-bold" style="font-size: 11.5px;">
											<i class="fa fa-tags text-primary mr-1"></i> Voucher Tersedia:
										</small>
										<div id="checkout-available-vouchers-list" class="d-flex flex-column" style="gap: 6px; max-height: 165px; overflow-y: auto;"></div>
									</div>
								</div>

								<!-- Rincian Total -->
								<div class="checkout-card-box">
									<div class="d-flex justify-content-between mb-1 small">
										<span class="text-muted">Total Produk</span>
										<strong id="checkout-summary-total-qty" class="text-dark">0 item</strong>
									</div>
									<div class="d-flex justify-content-between mb-1 small">
										<span class="text-muted">Subtotal Belanja</span>
										<strong id="checkout-summary-subtotal" class="text-dark">Rp 0</strong>
									</div>
									<div class="d-flex justify-content-between mb-1 small text-success" id="checkout-summary-discount-row" style="display: none !important;">
										<span>
											<i class="fa fa-ticket mr-1"></i> Diskon (<span id="checkout-applied-code"></span>)
											<a href="javascript:void(0);" onclick="removeVoucher()" class="text-danger ml-1" title="Hapus Voucher">[Hapus]</a>
										</span>
										<strong id="checkout-summary-discount">- Rp 0</strong>
									</div>
									<div class="d-flex justify-content-between mb-2 small">
										<span class="text-muted">Ongkos Kirim <span id="checkout-summary-kurir" class="d-block text-primary font-weight-bold" style="font-size: 10.5px;"></span></span>
										<strong id="checkout-summary-ongkir" class="text-muted">Pilih kurir</strong>
									</div>
									<hr class="my-2">
									<div class="d-flex justify-content-between align-items-center">
										<span class="font-weight-bold text-dark" style="font-size: 14px;">Total Bayar</span>
										<strong id="checkout-summary-total-price" style="font-size: 1.25rem; color: #e65540; font-weight: 700;">Rp 0</strong>
									</div>
									<div class="mt-2 pt-2 border-top text-muted text-center" style="font-size: 11px;">
										<i class="fa fa-lock text-success mr-1"></i> Midtrans: bayar maks. 24 jam, status pesanan diperbarui otomatis
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer bg-white px-4 py-3 d-flex justify-content-between border-top">
						<button type="button" class="btn btn-light border px-3 font-weight-bold text-muted" data-dismiss="modal" style="border-radius: 10px; font-size: 13px;">
							<i class="fa fa-times mr-1"></i> Batal
						</button>
						<button type="submit" id="btn-submit-checkout" class="btn-checkout-submit-modern">
							<i class="fa fa-check-circle mr-1"></i> Buat Pesanan Sekarang
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Modal Struk / Status Pembayaran Modern -->
	<div class="modal fade" id="modalOrderReceipt" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
			<div class="modal-content border-0" style="border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);">
				<div id="receipt-hero" class="text-center px-4 pt-4 pb-4" style="background: linear-gradient(135deg, #ecfdf5 0%, #eef2ff 100%);">
					<div id="receipt-icon-wrap" class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 68px; height: 68px; background: #10b981; color: #fff; box-shadow: 0 12px 28px rgba(16, 185, 129, 0.3);">
						<i id="receipt-icon" class="fa fa-check" style="font-size: 28px;"></i>
					</div>
					<div id="receipt-badge" class="mb-2">
						<span class="badge badge-success px-3 py-1" style="border-radius: 999px; font-size: 11.5px; letter-spacing: 0.02em;">Pembayaran Berhasil</span>
					</div>
					<h3 id="receipt-amount" class="font-weight-bold text-dark mb-1" style="font-size: 1.9rem; letter-spacing: -0.03em; font-variant-numeric: tabular-nums;">Rp 0</h3>
					<div id="receipt-subtitle" class="text-muted" style="font-size: 13px;">Pesanan Anda sedang disiapkan untuk dikirim</div>
				</div>

				<div class="modal-body px-4 py-4 bg-white">
					<!-- Status Auto-Sync Live (khusus pending Midtrans) -->
					<div id="receipt-pending-bar" class="p-3 mb-3 rounded" style="display: none; background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px !important;">
						<div class="d-flex align-items-center justify-content-between" style="gap: 8px;">
							<div class="d-flex align-items-center text-dark" style="font-size: 12px; gap: 8px;">
								<i class="fa fa-circle-o-notch fa-spin text-warning"></i>
								<span>Memantau pembayaran otomatis...</span>
							</div>
							<button type="button" id="receipt-recheck-btn" onclick="manualCheckReceiptStatus()" class="btn btn-xs btn-warning text-white font-weight-bold px-2 py-1" style="border-radius: 8px; font-size: 11px;">
								Cek Status
							</button>
						</div>
					</div>

					<div class="p-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px;">
						<div class="d-flex justify-content-between py-2 small border-bottom">
							<span class="text-muted">Nomor Pesanan</span>
							<strong id="receipt-kode" class="text-dark font-monospace">-</strong>
						</div>
						<div class="d-flex justify-content-between py-2 small border-bottom">
							<span class="text-muted">Metode Pembayaran</span>
							<strong id="receipt-metode" class="text-dark text-uppercase">-</strong>
						</div>
						<div class="d-flex justify-content-between py-2 small">
							<span class="text-muted">Kurir Pengiriman</span>
							<strong id="receipt-kurir" class="text-dark text-right">-</strong>
						</div>
					</div>

					<div class="d-flex flex-column" style="gap: 8px;">
						<button type="button" id="receipt-reopen-snap-btn" onclick="reopenReceiptSnap()" class="btn btn-warning text-white font-weight-bold py-2" style="display: none; border-radius: 12px;">
							Lanjutkan Pembayaran Midtrans
						</button>
						<a id="receipt-track-btn" href="#" class="btn btn-primary font-weight-bold py-2" style="border-radius: 12px; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border: none; box-shadow: 0 8px 18px rgba(79, 70, 229, 0.25);">
							Lacak Status Pesanan
						</a>
						<div class="row" style="margin-left: -4px; margin-right: -4px;">
							<div class="col-6 px-1">
								<a id="receipt-invoice-btn" href="#" target="_blank" class="btn btn-light border btn-block btn-sm py-2 font-weight-bold text-dark" style="border-radius: 10px;">
									Cetak Invoice
								</a>
							</div>
							<div class="col-6 px-1">
								<a id="receipt-wa-btn" href="#" target="_blank" class="btn btn-outline-success btn-block btn-sm py-2 font-weight-bold" style="border-radius: 10px;">
									WhatsApp Admin
								</a>
							</div>
						</div>
						<button type="button" class="btn btn-link btn-sm text-muted mt-1" data-dismiss="modal">Lanjut Belanja</button>
					</div>
				</div>
				<!-- Progress bar auto-close 3 detik -->
				<div id="receipt-progress-wrap" style="display: none; height: 5px; width: 100%; background: #e2e8f0; overflow: hidden;">
					<div id="receipt-progress-bar" style="height: 100%; width: 100%; background: linear-gradient(90deg, #10b981 0%, #4f46e5 100%);"></div>
				</div>
			</div>
		</div>
	</div>

