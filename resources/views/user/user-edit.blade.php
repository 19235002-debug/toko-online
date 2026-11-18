@extends('template.main')
@section('title', 'Edit User')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/user">User</a></li>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-user-pen mr-1"></i> Form Edit User
                            </h3>
                            <div class="card-tools">
                                {!! btn_back('/user') !!}
                            </div>
                        </div>
                        <form action="{{ route('user.update', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <!-- KIRI: Form input teks -->
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="nama">Nama</label>
                                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" required>
                                            @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="no_hp">No HP</label>
                                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $user->no_hp) }}">
                                            @error('no_hp') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                                <option value="">-- Pilih Role --</option>
                                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                            </select>
                                            @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- KANAN: Upload & preview gambar -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user_image">Foto Profil</label>
                                            <input type="file" name="user_image" class="form-control-file @error('user_image') is-invalid @enderror">
                                            @error('user_image') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                                            @if ($user->user_image)
                                            <div class="mt-3">
                                                <small class="d-block mb-1">Foto saat ini:</small>
                                                <img src="{{ asset('storage/' . $user->user_image) }}" alt="Foto Profil" class="img-fluid rounded border" style="max-height: 200px;">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex flex-wrap justify-content-end">
                                {!! btn_reset() !!}
                                {!! btn_save('Perbarui User') !!}
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.content -->
            </div>
        </div>
    </div>
</div>

@endsection