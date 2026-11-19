@extends('template.main')
@section('title', 'Pengaturan Toko')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-sliders text-primary mr-2"></i>@yield('title')</h1>
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
            <form action="{{ route('setting.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Top Overview Banner -->
                <div class="card mb-4">
                    <div class="card-body py-3 d-flex flex-wrap align-items-center justify-content-between" style="gap: 14px;">
                        <div class="d-flex align-items-center" style="gap: 14px;">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 14px; background: linear-gradient(135deg, #465fff 0%, #6366f1 100%); color: #ffffff; box-shadow: 0 6px 14px rgba(70, 95, 255, 0.25);">
                                <i class="fa-solid fa-store fa-lg"></i>
                            </span>
                            <div>
                                <div class="h5 font-weight-bold mb-0">{{ $setting->nama_toko ?: 'Fashion Store' }}</div>
                                <div class="text-muted small mt-0.5">
                                    <i class="fab fa-whatsapp text-success mr-1"></i>{{ $setting->no_whatsapp }}
                                    <span class="mx-2">&bull;</span>
                                    <i class="fa-regular fa-envelope text-primary mr-1"></i>{{ $setting->email_toko }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <button type="reset" class="btn btn-light border btn-action px-3" style="height: 40px;">
                                <i class="fa-solid fa-arrows-rotate mr-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary btn-action px-4 font-weight-bold" style="height: 40px; box-shadow: 0 4px 12px rgba(70, 95, 255, 0.25);">
                                <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Kolom Kiri: Identitas & Kontak Toko -->
                    <div class="col-lg-7">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold d-flex align-items-center" style="gap: 8px;">
                                    <span class="d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 8px; background: #eef2ff; color: #465fff;">
                                        <i class="fa-solid fa-building-user" style="font-size: 13px;"></i>
                                    </span>
                                    <span>Identitas &amp; Kontak Toko</span>
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_toko">Nama Toko Online <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa-solid fa-shop text-primary"></i></span>
                                        </div>
                                        <input type="text" name="nama_toko" id="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror" value="{{ old('nama_toko', $setting->nama_toko) }}" placeholder="Masukkan nama toko" required>
                                    </div>
                                    @error('nama_toko')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="no_whatsapp">Nomor WhatsApp Admin <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-whatsapp text-success" style="font-size: 16px;"></i></span>
                                                </div>
                                                <input type="tel" name="no_whatsapp" id="no_whatsapp" class="form-control @error('no_whatsapp') is-invalid @enderror" value="{{ old('no_whatsapp', $setting->no_whatsapp) }}" placeholder="Contoh: 081234567890" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                            </div>
                                            <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Digunakan untuk tombol chat WhatsApp &amp; konfirmasi pesanan.</small>
                                            @error('no_whatsapp')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email_toko">Alamat Email Toko <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa-solid fa-envelope text-primary"></i></span>
                                                </div>
                                                <input type="email" name="email_toko" id="email_toko" class="form-control @error('email_toko') is-invalid @enderror" value="{{ old('email_toko', $setting->email_toko) }}" placeholder="admin@tokoanda.com" required>
                                            </div>
                                            <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Ditampilkan pada footer toko dan header cetak invoice.</small>
                                            @error('email_toko')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="alamat_toko">Alamat Fisik / Lokasi Toko</label>
                                    <textarea name="alamat_toko" id="alamat_toko" rows="3" class="form-control @error('alamat_toko') is-invalid @enderror" placeholder="Alamat lengkap toko atau gudang pengiriman...">{{ old('alamat_toko', $setting->alamat_toko) }}</textarea>
                                    @error('alamat_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <label for="deskripsi_toko">Deskripsi Singkat Toko (Footer)</label>
                                    <textarea name="deskripsi_toko" id="deskripsi_toko" rows="3" class="form-control @error('deskripsi_toko') is-invalid @enderror" placeholder="Ringkasan singkat mengenai toko Anda...">{{ old('deskripsi_toko', $setting->deskripsi_toko) }}</textarea>
                                    @error('deskripsi_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Tarif Ongkir & Rekening Pembayaran -->
                    <div class="col-lg-5">
                        <!-- Card Pengaturan Ongkos Kirim -->
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold d-flex align-items-center" style="gap: 8px;">
                                    <span class="d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 8px; background: #ecfdf5; color: #10b981;">
                                        <i class="fa-solid fa-truck-fast" style="font-size: 13px;"></i>
                                    </span>
                                    <span>Pengaturan Tarif Ongkos Kirim</span>
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="p-3 mb-3 rounded border" style="background: #f8fafc;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark" style="font-size: 13px;">
                                            <i class="fa-solid fa-plug-circle-check text-success mr-1"></i> API RajaOngkir (Komerce)
                                        </strong>
                                        @if(config('rajaongkir.api_key'))
                                            <span class="badge rounded-pill px-2.5 py-1" style="background: #dcfce7; color: #15803d; font-size: 11px;">Terhubung (Sandbox/Starter)</span>
                                        @else
                                            <span class="badge rounded-pill px-2.5 py-1" style="background: #fef3c7; color: #b45309; font-size: 11px;">Mode Fallback</span>
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size: 11.5px;">
                                        Origin: <strong>{{ config('rajaongkir.origin_label') }}</strong><br>
                                        Kurir Aktif: <strong class="text-uppercase">{{ str_replace(':', ', ', config('rajaongkir.couriers')) }}</strong><br>
                                        Berat Estimasi: <strong>{{ config('rajaongkir.item_weight', 350) }} gram / item</strong>
                                    </div>
                                </div>

                                <div class="alert alert-light border mb-0" style="font-size: 12px;">
                                    <i class="fa fa-info-circle text-primary mr-1"></i> Ongkos kirim saat checkout dihitung otomatis secara real-time mengikuti tarif resmi dari <strong>API RajaOngkir</strong>.
                                </div>
                            </div>
                        </div>

                        <!-- Card Rekening Bank -->
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold d-flex align-items-center" style="gap: 8px;">
                                    <span class="d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 8px; background: #fff7ed; color: #f59e0b;">
                                        <i class="fa-solid fa-building-columns" style="font-size: 13px;"></i>
                                    </span>
                                    <span>Informasi Rekening Toko</span>
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="rekening_bank">Daftar Rekening Bank</label>
                                    <textarea name="rekening_bank" id="rekening_bank" rows="3" class="form-control @error('rekening_bank') is-invalid @enderror" placeholder="Contoh: BCA: 123456789 a.n PT Coza Store&#10;Mandiri: 987654321 a.n PT Coza Store">{{ old('rekening_bank', $setting->rekening_bank) }}</textarea>
                                    <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Catatan referensi rekening resmi toko.</small>
                                    @error('rekening_bank')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="reset" class="btn btn-light border">
                                    <i class="fa-solid fa-arrows-rotate mr-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary font-weight-bold px-4">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
