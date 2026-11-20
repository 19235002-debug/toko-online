@php
	$catSlug = \Illuminate\Support\Str::slug($item->kategori);
	$defaultImages = [
		'images/product-01.jpg',
		'images/product-02.jpg',
		'images/product-03.jpg',
		'images/product-04.jpg',
		'images/product-05.jpg',
		'images/product-06.jpg',
		'images/product-07.jpg',
		'images/product-08.jpg',
	];
	$imgSrc = $item->gambar ? asset('storage/' . $item->gambar) : asset('frontend-assets/' . $defaultImages[$index % count($defaultImages)]);
	$avgRat = $item->average_rating;
	$totRev = $item->total_reviews;
@endphp
<div class="{{ $colClass ?? 'col-6 col-sm-6 col-md-4 col-lg-3 p-b-30 isotope-item cat-' . $catSlug }}">
	<div class="product-card-modern">
		<div class="product-img-modern">
			<img src="{{ $imgSrc }}" alt="{{ $item->nama }}" class="js-show-product-modal"
			     data-id="{{ $item->id_barang }}"
			     data-nama="{{ $item->nama }}"
			     data-kategori="{{ $item->kategori }}"
			     data-supplier="{{ $item->supplier }}"
			     data-stok="{{ $item->stok_aktual }}"
			     data-rawprice="{{ $item->harga_final }}"
			     data-harga="Rp {{ number_format($item->harga_final, 0, ',', '.') }}"
			     data-gambar="{{ $imgSrc }}">

			@if($item->is_promo_active)
				<div style="position: absolute; top: 10px; left: 10px; z-index: 5; display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
					<span style="background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 99px; box-shadow: 0 3px 10px rgba(239,68,68,0.3);">
						<i class="fa fa-tag mr-1"></i>PROMO -{{ $item->diskon_persen_aktual }}%
					</span>
					@if($item->promo_selesai)
						<span class="js-promo-countdown" data-promo-end="{{ $item->promo_selesai->toIso8601String() }}" style="background: rgba(15, 23, 42, 0.85); color: #fff; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 99px;">
							<i class="fa fa-clock-o mr-1"></i>--:--:--
						</span>
					@endif
				</div>
			@endif

			@if($item->gambars->count() > 0)
				<span class="badge-gallery-float">
					<i class="fa fa-images"></i> +{{ $item->gambars->count() }} Foto
				</span>
			@endif

			<div class="btn-wishlist-float">
				<a href="javascript:void(0);" class="btn-addwish-b2 dis-block pos-relative js-toggle-wishlist"
				   data-id="{{ $item->id_barang }}"
				   data-nama="{{ $item->nama }}"
				   data-kategori="{{ $item->kategori }}"
				   data-rawprice="{{ $item->harga_final }}"
				   data-harga="Rp {{ number_format($item->harga_final, 0, ',', '.') }}"
				   data-gambar="{{ $imgSrc }}"
				   title="Tambah ke Wishlist">
					<img class="icon-heart1 dis-block trans-04" src="{{ asset('frontend-assets/images/icons/icon-heart-01.png') }}" alt="ICON">
					<img class="icon-heart2 dis-block trans-04 ab-t-l" src="{{ asset('frontend-assets/images/icons/icon-heart-02.png') }}" alt="ICON">
				</a>
			</div>

			<a href="javascript:void(0);"
			   class="btn-quickview-modern js-show-product-modal"
			   data-id="{{ $item->id_barang }}"
			   data-nama="{{ $item->nama }}"
			   data-kategori="{{ $item->kategori }}"
			   data-supplier="{{ $item->supplier }}"
			   data-stok="{{ $item->stok_aktual }}"
			   data-rawprice="{{ $item->harga_final }}"
			   data-harga="Rp {{ number_format($item->harga_final, 0, ',', '.') }}"
			   data-gambar="{{ $imgSrc }}">
				<i class="fa fa-eye"></i> Quick View
			</a>
		</div>

		<div class="product-body-modern">
			<div class="product-meta-row">
				<span class="badge-cat-modern product-item-category">{{ $item->kategori }}</span>
				@if($item->stok_aktual > 0)
					<span class="stock-indicator text-success">
						<span class="stock-dot" style="background: #10b981;"></span> Stok {{ $item->stok_aktual }}
					</span>
				@else
					<span class="stock-indicator text-danger">
						<span class="stock-dot" style="background: #ef4444;"></span> Habis
					</span>
				@endif
			</div>

			<a href="javascript:void(0);"
			   class="product-title-modern product-item-title js-show-product-modal"
			   data-id="{{ $item->id_barang }}"
			   data-nama="{{ $item->nama }}"
			   data-kategori="{{ $item->kategori }}"
			   data-supplier="{{ $item->supplier }}"
			   data-stok="{{ $item->stok_aktual }}"
			   data-rawprice="{{ $item->harga_final }}"
			   data-harga="Rp {{ number_format($item->harga_final, 0, ',', '.') }}"
			   data-gambar="{{ $imgSrc }}">
				{{ $item->nama }}
			</a>

			@if($item->variasis->count() > 0)
			<div class="d-flex align-items-center flex-wrap mb-2" style="gap: 5px;">
				@if($item->ukuran_list->count() > 0)
					<span class="chip-var">
						<i class="fa fa-ruler-horizontal text-muted"></i> {{ $item->ukuran_list->take(4)->implode('/') }}
					</span>
				@endif
				@if($item->warna_list->count() > 0)
					<span class="chip-var chip-var-color">
						<i class="fa fa-palette"></i> {{ $item->warna_list->count() }} Warna
					</span>
				@endif
			</div>
			@endif

			<div class="product-footer-modern">
				<div>
					<span class="product-price-modern product-item-price {{ $item->is_promo_active ? 'text-danger' : '' }}">
						Rp {{ number_format($item->harga_final, 0, ',', '.') }}
					</span>
					@if($item->is_promo_active)
						<div class="small text-muted" style="font-size: 0.72rem; line-height: 1.1;">
							<del>Rp {{ number_format($item->harga, 0, ',', '.') }}</del>
						</div>
					@endif
				</div>

				@if($totRev > 0)
					<span class="rating-pill-modern" title="Rating {{ $avgRat }} dari {{ $totRev }} ulasan">
						<i class="fa fa-star text-warning"></i> {{ $avgRat }} <span class="text-muted font-weight-normal">({{ $totRev }})</span>
					</span>
				@else
					<span class="rating-pill-new">Baru</span>
				@endif
			</div>
		</div>
	</div>
</div>

