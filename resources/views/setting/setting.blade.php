@extends('template.main')
@section('title', 'Pengaturan Toko')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-sliders mr-2"></i>@yield('title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-store mr-1"></i> Identitas & Kontak Toko
                            </h3>
                        </div>

                        <form action="{{ route('setting.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_toko" class="font-weight-bold">Nama Toko Online <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_toko" id="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror" value="{{ old('nama_toko', $setting->nama_toko) }}" required>
                                    @error('nama_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="no_whatsapp" class="font-weight-bold">Nomor WhatsApp Admin <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                                </div>
                                                <input type="tel" name="no_whatsapp" id="no_whatsapp" class="form-control @error('no_whatsapp') is-invalid @enderror" value="{{ old('no_whatsapp', $setting->no_whatsapp) }}" placeholder="Contoh: 081234567890" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                            </div>
                                            <small class="text-muted">Digunakan untuk tombol chat WhatsApp di toko frontend dan konfirmasi pesanan.</small>
                                            @error('no_whatsapp')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email_toko" class="font-weight-bold">Alamat Email Toko <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
                                                </div>
                                                <input type="email" name="email_toko" id="email_toko" class="form-control @error('email_toko') is-invalid @enderror" value="{{ old('email_toko', $setting->email_toko) }}" required>
                                            </div>
                                            @error('email_toko')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="alamat_toko" class="font-weight-bold">Alamat Fisik / Lokasi Toko</label>
                                    <textarea name="alamat_toko" id="alamat_toko" rows="3" class="form-control @error('alamat_toko') is-invalid @enderror" placeholder="Alamat lengkap toko Anda...">{{ old('alamat_toko', $setting->alamat_toko) }}</textarea>
                                    @error('alamat_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="rekening_bank" class="font-weight-bold">Rekening Bank untuk Pembayaran Manual</label>
                                    <textarea name="rekening_bank" id="rekening_bank" rows="3" class="form-control @error('rekening_bank') is-invalid @enderror" placeholder="Contoh: BCA: 123456789 a.n PT Coza Store&#10;Mandiri: 987654321 a.n PT Coza Store">{{ old('rekening_bank', $setting->rekening_bank) }}</textarea>
                                    <small class="text-muted">Ditampilkan kepada pembeli yang memilih metode pembayaran Transfer Manual.</small>
                                    @error('rekening_bank')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label for="deskripsi_toko" class="font-weight-bold">Deskripsi Singkat Toko (Footer)</label>
                                    <textarea name="deskripsi_toko" id="deskripsi_toko" rows="2" class="form-control @error('deskripsi_toko') is-invalid @enderror">{{ old('deskripsi_toko', $setting->deskripsi_toko) }}</textarea>
                                    @error('deskripsi_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>
                                <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-truck text-primary mr-1"></i> Pengaturan Tarif Ongkos Kirim</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="ongkir_reguler" class="font-weight-bold small">Ongkir Reguler (Rp)</label>
                                            <input type="number" min="0" name="ongkir_reguler" id="ongkir_reguler" class="form-control @error('ongkir_reguler') is-invalid @enderror" value="{{ old('ongkir_reguler', $setting->ongkir_reguler ?? 15000) }}" required>
                                            @error('ongkir_reguler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="ongkir_express" class="font-weight-bold small">Ongkir Express (Rp)</label>
                                            <input type="number" min="0" name="ongkir_express" id="ongkir_express" class="form-control @error('ongkir_express') is-invalid @enderror" value="{{ old('ongkir_express', $setting->ongkir_express ?? 30000) }}" required>
                                            @error('ongkir_express') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="min_gratis_ongkir" class="font-weight-bold small">Min. Belanja Gratis Ongkir Reguler (Rp)</label>
                                            <input type="number" min="0" name="min_gratis_ongkir" id="min_gratis_ongkir" class="form-control @error('min_gratis_ongkir') is-invalid @enderror" value="{{ old('min_gratis_ongkir', $setting->min_gratis_ongkir ?? 300000) }}" required>
                                            <small class="text-muted">Isi 0 jika selalu gratis ongkir reguler.</small>
                                            @error('min_gratis_ongkir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light d-flex justify-content-end">
                                <button type="reset" class="btn btn-secondary mr-2">
                                    <i class="fa-solid fa-arrows-rotate mr-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary font-weight-bold px-4">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

