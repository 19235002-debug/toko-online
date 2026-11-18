@extends('template.main')
@section('title', 'Data Ulasan & Rating')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
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

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3>{{ $stats['total'] }}</h3>
                            <p>Total Ulasan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success shadow-sm">
                        <div class="inner">
                            <h3>{{ $stats['approved'] }}</h3>
                            <p>Ulasan Disetujui</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner">
                            <h3>{{ $stats['pending'] }}</h3>
                            <p>Ulasan Menunggu</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary shadow-sm">
                        <div class="inner">
                            <h3>{{ $stats['avg_rating'] }} <small style="font-size: 18px;"><i class="fas fa-star text-warning"></i> / 5.0</small></h3>
                            <p>Rata-rata Rating</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('review.index') }}" class="form-inline d-flex flex-wrap gap-2">
                        <div class="form-group mr-2 mb-2">
                            <label class="mr-2 font-weight-bold">Status:</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="">Semua Status</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <label class="mr-2 font-weight-bold">Produk:</label>
                            <select name="barang_id" class="form-control form-control-sm" style="max-width: 260px;">
                                <option value="">Semua Produk</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id_barang }}" {{ request('barang_id') == $b->id_barang ? 'selected' : '' }}>{{ $b->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary mb-2 mr-2">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('review.index') }}" class="btn btn-sm btn-secondary mb-2">
                            <i class="fas fa-undo mr-1"></i> Reset
                        </a>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fa-solid fa-list-check mr-1"></i> Daftar Ulasan Pembeli
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-striped table-bordered table-hover text-center" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>Produk</th>
                                    <th>Reviewer</th>
                                    <th>Rating</th>
                                    <th style="min-width: 250px;">Komentar</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle text-left">
                                            @if($review->barang)
                                                <div class="d-flex align-items-center">
                                                    @if($review->barang->gambar)
                                                        <img src="{{ asset('storage/' . $review->barang->gambar) }}" alt="{{ $review->barang->nama }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; margin-right: 8px;">
                                                    @else
                                                        <div class="bg-light rounded text-muted d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px;"><i class="fas fa-box"></i></div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $review->barang->nama }}</strong>
                                                        <div class="small text-muted">Rp {{ number_format($review->barang->harga, 0, ',', '.') }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted font-italic">(Produk Dihapus)</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-left">
                                            <strong>{{ $review->nama_reviewer }}</strong>
                                            @if($review->kode_order)
                                                <div>
                                                    <span class="badge badge-success" style="font-size: 11px;">
                                                        <i class="fas fa-check-circle mr-1"></i> {{ $review->kode_order }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if($review->email_reviewer)
                                                <div class="small text-muted">{{ $review->email_reviewer }}</div>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="font-weight-bold text-dark">{{ $review->rating }}.0</span>
                                        </td>
                                        <td class="align-middle text-left font-italic">
                                            "{{ $review->komentar }}"
                                        </td>
                                        <td class="align-middle small">
                                            {{ $review->created_at->format('d M Y') }}<br>
                                            <span class="text-muted">{{ $review->created_at->format('H:i') }} WIB</span>
                                        </td>
                                        <td class="align-middle">
                                            @if($review->status === 'approved')
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Disetujui</span>
                                            @else
                                                <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-action-group">
                                                <form action="{{ route('review.toggle', $review->id_review) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $review->status === 'approved' ? 'btn-outline-warning' : 'btn-success' }} btn-action" title="{{ $review->status === 'approved' ? 'Sembunyikan' : 'Setujui' }}">
                                                        <i class="fas {{ $review->status === 'approved' ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('review.destroy', $review->id_review) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')" title="Hapus Ulasan">
                                                        <i class="fas fa-trash"></i>
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

