<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Kelompok 3</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">
    
    <style>
        /* Responsive Optimizations for Admin Panel */
        @media (max-width: 767.98px) {
            .content-header h1 {
                font-size: 1.4rem;
            }
            .content-header .breadcrumb {
                float: left !important;
                margin-top: 5px;
                padding-left: 0;
            }
            .main-header .navbar-nav .nav-link {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .card-header .btn {
                font-size: 0.8rem;
                padding: 0.35rem 0.6rem;
            }
            .card-title {
                font-size: 1rem;
            }
            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.875rem;
                white-space: nowrap;
                vertical-align: middle;
            }
            .small-box .inner h3 {
                font-size: 1.6rem;
            }
            .small-box .inner p {
                font-size: 0.9rem;
            }
            .card-footer {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                justify-content: flex-end;
            }
        }
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }
        .card-header::after {
            display: none !important;
        }
        .card-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
        }
        .card-header .card-tools,
        .card-header > div:last-child {
            margin-left: auto !important;
            float: none !important;
        }

        /* Consistent Action Buttons Styling */
        .btn-action-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px !important;
            font-size: 0.8125rem;
            font-weight: 500;
            padding: 0.32rem 0.65rem;
            line-height: 1.4;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
            white-space: nowrap;
        }
        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.12) !important;
        }
        .btn-action:active {
            transform: translateY(0);
        }
        .btn-action-warning {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #ffffff !important;
        }
        .btn-action-warning:hover {
            background-color: #d97706 !important;
            border-color: #d97706 !important;
            color: #ffffff !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">

    @include('sweetalert::alert')

    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto align-items-center">
                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin']))
                @php
                    $notifOrders = \App\Models\Order::whereIn('status', ['pending', 'diproses'])
                        ->latest('id_order')
                        ->take(6)
                        ->get();
                    $notifCount = \App\Models\Order::whereIn('status', ['pending', 'diproses'])->count();
                @endphp
                <li class="nav-item dropdown mr-3">
                    <a class="nav-link position-relative" data-toggle="dropdown" href="#" title="Notifikasi Pesanan" style="cursor: pointer;">
                        <i class="far fa-bell" style="font-size: 18px;"></i>
                        @if($notifCount > 0)
                        <span class="badge badge-danger navbar-badge" style="font-size: 10px; right: 2px; top: 4px;">{{ $notifCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-sm border-0" style="min-width: 280px; border-radius: 10px;">
                        <span class="dropdown-header font-weight-bold text-dark">
                            {{ $notifCount }} Pesanan Perlu Perhatian
                        </span>
                        <div class="dropdown-divider"></div>
                        @forelse($notifOrders as $no)
                        <a href="{{ route('order.show', $no->id_order) }}" class="dropdown-item py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-dark" style="font-size: 12.5px;">{{ $no->kode_order }}</strong>
                                <span class="badge {{ $no->status === 'pending' ? 'badge-warning' : 'badge-info' }}" style="font-size: 10px;">{{ ucfirst($no->status) }}</span>
                            </div>
                            <div class="text-muted small text-truncate">
                                {{ $no->nama_customer }} &bull; Rp {{ number_format($no->total_harga, 0, ',', '.') }}
                            </div>
                        </a>
                        <div class="dropdown-divider my-0"></div>
                        @empty
                        <div class="dropdown-item text-center text-muted small py-3">
                            Tidak ada pesanan pending / diproses.
                        </div>
                        @endforelse
                        <a href="/order" class="dropdown-item dropdown-footer text-primary font-weight-bold text-center">
                            Lihat Semua Pesanan
                        </a>
                    </div>
                </li>
                @endif

                @if(auth()->check())
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center p-0" data-toggle="dropdown" href="#" style="cursor: pointer;">
                        @if(auth()->user()->user_image)
                        <img src="{{ asset('storage/' . auth()->user()->user_image) }}" class="img-circle elevation-1 mr-1" alt="User Image" style="width: 32px; height: 32px; object-fit: cover;">
                        @else
                        <img src="/assets/dist/img/user2-160x160.jpg" class="img-circle elevation-1 mr-1" alt="Default User Image" style="width: 32px; height: 32px; object-fit: cover;">
                        @endif
                        <span class="d-none d-md-inline text-dark font-weight-bold ml-1 mr-1">{{ auth()->user()->nama }}</span>
                        <i class="fas fa-angle-down text-muted"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="min-width: 190px; border-radius: 8px;">
                        <div class="dropdown-header text-center">
                            <strong class="text-dark">{{ auth()->user()->nama }}</strong>
                            <div class="small text-muted">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item text-dark">
                            <i class="fas fa-user-gear mr-2 text-secondary"></i> Edit Profil
                        </a>
                        <a href="/" target="_blank" class="dropdown-item text-dark">
                            <i class="fas fa-store mr-2 text-secondary"></i> Lihat Toko
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="document.getElementById('logging-out').submit();" class="dropdown-item text-dark">
                            <i class="fas fa-arrow-right-from-bracket mr-2 text-secondary"></i> Logout
                        </a>
                    </div>
                </li>
                @endif
            </ul>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/dashboard" class="brand-link">
                <img src="/assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Kelompok 3</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image mt-2">
                        @if(auth()->check() && auth()->user()->user_image)
                        <img src="{{ asset('storage/' . auth()->user()->user_image) }}" class="img-circle elevation-2" alt="User Image" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <img src="/assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="Default User Image" style="width: 50px; height: 50px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="info ml-2">
                        @if(auth()->check())
                        <a href="{{ route('profile.edit') }}" class="d-block">{{ auth()->user()->nama }}</a>
                        <span class="badge 
            @if(auth()->user()->role == 'admin') bg-danger 
            @elseif(auth()->user()->role == 'user') bg-primary 
            @else bg-secondary 
            @endif">
                            <i class="fas fa-user-tag"></i> {{ ucfirst(auth()->user()->role) }}
                        </span>
                        @else
                        <a href="#" class="d-block">Guest</a>
                        @endif
                    </div>
                </div>


                <!-- SidebarSearch Form -->
                {{-- <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div> --}}

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="/dashboard" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-gauge-high"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/barang" class="nav-link {{ request()->is('barang*') || request()->is('produk*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-box"></i>
                                <p>Data Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/kategori" class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-tags"></i>
                                <p>Data Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/banner" class="nav-link {{ request()->is('banner*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-panorama"></i>
                                <p>Data Banner</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/order" class="nav-link {{ request()->is('order*') || request()->is('pesanan*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-receipt"></i>
                                <p>Data Pesanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/laporan" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-chart-line"></i>
                                <p>Laporan Penjualan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/review" class="nav-link {{ request()->is('review*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-star"></i>
                                <p>Ulasan & Rating</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/voucher" class="nav-link {{ request()->is('voucher*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-ticket"></i>
                                <p>Voucher & Promo</p>
                            </a>
                        </li>

                        @if(auth()->check() && auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a href="/setting" class="nav-link {{ request()->is('setting*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-sliders"></i>
                                <p>Pengaturan Toko</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/user" class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-users"></i>
                                <p>Data User</p>
                            </a>
                        </li>
                        @endif

                        <hr class="dropdown-divider mt-1">
                        </hr>
                        <li class="nav-item mt-2">
                            <a class="log-out ml-3" href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-arrow-right-from-bracket"></i>
                                Logout
                                <form action="/logout" method="POST" id="logging-out">
                                    @csrf
                                </form>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        @yield('content')

        <!-- Content Wrapper. Contains page content -->
        {{-- content here --}}
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">

            </div>
            <!-- Default to the left -->
            <strong>
                &copy; {{ date('Y') }}
                <a href="https://instagram.com/guntur713" target="_blank">
                    <i class="fab fa-instagram"></i> @Guntur713
                </a>.
            </strong>
            All right reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/dist/js/adminlte.min.js"></script>
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            var url = window.location;
            // for single sidebar menu
            $('ul.nav-sidebar a').filter(function() {
                return this.href == url;
            }).addClass('active');

            // for sidebar menu and treeview
            $('ul.nav-treeview a').filter(function() {
                    return this.href == url;
                }).parentsUntil(".nav-sidebar > .nav-treeview")
                .css({
                    'display': 'block'
                })
                .addClass('menu-open').prev('a')
                .addClass('active');
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#example1').DataTable({
                responsive: true
            });

        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $(document).on('click', '#btn-delete, .btn-delete', function(e) {
            e.preventDefault();
            var form = $(this).closest("form");
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    </script>

    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
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

    <script>
        $(".log-out").on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7367f0',
                cancelButtonColor: '#82868b',
                confirmButtonText: 'Yes, Log Out !'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logging-out').submit()
                }
            })
        });
    </script>

</body>

</html>