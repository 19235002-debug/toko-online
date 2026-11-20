@php
    $suffix = $idSuffix ?? 'default';
    $activeItem = $activeMenu ?? '';
@endphp

@auth
<div class="js-user-dropdown {{ $extraClass ?? '' }}">
    <a href="javascript:void(0);" class="ta-frontend-trigger js-user-dropdown-toggle" role="button" aria-expanded="false" title="{{ auth()->user()->nama }}">
        <div class="ta-frontend-avatar-wrap">
            @if(auth()->user()->user_image)
                <img src="{{ asset('storage/' . auth()->user()->user_image) }}" alt="{{ auth()->user()->nama }}" class="ta-frontend-avatar-img">
            @else
                <div class="ta-frontend-avatar-fallback">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </div>
            @endif
            <span class="ta-frontend-status-dot" title="Online"></span>
        </div>
        <div class="ta-frontend-user-meta">
            <div class="ta-frontend-user-name">{{ auth()->user()->nama }}</div>
            <div class="ta-frontend-user-role">
                {{ in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'Admin' : 'Member' }}
            </div>
        </div>
        <i class="fa-solid fa-chevron-down ta-frontend-chevron"></i>
    </a>

    <div class="ta-frontend-dropdown-menu user-dropdown-menu">
        <!-- Header Info -->
        <div class="ta-frontend-dropdown-header">
            <div class="ta-frontend-dropdown-title">{{ auth()->user()->nama }}</div>
            <div class="ta-frontend-dropdown-sub">{{ auth()->user()->email }}</div>
            <span class="ta-frontend-role-badge {{ in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'badge-admin' : 'badge-member' }}">
                <i class="fa-solid {{ in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'fa-shield-halved' : 'fa-circle-check' }} mr-1"></i>
                {{ in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'Administrator' : 'Member' }}
            </span>
        </div>

        <!-- Navigation Links -->
        <div class="py-1">
            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                <a href="/dashboard" class="ta-frontend-dropdown-item">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard Admin
                </a>
                <a href="{{ route('profile.edit') }}" class="ta-frontend-dropdown-item {{ $activeItem === 'profile' ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i> Profil Admin
                </a>
                <a href="/order" class="ta-frontend-dropdown-item">
                    <i class="fa-solid fa-box-archive"></i> Kelola Pesanan
                </a>
            @else
                <a href="/pesanan-saya" class="ta-frontend-dropdown-item {{ $activeItem === 'pesanan' ? 'active' : '' }}">
                    <i class="fa-solid fa-bag-shopping"></i> Pesanan Saya
                </a>
                <a href="{{ route('profile.edit') }}" class="ta-frontend-dropdown-item {{ $activeItem === 'profile' ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i> Profil Saya
                </a>
                <a href="/lacak-pesanan" class="ta-frontend-dropdown-item {{ $activeItem === 'track' ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-fast"></i> Lacak Pesanan
                </a>
            @endif
        </div>

        <div class="ta-frontend-dropdown-divider"></div>

        <!-- Logout Action -->
        <a href="javascript:void(0);" class="ta-frontend-dropdown-logout js-frontend-logout-btn" data-form-id="logout-form-{{ $suffix }}">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
        <form id="logout-form-{{ $suffix }}" action="/logout" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
@else
<div class="{{ $extraClass ?? '' }}">
    <a href="/login" class="ta-frontend-trigger" title="Masuk / Daftar" style="padding: 4px 14px 4px 5px;">
        <div class="ta-frontend-avatar-wrap">
            <div class="ta-frontend-avatar-fallback" style="background: #eef2ff !important; color: #465fff !important; box-shadow: none !important; border: 1px solid #e0e7ff;">
                <i class="fa-solid fa-user" style="font-size: 12px;"></i>
            </div>
        </div>
        <div class="ta-frontend-user-meta">
            <div class="ta-frontend-user-name">Masuk / Daftar</div>
            <div class="ta-frontend-user-role">Akun Pelanggan</div>
        </div>
    </a>
</div>
@endauth
