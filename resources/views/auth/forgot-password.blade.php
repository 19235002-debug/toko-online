<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password | Kelompok 3 Fashion Store</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend-assets/images/icons/favicon.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap">
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .login-box {
            width: 440px;
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
        }
        .btn-primary:hover {
            background-color: #5b69c7;
            border-color: #5b69c7;
        }
    </style>
</head>

<body class="hold-transition login-page">
    @include('sweetalert::alert')

    <div class="login-box">
        <div class="card">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h3 font-weight-bold text-dark" style="text-decoration: none;">
                    FASHION <span style="color: #717fe0;">STORE</span>
                </a>
                <p class="text-muted small mb-0 mt-1">Atur ulang password akun Anda</p>
            </div>
            <div class="card-body p-4">
                @if (session('status'))
                    <div class="alert alert-success small">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
                    </div>
                @endif

                @if (session('reset_link_demo'))
                    <div class="alert alert-info small">
                        <strong>Tautan Reset Langsung:</strong><br>
                        <a href="{{ session('reset_link_demo') }}" class="font-weight-bold" style="word-break: break-all;">
                            Klik di sini untuk membuat password baru
                        </a>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger small">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="small font-weight-bold text-dark">Alamat Email Terdaftar</label>
                        <div class="input-group">
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nama@email.com" required autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope text-muted"></span></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Tautan Reset
                    </button>
                </form>

                <div class="text-center mt-2">
                    <a href="{{ route('login') }}" class="small text-muted">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>

