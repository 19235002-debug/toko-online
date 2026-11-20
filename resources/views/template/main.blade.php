<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ store_setting()->nama_toko ?: 'Coza Store' }} Admin</title>
    <link rel="icon" type="image/png" href="{{ store_favicon_url() }}">

    <!-- Google Font: Outfit (TailAdmin) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 4 (for existing grid, forms, modals & DataTables BS4) -->
    <link rel="stylesheet" href="/frontend-assets/vendor/bootstrap/css/bootstrap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- TailAdmin Compiled Styles -->
    <link rel="stylesheet" href="/tailadmin/style.css">
    <!-- TailAdmin + Bootstrap Compatibility Overrides -->
    <link rel="stylesheet" href="/tailadmin/admin-compat.css">
</head>

<body
    x-data="{ page: '{{ request()->segment(1) ?: 'dashboard' }}', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode')) ?? false;
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
    "
    :class="{ 'dark bg-gray-900': darkMode === true }"
    class="bg-gray-50 text-gray-800 antialiased"
>
    @include('sweetalert::alert')

    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Sidebar Backdrop -->
        <div
            x-cloak
            x-show="sidebarToggle"
            class="fixed inset-0 z-99 bg-gray-900/50 backdrop-blur-xs xl:hidden"
            aria-hidden="true"
            @click="sidebarToggle = false"
            x-transition.opacity
        ></div>

        <!-- ===== TailAdmin Sidebar Start ===== -->
        @php
            $sidebarPendingCount = (auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin']))
                ? \App\Models\Order::whereIn('status', ['pending', 'diproses'])->count()
                : 0;
        @endphp
        <aside
            x-cloak
            :class="sidebarToggle ? 'translate-x-0 xl:w-22.5 ta-sidebar-collapsed' : '-translate-x-full xl:translate-x-0'"
            class="sidebar ta-sidebar fixed top-0 left-0 z-9999 flex h-screen w-72.5 flex-col px-4 transition-all duration-300 xl:static"
            @click.outside="if (window.innerWidth < 1280) sidebarToggle = false"
            aria-label="sidebar navigation"
        >
            <!-- Sidebar Brand Header -->
            <div :class="sidebarToggle ? 'justify-center' : 'justify-between'" class="flex items-center gap-3 border-b border-gray-100 py-4.5 dark:border-gray-800">
                <a href="/dashboard" class="flex items-center gap-3">
                    @if(store_logo_url())
                        <img src="{{ store_logo_url() }}" alt="{{ store_setting()->nama_toko ?: 'Coza Store' }}" class="h-10 max-w-[140px] object-contain">
                    @else
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-blue-500 text-white font-bold shadow-sm">
                            {{ strtoupper(substr(store_setting()->nama_toko ?: 'C', 0, 1)) }}
                        </div>
                        <div :class="sidebarToggle ? 'xl:hidden' : ''" class="leading-tight">
                            <div class="text-base font-bold tracking-tight text-gray-900 dark:text-white">
                                {{ store_setting()->nama_toko ?: 'Coza Store' }}
                            </div>
                            <div class="text-[11px] font-medium text-gray-400">Admin Panel</div>
                        </div>
                    @endif
                </a>
                <button
                    type="button"
                    @click="sidebarToggle = false"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 xl:hidden dark:hover:bg-gray-800"
                    aria-label="Tutup Sidebar"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Sidebar Menu -->
            <div class="ta-sidebar-scroll py-3">
                <nav aria-label="sidebar navigation" class="pb-4">
                    <!-- Ringkasan -->
                    <div class="ta-sidebar-section-title" :class="sidebarToggle ? 'xl:text-center xl:px-0' : ''">
                        <span :class="sidebarToggle ? 'xl:hidden' : ''">Ringkasan</span>
                        <span :class="sidebarToggle ? 'hidden xl:inline' : 'hidden'">&bull;</span>
                    </div>

                    <ul class="flex flex-col gap-1 list-unstyled mb-1">
                        <li>
                            <a href="/dashboard" title="Dashboard" class="ta-nav-link {{ request()->is('dashboard*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Dashboard</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Katalog & Konten -->
                    <div class="ta-sidebar-section-title" :class="sidebarToggle ? 'xl:text-center xl:px-0' : ''">
                        <span :class="sidebarToggle ? 'xl:hidden' : ''">Katalog &amp; Konten</span>
                        <span :class="sidebarToggle ? 'hidden xl:inline' : 'hidden'">&bull;</span>
                    </div>
                    <ul class="flex flex-col gap-1 list-unstyled mb-1">
                        <li>
                            <a href="/barang" title="Data Produk" class="ta-nav-link {{ request()->is('barang*') || request()->is('produk*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-box"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Data Produk</span>
                            </a>
                        </li>
                        <li>
                            <a href="/kategori" title="Data Kategori" class="ta-nav-link {{ request()->is('kategori*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-tags"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Data Kategori</span>
                            </a>
                        </li>
                        <li>
                            <a href="/banner" title="Data Banner" class="ta-nav-link {{ request()->is('banner*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-panorama"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Data Banner</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Transaksi & Pemasaran -->
                    <div class="ta-sidebar-section-title" :class="sidebarToggle ? 'xl:text-center xl:px-0' : ''">
                        <span :class="sidebarToggle ? 'xl:hidden' : ''">Transaksi &amp; Promo</span>
                        <span :class="sidebarToggle ? 'hidden xl:inline' : 'hidden'">&bull;</span>
                    </div>
                    <ul class="flex flex-col gap-1 list-unstyled mb-1">
                        <li>
                            <a href="/order" title="Data Pesanan" class="ta-nav-link {{ request()->is('order*') || request()->is('pesanan*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-receipt"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Data Pesanan</span>
                                @if($sidebarPendingCount > 0)
                                    <span class="ta-nav-badge" :class="sidebarToggle ? 'xl:hidden' : ''">{{ $sidebarPendingCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="/laporan" title="Laporan Penjualan" class="ta-nav-link {{ request()->is('laporan*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-chart-line"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Laporan Penjualan</span>
                            </a>
                        </li>
                        <li>
                            <a href="/review" title="Ulasan & Rating" class="ta-nav-link {{ request()->is('review*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-star"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Ulasan &amp; Rating</span>
                            </a>
                        </li>
                        <li>
                            <a href="/voucher" title="Voucher & Promo" class="ta-nav-link {{ request()->is('voucher*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-ticket"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Voucher &amp; Promo</span>
                            </a>
                        </li>
                    </ul>

                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <!-- Sistem -->
                    <div class="ta-sidebar-section-title" :class="sidebarToggle ? 'xl:text-center xl:px-0' : ''">
                        <span :class="sidebarToggle ? 'xl:hidden' : ''">Sistem</span>
                        <span :class="sidebarToggle ? 'hidden xl:inline' : 'hidden'">&bull;</span>
                    </div>
                    <ul class="flex flex-col gap-1 list-unstyled mb-3">
                        <li>
                            <a href="/setting" title="Pengaturan Toko" class="ta-nav-link {{ request()->is('setting') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-sliders"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Pengaturan Toko</span>
                            </a>
                        </li>
                        <li>
                            <a href="/setting/metode" title="Opsi Kurir &amp; Pembayaran" class="ta-nav-link {{ request()->is('setting/metode*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-toggle-on text-indigo-500"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Kurir &amp; Pembayaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="/user" title="Data User" class="ta-nav-link {{ request()->is('user*') ? 'is-active' : '' }}">
                                <span class="ta-nav-icon"><i class="fa-solid fa-users"></i></span>
                                <span :class="sidebarToggle ? 'xl:hidden' : ''">Data User</span>
                            </a>
                        </li>
                    </ul>
                    @endif

                    <!-- Bottom Actions -->
                    <div class="mt-auto border-t border-gray-100 pt-3 dark:border-gray-800">
                        <ul class="flex flex-col gap-1 list-unstyled mb-0">
                            <li>
                                <a href="/" target="_blank" title="Lihat Toko" class="ta-nav-link">
                                    <span class="ta-nav-icon"><i class="fa-solid fa-store"></i></span>
                                    <span :class="sidebarToggle ? 'xl:hidden' : ''">Lihat Toko</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" title="Logout" class="log-out ta-nav-link text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                    <span class="ta-nav-icon bg-red-50 text-red-500 dark:bg-red-500/15 dark:text-red-400"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                                    <span :class="sidebarToggle ? 'xl:hidden' : ''">Logout</span>
                                </a>
                                <form action="/logout" method="POST" id="logging-out" class="hidden">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </aside>
        <!-- ===== TailAdmin Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
            <!-- ===== TailAdmin Header Start ===== -->
            <header class="ta-header flex w-full shrink-0">
                <div class="flex grow items-center justify-between gap-3 px-4 py-3 xl:px-6">
                    <!-- Left: Sidebar Toggle, Page Context & Quick Search -->
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            @click.stop="sidebarToggle = !sidebarToggle"
                            aria-label="Toggle Sidebar"
                            class="ta-header-btn"
                        >
                            <i class="fa-solid fa-bars text-sm"></i>
                        </button>

                        <div class="hidden sm:flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-400">Admin</span>
                            <span class="text-xs text-gray-300 dark:text-gray-600">/</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-white">@yield('title')</span>
                        </div>

                        <form action="/order" method="GET" class="hidden lg:block ml-2">
                            <div class="ta-header-search">
                                <i class="fa-solid fa-magnifying-glass text-xs text-gray-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode order / pembeli...">
                            </div>
                        </form>
                    </div>

                    <!-- Right: Jam Real-time Jakarta, Lihat Toko, Dark Mode, Notification Bell, User Dropdown -->
                    <div class="flex items-center gap-2.5">
                        <div class="hidden xl:flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800 text-xs font-semibold text-gray-700 dark:text-gray-300" title="Waktu Real-time Jakarta (WIB / UTC+7)">
                            <i class="fa-regular fa-clock text-indigo-500"></i>
                            <span id="live-jakarta-clock">--:-- WIB</span>
                        </div>

                        <a href="/" target="_blank" class="ta-store-btn hidden md:inline-flex" title="Buka Halaman Toko">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs" style="color: #465fff;"></i>
                            <span>Lihat Toko</span>
                        </a>

                        <!-- Dark Mode Toggle -->
                        <button
                            type="button"
                            @click.prevent="darkMode = !darkMode"
                            title="Ubah Tema Gelap/Terang"
                            class="ta-header-btn"
                        >
                            <i x-show="!darkMode" class="fa-regular fa-moon text-sm"></i>
                            <i x-show="darkMode" x-cloak class="fa-regular fa-sun text-sm text-yellow-400"></i>
                        </button>

                        <!-- Notification Bell Dropdown -->
                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin']))
                        @php
                            $notifOrders = \App\Models\Order::whereIn('status', ['pending', 'diproses'])
                                ->latest('id_order')
                                ->take(6)
                                ->get();
                            $notifCount = \App\Models\Order::whereIn('status', ['pending', 'diproses'])->count();
                        @endphp
                        <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                            <button
                                type="button"
                                @click.prevent="dropdownOpen = !dropdownOpen"
                                title="Notifikasi Pesanan"
                                class="ta-header-btn"
                            >
                                <i class="fa-regular fa-bell text-sm"></i>
                                @if($notifCount > 0)
                                <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle bg-danger text-white font-weight-bold shadow-sm" style="top: -5px; right: -5px; min-width: 19px; height: 19px; font-size: 10px; padding: 0 4px;">
                                    {{ $notifCount }}
                                </span>
                                @endif
                            </button>

                            <div
                                x-cloak
                                x-show="dropdownOpen"
                                x-transition
                                class="absolute right-0 mt-2 w-80 rounded-2xl border border-gray-200 bg-white p-3 shadow-xl dark:border-gray-800 dark:bg-gray-900"
                            >
                                <div class="mb-2 flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                                    <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ $notifCount }} Pesanan Perlu Perhatian
                                    </span>
                                </div>
                                <div class="max-h-72 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
                                    @forelse($notifOrders as $no)
                                    <a href="{{ route('order.show', $no->id_order) }}" class="block rounded-lg px-2 py-2.5 transition hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <div class="flex items-center justify-between">
                                            <strong class="text-xs font-semibold text-gray-900 dark:text-white">{{ $no->kode_order }}</strong>
                                            <span class="badge {{ $no->status === 'pending' ? 'badge-warning' : 'badge-info' }}" style="font-size: 10px;">
                                                {{ ucfirst($no->status) }}
                                            </span>
                                        </div>
                                        <div class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400">
                                            {{ $no->nama_customer }} &bull; Rp {{ number_format($no->total_harga, 0, ',', '.') }}
                                        </div>
                                    </a>
                                    @empty
                                    <div class="py-4 text-center text-xs text-gray-500 dark:text-gray-400">
                                        Tidak ada pesanan pending / diproses.
                                    </div>
                                    @endforelse
                                </div>
                                <a href="/order" class="mt-2 block rounded-lg border border-gray-200 py-2 text-center text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                    Lihat Semua Pesanan
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- User Dropdown -->
                        @if(auth()->check())
                        <div class="relative" x-data="{ userDropdownOpen: false }" @click.outside="userDropdownOpen = false">
                            <button
                                type="button"
                                @click.prevent="userDropdownOpen = !userDropdownOpen"
                                class="ta-user-trigger"
                            >
                                <div class="relative shrink-0">
                                    @if(auth()->user()->user_image)
                                    <img src="{{ asset('storage/' . auth()->user()->user_image) }}" class="h-8 w-8 rounded-full object-cover" alt="User Image">
                                    @else
                                    <img src="/assets/dist/img/user2-160x160.jpg" class="h-8 w-8 rounded-full object-cover" alt="Default User Image">
                                    @endif
                                    <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-white bg-emerald-500 dark:border-slate-800"></span>
                                </div>
                                <div class="hidden text-left sm:block">
                                    <div class="ta-user-name">{{ auth()->user()->nama }}</div>
                                    <div class="ta-user-role">{{ ucfirst(auth()->user()->role) }}</div>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[11px] text-gray-400"></i>
                            </button>

                            <div
                                x-cloak
                                x-show="userDropdownOpen"
                                x-transition
                                class="absolute right-0 mt-2 w-56 rounded-2xl border border-gray-200 bg-white p-2 shadow-xl dark:border-gray-800 dark:bg-gray-900"
                            >
                                <div class="border-b border-gray-100 px-3 py-2 dark:border-gray-800">
                                    <div class="text-sm font-semibold text-gray-800 dark:text-white">{{ auth()->user()->nama }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                        <i class="fa-solid fa-user-gear w-4 text-gray-500"></i> Edit Profil
                                    </a>
                                    <a href="/" target="_blank" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                        <i class="fa-solid fa-store w-4 text-gray-500"></i> Lihat Toko
                                    </a>
                                </div>
                                <div class="border-t border-gray-100 pt-1 dark:border-gray-800">
                                    <a href="javascript:void(0)" class="log-out flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </header>
            <!-- ===== TailAdmin Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main id="main-content" class="flex-1">
                <div class="mx-auto max-w-(--breakpoint-2xl) p-4 pb-16 md:p-6 md:pb-10">
                    @yield('content')
                </div>
            </main>
            <!-- ===== Main Content End ===== -->

            <!-- Footer -->
            <footer class="border-t border-gray-200 bg-white px-6 py-4 text-xs text-gray-500 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400">
                <div class="flex flex-col items-center justify-between gap-2 sm:flex-row">
                    <div>
                        &copy; {{ date('Y') }} <strong>Kelompok 3 Fashion Store</strong>. All rights reserved.
                    </div>
                    <div>
                        Admin Panel powered by TailAdmin
                    </div>
                </div>
            </footer>
        </div>
        <!-- ===== Content Area End ===== -->
    </div>

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables & Plugins -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <!-- TailAdmin Bundle (Alpine.js & UI interactions) -->
    <script src="/tailadmin/bundle.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            var $dt = $('#example1');
            if ($dt.length && !$.fn.DataTable.isDataTable('#example1')) {
                var allowSearch = $dt.data('dt-searching') !== false;
                $dt.DataTable({
                    responsive: true,
                    autoWidth: false,
                    searching: allowSearch,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 data",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        zeroRecords: "Data tidak ditemukan",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        }
                    }
                });
            }
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
            });
        });

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

        $(document).on('click', '.log-out', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#465fff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logging-out').submit();
                }
            });
        });
        // Real-time Jakarta Clock (WIB / UTC+7)
        function updateJakartaClock() {
            try {
                var now = new Date();
                var timeStr = new Intl.DateTimeFormat('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).format(now);
                var dateStr = new Intl.DateTimeFormat('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                }).format(now);
                $('#live-jakarta-clock').text(dateStr + ' • ' + timeStr + ' WIB');
            } catch (e) {}
        }
        setInterval(updateJakartaClock, 1000);
        updateJakartaClock();
    </script>

</body>

</html>
