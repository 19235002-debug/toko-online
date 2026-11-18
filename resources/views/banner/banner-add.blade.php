@extends('template.main')
@section('title', 'Tambah Banner')
@section('content')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><i class="fa-solid fa-plus-circle mr-2"></i>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('banner.index') }}">Banner</a></li>
            <li class="breadcrumb-item active">@yield('title')</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title font-weight-bold">
                <i class="fa-solid fa-panorama mr-1"></i> Form Tambah Banner Baru
              </h3>
              <div class="card-tools">
                {!! btn_back(route('banner.index')) !!}
              </div>
            </div>

            <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="row">
                  <!-- KIRI: Form Teks -->
                  <div class="col-lg-7">
                    <div class="form-group">
                      <label for="judul">Judul Utama Banner <span class="text-danger">*</span></label>
                      <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" id="judul" placeholder="Contoh: JACKETS & COATS atau NEW SEASON" value="{{ old('judul') }}" required>
                      @error('judul')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="subjudul">Subjudul / Kategori Banner</label>
                      <input type="text" name="subjudul" class="form-control @error('subjudul') is-invalid @enderror" id="subjudul" placeholder="Contoh: Men New-Season atau Women Collection" value="{{ old('subjudul') }}">
                      @error('subjudul')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="link">Link Tujuan Tombol "Shop Now"</label>
                      <input type="text" name="link" class="form-control @error('link') is-invalid @enderror" id="link" placeholder="Default: /frontend#product-grid" value="{{ old('link', '/frontend#product-grid') }}">
                      <small class="text-muted">Dapat diisi link halaman seperti <code>/frontend#product-grid</code> atau URL eksternal.</small>
                      @error('link')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="urutan">Nomor Urutan Tampil</label>
                          <input type="number" min="1" name="urutan" class="form-control @error('urutan') is-invalid @enderror" id="urutan" placeholder="1, 2, 3..." value="{{ old('urutan', 1) }}">
                          @error('urutan')
                            <span class="invalid-feedback">{{ $message }}</span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-sm-6 d-flex align-items-center">
                        <div class="custom-control custom-switch mt-3">
                          <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" checked>
                          <label class="custom-control-label font-weight-bold" for="status">Tampilkan Banner (Aktif)</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- KANAN: Upload Foto -->
                  <div class="col-lg-5">
                    <div class="form-group">
                      <label for="gambar">File Foto Banner <span class="text-danger">*</span></label>
                      <input type="file" name="gambar" id="gambar" class="form-control-file @error('gambar') is-invalid @enderror" onchange="previewBanner(event)" accept="image/*" required>
                      <small class="text-muted d-block mt-1">Disarankan gambar landscape rasio 16:9 atau lebar minimal 1200px (format: JPG, PNG, WEBP max 4MB).</small>
                      @error('gambar')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                      @enderror

                      <div class="mt-3">
                        <small class="d-block mb-1 font-weight-bold text-muted">Preview Gambar Banner:</small>
                        <div class="border rounded p-2 text-center bg-light" style="min-height: 180px; display: flex; align-items: center; justify-content: center;">
                          <img id="bannerPreview" src="#" alt="Preview Banner" class="img-fluid rounded" style="display: none; max-height: 220px; width: 100%; object-fit: cover;">
                          <span id="previewPlaceholder" class="text-muted">
                            <i class="fa-regular fa-image fa-2x d-block mb-1"></i>
                            Pilih file gambar untuk melihat preview
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-footer d-flex flex-wrap justify-content-end">
                {!! btn_reset() !!}
                {!! btn_save('Simpan Banner') !!}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function previewBanner(event) {
    const input = event.target;
    const preview = document.getElementById('bannerPreview');
    const placeholder = document.getElementById('previewPlaceholder');

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection

