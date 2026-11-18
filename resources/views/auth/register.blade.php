<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru | Kelompok 3 Fashion Store</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend-assets/images/icons/favicon.png') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap">
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .register-box {
            width: 460px;
            max-width: 92%;
            margin: 40px auto;
        }
        .card {
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: none;
        }
        .card-header {
            border-bottom: 1px solid #edf2f7;
            padding: 24px 20px 16px;
            background: #fff;
            border-radius: 14px 14px 0 0 !important;
        }
        .form-control {
            border-radius: 8px;
            height: 44px;
            font-size: 14px;
        }
        .input-group-text {
            border-radius: 0 8px 8px 0;
            background: #f8fafc;
        }
        .btn-primary {
            background-color: #717fe0;
            border-color: #717fe0;
            border-radius: 8px;
            height: 44px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #5d6bc9;
            border-color: #5d6bc9;
        }
    </style>
</head>

<body class="hold-transition register-page">
    @include('sweetalert::alert')

    <div class="register-box">
        <div class="card">
            <div class="card-header text-center">
                <a href="/" class="d-inline-block text-dark text-decoration-none">
                    <span style="font-size: 26px; font-weight: 800; color: #222; letter-spacing: -0.5px;">
                        Kelompok <span style="color: #717fe0;">3</span>
                    </span>
                    <div class="text-muted small font-weight-normal mt-1">Fashion Store & Catalog</div>
                </a>
            </div>
            <div class="card-body p-4">
                <h5 class="text-center font-weight-bold text-dark mb-1">Buat Akun Pelanggan</h5>
                <p class="text-center text-muted small mb-4">Daftar sekarang untuk kemudahan belanja & melacak pesanan</p>

                <form class="needs-validation" novalidate action="/register" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                placeholder="Contoh: Budi Santoso" value="{{ old('nama') }}" required autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user text-muted"></span>
                                </div>
                            </div>
                            @error('nama')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="nama@email.com" value="{{ old('email') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope text-muted"></span>
                                </div>
                            </div>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Nomor WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="tel" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                placeholder="Contoh: 081234567890" value="{{ old('no_hp') }}" inputmode="numeric" pattern="[0-9]{10,15}" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone text-muted"></span>
                                </div>
                            </div>
                            @error('no_hp')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <small class="text-muted" style="font-size: 11px;">Wajib angka (10-15 digit) untuk konfirmasi pesanan & resi.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Alamat Pengiriman Lengkap <span class="text-danger">*</span></label>
                        <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" style="height: auto; padding-top: 10px;" placeholder="Jalan, No Rumah, RT/RW, Kecamatan, Kota/Kabupaten, Kode Pos" minlength="10" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="small font-weight-bold text-dark mb-1">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Min. 6 karakter"
                                    required>
                                @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="small font-weight-bold text-dark mb-1">Ulangi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="passwordConfirm" id="passwordConfirm"
                                    class="form-control @error('passwordConfirm') is-invalid @enderror"
                                    placeholder="Ketik ulang" required>
                                @error('passwordConfirm')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block my-3">
                        <i class="fas fa-user-plus mr-1"></i> Daftar Sekarang
                    </button>

                    <div class="text-center small text-muted">
                        Sudah punya akun? <a href="/login" class="font-weight-bold" style="color: #717fe0;">Masuk di sini</a>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light text-center py-3 border-top" style="border-radius: 0 0 14px 14px;">
                <a href="/" class="small text-muted text-decoration-none">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Halaman Utama Toko
                </a>
            </div>
        </div>
    </div>

    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/dist/js/adminlte.min.js"></script>
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
