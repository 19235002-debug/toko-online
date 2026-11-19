@extends('template.main')

@section('title', 'Manajemen User')

@section('content')
@php
    $totalUsers = $users->count();
    $totalAdmins = $users->whereIn('role', ['admin', 'superadmin'])->count();
    $totalCustomers = $users->where('role', 'user')->count();
@endphp

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-users text-primary mr-2"></i>@yield('title')</h1>
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
            <!-- Summary Strip -->
            <div class="row mb-3">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Total Pengguna</div>
                                <div class="h4 font-weight-bold mb-0 mt-1">{{ $totalUsers }}</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 12px; background: #eef2ff; color: #465fff;">
                                <i class="fa-solid fa-users fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Administrator</div>
                                <div class="h4 font-weight-bold mb-0 mt-1" style="color: #e11d48;">{{ $totalAdmins }}</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 12px; background: #ffe4e6; color: #e11d48;">
                                <i class="fa-solid fa-user-shield fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-0 h-100">
                        <div class="card-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.05em;">Pelanggan</div>
                                <div class="h4 font-weight-bold mb-0 mt-1 text-success">{{ $totalCustomers }}</div>
                            </div>
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #10b981;">
                                <i class="fa-solid fa-user-check fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-users mr-1 text-primary"></i> Daftar Akun Pengguna
                    </h3>
                    <div class="card-tools">
                        {!! btn_create('/user/create', 'Tambah User') !!}
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 45px;" class="text-center">#</th>
                                    <th>Pengguna</th>
                                    <th class="text-center" style="width: 130px;">Role</th>
                                    <th>Kontak &amp; Alamat</th>
                                    <th class="text-center" style="width: 170px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td class="text-center text-muted font-weight-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center" style="gap: 12px;">
                                            @if ($user->user_image)
                                                <img src="{{ asset('storage/' . $user->user_image) }}" alt="{{ $user->nama }}" class="rounded-circle border" style="width: 42px; height: 42px; object-fit: cover; flex-shrink: 0;">
                                            @else
                                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle font-weight-bold"
                                                      style="width: 42px; height: 42px; background: #eef2ff; color: #465fff; font-size: 15px; flex-shrink: 0;">
                                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                                </span>
                                            @endif
                                            <div style="min-width: 0;">
                                                <div class="font-weight-bold text-dark" style="font-size: 14px;">{{ $user->nama }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if(in_array($user->role, ['admin', 'superadmin']))
                                            <span class="badge rounded-pill px-3 py-1.5" style="background: #ffe4e6; color: #be123c; font-size: 11.5px; font-weight: 600;">
                                                <i class="fa-solid fa-shield-halved mr-1"></i> {{ ucfirst($user->role) }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-3 py-1.5" style="background: #eef2ff; color: #465fff; font-size: 11.5px; font-weight: 600;">
                                                <i class="fa-solid fa-user mr-1"></i> {{ ucfirst($user->role) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->no_hp)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->no_hp) }}" target="_blank"
                                               class="badge rounded-pill px-2.5 py-1 d-inline-flex align-items-center"
                                               style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; gap: 5px; font-size: 11.5px;">
                                                <i class="fab fa-whatsapp"></i> {{ $user->no_hp }}
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                        @if($user->alamat)
                                            <div class="text-muted small text-truncate mt-1" style="max-width: 260px;" title="{{ $user->alamat }}">
                                                <i class="fa-solid fa-location-dot mr-1 text-secondary"></i>{{ $user->alamat }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {!! btn_actions(route('user.edit', $user->id_user), '/user/' . $user->id_user) !!}
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
