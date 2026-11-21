@include('frontend.partials.storefront-head', ['pageTitle' => 'Coza Store | Kelompok 3'])
<body>
	@include('sweetalert::alert')
	@include('frontend.partials.storefront-header')

	<!-- Slider -->
	<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">
				@php
					$animations = [
						['down' => 'fadeInDown', 'up' => 'fadeInUp', 'btn' => 'zoomIn'],
						['down' => 'rollIn', 'up' => 'lightSpeedIn', 'btn' => 'slideInUp'],
						['down' => 'rotateInDownLeft', 'up' => 'rotateInUpRight', 'btn' => 'rotateIn'],
					];
				@endphp

				@forelse($banners as $index => $banner)
					@php
						$anim = $animations[$index % count($animations)];
						$imgUrl = str_starts_with($banner->gambar, 'banners/')
							? asset('storage/' . $banner->gambar)
							: (file_exists(public_path('storage/' . $banner->gambar)) ? asset('storage/' . $banner->gambar) : asset('frontend-assets/' . $banner->gambar));
						$linkUrl = $banner->link ?: '/frontend#product-grid';
					@endphp
					<div class="item-slick1" data-link="{{ $linkUrl }}" style="background-image: url({{ $imgUrl }}); cursor: pointer;">
						<div class="container h-full">
							<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
								@if($banner->subjudul)
								<div class="layer-slick1 animated visible-false" data-appear="{{ $anim['down'] }}" data-delay="0">
									<span class="ltext-101 cl2 respon2 font-weight-bold" style="text-shadow: 0 1px 3px rgba(255,255,255,0.7);">
										{{ $banner->subjudul }}
									</span>
								</div>
								@endif
									
								<div class="layer-slick1 animated visible-false" data-appear="{{ $anim['up'] }}" data-delay="800">
									<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1 font-weight-bold" style="text-shadow: 0 1px 4px rgba(255,255,255,0.7);">
										{{ $banner->judul }}
									</h2>
								</div>
									
								<div class="layer-slick1 animated visible-false" data-appear="{{ $anim['btn'] }}" data-delay="1600">
									<a href="{{ $linkUrl }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-banner-btn">
										Shop Now
									</a>
								</div>
							</div>
						</div>
					</div>
				@empty
					<div class="item-slick1" style="background-image: url({{ asset('frontend-assets/images/slide-01.jpg') }});">
						<div class="container h-full">
							<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
								<div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
									<span class="ltext-101 cl2 respon2">Women Collection</span>
								</div>
								<div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
									<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">NEW SEASON</h2>
								</div>
								<div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
									<a href="/frontend#product-grid" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">Shop Now</a>
								</div>
							</div>
						</div>
					</div>
				@endforelse
			</div>
		</div>
	</section>


	<!-- Kategori Pilihan Modern -->
	@php
		$categoryCounts = \App\Models\Barang::selectRaw('kategori, COUNT(*) as total')
			->groupBy('kategori')
			->pluck('total', 'kategori');

		$featuredCategories = [
			[
				'name' => 'Wanita',
				'subtitle' => 'Koleksi Terbaru & Trendi',
				'slug' => 'Wanita',
				'img' => asset('frontend-assets/images/banner-01.jpg'),
				'bg' => 'linear-gradient(135deg, #fdf2f8 0%, #eef2ff 100%)',
				'accent' => '#db2777',
			],
			[
				'name' => 'Pria',
				'subtitle' => 'Gaya Kasual & Formal',
				'slug' => 'Pria',
				'img' => asset('frontend-assets/images/banner-02.jpg'),
				'bg' => 'linear-gradient(135deg, #eff6ff 0%, #f1f5f9 100%)',
				'accent' => '#2563eb',
			],
			[
				'name' => 'Anak',
				'subtitle' => 'Nyaman Dipakai Seharian',
				'slug' => 'Anak',
				'img' => asset('storage/barang_images/dummy-anak-atasan.jpg'),
				'bg' => 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)',
				'accent' => '#d97706',
			],
			[
				'name' => 'Bayi',
				'subtitle' => 'Katun Lembut & Aman',
				'slug' => 'Bayi',
				'img' => asset('storage/barang_images/dummy-bayi-setelan.jpg'),
				'bg' => 'linear-gradient(135deg, #ecfdf5 0%, #e0f2fe 100%)',
				'accent' => '#059669',
			],
		];
	@endphp
	<style>
		.modern-cat-card {
			position: relative;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			height: 240px;
			border-radius: 20px;
			border: 1px solid #e2e8f0;
			overflow: hidden;
			text-decoration: none !important;
			box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05);
			transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
		}
		.modern-cat-card:hover {
			transform: translateY(-6px);
			box-shadow: 0 18px 36px rgba(79, 70, 229, 0.14);
			border-color: #c7d2fe;
		}
		.modern-cat-card img {
			position: absolute;
			right: 0;
			bottom: 0;
			width: 62%;
			height: 100%;
			object-fit: cover;
			object-position: top center;
			transition: transform 0.35s ease;
			-webkit-mask-image: linear-gradient(to right, transparent 0%, black 28%);
			mask-image: linear-gradient(to right, transparent 0%, black 28%);
		}
		.modern-cat-card:hover img {
			transform: scale(1.06);
		}
		.modern-cat-content {
			position: relative;
			z-index: 2;
			padding: 24px 22px;
			max-width: 68%;
			height: 100%;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}
		.modern-cat-cta {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			font-size: 12.5px;
			font-weight: 700;
			color: #0f172a;
			background: rgba(255, 255, 255, 0.9);
			backdrop-filter: blur(6px);
			padding: 7px 14px;
			border-radius: 999px;
			border: 1px solid rgba(226, 232, 240, 0.9);
			width: fit-content;
			transition: background 0.2s ease, color 0.2s ease;
		}
		.modern-cat-card:hover .modern-cat-cta {
			background: #4f46e5;
			color: #ffffff;
			border-color: #4f46e5;
		}
	</style>
	<div class="sec-banner bg0 p-t-65 p-b-45">
		<div class="container">
			<div class="d-flex flex-wrap justify-content-between align-items-end mb-4" style="gap: 10px;">
				<div>
					<span class="text-uppercase font-weight-bold" style="font-size: 11.5px; letter-spacing: 0.08em; color: #4f46e5;">Kategori Pilihan</span>
					<h3 class="font-weight-bold text-dark mb-0" style="font-size: 1.65rem; letter-spacing: -0.02em;">Jelajahi Koleksi Kami</h3>
				</div>
				<a href="{{ route('frontend.shop') }}" class="font-weight-bold" style="font-size: 13.5px; color: #4f46e5;">
					Lihat Semua Kategori <i class="fa fa-arrow-right ml-1"></i>
				</a>
			</div>
			<div class="row">
				@foreach($featuredCategories as $cat)
				<div class="col-sm-6 col-lg-3 p-b-24">
					<a href="{{ route('frontend.shop', ['kategori' => $cat['slug']]) }}" class="modern-cat-card" style="background: {{ $cat['bg'] }};">
						<img src="{{ $cat['img'] }}" alt="{{ $cat['name'] }}">
						<div class="modern-cat-content">
							<div>
								<span class="badge bg-white border mb-2 px-2 py-1" style="border-radius: 999px; font-size: 10.5px; font-weight: 700; color: {{ $cat['accent'] }};">
									{{ intval($categoryCounts[$cat['slug']] ?? 0) }} Produk
								</span>
								<h4 class="font-weight-bold text-dark mb-1" style="font-size: 1.35rem; letter-spacing: -0.02em;">{{ $cat['name'] }}</h4>
								<p class="text-muted mb-0" style="font-size: 12.5px; line-height: 1.4;">{{ $cat['subtitle'] }}</p>
							</div>
							<span class="modern-cat-cta">
								Belanja <i class="fa fa-angle-right"></i>
							</span>
						</div>
					</a>
				</div>
				@endforeach
			</div>
		</div>
	</div>

	@if(isset($promoProducts) && $promoProducts->count() > 0)
	@php
		$nearestPromoEnd = $promoProducts->pluck('promo_selesai')->filter()->sort()->first();
	@endphp
	<!-- Promo Spesial Showcase -->
	<section class="py-4" style="background: linear-gradient(135deg, #fff1f2 0%, #fef2f2 55%, #fff7ed 100%); border-top: 1px solid #ffe4e6; border-bottom: 1px solid #ffe4e6;">
		<div class="container">
			<div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 10px;">
				<div>
					<span class="badge badge-danger px-2 py-1 mb-1" style="font-size: 11px; border-radius: 99px;"><i class="fa fa-tag mr-1"></i> PROMO TERBATAS</span>
					<h4 class="font-weight-bold text-dark mb-0" style="font-size: 1.4rem;">Promo Spesial Hari Ini</h4>
				</div>
				<div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
					@if($nearestPromoEnd)
					<div class="d-inline-flex align-items-center px-3 py-1 text-white" style="background: #0f172a; border-radius: 99px; font-size: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);">
						<span class="text-white-50 mr-2">Berakhir dalam</span>
						<span class="js-promo-countdown font-weight-bold text-warning" data-promo-end="{{ $nearestPromoEnd->toIso8601String() }}" style="font-variant-numeric: tabular-nums;">--:--:--</span>
					</div>
					@endif
					<a href="{{ route('frontend.shop', ['promo' => 1]) }}" class="btn btn-sm btn-outline-danger font-weight-bold px-3" style="border-radius: 99px;">
						Lihat Semua Promo <i class="fa fa-arrow-right ml-1"></i>
					</a>
				</div>
			</div>

			<div class="row">
				@foreach($promoProducts->take(4) as $pItem)
					@php
						$pImg = $pItem->gambar ? asset('storage/' . $pItem->gambar) : asset('frontend-assets/images/product-01.jpg');
					@endphp
					<div class="col-6 col-md-4 col-lg-3 mb-3">
						<div class="product-card-modern h-100">
							<div class="product-img-modern">
								<img src="{{ $pImg }}" alt="{{ $pItem->nama }}" class="js-show-product-modal"
								     data-id="{{ $pItem->id_barang }}"
								     data-nama="{{ $pItem->nama }}"
								     data-kategori="{{ $pItem->kategori }}"
								     data-supplier="{{ $pItem->supplier }}"
								     data-stok="{{ $pItem->stok_aktual }}"
								     data-rawprice="{{ $pItem->harga_final }}"
								     data-harga="Rp {{ number_format($pItem->harga_final, 0, ',', '.') }}"
								     data-gambar="{{ $pImg }}">

								<span style="position: absolute; top: 10px; left: 10px; z-index: 5; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 99px; box-shadow: 0 3px 10px rgba(239,68,68,0.3);">
									<i class="fa fa-tag mr-1"></i>PROMO -{{ $pItem->diskon_persen_aktual }}%
								</span>

								<a href="javascript:void(0);"
								   class="btn-quickview-modern js-show-product-modal"
								   data-id="{{ $pItem->id_barang }}"
								   data-nama="{{ $pItem->nama }}"
								   data-kategori="{{ $pItem->kategori }}"
								   data-supplier="{{ $pItem->supplier }}"
								   data-stok="{{ $pItem->stok_aktual }}"
								   data-rawprice="{{ $pItem->harga_final }}"
								   data-harga="Rp {{ number_format($pItem->harga_final, 0, ',', '.') }}"
								   data-gambar="{{ $pImg }}">
									<i class="fa fa-eye"></i> Quick View
								</a>
							</div>
							<div class="product-body-modern">
								<div class="product-meta-row">
									<span class="badge-cat-modern">{{ $pItem->kategori }}</span>
									@if($pItem->promo_selesai)
										<span class="text-danger" style="font-size: 11px; font-weight: 600;">
											<i class="fa fa-clock"></i> s/d {{ $pItem->promo_selesai->format('d M') }}
										</span>
									@endif
								</div>
								<a href="javascript:void(0);"
								   class="product-title-modern js-show-product-modal"
								   data-id="{{ $pItem->id_barang }}"
								   data-nama="{{ $pItem->nama }}"
								   data-kategori="{{ $pItem->kategori }}"
								   data-supplier="{{ $pItem->supplier }}"
								   data-stok="{{ $pItem->stok_aktual }}"
								   data-rawprice="{{ $pItem->harga_final }}"
								   data-harga="Rp {{ number_format($pItem->harga_final, 0, ',', '.') }}"
								   data-gambar="{{ $pImg }}">
									{{ $pItem->nama }}
								</a>
								<div class="product-footer-modern">
									<div>
										<span class="product-price-modern text-danger">Rp {{ number_format($pItem->harga_final, 0, ',', '.') }}</span>
										<div class="small text-muted" style="font-size: 0.72rem; line-height: 1.1;">
											<del>Rp {{ number_format($pItem->harga, 0, ',', '.') }}</del>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
	@endif

	<!-- Product -->
	<section class="bg0 p-t-23 p-b-140" id="section-produk" style="scroll-margin-top: 85px;">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl5 font-weight-bold">
					Katalog Produk
				</h3>
			</div>

			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
						Semua Produk
					</button>

					@foreach($categories as $cat)
						@php $catSlug = \Illuminate\Support\Str::slug($cat); @endphp
						<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".cat-{{ $catSlug }}">
							{{ $cat }}
						</button>
					@endforeach
				</div>

				<div class="flex-w flex-c-m m-tb-10">
					<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
						<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
						<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						 Filter
					</div>

					<div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
						<i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
						<i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						Search
					</div>
				</div>
				
				<!-- Search product -->
				<div class="dis-none panel-search w-full p-t-10 p-b-15">
					<div class="bor8 dis-flex p-l-15">
						<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
							<i class="zmdi zmdi-search"></i>
						</button>

						<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" placeholder="Search">
					</div>	
				</div>

				<!-- Filter -->
				<div class="dis-none panel-filter w-full p-t-10">
					<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
						<div class="filter-col1 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Sort By
							</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Default
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Popularity
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Average rating
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Newness
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Price: Low to High
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Price: High to Low
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col2 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Price
							</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										All
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$0.00 - $50.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$50.00 - $100.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$100.00 - $150.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$150.00 - $200.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$200.00+
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col3 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Color
							</div>

							<ul>
								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #222;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Black
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #4272d7;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Blue
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #b3b3b3;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Grey
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #00ad5f;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Green
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #fa4251;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Red
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #aaa;">
										<i class="zmdi zmdi-circle-o"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										White
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col4 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Tags
							</div>

							<div class="flex-w p-t-4 m-r--5">
								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Fashion
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Lifestyle
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Denim
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Streetstyle
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Crafts
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row isotope-grid" id="product-grid">
				@forelse($barang as $index => $item)
					@include('frontend.partials.product-card', ['item' => $item, 'index' => $index])
				@empty
					<div class="col-12 text-center p-t-50 p-b-50">
						<i class="zmdi zmdi-info-outline" style="font-size: 48px; color: #aaa;"></i>
						<h4 class="stext-104 cl4 m-t-15 font-weight-bold">Belum ada produk di database</h4>
						<p class="stext-105 cl6 m-t-5">Silakan masuk ke panel admin untuk menambahkan produk baru.</p>
						@auth
							<a href="/barang/create" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 m-t-15 d-inline-flex">Tambah Produk Sekarang</a>
						@else
							<a href="/login" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 m-t-15 d-inline-flex">Login Admin</a>
						@endauth
					</div>
				@endforelse
			</div>

			<!-- Lihat Semua Produk -->
			<div class="flex-c-m flex-w w-full p-t-45">
				<a href="{{ route('frontend.shop') }}" class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn1 p-lr-25 trans-04" style="background: #717fe0; border-color: #717fe0; text-decoration: none; gap: 8px;">
					<span>Lihat Semua Produk ({{ $totalProduk ?? count($barang) }})</span>
					<i class="fa fa-arrow-right"></i>
				</a>
			</div>
		</div>
	</section>



@include('frontend.partials.storefront-footer-scripts', ['enableHomeInteractions' => true])
