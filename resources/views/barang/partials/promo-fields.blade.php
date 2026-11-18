@php
  /** @var \App\Models\Barang|null $barangItem */
  $barangItem = $barang ?? null;
@endphp

<!-- Pengaturan Promo & Durasi -->
<div class="card card-outline card-danger shadow-none border mt-2 mb-0">
  <div class="card-header py-2 d-flex justify-content-between align-items-center">
    <h3 class="card-title font-weight-bold" style="font-size: 0.92rem;">
      <i class="fa fa-tags text-danger mr-1"></i> Pengaturan Promo & Durasi
    </h3>
    <div class="custom-control custom-switch ml-auto">
      <input type="checkbox" class="custom-control-input" id="promo_aktif" name="promo_aktif" value="1" {{ old('promo_aktif', $barangItem ? $barangItem->promo_aktif : false) ? 'checked' : '' }}>
      <label class="custom-control-label font-weight-bold" for="promo_aktif" style="font-size: 0.85rem; cursor: pointer;">Aktifkan Promo</label>
    </div>
  </div>
  <div class="card-body py-2">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group mb-2">
          <label for="promo_persen" class="small font-weight-bold">Diskon (%)</label>
          <input type="number" min="1" max="99" name="promo_persen" id="promo_persen" class="form-control form-control-sm @error('promo_persen') is-invalid @enderror" placeholder="Contoh: 20" value="{{ old('promo_persen', $barangItem ? $barangItem->promo_persen : '') }}" oninput="syncPromoFromPercent()">
          @error('promo_persen') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-2">
          <label for="harga_promo" class="small font-weight-bold">Harga Promo (Rp)</label>
          <input type="number" min="0" name="harga_promo" id="harga_promo" class="form-control form-control-sm @error('harga_promo') is-invalid @enderror" placeholder="Otomatis / isi manual" value="{{ old('harga_promo', $barangItem ? $barangItem->harga_promo : '') }}" oninput="syncPromoFromPrice()">
          @error('harga_promo') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group mb-1">
          <label for="promo_mulai" class="small font-weight-bold">Mulai Promo (Opsional)</label>
          <input type="datetime-local" name="promo_mulai" id="promo_mulai" class="form-control form-control-sm @error('promo_mulai') is-invalid @enderror" value="{{ old('promo_mulai', ($barangItem && $barangItem->promo_mulai) ? $barangItem->promo_mulai->format('Y-m-d\TH:i') : '') }}">
          @error('promo_mulai') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-1">
          <label for="promo_selesai" class="small font-weight-bold">Selesai Promo (Opsional)</label>
          <input type="datetime-local" name="promo_selesai" id="promo_selesai" class="form-control form-control-sm @error('promo_selesai') is-invalid @enderror" value="{{ old('promo_selesai', ($barangItem && $barangItem->promo_selesai) ? $barangItem->promo_selesai->format('Y-m-d\TH:i') : '') }}">
          @error('promo_selesai') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
      </div>
    </div>
    <small class="text-muted d-block mt-1" style="font-size: 11px;">*Kosongkan tanggal mulai/selesai jika promo berlaku terus selama sakelar diaktifkan.</small>
  </div>
</div>

<script>
  function syncPromoFromPercent() {
    const harga = parseFloat(document.getElementById('harga').value) || 0;
    const persen = parseFloat(document.getElementById('promo_persen').value);
    if (harga > 0 && !isNaN(persen) && persen > 0 && persen < 100) {
      document.getElementById('harga_promo').value = Math.round(harga - (harga * (persen / 100)));
    }
  }

  function syncPromoFromPrice() {
    const harga = parseFloat(document.getElementById('harga').value) || 0;
    const hargaPromo = parseFloat(document.getElementById('harga_promo').value);
    if (harga > 0 && !isNaN(hargaPromo) && hargaPromo >= 0 && hargaPromo < harga) {
      const pct = Math.round(((harga - hargaPromo) / harga) * 100);
      document.getElementById('promo_persen').value = Math.max(1, Math.min(99, pct));
    }
  }
</script>

