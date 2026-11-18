@extends('template.main')
@section('title', 'Edit Produk')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-pen-to-square mr-2"></i>@yield('title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/barang">Produk</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Data Produk & Variasi
                            </h3>
                            <div class="card-tools">
                                {!! btn_back('/barang') !!}
                            </div>
                        </div>

                        <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data" id="formBarangEdit">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <!-- KIRI: Data Utama -->
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="nama" class="font-weight-bold">Nama Produk <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Nama Produk" value="{{ old('nama', $barang->nama) }}" required>
                                            @error('nama') <span class="invalid-feedback text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kategori" class="font-weight-bold">Kategori Utama <span class="text-danger">*</span></label>
                                                    <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required onchange="updateSubKategoriEditOptions()">
                                                        <option value="">-- Pilih Kategori --</option>
                                                        @foreach($mainKategoris as $cat)
                                                            <option value="{{ $cat->nama_kategori }}" {{ strtolower(old('kategori', $barang->kategori)) == strtolower($cat->nama_kategori) ? 'selected' : '' }}>
                                                                {{ $cat->nama_kategori }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('kategori') <span class="invalid-feedback text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="sub_kategori" class="font-weight-bold">Sub-Kategori</label>
                                                    <select name="sub_kategori" id="sub_kategori" class="form-control @error('sub_kategori') is-invalid @enderror">
                                                        <option value="">-- Pilih Sub-Kategori --</option>
                                                    </select>
                                                    @error('sub_kategori') <span class="invalid-feedback text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="supplier" class="font-weight-bold">Supplier / Brand <span class="text-danger">*</span></label>
                                                    <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror" id="supplier" placeholder="Supplier" value="{{ old('supplier', $barang->supplier) }}" required>
                                                    @error('supplier') <span class="invalid-feedback text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            const categoryTreeEdit = @json($mainKategoris->mapWithKeys(fn($m) => [$m->nama_kategori => $m->children->pluck('nama_kategori')]));
                                            const currentSubKatEdit = @json(old('sub_kategori', $barang->sub_kategori ?? ''));
                                            function updateSubKategoriEditOptions() {
                                                const mainVal = document.getElementById('kategori').value;
                                                const subSelect = document.getElementById('sub_kategori');
                                                subSelect.innerHTML = '<option value="">-- Pilih Sub-Kategori --</option>';
                                                const subs = categoryTreeEdit[mainVal] || [];
                                                subs.forEach(function(sub) {
                                                    const opt = document.createElement('option');
                                                    opt.value = sub;
                                                    opt.textContent = sub;
                                                    if (sub === currentSubKatEdit) opt.selected = true;
                                                    subSelect.appendChild(opt);
                                                });
                                            }
                                            document.addEventListener('DOMContentLoaded', updateSubKategoriEditOptions);
                                        </script>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="harga" class="font-weight-bold">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                                                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" id="harga" placeholder="Harga" value="{{ old('harga', $barang->harga) }}" required>
                                                    @error('harga') <span class="invalid-feedback text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="stok" class="font-weight-bold">Total Stok</label>
                                                    <input type="number" min="0" name="stok" class="form-control @error('stok') is-invalid @enderror" id="stok" placeholder="Stok" value="{{ old('stok', $barang->stok) }}">
                                                    <small class="text-muted d-block" style="font-size: 11px;">*Otomatis dihitung dari jumlah stok variasi di bawah.</small>
                                                    @error('stok') <span class="invalid-feedback text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        @include('barang.partials.promo-fields', ['barang' => $barang])
                                    </div>

                                    <!-- KANAN: Foto Utama & Galeri Tambahan -->
                                    <div class="col-lg-6">
                                        <div class="card card-outline card-info shadow-none border">
                                            <div class="card-header py-2">
                                                <h3 class="card-title font-weight-bold" style="font-size: 0.95rem;">
                                                    <i class="fa-solid fa-images mr-1 text-info"></i> Foto Utama & Galeri Gambar
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <!-- Foto Utama -->
                                                <div class="form-group mb-3">
                                                    <label for="gambar" class="font-weight-bold">Foto Utama (Sampul)</label>
                                                    <input type="file" name="gambar" id="gambar" class="form-control-file @error('gambar') is-invalid @enderror" onchange="previewImage(event)" accept="image/*">
                                                    @error('gambar') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                                                    <div class="mt-2 d-flex align-items-center">
                                                        @if ($barang->gambar)
                                                            <div class="mr-3">
                                                                <small class="d-block text-muted mb-1">Foto Sampul Saat Ini:</small>
                                                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Foto Utama" class="img-thumbnail" style="max-height: 110px; object-fit: contain;">
                                                            </div>
                                                        @endif
                                                        <div id="imagePreviewWrapper" style="display: none;">
                                                            <small class="d-block text-primary mb-1">Preview Foto Baru:</small>
                                                            <img id="imagePreview" src="#" alt="Preview Foto Utama" class="img-thumbnail" style="max-height: 110px; object-fit: contain;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr class="my-2">

                                                <!-- Galeri Foto Saat Ini -->
                                                <div class="form-group mb-2">
                                                    <label class="font-weight-bold">
                                                        <i class="fa fa-photo-film text-secondary mr-1"></i> Foto Galeri Tambahan Saat Ini:
                                                    </label>

                                                    @if($barang->gambars->count() > 0)
                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                            @foreach($barang->gambars as $g)
                                                                <div class="position-relative border rounded p-1 bg-white shadow-sm text-center" style="width: 80px; height: 95px; margin: 4px;">
                                                                    <img src="{{ asset('storage/' . $g->gambar) }}" alt="Galeri" style="width: 100%; height: 60px; object-fit: cover; border-radius: 4px;">
                                                                    <form action="{{ route('barang.gambar.delete', $g->id_gambar) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus foto ini dari galeri?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-xs btn-danger btn-block mt-1" style="font-size: 10px; padding: 1px 3px;" title="Hapus foto ini">
                                                                            <i class="fa fa-trash"></i> Hapus
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-muted small font-italic mb-2">Belum ada foto tambahan di galeri produk ini.</p>
                                                    @endif

                                                    <label for="galeri_gambar" class="font-weight-bold mt-2" style="font-size: 0.88rem;">
                                                        + Unggah Foto Tambahan Baru (Bisa Pilih Banyak):
                                                    </label>
                                                    <input type="file" name="galeri_gambar[]" id="galeri_gambar" multiple class="form-control-file @error('galeri_gambar.*') is-invalid @enderror" onchange="previewGalleryImages(event)" accept="image/*">
                                                    <small class="text-muted d-block">Tahan <strong>Ctrl</strong> (atau Shift) untuk memilih lebih dari 1 foto.</small>

                                                    <div id="galleryPreviewContainer" class="d-flex flex-wrap gap-2 mt-2" style="display: none !important;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- BAGIAN BAWAH: Variasi Ukuran & Warna -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-outline card-primary shadow-none border">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h3 class="card-title font-weight-bold text-dark" style="font-size: 1rem;">
                                                        <i class="fa-solid fa-palette text-primary mr-1"></i> Variasi Produk (Ukuran & Warna)
                                                    </h3>
                                                    <small class="d-block text-muted">Sesuaikan varian warna, ukuran, dan stok barang masing-masing.</small>
                                                </div>
                                                <div class="card-tools d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSizePreset()">
                                                        <i class="fa fa-shirt mr-1"></i> + Preset Ukuran Baju (S, M, L, XL)
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="addVariantRow()">
                                                        <i class="fa fa-plus mr-1"></i> Tambah Baris Variasi
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped mb-0 text-center" id="tableVariasi">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th style="width: 50px;">#</th>
                                                                <th>Warna (Opsional)</th>
                                                                <th>Ukuran (Opsional)</th>
                                                                <th style="width: 160px;">Stok Varian <span class="text-danger">*</span></th>
                                                                <th style="width: 180px;">Harga Khusus (Opsional)</th>
                                                                <th style="width: 80px;">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="variantTableBody">
                                                            @forelse($barang->variasis as $v)
                                                                <tr class="variant-row">
                                                                    <td class="align-middle row-number">{{ $loop->iteration }}</td>
                                                                    <td class="align-middle">
                                                                        <input type="text" name="variasi_warna[]" class="form-control form-control-sm" placeholder="Contoh: Hitam / Putih / Navy" value="{{ $v->warna }}">
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <input type="text" name="variasi_ukuran[]" class="form-control form-control-sm" placeholder="Contoh: S, M, L, XL, All Size" value="{{ $v->ukuran }}">
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <input type="number" min="0" name="variasi_stok[]" class="form-control form-control-sm text-center variant-stock-input" value="{{ $v->stok }}" required onchange="calculateTotalStock()">
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <input type="number" min="0" name="variasi_harga[]" class="form-control form-control-sm text-center" placeholder="Kosongkan jika sama" value="{{ $v->harga }}">
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <button type="button" class="btn btn-sm btn-danger btn-action" onclick="removeVariantRow(this)" title="Hapus Baris">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr class="variant-row">
                                                                    <td class="align-middle row-number">1</td>
                                                                    <td class="align-middle">
                                                                        <input type="text" name="variasi_warna[]" class="form-control form-control-sm" placeholder="Contoh: Hitam / Putih / Navy" value="">
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <input type="text" name="variasi_ukuran[]" class="form-control form-control-sm" placeholder="Contoh: S, M, L, XL, All Size" value="">
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <input type="number" min="0" name="variasi_stok[]" class="form-control form-control-sm text-center variant-stock-input" value="{{ $barang->stok }}" required onchange="calculateTotalStock()">
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <input type="number" min="0" name="variasi_harga[]" class="form-control form-control-sm text-center" placeholder="Kosongkan jika sama" value="">
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <button type="button" class="btn btn-sm btn-danger btn-action" onclick="removeVariantRow(this)" title="Hapus Baris">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                        <tfoot class="bg-light font-weight-bold">
                                                            <tr>
                                                                <td colspan="3" class="text-right align-middle">TOTAL STOK DARI SEMUA VARIAN:</td>
                                                                <td class="text-center align-middle" id="totalVariantStockDisplay" style="color: #007bff; font-size: 1.05rem;">
                                                                    {{ $barang->stok }}
                                                                </td>
                                                                <td colspan="2" class="text-muted small text-left align-middle font-italic">
                                                                    *Total stok di atas otomatis menyinkronkan stok produk utama.
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer d-flex flex-wrap justify-content-end">
                                {!! btn_reset() !!}
                                {!! btn_save('Perbarui Produk') !!}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    const wrapper = document.getElementById('imagePreviewWrapper');

    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        wrapper.style.display = 'block';
      }
      reader.readAsDataURL(input.files[0]);
    } else {
      wrapper.style.display = 'none';
    }
  }

  function previewGalleryImages(event) {
    const container = document.getElementById('galleryPreviewContainer');
    container.innerHTML = '';
    const files = event.target.files;

    if (files && files.length > 0) {
      container.style.display = 'flex';
      Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
          const div = document.createElement('div');
          div.className = 'position-relative m-1 border rounded p-1 bg-white shadow-sm';
          div.style.width = '75px';
          div.style.height = '75px';
          div.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                           <span class="badge badge-info position-absolute" style="top: 2px; right: 2px; font-size: 9px;">Baru #${index+1}</span>`;
          container.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    } else {
      container.style.display = 'none';
    }
  }

  function addVariantRow(warna = '', ukuran = '', stok = 10, harga = '') {
    const tbody = document.getElementById('variantTableBody');
    const rowCount = tbody.querySelectorAll('tr').length + 1;

    const tr = document.createElement('tr');
    tr.className = 'variant-row';
    tr.innerHTML = `
      <td class="align-middle row-number">${rowCount}</td>
      <td class="align-middle">
        <input type="text" name="variasi_warna[]" class="form-control form-control-sm" placeholder="Contoh: Hitam / Putih / Navy" value="${warna}">
      </td>
      <td class="align-middle">
        <input type="text" name="variasi_ukuran[]" class="form-control form-control-sm" placeholder="Contoh: S, M, L, XL, All Size" value="${ukuran}">
      </td>
      <td class="align-middle">
        <input type="number" min="0" name="variasi_stok[]" class="form-control form-control-sm text-center variant-stock-input" value="${stok}" required onchange="calculateTotalStock()">
      </td>
      <td class="align-middle">
        <input type="number" min="0" name="variasi_harga[]" class="form-control form-control-sm text-center" placeholder="Kosongkan jika sama" value="${harga}">
      </td>
      <td class="align-middle">
        <button type="button" class="btn btn-sm btn-danger btn-action" onclick="removeVariantRow(this)" title="Hapus Baris">
          <i class="fa fa-trash"></i>
        </button>
      </td>
    `;
    tbody.appendChild(tr);
    reindexRows();
    calculateTotalStock();
  }

  function removeVariantRow(button) {
    const row = button.closest('tr');
    const tbody = document.getElementById('variantTableBody');
    if (tbody.querySelectorAll('tr').length > 1) {
      row.remove();
      reindexRows();
      calculateTotalStock();
    } else {
      row.querySelectorAll('input').forEach(i => i.value = '');
      calculateTotalStock();
    }
  }

  function reindexRows() {
    const rows = document.querySelectorAll('#variantTableBody tr');
    rows.forEach((r, idx) => {
      const numCell = r.querySelector('.row-number');
      if (numCell) numCell.textContent = idx + 1;
    });
  }

  function calculateTotalStock() {
    let total = 0;
    const inputs = document.querySelectorAll('.variant-stock-input');
    inputs.forEach(input => {
      const val = parseInt(input.value) || 0;
      total += val;
    });
    document.getElementById('totalVariantStockDisplay').textContent = total;
    if (total > 0) {
      document.getElementById('stok').value = total;
    }
  }

  function addSizePreset() {
    const sizes = ['S', 'M', 'L', 'XL'];
    const tbody = document.getElementById('variantTableBody');

    const firstRow = tbody.querySelector('tr');
    const firstUkuran = firstRow ? firstRow.querySelector('input[name="variasi_ukuran[]"]').value.trim() : 'filled';
    const firstWarna = firstRow ? firstRow.querySelector('input[name="variasi_warna[]"]').value.trim() : 'filled';

    if (firstRow && firstUkuran === '' && firstWarna === '') {
      firstRow.remove();
    }

    sizes.forEach(sz => {
      addVariantRow('', sz, 10, '');
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    calculateTotalStock();
  });
</script>
@endsection
