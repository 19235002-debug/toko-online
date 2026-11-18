@extends('template.main')
@section('title', 'Banner')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-images mr-2"></i>@yield('title') Slider</h1>
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
                                <i class="fas fa-panorama mr-1"></i> Data Banner Slider Frontend
                            </h3>
                            <div class="card-tools">
                                {!! btn_create(route('banner.create'), 'Tambah Banner') !!}
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Preview Banner</th>
                                            <th>Judul Utama</th>
                                            <th>Subjudul</th>
                                            <th>Link Tujuan</th>
                                            <th>Urutan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($banners as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @php
                                                    $imgUrl = str_starts_with($data->gambar, 'banners/') 
                                                        ? asset('storage/' . $data->gambar) 
                                                        : asset('frontend-assets/' . $data->gambar);
                                                @endphp
                                                <a href="{{ $imgUrl }}" target="_blank" title="Klik untuk perbesar">
                                                    <img src="{{ $imgUrl }}" alt="{{ $data->judul }}" class="rounded shadow-sm" style="width: 140px; height: 65px; object-fit: cover;">
                                                </a>
                                            </td>
                                            <td class="font-weight-bold text-left">{{ $data->judul }}</td>
                                            <td class="text-left text-muted">{{ $data->subjudul ?? '-' }}</td>
                                            <td class="text-left">
                                                <small class="text-primary font-monospace">{{ $data->link ?? '/frontend#product-grid' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-light border font-weight-bold">{{ $data->urutan }}</span>
                                            </td>
                                            <td>
                                                @if($data->status)
                                                    <span class="badge badge-success"><i class="fa fa-check mr-1"></i> Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                {!! btn_actions(route('banner.edit', $data->id_banner), route('banner.destroy', $data->id_banner)) !!}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

