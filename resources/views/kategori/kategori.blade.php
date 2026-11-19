@extends('template.main')
@section('title', 'Manajemen Kategori & Sub-Kategori')
@section('content')

@php
    $totalMain = $mainKategoris->count();
    $totalSub = $mainKategoris->sum(fn($m) => $m->children->count());
    $totalProd = $mainKategoris->sum('barangs_count');
@endphp

<div class="content-wrapper" x-data="{ activeMain: 'all', searchQuery: '' }">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-tags mr-2 text-primary"></i>@yield('title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Kategori</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Summary Strip -->
            <div class="row mb-3">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Kategori Utama</div>
                                <div class="h4 font-weight-bold mb-0 mt-1">{{ $totalMain }}</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center rounded-xl" style="width: 44px; height: 44px; background: #eef2ff; color: #465fff;">
                                <i class="fa-solid fa-layer-group fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Total Sub-Kategori</div>
                                <div class="h4 font-weight-bold mb-0 mt-1">{{ $totalSub }}</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center rounded-xl" style="width: 44px; height: 44px; background: #ecfdf5; color: #10b981;">
                                <i class="fa-solid fa-sitemap fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Total Produk</div>
                                <div class="h4 font-weight-bold mb-0 mt-1">{{ $totalProd }}</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center rounded-xl" style="width: 44px; height: 44px; background: #fff7ed; color: #f59e0b;">
                                <i class="fa-solid fa-boxes-stacked fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fa-solid fa-sitemap mr-1 text-primary"></i> Struktur Kategori &amp; Sub-Kategori
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm btn-action">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Kategori Utama
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filter Pills & Search -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom" style="gap: 12px;">
                        <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                            <button type="button"
                                @click="activeMain = 'all'"
                                :class="activeMain === 'all' ? 'btn-primary' : 'btn-light border text-dark'"
                                class="btn btn-sm rounded-pill px-3 font-weight-bold" style="font-size: 12.5px;">
                                Semua ({{ $totalMain }})
                            </button>
                            @foreach($mainKategoris as $tab)
                            <button type="button"
                                @click="activeMain = '{{ $tab->id_kategori }}'"
                                :class="activeMain === '{{ $tab->id_kategori }}' ? 'btn-primary' : 'btn-light border text-dark'"
                                class="btn btn-sm rounded-pill px-3 font-weight-bold" style="font-size: 12.5px;">
                                {{ $tab->nama_kategori }} ({{ $tab->children->count() }})
                            </button>
                            @endforeach
                        </div>

                        <div style="width: 260px; max-width: 100%;">
                            <div class="input-group input-group-sm">
                                <input type="text" x-model="searchQuery" class="form-control" placeholder="Cari sub-kategori...">
                            </div>
                        </div>
                    </div>

                    @forelse($mainKategoris as $main)
                    <div
                        x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }"
                        x-show="activeMain === 'all' || activeMain === '{{ $main->id_kategori }}'"
                        class="card border mb-3 shadow-none"
                        style="border-radius: 14px !important; overflow: hidden;"
                    >
                        <div class="card-header py-3 px-3 d-flex justify-content-between align-items-center flex-wrap" style="background: #f8fafc !important; gap: 10px;">
                            <div class="d-flex align-items-center" style="gap: 12px; cursor: pointer;" @click="open = !open">
                                <button type="button" class="btn btn-light btn-sm border d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 8px; padding: 0;">
                                    <i class="fa-solid text-muted" :class="open || searchQuery.trim() !== '' ? 'fa-chevron-down' : 'fa-chevron-right'" style="font-size: 11px;"></i>
                                </button>
                                @if($main->gambar)
                                    <img src="{{ asset('storage/' . $main->gambar) }}" class="rounded-lg border" style="width: 38px; height: 38px; object-fit: cover;">
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 10px; background: #eef2ff; color: #465fff;">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </span>
                                @endif
                                <div>
                                    <div class="font-weight-bold text-dark" style="font-size: 1rem;">{{ $main->nama_kategori }}</div>
                                    <div class="d-flex flex-wrap align-items-center mt-1" style="gap: 6px;">
                                        <span class="badge rounded-pill px-2 py-1" style="background: #e0e7ff; color: #3730a3; font-size: 11px; font-weight: 600;">
                                            {{ $main->children->count() }} Sub-Kategori
                                        </span>
                                        <span class="badge rounded-pill px-2 py-1" style="background: #f1f5f9; color: #475569; font-size: 11px; font-weight: 600;">
                                            {{ $main->barangs_count }} Produk
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-action-group">
                                <a href="{{ route('kategori.create', ['parent_id' => $main->id_kategori]) }}" class="btn btn-success btn-sm btn-action" style="gap: 5px;">
                                    <i class="fa-solid fa-plus"></i> <span>Tambah Sub-Kategori</span>
                                </a>
                                <a href="{{ route('kategori.edit', $main->id_kategori) }}" class="btn btn-action btn-action-warning btn-sm" style="gap: 5px;">
                                    <i class="fa-solid fa-pen-to-square"></i> <span>Edit</span>
                                </a>
                                <form action="{{ route('kategori.destroy', $main->id_kategori) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori utama {{ $main->nama_kategori }} beserta seluruh sub-kategorinya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-action" style="gap: 5px;">
                                        <i class="fa-solid fa-trash-can"></i> <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body" style="padding: 0 !important;" x-show="open || searchQuery.trim() !== '' || activeMain !== 'all'">
                            @if($main->children->isEmpty())
                                <div class="text-muted small text-center py-4">
                                    Belum ada sub-kategori untuk {{ $main->nama_kategori }}.
                                    <a href="{{ route('kategori.create', ['parent_id' => $main->id_kategori]) }}" class="font-weight-bold">Tambah sekarang</a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 75px;" class="text-center">Urutan</th>
                                                <th style="width: 80px;" class="text-center">Gambar</th>
                                                <th>Nama Sub-Kategori</th>
                                                <th>Slug</th>
                                                <th class="text-center" style="width: 100px;">Produk</th>
                                                <th class="text-center" style="width: 185px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($main->children as $sub)
                                            <tr x-show="searchQuery.trim() === '' || '{{ strtolower($sub->nama_kategori . ' ' . $sub->slug) }}'.includes(searchQuery.toLowerCase())">
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-light border px-2 py-1" style="border-radius: 7px; font-weight: 600;">{{ $sub->urutan }}</span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if($sub->gambar)
                                                        <img src="{{ asset('storage/' . $sub->gambar) }}" alt="{{ $sub->nama_kategori }}" class="border" style="width: 40px; height: 40px; object-fit: cover; border-radius: 10px;">
                                                    @else
                                                        <span class="d-inline-flex align-items-center justify-content-center border text-muted" style="width: 40px; height: 40px; border-radius: 10px; background: #f8fafc;">
                                                            <i class="fa-solid fa-shirt"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="align-middle font-weight-bold">{{ $sub->nama_kategori }}</td>
                                                <td class="align-middle">
                                                    <code class="px-2 py-1 rounded" style="background: #f1f5f9; color: #e11d48; font-size: 12px;">{{ $sub->slug }}</code>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <span class="badge rounded-pill px-2 py-1" style="background: #eef2ff; color: #465fff; font-weight: 700;">
                                                        {{ $sub->sub_barangs_count }}
                                                    </span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="btn-action-group">
                                                        <a href="{{ route('kategori.edit', $sub->id_kategori) }}" class="btn btn-action btn-action-warning btn-sm" style="gap: 5px;">
                                                            <i class="fa-solid fa-pen-to-square"></i> <span>Edit</span>
                                                        </a>
                                                        <form action="{{ route('kategori.destroy', $sub->id_kategori) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus sub-kategori {{ $sub->nama_kategori }}?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm btn-action" style="gap: 5px;">
                                                                <i class="fa-solid fa-trash-can"></i> <span>Hapus</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-tags fa-3x mb-2"></i>
                        <p>Belum ada data kategori.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
