@extends('template.main')
@section('title', 'Manajemen Kategori & Sub-Kategori')
@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-tags mr-2"></i>@yield('title')</h1>
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
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fa-solid fa-sitemap mr-1"></i> Struktur Kategori & Sub-Kategori
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Kategori Utama
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @forelse($mainKategoris as $main)
                    <div class="card border mb-3 shadow-none" style="border-radius: 10px; overflow: hidden;">
                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
                            <div class="d-flex align-items-center" style="gap: 10px;">
                                @if($main->gambar)
                                    <img src="{{ asset('storage/' . $main->gambar) }}" class="rounded border" style="width: 36px; height: 36px; object-fit: cover;">
                                @else
                                    <span class="badge badge-primary px-2 py-2" style="font-size: 0.9rem;">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </span>
                                @endif
                                <div>
                                    <strong style="font-size: 1.05rem;">{{ $main->nama_kategori }}</strong>
                                    <span class="badge badge-info ml-2">{{ $main->children->count() }} Sub-Kategori</span>
                                    <span class="badge badge-secondary ml-1">{{ $main->barangs_count }} Produk</span>
                                </div>
                            </div>

                            <div class="btn-action-group">
                                <a href="{{ route('kategori.create', ['parent_id' => $main->id_kategori]) }}" class="btn btn-success btn-sm btn-action">
                                    <i class="fa-solid fa-plus mr-1"></i> Tambah Sub-Kategori
                                </a>
                                <a href="{{ route('kategori.edit', $main->id_kategori) }}" class="btn btn-warning btn-sm btn-action text-white">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('kategori.destroy', $main->id_kategori) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori utama {{ $main->nama_kategori }} beserta seluruh sub-kategorinya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-action">
                                        <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            @if($main->children->isEmpty())
                                <div class="text-muted small text-center py-3">
                                    Belum ada sub-kategori untuk {{ $main->nama_kategori }}.
                                    <a href="{{ route('kategori.create', ['parent_id' => $main->id_kategori]) }}">Tambah sekarang</a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 60px;" class="text-center">Urutan</th>
                                                <th style="width: 70px;" class="text-center">Gambar</th>
                                                <th>Nama Sub-Kategori</th>
                                                <th>Slug</th>
                                                <th class="text-center">Produk</th>
                                                <th class="text-center" style="width: 170px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($main->children as $sub)
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-light border">{{ $sub->urutan }}</span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if($sub->gambar)
                                                        <img src="{{ asset('storage/' . $sub->gambar) }}" alt="{{ $sub->nama_kategori }}" class="rounded border" style="width: 42px; height: 42px; object-fit: cover;">
                                                    @else
                                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light border text-muted" style="width: 42px; height: 42px;">
                                                            <i class="fa-solid fa-shirt"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="align-middle font-weight-bold">{{ $sub->nama_kategori }}</td>
                                                <td class="align-middle text-muted small"><code>{{ $sub->slug }}</code></td>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-light border">{{ $sub->sub_barangs_count }}</span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="btn-action-group">
                                                        <a href="{{ route('kategori.edit', $sub->id_kategori) }}" class="btn btn-warning btn-xs btn-action text-white">
                                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                                        </a>
                                                        <form action="{{ route('kategori.destroy', $sub->id_kategori) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus sub-kategori {{ $sub->nama_kategori }}?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-xs btn-action">
                                                                <i class="fa-solid fa-trash-can"></i> Hapus
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
