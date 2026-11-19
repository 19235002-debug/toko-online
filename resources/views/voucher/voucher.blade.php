@extends('template.main')
@section('title', 'Voucher & Promo')
@section('content')

@php
    $totalVoucher = $vouchers->count();
    $aktifVoucher = $vouchers->where('status', true)->count();
    $totalDigunakan = $vouchers->sum('digunakan');
@endphp

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-ticket text-primary mr-2"></i>@yield('title')</h1>
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
            <div class="row mb-3">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Total Voucher</div>
                                <div class="h4 font-weight-bold mb-0 mt-1">{{ $totalVoucher }}</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 12px; background: #eef2ff; color: #465fff;">
                                <i class="fa-solid fa-ticket fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Voucher Aktif</div>
                                <div class="h4 font-weight-bold mb-0 mt-1 text-success">{{ $aktifVoucher }}</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #10b981;">
                                <i class="fa-solid fa-circle-check fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Total Pemakaian</div>
                                <div class="h4 font-weight-bold mb-0 mt-1">{{ $totalDigunakan }} <span class="text-muted font-weight-normal" style="font-size: 13px;">kali</span></div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 12px; background: #fff7ed; color: #f59e0b;">
                                <i class="fa-solid fa-fire fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fa-solid fa-tags mr-1 text-primary"></i> Daftar Voucher Promo
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('voucher.create') }}" class="btn btn-primary btn-sm btn-action" style="gap: 5px;">
                            <i class="fa-solid fa-plus"></i> <span>Tambah Voucher</span>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 45px;" class="text-center">#</th>
                                    <th>Kode &amp; Nama Promo</th>
                                    <th>Diskon &amp; Min. Belanja</th>
                                    <th>Kuota &amp; Periode</th>
                                    <th class="text-center" style="width: 120px;">Status</th>
                                    <th class="text-center" style="width: 165px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vouchers as $v)
                                @php
                                    $pct = $v->kuota ? min(100, (int) round(($v->digunakan / max(1, $v->kuota)) * 100)) : 0;
                                @endphp
                                <tr>
                                    <td class="text-center text-muted font-weight-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge px-2.5 py-1 font-monospace" style="background: #1e293b; color: #ffffff; font-size: 12px; letter-spacing: 0.6px; border-radius: 8px;">
                                            <i class="fa-solid fa-ticket mr-1 text-info"></i>{{ $v->kode_voucher }}
                                        </span>
                                        <div class="font-weight-bold text-dark mt-1" style="font-size: 13.5px;">{{ $v->nama_voucher }}</div>
                                    </td>
                                    <td>
                                        @if($v->tipe === 'persen')
                                            <div class="font-weight-bold text-success" style="font-size: 14px;">
                                                Diskon {{ $v->nilai }}%
                                                @if($v->maksimal_diskon)
                                                    <span class="text-muted font-weight-normal" style="font-size: 11.5px;">(Maks. Rp {{ number_format($v->maksimal_diskon, 0, ',', '.') }})</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="font-weight-bold text-success" style="font-size: 14px;">
                                                Potongan Rp {{ number_format($v->nilai, 0, ',', '.') }}
                                            </div>
                                        @endif
                                        <small class="text-muted">
                                            @if($v->minimal_belanja > 0)
                                                Min. belanja Rp {{ number_format($v->minimal_belanja, 0, ',', '.') }}
                                            @else
                                                Tanpa minimal belanja
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center" style="gap: 8px;">
                                            <span class="badge rounded-pill px-2 py-0.5" style="background: #f1f5f9; color: #334155; font-size: 11.5px; font-weight: 600;">
                                                {{ $v->digunakan }} / {{ $v->kuota ? $v->kuota : '∞' }}
                                            </span>
                                            @if($v->kuota)
                                            <div class="progress flex-fill" style="height: 6px; max-width: 90px; border-radius: 99px; background: #e2e8f0;">
                                                <div class="progress-bar bg-primary" style="width: {{ $pct }}%; border-radius: 99px;"></div>
                                            </div>
                                            @endif
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fa-regular fa-calendar mr-1"></i>
                                            @if($v->tanggal_mulai || $v->tanggal_selesai)
                                                {{ $v->tanggal_mulai ? $v->tanggal_mulai->format('d/m/Y') : 'Sekarang' }}
                                                &ndash;
                                                {{ $v->tanggal_selesai ? $v->tanggal_selesai->format('d/m/Y') : 'Selamanya' }}
                                            @else
                                                Tidak dibatasi
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('voucher.toggle', $v->id_voucher) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm rounded-pill px-3 py-1 font-weight-bold border-0"
                                                style="background: {{ $v->status ? '#dcfce7' : '#f1f5f9' }}; color: {{ $v->status ? '#15803d' : '#64748b' }}; font-size: 11.5px;"
                                                title="Klik untuk ubah status">
                                                <i class="fa {{ $v->status ? 'fa-check-circle' : 'fa-ban' }} mr-1"></i>
                                                {{ $v->status ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-action-group justify-content-center">
                                            <a href="{{ route('voucher.edit', $v->id_voucher) }}" class="btn btn-action btn-action-warning btn-sm" style="gap: 5px;">
                                                <i class="fa-solid fa-pen-to-square"></i> <span>Edit</span>
                                            </a>
                                            <form action="{{ route('voucher.destroy', $v->id_voucher) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" id="btn-delete" class="btn btn-danger btn-sm btn-action" style="gap: 5px;">
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
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
