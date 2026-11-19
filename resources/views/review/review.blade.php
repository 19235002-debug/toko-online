@extends('template.main')
@section('title', 'Data Ulasan & Rating')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-star text-warning mr-2"></i>@yield('title')</h1>
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

            <!-- Summary Cards (TailAdmin KPI Style) -->
            <div class="row mb-3">
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Total Ulasan</div>
                                <div class="h4 font-weight-bold mb-1 mt-1">{{ $stats['total'] }}</div>
                                <div class="text-muted" style="font-size: 11.5px;">Seluruh ulasan masuk</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center shrink-0" style="width: 48px; height: 48px; border-radius: 14px; background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-comments fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Ulasan Disetujui</div>
                                <div class="h4 font-weight-bold mb-1 mt-1 text-success">{{ $stats['approved'] }}</div>
                                <div class="text-muted" style="font-size: 11.5px;">Tampil di halaman toko</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center shrink-0" style="width: 48px; height: 48px; border-radius: 14px; background: #ecfdf5; color: #10b981;">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Ulasan Menunggu</div>
                                <div class="h4 font-weight-bold mb-1 mt-1" style="color: #d97706;">{{ $stats['pending'] }}</div>
                                <div class="text-muted" style="font-size: 11.5px;">Perlu moderasi admin</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center shrink-0" style="width: 48px; height: 48px; border-radius: 14px; background: #fef3c7; color: #d97706;">
                                <i class="fas fa-clock fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Rata-rata Rating</div>
                                <div class="h4 font-weight-bold mb-1 mt-1">
                                    {{ $stats['avg_rating'] }}
                                    <span class="text-warning" style="font-size: 16px;"><i class="fas fa-star"></i></span>
                                    <span class="text-muted font-weight-normal" style="font-size: 14px;">/ 5.0</span>
                                </div>
                                <div class="text-muted" style="font-size: 11.5px;">Skor kepuasan pembeli</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center shrink-0" style="width: 48px; height: 48px; border-radius: 14px; background: #eef2ff; color: #465fff;">
                                <i class="fas fa-star-half-alt fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('review.index') }}" class="row align-items-end" style="row-gap: 12px;">
                        <div class="col-md-4 col-sm-6">
                            <label class="mb-1">Status Ulasan</label>
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                            </select>
                        </div>
                        <div class="col-md-5 col-sm-6">
                            <label class="mb-1">Filter Produk</label>
                            <select name="barang_id" class="form-control">
                                <option value="">Semua Produk</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id_barang }}" {{ request('barang_id') == $b->id_barang ? 'selected' : '' }}>{{ $b->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-12 d-flex" style="gap: 8px;">
                            <button type="submit" class="btn btn-primary font-weight-bold flex-fill" style="height: 42px; border-radius: 10px;">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                            <a href="{{ route('review.index') }}" class="btn btn-light border font-weight-bold d-inline-flex align-items-center justify-content-center px-3" style="height: 42px; border-radius: 10px;">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fa-solid fa-list-check mr-1 text-primary"></i> Daftar Ulasan Pembeli
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 45px;" class="text-center">#</th>
                                    <th>Produk</th>
                                    <th>Reviewer &amp; Tanggal</th>
                                    <th>Rating &amp; Komentar</th>
                                    <th class="text-center" style="width: 120px;">Status</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td class="text-center text-muted font-weight-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            @if($review->barang)
                                                <div class="d-flex align-items-center" style="gap: 10px;">
                                                    @if($review->barang->gambar)
                                                        <img src="{{ asset('storage/' . $review->barang->gambar) }}" alt="{{ $review->barang->nama }}" class="border" style="width: 42px; height: 42px; object-fit: cover; border-radius: 10px;">
                                                    @else
                                                        <div class="border text-muted d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 10px; background: #f8fafc;">
                                                            <i class="fas fa-box"></i>
                                                        </div>
                                                    @endif
                                                    <div style="min-width: 0;">
                                                        <strong class="d-block text-dark" style="font-size: 13.5px;">{{ $review->barang->nama }}</strong>
                                                        <small class="text-muted">Rp {{ number_format($review->barang->harga, 0, ',', '.') }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted font-italic">(Produk Dihapus)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $review->nama_reviewer }}</div>
                                            @if($review->kode_order)
                                                <span class="badge rounded-pill px-2 py-0.5 mt-1" style="background: #ecfdf5; color: #059669; font-size: 10.5px;">
                                                    <i class="fas fa-check-circle mr-1"></i>{{ $review->kode_order }}
                                                </span>
                                            @endif
                                            @if($review->email_reviewer)
                                                <div class="small text-muted mt-0.5">{{ $review->email_reviewer }}</div>
                                            @endif
                                            <div class="small text-muted mt-0.5">
                                                <i class="fa-regular fa-clock mr-1"></i>{{ $review->created_at->format('d M Y, H:i') }} WIB
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center mb-1" style="gap: 6px;">
                                                <div class="text-warning" style="font-size: 12.5px;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="badge rounded-pill px-2 py-0.5" style="background: #fef3c7; color: #b45309; font-size: 11px; font-weight: 700;">
                                                    {{ $review->rating }}.0
                                                </span>
                                            </div>
                                            <div class="text-muted font-italic" style="font-size: 13px;">
                                                "{{ $review->komentar }}"
                                            </div>
                                            @if($review->foto)
                                                <a href="{{ asset('storage/' . $review->foto) }}" target="_blank" class="d-inline-block mt-1.5">
                                                    <img src="{{ asset('storage/' . $review->foto) }}" alt="Foto Ulasan" class="border" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px;">
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($review->status === 'approved')
                                                <span class="badge rounded-pill px-3 py-1.5" style="background: #dcfce7; color: #15803d; font-size: 11.5px;">
                                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                                </span>
                                            @else
                                                <span class="badge rounded-pill px-3 py-1.5" style="background: #fef3c7; color: #b45309; font-size: 11.5px;">
                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-action-group justify-content-center">
                                                <form action="{{ route('review.toggle', $review->id_review) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $review->status === 'approved' ? 'btn-action-warning' : 'btn-success' }} btn-action" title="{{ $review->status === 'approved' ? 'Sembunyikan' : 'Setujui' }}">
                                                        <i class="fas {{ $review->status === 'approved' ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('review.destroy', $review->id_review) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-action btn-delete" title="Hapus">
                                                        <i class="fas fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
