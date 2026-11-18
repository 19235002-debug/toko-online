@extends('template.main')
@section('title', 'Voucher & Promo')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-ticket text-info mr-2"></i>@yield('title')</h1>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-tags mr-1"></i> Daftar Voucher Promo
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('voucher.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus mr-1"></i> Tambah Voucher
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode Voucher</th>
                                            <th>Nama Promo</th>
                                            <th>Nilai Diskon</th>
                                            <th>Min. Belanja</th>
                                            <th>Kuota / Dipakai</th>
                                            <th>Periode</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vouchers as $v)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge badge-dark px-2 py-1" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                                                    {{ $v->kode_voucher }}
                                                </span>
                                            </td>
                                            <td class="text-left font-weight-bold">{{ $v->nama_voucher }}</td>
                                            <td>
                                                @if($v->tipe === 'persen')
                                                    <span class="text-success font-weight-bold">{{ $v->nilai }}%</span>
                                                    @if($v->maksimal_diskon)
                                                        <div class="small text-muted">Maks: Rp {{ number_format($v->maksimal_diskon, 0, ',', '.') }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-success font-weight-bold">Rp {{ number_format($v->nilai, 0, ',', '.') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($v->minimal_belanja > 0)
                                                    Rp {{ number_format($v->minimal_belanja, 0, ',', '.') }}
                                                @else
                                                    <span class="text-muted">Tanpa Minimal</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-light border">
                                                    {{ $v->digunakan }} / {{ $v->kuota ? $v->kuota : '∞' }}
                                                </span>
                                            </td>
                                            <td class="small">
                                                @if($v->tanggal_mulai || $v->tanggal_selesai)
                                                    {{ $v->tanggal_mulai ? $v->tanggal_mulai->format('d/m/Y') : 'Sekarang' }}
                                                    -
                                                    {{ $v->tanggal_selesai ? $v->tanggal_selesai->format('d/m/Y') : 'Selamanya' }}
                                                @else
                                                    <span class="text-muted">Tidak dibatasi</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('voucher.toggle', $v->id_voucher) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs {{ $v->status ? 'btn-success' : 'btn-secondary' }}" title="Klik untuk ubah status">
                                                        {!! $v->status ? '<i class="fa fa-check-circle mr-1"></i> Aktif' : '<i class="fa fa-ban mr-1"></i> Nonaktif' !!}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-action-group">
                                                    <a href="{{ route('voucher.edit', $v->id_voucher) }}" class="btn btn-warning btn-sm btn-action text-white">
                                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('voucher.destroy', $v->id_voucher) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" id="btn-delete" class="btn btn-danger btn-sm btn-action">
                                                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
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
    </div>
</div>

@endsection

