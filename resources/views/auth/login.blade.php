<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk ke Akun | Kelompok 3 Fashion Store</title>
    <link rel="icon" type="image/png" href="{{ store_favicon_url() }}">
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
        .login-box {
            width: 420px;
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

<body class="hold-transition login-page">

    @include('sweetalert::alert')

    <div class="login-box">
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
                <h5 class="text-center font-weight-bold text-dark mb-1">Selamat Datang!</h5>
                <p class="text-center text-muted small mb-4">Silakan masuk menggunakan email dan password Anda</p>

                <form class="needs-validation" novalidate action="/login" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Email</label>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope text-muted"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password Anda" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock text-muted"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                            <label class="custom-control-label small text-muted" for="remember">Ingat Saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small font-weight-bold" style="color: #717fe0;">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-sign-in-alt mr-1"></i> Masuk Sekarang
                    </button>

                    <div class="text-center small text-muted">
                        Belum punya akun? <a href="/register" class="font-weight-bold" style="color: #717fe0;">Daftar di sini</a>
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
