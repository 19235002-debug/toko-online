@extends('template.main')
@section('title', 'Edit Kategori')
@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-pen-to-square mr-1 text-warning"></i> Edit: {{ $kategori->nama_kategori }}
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('kategori.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Induk Kategori (Opsional)</label>
                                    <select name="parent_id" class="form-control">
                                        <option value="">-- Kategori Utama (Level Atas) --</option>
                                        @foreach($parentKategoris as $parent)
                                            <option value="{{ $parent->id_kategori }}" {{ (old('parent_id', $kategori->parent_id) == $parent->id_kategori) ? 'selected' : '' }}>
                                                Sub-Kategori dari: {{ $parent->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-8 form-group">
                                        <label>Nama Kategori / Sub-Kategori <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                                        @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Urutan Tampil</label>
                                        <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $kategori->urutan) }}" min="0">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Gambar / Ikon Sub-Kategori</label>
                                    <input type="file" name="gambar" class="form-control-file @error('gambar') is-invalid @enderror" accept="image/*" onchange="previewKategoriImg(this)">
                                    <small class="text-muted">Unggah gambar baru jika ingin mengganti gambar saat ini.</small>
                                    @error('gambar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    <div class="mt-2">
                                        @if($kategori->gambar)
                                            <img id="preview_kat_img" src="{{ asset('storage/' . $kategori->gambar) }}" class="rounded border" style="width: 90px; height: 90px; object-fit: cover;">
                                        @else
                                            <img id="preview_kat_img" src="" class="rounded border d-none" style="width: 90px; height: 90px; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label>Keterangan (Opsional)</label>
                                    <textarea name="keterangan" rows="2" class="form-control">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-warning text-white">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Perbarui Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewKategoriImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('preview_kat_img');
                img.src = e.target.result;
                img.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection
