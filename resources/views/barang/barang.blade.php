@extends('template.main')
@section('title', 'Produk')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-box mr-2"></i>@yield('title')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-boxes mr-1"></i> Data Produk
                            </h3>
                            <div class="card-tools">
                                {!! btn_create('/barang/create', 'Tambah Produk') !!}
                                {!! btn_print(route('barang.print'), 'Cetak Laporan') !!}
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Foto</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th>Supplier</th>
                                            <th>Stok</th>
                                            <th>Harga & Promo</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barang as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="position-relative d-inline-block">
                                                    @if ($data->gambar)
                                                    <img src="{{ asset('storage/' . $data->gambar) }}" alt="Foto Produk" class="rounded border" style="width: 48px; height: 48px; object-fit: cover;">
                                                    @else
                                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                                        <i class="fa fa-box"></i>
                                                    </div>
                                                    @endif
                                                    @if($data->gambars->count() > 0)
                                                    <span class="badge badge-info position-absolute" style="top: -5px; right: -5px; font-size: 10px;" title="{{ $data->gambars->count() }} foto tambahan">
                                                        +{{ $data->gambars->count() }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="font-weight-bold text-left">
                                                {{ $data->nama }}
                                                @if($data->variasis->count() > 0)
                                                <div class="mt-1 d-flex flex-wrap gap-1">
                                                    @if($data->ukuran_list->count() > 0)
                                                    <span class="badge badge-light border text-dark mr-1" style="font-size: 11px;">
                                                        <i class="fa fa-ruler mr-1 text-secondary"></i> {{ $data->ukuran_list->implode(', ') }}
                                                    </span>
                                                    @endif
                                                    @if($data->warna_list->count() > 0)
                                                    <span class="badge badge-light border text-primary" style="font-size: 11px;">
                                                        <i class="fa fa-palette mr-1"></i> {{ $data->warna_list->implode(', ') }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @endif
                                            </td>
                                            <td><span class="badge badge-secondary">{{ $data->kategori }}</span></td>
                                            <td>{{ $data->supplier }}</td>
                                            <td>{{ $data->stok_aktual }}</td>
                                            <td class="text-left">
                                                @if($data->is_promo_active)
                                                    <div class="font-weight-bold text-danger">Rp {{ number_format($data->harga_final, 0, ',', '.') }}</div>
                                                    <div class="small text-muted"><del>Rp {{ number_format($data->harga, 0, ',', '.') }}</del> <span class="badge badge-danger">-{{ $data->diskon_persen_aktual }}%</span></div>
                                                @else
                                                    <div class="font-weight-bold text-success">Rp {{ number_format($data->harga, 0, ',', '.') }}</div>
                                                @endif
                                                @if($data->promo_persen || $data->harga_promo)
                                                    <div class="mt-1 d-flex align-items-center" style="gap: 4px;">
                                                        @php
                                                            $pStatus = $data->promo_status_label;
                                                            $pBadge = $pStatus === 'Aktif' ? 'badge-success' : ($pStatus === 'Terjadwal' ? 'badge-warning' : ($pStatus === 'Berakhir' ? 'badge-secondary' : 'badge-light border'));
                                                        @endphp
                                                        <span class="badge {{ $pBadge }}" style="font-size: 10px;">Promo: {{ $pStatus }}</span>
                                                        <form action="{{ route('barang.togglePromo', $data->id_barang) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-xs {{ $data->promo_aktif ? 'btn-outline-danger' : 'btn-outline-success' }}" style="font-size: 10px; padding: 1px 6px;" title="{{ $data->promo_aktif ? 'Matikan Promo' : 'Aktifkan Promo' }}">
                                                                {{ $data->promo_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                    @if($data->promo_selesai)
                                                        <small class="text-muted d-block" style="font-size: 10px;">s/d {{ $data->promo_selesai->format('d M Y H:i') }}</small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {!! btn_actions('/barang/' . $data->id_barang . '/edit', '/barang/' . $data->id_barang) !!}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
    </div>
</div>

@endsection