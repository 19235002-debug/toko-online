@extends('template.main')
@section('title', 'Edit Voucher')
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
                        <li class="breadcrumb-item"><a href="{{ route('voucher.index') }}">Voucher</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-pen-to-square mr-1 text-warning"></i> Edit Voucher: {{ $voucher->kode_voucher }}
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('voucher.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('voucher.update', $voucher->id_voucher) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Kode Voucher <span class="text-danger">*</span></label>
                                        <input type="text" name="kode_voucher" class="form-control text-uppercase @error('kode_voucher') is-invalid @enderror" value="{{ old('kode_voucher', $voucher->kode_voucher) }}" required style="text-transform: uppercase;">
                                        @error('kode_voucher') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Nama Promo <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_voucher" class="form-control @error('nama_voucher') is-invalid @enderror" value="{{ old('nama_voucher', $voucher->nama_voucher) }}" required>
                                        @error('nama_voucher') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Tipe Potongan <span class="text-danger">*</span></label>
                                        <select name="tipe" id="tipe_voucher" class="form-control" onchange="toggleMaxDiskon()">
                                            <option value="nominal" {{ old('tipe', $voucher->tipe) == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                                            <option value="persen" {{ old('tipe', $voucher->tipe) == 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Nilai Potongan <span class="text-danger">*</span></label>
                                        <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" value="{{ old('nilai', $voucher->nilai) }}" min="1" required>
                                        @error('nilai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 form-group" id="group_max_diskon">
                                        <label>Maksimal Diskon (Rp)</label>
                                        <input type="number" name="maksimal_diskon" class="form-control" value="{{ old('maksimal_diskon', $voucher->maksimal_diskon) }}" min="0">
                                        <small class="text-muted">Khusus tipe persentase.</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Minimal Belanja (Rp)</label>
                                        <input type="number" name="minimal_belanja" class="form-control" value="{{ old('minimal_belanja', $voucher->minimal_belanja) }}" min="0">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Kuota Pemakaian</label>
                                        <input type="number" name="kuota" class="form-control" value="{{ old('kuota', $voucher->kuota) }}" placeholder="Tak terbatas" min="1">
                                        <small class="text-muted">Sudah digunakan: {{ $voucher->digunakan }}x</small>
                                    </div>
                                    <div class="col-md-4 form-group d-flex align-items-end">
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="statusVoucher" name="status" value="1" {{ old('status', $voucher->status) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-bold" for="statusVoucher">Aktifkan Voucher</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Tanggal Mulai (Opsional)</label>
                                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $voucher->tanggal_mulai ? $voucher->tanggal_mulai->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Tanggal Berakhir (Opsional)</label>
                                        <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $voucher->tanggal_selesai ? $voucher->tanggal_selesai->format('Y-m-d') : '') }}">
                                        @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-warning text-white">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Perbarui Voucher
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
    function toggleMaxDiskon() {
        var tipe = document.getElementById('tipe_voucher').value;
        document.getElementById('group_max_diskon').style.display = (tipe === 'persen') ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', toggleMaxDiskon);
</script>

@endsection

