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
										<label class="small font-weight-bold text-dark mb-1">Alamat Pengiriman Lengkap <span class="text-danger">*</span></label>
										<textarea name="alamat" id="checkout_alamat" rows="3" class="form-control checkout-input-modern" placeholder="Jalan, No Rumah, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos" required>{{ auth()->check() ? auth()->user()->alamat : '' }}</textarea>
									</div>

									<div class="form-group mb-3">
										<label class="small font-weight-bold text-dark mb-2 d-block">Metode Pembayaran <span class="text-danger">*</span></label>
										<div class="row" style="margin-left: -6px; margin-right: -6px;">
											<div class="col-6 px-1">
												<label class="checkout-pay-card">
													<input type="radio" name="metode_pembayaran" value="midtrans" checked>
													<span class="checkout-pay-check"><i class="fa fa-check"></i></span>
													<i class="fa fa-credit-card text-primary mb-1" style="font-size: 20px;"></i>
													<span class="font-weight-bold text-dark" style="font-size: 13px;">Midtrans (Online)</span>
													<small class="text-muted text-center" style="font-size: 10.5px;">VA Bank, QRIS, E-Wallet</small>
												</label>
											</div>
											<div class="col-6 px-1">
												<label class="checkout-pay-card">
													<input type="radio" name="metode_pembayaran" value="cod">
													<span class="checkout-pay-check"><i class="fa fa-check"></i></span>
													<i class="fa fa-motorcycle text-warning mb-1" style="font-size: 20px;"></i>
													<span class="font-weight-bold text-dark" style="font-size: 13px;">COD (Bayar di Tempat)</span>
													<small class="text-muted text-center" style="font-size: 10.5px;">Tunai saat barang sampai</small>
												</label>
											</div>
										</div>
									</div>

									@php
										$shipSetting = \App\Models\Setting::getSettings();
										$sReg = intval($shipSetting->ongkir_reguler ?? 15000);
										$sExp = intval($shipSetting->ongkir_express ?? 30000);
										$sMinFree = intval($shipSetting->min_gratis_ongkir ?? 300000);
									@endphp
									<div class="form-group mb-3">
										<label class="small font-weight-bold text-dark mb-2 d-block">Pilihan Kurir & Ongkos Kirim <span class="text-danger">*</span></label>
										<div class="row" style="margin-left: -6px; margin-right: -6px;">
											<div class="col-6 px-1">
												<label class="checkout-pay-card">
													<input type="radio" name="kurir" value="reguler" checked onchange="recalculateCheckoutTotals()">
													<span class="checkout-pay-check"><i class="fa fa-check"></i></span>
													<i class="fa fa-truck text-info mb-1" style="font-size: 18px;"></i>
													<span class="font-weight-bold text-dark" style="font-size: 13px;">Reguler (2-3 Hari)</span>
													<small class="text-muted text-center" id="kurir-reguler-price-label" style="font-size: 10.5px;">Rp {{ number_format($sReg, 0, ',', '.') }} (Gratis ≥ {{ number_format($sMinFree, 0, ',', '.') }})</small>
												</label>
											</div>
											<div class="col-6 px-1">
												<label class="checkout-pay-card">
													<input type="radio" name="kurir" value="express" onchange="recalculateCheckoutTotals()">
													<span class="checkout-pay-check"><i class="fa fa-check"></i></span>
													<i class="fa fa-bolt text-danger mb-1" style="font-size: 18px;"></i>
													<span class="font-weight-bold text-dark" style="font-size: 13px;">Express (1 Hari)</span>
													<small class="text-muted text-center" style="font-size: 10.5px;">Rp {{ number_format($sExp, 0, ',', '.') }}</small>
												</label>
											</div>
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

									@if(isset($vouchers) && $vouchers->count() > 0)
									<div class="mt-2 pt-2 border-top">
										<small class="text-muted d-block mb-1" style="font-size: 11px;">Voucher tersedia (klik untuk pakai):</small>
										<div class="d-flex flex-wrap" style="gap: 5px;">
											@foreach($vouchers as $v)
											<button type="button" onclick="applyVoucher('{{ $v->kode_voucher }}')" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0" style="font-size: 11px;">
												<i class="fa fa-tag mr-1"></i>{{ $v->kode_voucher }}
											</button>
											@endforeach
										</div>
									</div>
									@endif
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
										<span class="text-muted">Ongkos Kirim</span>
										<strong id="checkout-summary-ongkir" class="text-dark">Rp 15.000</strong>
									</div>
									<hr class="my-2">
									<div class="d-flex justify-content-between align-items-center">
										<span class="font-weight-bold text-dark" style="font-size: 14px;">Total Bayar</span>
										<strong id="checkout-summary-total-price" style="font-size: 1.25rem; color: #e65540; font-weight: 700;">Rp 0</strong>
									</div>
									<div class="mt-2 pt-2 border-top text-muted text-center" style="font-size: 11px;">
										<i class="fa fa-lock text-success mr-1"></i> Transaksi aman & terkonfirmasi otomatis via WhatsApp
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

