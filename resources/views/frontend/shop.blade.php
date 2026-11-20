@include('frontend.partials.storefront-head', ['pageTitle' => 'Katalog Produk | Coza Store'])
<body class="animsition">
	@include('sweetalert::alert')
	@include('frontend.partials.storefront-header', ['headerClass' => 'header-v4'])

	<!-- Compact Modern Shop Header -->
	<section class="border-bottom" style="background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%); padding: 26px 0;">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 16px;">
				<div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb bg-transparent p-0 mb-1" style="font-size: 12.5px;">
							<li class="breadcrumb-item"><a href="/" class="text-muted">Home</a></li>
							<li class="breadcrumb-item active font-weight-bold text-primary" aria-current="page">Shop</li>
						</ol>
					</nav>
					<h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.75rem; letter-spacing: -0.4px;">
						Katalog Produk
					</h2>
					<p class="text-muted mb-0" style="font-size: 13.5px;">
						Temukan koleksi fashion terbaik dengan filter kategori, harga, dan ketersediaan stok.
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Sleek UNIQLO-Style Compact Category Bar + Floating Mega-Menu -->
	@if(isset($mainKategoris) && $mainKategoris->count() > 0)
	<section class="bg-white border-bottom position-relative" id="uniqlo-cat-bar" style="z-index: 90;">
		<div class="container">
			<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px; min-height: 52px;">
				<div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
					<a href="{{ route('frontend.shop') }}"
					   class="btn btn-sm rounded-pill px-3 text-decoration-none {{ empty($kategori) || $kategori === 'all' ? 'font-weight-bold text-white' : 'text-dark bg-light border' }}"
					   style="font-size: 13px; {{ empty($kategori) || $kategori === 'all' ? 'background: #717fe0; border-color: #717fe0;' : '' }}">
						Semua
					</a>

					@foreach($mainKategoris as $mCat)
					@php $isTabActive = strtolower($kategori ?? '') === strtolower($mCat->nama_kategori); @endphp
					<button type="button"
					        onclick="toggleShopMegaMenu('{{ $mCat->id_kategori }}', this)"
					        class="btn btn-sm rounded-pill px-3 font-weight-bold js-uniqlo-tab {{ $isTabActive ? 'text-white' : 'text-dark bg-light border' }}"
					        style="font-size: 13px; outline: none !important; box-shadow: none !important; {{ $isTabActive ? 'background: #717fe0; border-color: #717fe0;' : '' }}">
						{{ $mCat->nama_kategori }} <i class="fa fa-angle-down ml-1 js-tab-chevron" style="font-size: 12px; transition: transform 0.2s;"></i>
					</button>
					@endforeach
				</div>
			</div>
		</div>

		<!-- Floating Mega-Menu Dropdown (Collapsed by default, opens on tab click) -->
		@foreach($mainKategoris as $mCat)
		<div class="js-uniqlo-subpanel d-none bg-white border-bottom shadow-lg position-absolute w-100"
		     id="uniqlo-subpanel-{{ $mCat->id_kategori }}"
		     style="left: 0; top: 100%; z-index: 1050; border-top: 1px solid #eef0f5;">
			<div class="container py-4">
				<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
					<div class="d-flex align-items-center" style="gap: 10px;">
						<strong class="text-dark" style="font-size: 15px;">Sub-Kategori {{ $mCat->nama_kategori }}</strong>
						<a href="{{ route('frontend.shop', ['kategori' => $mCat->nama_kategori]) }}" class="btn btn-xs btn-outline-primary rounded-pill px-3 py-1" style="font-size: 11.5px;">
							Lihat Semua {{ $mCat->nama_kategori }} <i class="fa fa-arrow-right ml-1"></i>
						</a>
					</div>
					<button type="button" onclick="closeAllShopMegaMenus()" class="btn btn-sm btn-light rounded-circle border" style="width: 30px; height: 30px; padding: 0;">
						<i class="fa fa-times text-muted"></i>
					</button>
				</div>

				<div class="row" style="margin-left: -6px; margin-right: -6px;">
					@foreach($mCat->children as $sub)
					@php
						$isSubActive = (strtolower($kategori ?? '') === strtolower($mCat->nama_kategori) && strtolower($subKategori ?? '') === strtolower($sub->nama_kategori));
						$subLower = strtolower($sub->nama_kategori);
						$iconClass = 'fa-shirt';
						if (str_contains($subLower, 'luaran') || str_contains($subLower, 'jaket')) $iconClass = 'fa-vest';
						elseif (str_contains($subLower, 'celana') || str_contains($subLower, 'jeans') || str_contains($subLower, 'kulot')) $iconClass = 'fa-socks';
						elseif (str_contains($subLower, 'gaun') || str_contains($subLower, 'rok')) $iconClass = 'fa-person-dress';
						elseif (str_contains($subLower, 'aksesoris') || str_contains($subLower, 'tas')) $iconClass = 'fa-glasses';
						elseif (str_contains($subLower, 'bayi') || str_contains($subLower, 'newborn') || str_contains($subLower, 'bodysuit')) $iconClass = 'fa-baby';
						elseif (str_contains($subLower, 'piyama') || str_contains($subLower, 'loungewear')) $iconClass = 'fa-bed';
						elseif (str_contains($subLower, 'dalaman')) $iconClass = 'fa-layer-group';
					@endphp
					<div class="col-4 col-sm-3 col-md-2 px-1 mb-2">
						<a href="{{ route('frontend.shop', ['kategori' => $mCat->nama_kategori, 'sub_kategori' => $sub->nama_kategori]) }}"
						   class="d-flex flex-column align-items-center text-center p-2 rounded text-decoration-none h-100 {{ $isSubActive ? 'border border-primary' : '' }}"
						   style="transition: all 0.2s ease; border-radius: 12px !important; {{ $isSubActive ? 'background: #eef2ff;' : '' }}"
						   onmouseover="this.style.backgroundColor='#f4f6ff'" onmouseout="this.style.backgroundColor='{{ $isSubActive ? '#eef2ff' : 'transparent' }}'">
							<div class="mb-2 d-flex align-items-center justify-content-center"
							     style="width: 54px; height: 54px; border-radius: 12px; background: #f8fafc; overflow: hidden; border: 1px solid #e2e8f0;">
								@if($sub->gambar)
									<img src="{{ asset('storage/' . $sub->gambar) }}" alt="{{ $sub->nama_kategori }}" style="width: 100%; height: 100%; object-fit: cover;">
								@else
									<i class="fa-solid {{ $iconClass }} text-primary" style="font-size: 20px; opacity: 0.85;"></i>
								@endif
							</div>
							<span class="{{ $isSubActive ? 'font-weight-bold text-primary' : 'text-dark' }}" style="font-size: 11.5px; line-height: 1.3;">
								{{ $sub->nama_kategori }}
							</span>
						</a>
					</div>
					@endforeach
				</div>
			</div>
		</div>
		@endforeach
	</section>
	<script>
		var currentOpenShopPanelId = null;

		function toggleShopMegaMenu(catId, btnEl) {
			var target = document.getElementById('uniqlo-subpanel-' + catId);
			var isAlreadyOpen = (currentOpenShopPanelId === catId && target && !target.classList.contains('d-none'));

			closeAllShopMegaMenus();

			if (!isAlreadyOpen && target) {
				target.classList.remove('d-none');
				currentOpenShopPanelId = catId;
				var chevron = btnEl.querySelector('.js-tab-chevron');
				if (chevron) chevron.style.transform = 'rotate(180deg)';
			}
		}

		function closeAllShopMegaMenus() {
			document.querySelectorAll('.js-uniqlo-subpanel').forEach(function(panel) {
				panel.classList.add('d-none');
			});
			document.querySelectorAll('.js-tab-chevron').forEach(function(icon) {
				icon.style.transform = 'rotate(0deg)';
			});
			currentOpenShopPanelId = null;
		}

		document.addEventListener('click', function(e) {
			var bar = document.getElementById('uniqlo-cat-bar');
			if (bar && !bar.contains(e.target)) {
				closeAllShopMegaMenus();
			}
		});
	</script>
	@endif

	<!-- Modern 2-Column Shop Layout -->
	<section class="py-5" id="section-produk" style="background-color: #f7f9fc;">
		<div class="container">
			<div class="row">
				<!-- Left Sidebar Filter -->
				<div class="col-lg-3 mb-4">
					<div class="bg-white border p-4 shadow-sm" style="border-radius: 16px; border-color: #edf0f5 !important; position: sticky; top: 96px;">
						<div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
							<h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">
								<i class="fa fa-sliders-h text-primary mr-2"></i> Filter Produk
							</h6>
							@if(!empty($search) || (!empty($kategori) && $kategori !== 'all') || (!empty($subKategori) && $subKategori !== 'all') || ($minHarga !== null && $minHarga !== '') || ($maxHarga !== null && $maxHarga !== '') || $readyStock == '1' || $sort !== 'terbaru')
							<a href="{{ route('frontend.shop') }}" class="small text-danger font-weight-bold" style="font-size: 12px;">Reset</a>
							@endif
						</div>

						<form action="{{ route('frontend.shop') }}" method="GET" id="shopFilterForm">
							<input type="hidden" name="sort" value="{{ $sort }}">
							@if(!empty($subKategori) && $subKategori !== 'all')
								<input type="hidden" name="sub_kategori" value="{{ $subKategori }}">
							@endif

							<!-- Search -->
							<div class="form-group mb-4">
								<label class="small font-weight-bold text-dark mb-2 d-block">Cari Produk</label>
								<div class="input-group input-group-sm">
									<input type="text" name="search" class="form-control" placeholder="Nama baju, kategori..." value="{{ $search }}" style="border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0; height: 38px;">
									<div class="input-group-append">
										<button type="submit" class="btn btn-primary px-3" style="background: #717fe0; border-color: #717fe0; border-radius: 0 10px 10px 0;">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</div>
							</div>

							<!-- Categories & Sub-Categories Tree -->
							<div class="form-group mb-4">
								<label class="small font-weight-bold text-dark mb-2 d-block">Kategori & Sub-Kategori</label>
								<div class="list-group list-group-flush">
									<a href="{{ route('frontend.shop', array_filter(['search' => $search, 'sort' => $sort, 'min_harga' => $minHarga, 'max_harga' => $maxHarga, 'ready_stock' => $readyStock])) }}"
									   class="d-flex justify-content-between align-items-center py-2 px-2 rounded mb-1 text-decoration-none {{ empty($kategori) || $kategori === 'all' ? 'font-weight-bold text-primary' : 'text-dark' }}"
									   style="font-size: 13.5px; {{ empty($kategori) || $kategori === 'all' ? 'background: #eef2ff;' : '' }}">
										<span>Semua Produk</span>
										<span class="badge badge-light border text-muted">{{ $allProductsCount ?? 0 }}</span>
									</a>
									@foreach($mainKategoris as $mCat)
									@php $isMainActive = (strtolower($kategori ?? '') === strtolower($mCat->nama_kategori)); @endphp
									<a href="{{ route('frontend.shop', array_filter(['kategori' => $mCat->nama_kategori, 'search' => $search, 'sort' => $sort, 'min_harga' => $minHarga, 'max_harga' => $maxHarga, 'ready_stock' => $readyStock])) }}"
									   class="d-flex justify-content-between align-items-center py-2 px-2 rounded mb-1 text-decoration-none {{ $isMainActive ? 'font-weight-bold text-primary' : 'text-dark' }}"
									   style="font-size: 13.5px; {{ $isMainActive ? 'background: #eef2ff;' : '' }}">
										<span>{{ $mCat->nama_kategori }}</span>
										<span class="badge badge-light border text-muted">{{ $categoryCounts[$mCat->nama_kategori] ?? 0 }}</span>
									</a>
										@if($isMainActive && $mCat->children->isNotEmpty())
										<div class="pl-3 mb-2 border-left ml-2" style="border-color: #d6ddff !important;">
											@foreach($mCat->children as $sub)
											<a href="{{ route('frontend.shop', array_filter(['kategori' => $mCat->nama_kategori, 'sub_kategori' => $sub->nama_kategori, 'search' => $search, 'sort' => $sort, 'min_harga' => $minHarga, 'max_harga' => $maxHarga, 'ready_stock' => $readyStock])) }}"
											   class="d-block py-1 px-2 rounded text-decoration-none {{ strtolower($subKategori ?? '') === strtolower($sub->nama_kategori) ? 'font-weight-bold text-primary' : 'text-muted' }}"
											   style="font-size: 12.5px;">
												{{ $sub->nama_kategori }}
											</a>
											@endforeach
										</div>
										@endif
									@endforeach
								</div>
								@if($kategori && $kategori !== 'all')
									<input type="hidden" name="kategori" value="{{ $kategori }}">
								@endif
							</div>

							<!-- Price Range -->
							<div class="form-group mb-4">
								<label class="small font-weight-bold text-dark mb-2 d-block">Rentang Harga (Rp)</label>
								<div class="d-flex align-items-center" style="gap: 8px;">
									<input type="number" name="min_harga" class="form-control form-control-sm" placeholder="Min" value="{{ $minHarga }}" min="0" style="border-radius: 8px; height: 36px;">
									<span class="text-muted">-</span>
									<input type="number" name="max_harga" class="form-control form-control-sm" placeholder="Maks" value="{{ $maxHarga }}" min="0" style="border-radius: 8px; height: 36px;">
								</div>
							</div>

							<!-- Stock Availability -->
							<div class="form-group mb-2">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="readyStockSwitch" name="ready_stock" value="1" {{ $readyStock == '1' ? 'checked' : '' }} onchange="this.form.submit()">
									<label class="custom-control-label small font-weight-bold text-dark" for="readyStockSwitch">Hanya Stok Tersedia</label>
								</div>
							</div>

							<!-- Active Promo Filter -->
							<div class="form-group mb-4">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="onlyPromoSwitch" name="promo" value="1" {{ ($onlyPromo ?? '') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
									<label class="custom-control-label small font-weight-bold text-danger" for="onlyPromoSwitch">
										<i class="fa fa-tag mr-1"></i> Sedang Promo
									</label>
								</div>
							</div>

							<button type="submit" class="btn btn-primary btn-block font-weight-bold py-2" style="background: linear-gradient(135deg, #717fe0 0%, #5865d6 100%); border: none; border-radius: 10px; font-size: 13.5px;">
								<i class="fa fa-filter mr-1"></i> Terapkan Filter
							</button>
						</form>
					</div>
				</div>

				<!-- Right Product Grid -->
				<div class="col-lg-9">
					<!-- Top Sort & Count Bar -->
					<div class="bg-white border px-3 py-2 mb-4 shadow-sm d-flex flex-wrap justify-content-between align-items-center" style="border-radius: 14px; border-color: #edf0f5 !important; gap: 10px;">
						<div class="small text-muted">
							Menampilkan <strong class="text-dark">{{ $barang->firstItem() ?? 0 }} - {{ $barang->lastItem() ?? 0 }}</strong> dari <strong class="text-dark">{{ $barang->total() }}</strong> produk
							@if($kategori && $kategori !== 'all')
								<span class="badge badge-primary ml-1" style="background: #717fe0;">{{ $kategori }}</span>
							@endif
							@if(!empty($subKategori) && $subKategori !== 'all')
								<span class="badge badge-info ml-1">{{ $subKategori }}</span>
							@endif
							@if($search !== '')
								<span class="badge badge-light border ml-1">"{{ $search }}"</span>
							@endif
							@if(($onlyPromo ?? '') == '1')
								<span class="badge badge-danger ml-1"><i class="fa fa-tag mr-1"></i>Sedang Promo</span>
							@endif
						</div>

						<form action="{{ route('frontend.shop') }}" method="GET" class="d-flex align-items-center" style="gap: 8px;">
							@if($kategori && $kategori !== 'all') <input type="hidden" name="kategori" value="{{ $kategori }}"> @endif
							@if(!empty($subKategori) && $subKategori !== 'all') <input type="hidden" name="sub_kategori" value="{{ $subKategori }}"> @endif
							@if($search !== '') <input type="hidden" name="search" value="{{ $search }}"> @endif
							@if($minHarga !== null && $minHarga !== '') <input type="hidden" name="min_harga" value="{{ $minHarga }}"> @endif
							@if($maxHarga !== null && $maxHarga !== '') <input type="hidden" name="max_harga" value="{{ $maxHarga }}"> @endif
							@if($readyStock == '1') <input type="hidden" name="ready_stock" value="1"> @endif
							@if(($onlyPromo ?? '') == '1') <input type="hidden" name="promo" value="1"> @endif

							<label class="small text-muted mb-0 d-none d-sm-inline">Urutkan:</label>
							<select name="sort" class="form-control form-control-sm" onchange="this.form.submit()" style="width: auto; border-radius: 10px; border: 1.5px solid #e2e8f0; height: 34px; font-size: 13px; font-weight: 500;">
								<option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
								<option value="termurah" {{ $sort === 'termurah' ? 'selected' : '' }}>Harga: Termurah</option>
								<option value="termahal" {{ $sort === 'termahal' ? 'selected' : '' }}>Harga: Termahal</option>
								<option value="nama" {{ $sort === 'nama' ? 'selected' : '' }}>Nama: A - Z</option>
							</select>
						</form>
					</div>


			<div class="row" id="product-grid">
				@forelse($barang as $index => $item)
					@include('frontend.partials.product-card', ['item' => $item, 'index' => $index, 'colClass' => 'col-6 col-md-4 mb-4'])
				@empty
					<div class="col-12">
						<div class="bg-white border rounded-lg p-5 text-center shadow-sm" style="border-radius: 16px;">
							<i class="zmdi zmdi-search-for" style="font-size: 56px; color: #cbd5e1;"></i>
							<h5 class="font-weight-bold text-dark mt-3">Produk tidak ditemukan</h5>
							<p class="text-muted small mb-4">Coba ubah kata kunci pencarian, rentang harga, atau pilih kategori lain.</p>
							<a href="{{ route('frontend.shop') }}" class="btn btn-primary rounded-pill px-4" style="background: #717fe0; border-color: #717fe0;">
								<i class="fa fa-refresh mr-1"></i> Tampilkan Semua Produk
							</a>
						</div>
					</div>
				@endforelse
			</div>

			<!-- Pagination -->
			@if($barang->hasPages())
			<div class="d-flex justify-content-center mt-4">
				{{ $barang->links('vendor.pagination.modern') }}
			</div>
			@endif
				</div>
			</div>
		</div>
	</section>



@include('frontend.partials.storefront-footer-scripts')
