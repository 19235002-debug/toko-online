@extends('template.main')
@section('title', 'Tambah Produk')
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
            <li class="breadcrumb-item"><a href="/barang">Produk</a></li>
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
          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title font-weight-bold">
                <i class="fa-solid fa-box mr-1"></i> Form Tambah Produk Baru
              </h3>
              <div class="card-tools">
                {!! btn_back('/barang') !!}
              </div>
            </div>
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate id="formBarang">
              @csrf
              <div class="card-body">
                <div class="row">
                  <!-- KIRI: Informasi Utama -->
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="nama" class="font-weight-bold">Nama Produk <span class="text-danger">*</span></label>
                      <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Contoh: Kemeja Flanel Slimfit" value="{{ old('nama') }}" required>
                      @error('nama')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="kategori" class="font-weight-bold">Kategori Utama <span class="text-danger">*</span></label>
                          <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required onchange="updateSubKategoriOptions()">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($mainKategoris as $cat)
                              <option value="{{ $cat->nama_kategori }}" {{ old('kategori') == $cat->nama_kategori ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                              </option>
                            @endforeach
                          </select>
                          @error('kategori')
                          <span class="invalid-feedback text-danger">{{ $message }}</span>
                          @enderror
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="sub_kategori" class="font-weight-bold">Sub-Kategori</label>
                          <select name="sub_kategori" id="sub_kategori" class="form-control @error('sub_kategori') is-invalid @enderror">
                            <option value="">-- Pilih Sub-Kategori --</option>
                          </select>
                          @error('sub_kategori')
                          <span class="invalid-feedback text-danger">{{ $message }}</span>
                          @enderror
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="supplier" class="font-weight-bold">Supplier / Brand <span class="text-danger">*</span></label>
                          <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror" id="supplier" placeholder="Contoh: Erigo Official" value="{{ old('supplier') }}" required>
                          @error('supplier')
                          <span class="invalid-feedback text-danger">{{ $message }}</span>
                          @enderror
                        </div>
                      </div>
                    </div>

                    <script>
                      const categoryTreeAdd = @json($mainKategoris->mapWithKeys(fn($m) => [$m->nama_kategori => $m->children->pluck('nama_kategori')]));
                      const oldSubKatAdd = @json(old('sub_kategori', ''));
                      function updateSubKategoriOptions() {
                        const mainVal = document.getElementById('kategori').value;
                        const subSelect = document.getElementById('sub_kategori');
                        subSelect.innerHTML = '<option value="">-- Pilih Sub-Kategori --</option>';
                        const subs = categoryTreeAdd[mainVal] || [];
                        subs.forEach(function(sub) {
                          const opt = document.createElement('option');
                          opt.value = sub;
                          opt.textContent = sub;
                          if (sub === oldSubKatAdd) opt.selected = true;
                          subSelect.appendChild(opt);
                        });
                      }
                      document.addEventListener('DOMContentLoaded', updateSubKategoriOptions);
                    </script>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="harga" class="font-weight-bold">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                          <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" id="harga" placeholder="Contoh: 150000" value="{{ old('harga') }}" required>
                          @error('harga')
                          <span class="invalid-feedback text-danger">{{ $message }}</span>
                          @enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="stok" class="font-weight-bold">Stok Dasar</label>
                          <input type="number" min="0" name="stok" class="form-control @error('stok') is-invalid @enderror" id="stok" placeholder="Contoh: 50" value="{{ old('stok', 0) }}">
                          <small class="text-muted d-block" style="font-size: 11px;">*Otomatis dihitung jika Anda mengisi tabel variasi di bawah.</small>
                          @error('stok')
                          <span class="invalid-feedback text-danger">{{ $message }}</span>
                          @enderror
                        </div>
                      </div>
                    </div>

                    @include('barang.partials.promo-fields')
                  </div>

                  <!-- KANAN: Manajemen Foto (Utama + Galeri Banyak Foto) -->
                  <div class="col-lg-6">
                    <div class="card card-outline card-info shadow-none border">
                      <div class="card-header py-2">
                        <h3 class="card-title font-weight-bold" style="font-size: 0.95rem;">
                          <i class="fa-solid fa-images mr-1 text-info"></i> Galeri & Foto Produk
                        </h3>
                      </div>
                      <div class="card-body">
                        <!-- Foto Utama -->
                        <div class="form-group mb-3">
                          <label for="gambar" class="font-weight-bold">Foto Utama (Sampul)</label>
                          <input type="file" name="gambar" id="gambar" class="form-control-file @error('gambar') is-invalid @enderror" onchange="previewImage(event)" accept="image/*">
                          <small class="text-muted d-block">Format: JPG, JPEG, PNG, WEBP (Maks 3MB)</small>
                          @error('gambar')
                          <span class="invalid-feedback d-block">{{ $message }}</span>
                          @enderror

                          <div class="mt-2 text-center">
                            <img id="imagePreview" src="#" alt="Preview Foto Utama" class="img-thumbnail" style="display: none; max-height: 140px; object-fit: contain;">
                          </div>
                        </div>

                        <hr class="my-2">

                        <!-- Foto Tambahan (Banyak Foto) -->
                        <div class="form-group mb-0">
                          <label for="galeri_gambar" class="font-weight-bold">
                            <i class="fa fa-photo-film text-secondary mr-1"></i> Foto Tambahan Lainnya (Bisa Pilih Banyak Foto Sekaligus)
                          </label>
                          <input type="file" name="galeri_gambar[]" id="galeri_gambar" multiple class="form-control-file @error('galeri_gambar.*') is-invalid @enderror" onchange="previewGalleryImages(event)" accept="image/*">
                          <small class="text-muted d-block">Tahan tombol <strong>Ctrl</strong> (atau Shift) saat memilih foto di folder Anda untuk mengunggah beberapa foto sekaligus.</small>
                          @error('galeri_gambar.*')
                          <span class="invalid-feedback d-block">{{ $message }}</span>
                          @enderror

                          <div id="galleryPreviewContainer" class="d-flex flex-wrap gap-2 mt-2" style="display: none !important;">
                            <!-- Preview thumbnails loaded here -->
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
                          <small class="d-block text-muted">Tambahkan varian ukuran (S, M, L, XL, dll.) dan warna (Hitam, Putih, dll.) beserta stok masing-masing.</small>
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
                              <!-- Default 1 Row Variasi -->
                              <tr class="variant-row">
                                <td class="align-middle row-number">1</td>
                                <td class="align-middle">
                                  <input type="text" name="variasi_warna[]" class="form-control form-control-sm" placeholder="Contoh: Hitam / Putih / Navy">
                                </td>
                                <td class="align-middle">
                                  <input type="text" name="variasi_ukuran[]" class="form-control form-control-sm" placeholder="Contoh: S, M, L, XL, All Size">
                                </td>
                                <td class="align-middle">
                                  <input type="number" min="0" name="variasi_stok[]" class="form-control form-control-sm text-center variant-stock-input" value="10" required onchange="calculateTotalStock()">
                                </td>
                                <td class="align-middle">
                                  <input type="number" min="0" name="variasi_harga[]" class="form-control form-control-sm text-center" placeholder="Kosongkan jika sama">
                                </td>
                                <td class="align-middle">
                                  <button type="button" class="btn btn-sm btn-danger btn-action" onclick="removeVariantRow(this)" title="Hapus Baris">
                                    <i class="fa fa-trash"></i>
                                  </button>
                                </td>
                              </tr>
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                              <tr>
                                <td colspan="3" class="text-right align-middle">TOTAL STOK DARI SEMUA VARIAN:</td>
                                <td class="text-center align-middle" id="totalVariantStockDisplay" style="color: #007bff; font-size: 1.05rem;">
                                  10
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
                {!! btn_save('Simpan Produk') !!}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Preview Foto Utama
  function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');

    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'inline-block';
      }
      reader.readAsDataURL(input.files[0]);
    } else {
      preview.style.display = 'none';
    }
  }

  // Preview Galeri Banyak Foto
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
                           <span class="badge badge-dark position-absolute" style="top: 2px; right: 2px; font-size: 9px;">#${index+1}</span>`;
          container.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    } else {
      container.style.display = 'none';
    }
  }

  // Baris Variasi Produk
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
      // Clear inputs instead of deleting only row
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
    // Sinkronkan ke input stok produk dasar jika lebih dari 0
    if (total > 0) {
      document.getElementById('stok').value = total;
    }
  }

  // Preset Cepat Ukuran Pakaian Standar (S, M, L, XL)
  function addSizePreset() {
    const sizes = ['S', 'M', 'L', 'XL'];
    const tbody = document.getElementById('variantTableBody');
    
    // Cek jika baris pertama masih kosong, gunakan baris pertama
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
