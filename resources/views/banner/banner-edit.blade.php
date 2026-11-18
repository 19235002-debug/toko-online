@extends('template.main')
@section('title', 'Edit Banner')
@section('content')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><i class="fa-solid fa-pen-to-square mr-2"></i>@yield('title')</h1>
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
                <i class="fa-solid fa-panorama mr-1"></i> Form Edit Banner Slider
              </h3>
              <div class="card-tools">
                {!! btn_back(route('banner.index')) !!}
              </div>
            </div>

            <form action="{{ route('banner.update', $banner->id_banner) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="row">
                  <!-- KIRI: Form Teks -->
                  <div class="col-lg-7">
                    <div class="form-group">
                      <label for="judul">Judul Utama Banner <span class="text-danger">*</span></label>
                      <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" id="judul" placeholder="Contoh: JACKETS & COATS" value="{{ old('judul', $banner->judul) }}" required>
                      @error('judul')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="subjudul">Subjudul / Kategori Banner</label>
                      <input type="text" name="subjudul" class="form-control @error('subjudul') is-invalid @enderror" id="subjudul" placeholder="Contoh: Men New-Season" value="{{ old('subjudul', $banner->subjudul) }}">
                      @error('subjudul')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="link">Link Tujuan Tombol "Shop Now"</label>
                      <input type="text" name="link" class="form-control @error('link') is-invalid @enderror" id="link" placeholder="Default: /frontend#product-grid" value="{{ old('link', $banner->link) }}">
                      <small class="text-muted">Link saat tombol Shop Now di banner diklik pengguna.</small>
                      @error('link')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="urutan">Nomor Urutan Tampil</label>
                          <input type="number" min="1" name="urutan" class="form-control @error('urutan') is-invalid @enderror" id="urutan" value="{{ old('urutan', $banner->urutan) }}">
                          @error('urutan')
                            <span class="invalid-feedback">{{ $message }}</span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-sm-6 d-flex align-items-center">
                        <div class="custom-control custom-switch mt-3">
                          <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', $banner->status) ? 'checked' : '' }}>
                          <label class="custom-control-label font-weight-bold" for="status">Tampilkan Banner (Aktif)</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- KANAN: Upload Foto -->
                  <div class="col-lg-5">
                    <div class="form-group">
                      <label for="gambar">Ganti Foto Banner (Opsional)</label>
                      <input type="file" name="gambar" id="gambar" class="form-control-file @error('gambar') is-invalid @enderror" onchange="previewBanner(event)" accept="image/*">
                      <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengubah foto banner.</small>
                      @error('gambar')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                      @enderror

                      <div class="mt-3">
                        <small class="d-block mb-1 font-weight-bold text-muted">Preview Gambar Banner:</small>
                        @php
                          $imgUrl = str_starts_with($banner->gambar, 'banners/') 
                              ? asset('storage/' . $banner->gambar) 
                              : asset('frontend-assets/' . $banner->gambar);
                        @endphp
                        <div class="border rounded p-2 text-center bg-light">
                          <img id="bannerPreview" src="{{ $imgUrl }}" alt="Foto Banner" class="img-fluid rounded" style="max-height: 220px; width: 100%; object-fit: cover;">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-footer d-flex flex-wrap justify-content-end">
                {!! btn_reset() !!}
                {!! btn_save('Perbarui Banner') !!}
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

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function(e) {
        preview.src = e.target.result;
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection

