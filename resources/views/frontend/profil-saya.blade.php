<!DOCTYPE html>
<html lang="id">
<head>
	<title>Profil Saya | Coza Store</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/png" href="{{ asset('frontend-assets/images/icons/favicon.png') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/iconic/css/material-design-iconic-font.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/linearicons-v1.0.0/icon-font.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/main.css') }}">
	<style>
		body {
			background-color: #f8fafc;
		}

		/* Modern Hero Header */
		.profile-hero-modern {
			background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 55%, #f5f3ff 100%);
			border-bottom: 1px solid #e2e8f0;
			padding: 28px 0;
			margin-bottom: 28px;
		}
		.profile-breadcrumb {
			font-size: 13px;
			color: #64748b;
			margin-bottom: 6px;
		}
		.profile-breadcrumb a {
			color: #64748b;
			text-decoration: none;
		}
		.profile-breadcrumb a:hover {
			color: #4f46e5;
		}

		/* Sidebar Card */
		.account-sidebar-card {
			background: #ffffff;
			border-radius: 18px;
			border: 1px solid #e2e8f0;
			box-shadow: 0 8px 24px -10px rgba(15, 23, 42, 0.06);
			padding: 24px 18px;
		}
		.account-avatar-fallback {
			width: 76px;
			height: 76px;
			border-radius: 50%;
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff;
			font-size: 26px;
			font-weight: 800;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 8px 18px -4px rgba(79, 70, 229, 0.4);
		}
		.account-nav-link {
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 10px 14px;
			border-radius: 12px;
			font-size: 13.5px;
			font-weight: 600;
			color: #475569;
			text-decoration: none !important;
			transition: all 0.2s ease;
			margin-bottom: 4px;
		}
		.account-nav-link:hover {
			background: #f1f5f9;
			color: #0f172a;
		}
		.account-nav-link.active {
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff;
			box-shadow: 0 6px 14px -4px rgba(79, 70, 229, 0.4);
		}

		/* Modern Profile Form Card */
		.profile-card-modern {
			background: #ffffff;
			border-radius: 18px;
			border: 1px solid #e2e8f0;
			box-shadow: 0 8px 24px -12px rgba(15, 23, 42, 0.08);
			padding: 28px;
		}
		.profile-avatar-box {
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			border-radius: 16px;
			padding: 24px 16px;
		}
		.profile-avatar-placeholder {
			width: 116px;
			height: 116px;
			border-radius: 50%;
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff;
			font-size: 38px;
			font-weight: 800;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 10px 22px -6px rgba(79, 70, 229, 0.45);
		}
		.form-label-modern {
			font-weight: 700;
			font-size: 13px;
			color: #334155;
			margin-bottom: 6px;
			display: block;
		}
		.input-modern {
			border-radius: 10px !important;
			font-size: 14px !important;
			padding: 10px 14px !important;
			min-height: 44px;
			border: 1.5px solid #e2e8f0 !important;
			background: #f8fafc !important;
			color: #0f172a !important;
			transition: all 0.2s ease;
		}
		.input-modern:focus {
			background: #ffffff !important;
			border-color: #4f46e5 !important;
			box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12) !important;
		}
		.btn-modern-primary {
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff !important;
			border: none;
			border-radius: 10px;
			font-weight: 700;
			box-shadow: 0 6px 16px -4px rgba(79, 70, 229, 0.4);
			transition: all 0.2s ease;
		}
		.btn-modern-primary:hover {
			transform: translateY(-1px);
			box-shadow: 0 10px 20px -4px rgba(79, 70, 229, 0.5);
			color: #ffffff !important;
		}
	</style>
	@include('frontend.partials.responsive-styles')
</head>
<body>
	@include('sweetalert::alert')

	@include('frontend.partials.simple-navbar', ['activeMenu' => 'profile'])

	<!-- Modern Page Header -->
	<section class="profile-hero-modern">
		<div class="container">
			<div class="profile-breadcrumb">
				<a href="/">Home</a> <span class="mx-1">/</span> <span class="text-dark font-weight-bold">Profil Saya</span>
			</div>
			<div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 12px;">
				<div>
					<h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.75rem; letter-spacing: -0.02em;">Pengaturan Profil</h2>
					<p class="text-muted mb-0" style="font-size: 14px;">Perbarui informasi kontak, alamat pengiriman utama, dan keamanan akun Anda.</p>
				</div>
				<a href="/shop" class="btn btn-light border btn-sm px-4 py-2 font-weight-bold" style="border-radius: 10px;">
					<i class="fa fa-arrow-left mr-1"></i> Kembali ke Toko
				</a>
			</div>
		</div>
	</section>

	<!-- Content Area -->
	<div class="container pb-5">
		<div class="row justify-content-center">
			<!-- Form Edit Profil (Full-Width Centered) -->
			<div class="col-lg-10">
				<div class="profile-card-modern">
					@if(session('success'))
					<div class="alert alert-success alert-dismissible fade show border-0" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46;">
						<i class="fa fa-check-circle mr-1"></i> {{ session('success') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					@endif

					<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
						@csrf
						@method('PUT')

						<div class="row">
							<!-- Foto Profil -->
							<div class="col-md-4 text-center mb-4">
								<div class="profile-avatar-box">
									<div class="mb-3">
										@if($user->user_image)
											<img src="{{ asset('storage/' . $user->user_image) }}" id="preview-avatar" class="rounded-circle border shadow-sm" style="width: 116px; height: 116px; object-fit: cover;">
										@else
											<div id="preview-placeholder" class="profile-avatar-placeholder mx-auto">
												{{ strtoupper(substr($user->nama, 0, 1)) }}
											</div>
											<img src="" id="preview-avatar" class="rounded-circle border shadow-sm d-none" style="width: 116px; height: 116px; object-fit: cover;">
										@endif
									</div>
									<label class="btn btn-sm btn-outline-primary px-3 mb-2 font-weight-bold" style="cursor: pointer; border-radius: 99px;">
										<i class="fa fa-camera mr-1"></i> Pilih Foto
										<input type="file" name="user_image" id="input-avatar" accept="image/*" class="d-none" onchange="previewImage(this)">
									</label>
									<div class="text-muted" style="font-size: 11.5px;">Maksimal 2 MB (JPG, PNG)</div>
									@error('user_image')
									<div class="text-danger small mt-1">{{ $message }}</div>
									@enderror
								</div>
							</div>

							<!-- Data Profil -->
							<div class="col-md-8">
								<div class="form-group mb-3">
									<label class="form-label-modern">Nama Lengkap <span class="text-danger">*</span></label>
									<input type="text" name="nama" class="form-control input-modern @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" required>
									@error('nama')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group mb-3">
									<label class="form-label-modern">Alamat Email <span class="text-danger">*</span></label>
									<input type="email" name="email" class="form-control input-modern @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
									@error('email')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group mb-3">
									<label class="form-label-modern">Nomor WhatsApp / HP</label>
									<input type="tel" name="no_hp" class="form-control input-modern @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 081234567890" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
									<small class="text-muted">Digunakan untuk konfirmasi pesanan dan pengiriman invoice.</small>
									@error('no_hp')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="form-group mb-4">
									<label class="form-label-modern">Alamat Pengiriman Utama</label>
									<textarea name="alamat" rows="3" class="form-control input-modern @error('alamat') is-invalid @enderror" style="height: auto;" placeholder="Jalan, No Rumah, RT/RW, Kecamatan, Kota/Kabupaten, Kode Pos">{{ old('alamat', $user->alamat) }}</textarea>
									<small class="text-muted">Otomatis terisi saat Anda melakukan checkout.</small>
									@error('alamat')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="p-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px;">
									<h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px;"><i class="fa fa-lock text-primary mr-1"></i> Ganti Password (Opsional)</h6>
									<p class="text-muted small mb-3">Kosongkan jika Anda tidak ingin mengubah password akun.</p>

									<div class="row">
										<div class="col-md-6 form-group mb-2">
											<label class="form-label-modern">Password Baru</label>
											<input type="password" name="password" class="form-control input-modern @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter">
											@error('password')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
										<div class="col-md-6 form-group mb-2">
											<label class="form-label-modern">Ulangi Password Baru</label>
											<input type="password" name="password_confirmation" class="form-control input-modern" placeholder="Konfirmasi password">
										</div>
									</div>
								</div>

								<div class="text-right">
									<button type="submit" class="btn btn-modern-primary px-4 py-2">
										<i class="fa fa-save mr-1"></i> Simpan Perubahan
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	@include('frontend.partials.footer')

	<!-- Scripts -->
	<script src="{{ asset('frontend-assets/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<script>
		function previewImage(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#preview-avatar').attr('src', e.target.result).removeClass('d-none');
					$('#preview-placeholder').addClass('d-none');
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>
</body>
</html>
