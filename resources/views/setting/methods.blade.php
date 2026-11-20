@extends('template.main')
@section('title', 'Opsi Kurir & Pembayaran')
@section('content')

<style>
/* Modern Toggle Switch Styling */
.method-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 18px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    box-shadow: 0 4px 12px -4px rgba(15, 23, 42, 0.04);
}
.method-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 8px 20px -6px rgba(15, 23, 42, 0.08);
}
.method-card.is-active {
    border-color: #6366f1;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}
.method-card.is-inactive {
    opacity: 0.65;
    background: #f8fafc;
}
.method-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    transition: transform 0.2s;
}
.method-card:hover .method-icon-box {
    transform: scale(1.06);
}

/* Custom Switch Toggle */
.switch-toggle-wrap {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 26px;
    margin-bottom: 0;
    cursor: pointer;
}
.switch-toggle-wrap input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: .3s cubic-bezier(0.16, 1, 0.3, 1);
    border-radius: 99px;
}
.switch-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s cubic-bezier(0.16, 1, 0.3, 1);
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.18);
}
input:checked + .switch-slider {
    background-color: #4f46e5;
}
input:checked + .switch-slider:before {
    transform: translateX(22px);
}
.pill-active-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 99px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
</style>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold" style="font-size: 1.5rem; letter-spacing: -0.02em;">
                        <i class="fa-solid fa-toggle-on text-primary mr-2"></i>Opsi Kurir &amp; Pembayaran
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right small">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/setting">Pengaturan</a></li>
                        <li class="breadcrumb-item active">Kurir &amp; Pembayaran</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Tab Navigasi Pengaturan Toko -->
            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3 flex-wrap" style="gap: 12px;">
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <a href="{{ route('setting.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 font-weight-bold" style="border-radius: 10px;">
                        <i class="fa-solid fa-store mr-1.5"></i> Informasi Toko
                    </a>
                    <a href="{{ route('setting.methods') }}" class="btn btn-primary btn-sm px-3 py-2 font-weight-bold" style="border-radius: 10px; background: #4f46e5; border-color: #4f46e5; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                        <i class="fa-solid fa-toggle-on mr-1.5"></i> Opsi Kurir &amp; Pembayaran
                    </a>
                </div>
                <div>
                    <span class="text-muted small">
                        <i class="fa-solid fa-circle-info text-primary mr-1"></i> Perubahan tombol switch aktif/nonaktif langsung tersimpan secara otomatis.
                    </span>
                </div>
            </div>

            <form action="{{ route('setting.methods.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ============================================== -->
                <!-- SEKSI 1: METODE PEMBAYARAN                    -->
                <!-- ============================================== -->
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 18px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px; background: #eef2ff; color: #4f46e5; font-size: 16px;">
                                <i class="fa-solid fa-credit-card"></i>
                            </span>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">Metode Pembayaran Toko</h5>
                                <div class="text-muted small">Atur metode pembayaran yang bisa dipilih pelanggan saat checkout</div>
                            </div>
                        </div>
                        <div>
                            <span class="badge badge-light border px-2.5 py-1 text-primary font-weight-bold" style="border-radius: 8px;">
                                <span id="payment-active-count">{{ count($activePayments) }}</span> dari {{ count($allPayments) }} Metode Aktif
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            @foreach($allPayments as $payKey => $payInfo)
                            @php
                                $isActive = in_array($payKey, $activePayments, true);
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="method-card {{ $isActive ? 'is-active' : 'is-inactive' }}" id="pay-card-{{ $payKey }}">
                                    <div class="d-flex align-items-start justify-content-between mb-3" style="gap: 12px;">
                                        <div class="d-flex align-items-center" style="gap: 12px;">
                                            <div class="method-icon-box" style="background: {{ $payInfo['color'] }}15; color: {{ $payInfo['color'] }};">
                                                <i class="{{ $payInfo['icon'] }}"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark" style="font-size: 14.5px;">{{ $payInfo['name'] }}</div>
                                                <span class="badge badge-light border text-muted" style="font-size: 11px;">{{ $payInfo['badge'] }}</span>
                                            </div>
                                        </div>

                                        <!-- Switch Toggle -->
                                        <div class="d-flex align-items-center" style="gap: 8px;">
                                            <span class="pill-active-badge {{ $isActive ? 'badge-success' : 'badge-secondary' }}" id="pay-badge-{{ $payKey }}">
                                                {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                            <label class="switch-toggle-wrap" title="Klik untuk mengaktifkan / menonaktifkan">
                                                <input type="checkbox" name="active_payment_methods[]" value="{{ $payKey }}" class="js-toggle-payment" data-method="{{ $payKey }}" {{ $isActive ? 'checked' : '' }}>
                                                <span class="switch-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-0" style="font-size: 12px; line-height: 1.45;">
                                        {{ $payInfo['desc'] }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- SEKSI 2: EKSPEDISI / KURIR PENGIRIMAN          -->
                <!-- ============================================== -->
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 18px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px; background: #ecfdf5; color: #059669; font-size: 16px;">
                                <i class="fa-solid fa-truck-fast"></i>
                            </span>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">Ekspedisi Kurir Pengiriman</h5>
                                <div class="text-muted small">Aktifkan atau nonaktifkan kurir yang muncul pada saat pelanggan cek ongkir &amp; checkout</div>
                            </div>
                        </div>
                        <div>
                            <span class="badge badge-light border px-2.5 py-1 text-success font-weight-bold" style="border-radius: 8px;">
                                <span id="courier-active-count">{{ count($activeCouriers) }}</span> dari {{ count($allCouriers) }} Kurir Aktif
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="alert alert-light border mb-4 d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px; border-radius: 12px;">
                            <div class="d-flex align-items-center" style="gap: 8px; font-size: 12.5px;">
                                <i class="fa-solid fa-circle-check text-success"></i>
                                <span>Kurir otomatis terhubung dengan layanan hitung ongkir otomatis, request pick-up, dan resi kurir resmi.</span>
                            </div>
                            <div class="d-flex align-items-center" style="gap: 6px;">
                                <button type="button" class="btn btn-xs btn-outline-primary px-2.5 py-1 font-weight-bold" id="btn-activate-all-couriers" style="border-radius: 6px;">
                                    Aktifkan Semua Kurir
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            @foreach($allCouriers as $courierCode => $courierInfo)
                            @php
                                $isActive = in_array($courierCode, $activeCouriers, true);
                            @endphp
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="method-card {{ $isActive ? 'is-active' : 'is-inactive' }}" id="courier-card-{{ $courierCode }}">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="method-icon-box" style="background: {{ $courierInfo['color'] }}15; color: {{ $courierInfo['color'] }}; width: 38px; height: 38px; font-size: 16px;">
                                            <i class="{{ $courierInfo['icon'] }}"></i>
                                        </div>
                                        <label class="switch-toggle-wrap" title="Klik untuk mengaktifkan / menonaktifkan">
                                            <input type="checkbox" name="active_couriers[]" value="{{ $courierCode }}" class="js-toggle-courier" data-code="{{ $courierCode }}" {{ $isActive ? 'checked' : '' }}>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>

                                    <div class="font-weight-bold text-dark mt-1" style="font-size: 14px;">{{ $courierInfo['name'] }}</div>
                                    <div class="text-muted small truncate" style="font-size: 11px;">{{ $courierInfo['full_name'] }}</div>
                                    <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                                        <span class="text-muted" style="font-size: 10.5px;">Layanan: {{ $courierInfo['services'] }}</span>
                                        <span class="pill-active-badge {{ $isActive ? 'badge-success' : 'badge-secondary' }}" id="courier-badge-{{ $courierCode }}" style="font-size: 10px;">
                                            {{ $isActive ? 'Aktif' : 'Off' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex align-items-center justify-content-between py-3">
                        <div class="text-muted small">
                            *Setiap perubahan toggle otomatis disimpan ke sistem secara real-time.
                        </div>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4" style="border-radius: 10px; background: #4f46e5; border-color: #4f46e5; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                            <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Semua Opsi
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Helper Toast SweetAlert2
    function showToast(icon, message) {
        if (typeof Swal !== 'undefined' && Swal.mixin) {
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            }).fire({
                icon: icon,
                title: message
            });
        }
    }

    // Toggle Kurir via AJAX
    $(document).on('change', '.js-toggle-courier', function() {
        var $checkbox = $(this);
        var code = $checkbox.data('code');
        var isChecked = $checkbox.is(':checked');

        $.ajax({
            url: '{{ route("setting.toggleCourier") }}',
            type: 'POST',
            data: {
                _token: csrfToken,
                code: code
            },
            success: function(resp) {
                if (resp.success) {
                    var card = $('#courier-card-' + code);
                    var badge = $('#courier-badge-' + code);

                    if (resp.is_active) {
                        card.removeClass('is-inactive').addClass('is-active');
                        badge.removeClass('badge-secondary').addClass('badge-success').text('Aktif');
                    } else {
                        card.removeClass('is-active').addClass('is-inactive');
                        badge.removeClass('badge-success').addClass('badge-secondary').text('Off');
                    }

                    $('#courier-active-count').text(resp.active_count);
                    showToast('success', resp.message);
                }
            },
            error: function(xhr) {
                // Kembalikan status checkbox jika gagal
                $checkbox.prop('checked', !isChecked);
                var err = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal memperbarui status kurir.';
                showToast('error', err);
            }
        });
    });

    // Toggle Metode Pembayaran via AJAX
    $(document).on('change', '.js-toggle-payment', function() {
        var $checkbox = $(this);
        var method = $checkbox.data('method');
        var isChecked = $checkbox.is(':checked');

        $.ajax({
            url: '{{ route("setting.togglePayment") }}',
            type: 'POST',
            data: {
                _token: csrfToken,
                method: method
            },
            success: function(resp) {
                if (resp.success) {
                    var card = $('#pay-card-' + method);
                    var badge = $('#pay-badge-' + method);

                    if (resp.is_active) {
                        card.removeClass('is-inactive').addClass('is-active');
                        badge.removeClass('badge-secondary').addClass('badge-success').text('Aktif');
                    } else {
                        card.removeClass('is-active').addClass('is-inactive');
                        badge.removeClass('badge-success').addClass('badge-secondary').text('Nonaktif');
                    }

                    $('#payment-active-count').text(resp.active_count);
                    showToast('success', resp.message);
                }
            },
            error: function(xhr) {
                // Kembalikan status checkbox jika gagal
                $checkbox.prop('checked', !isChecked);
                var err = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal memperbarui status pembayaran.';
                showToast('error', err);
            }
        });
    });

    // Tombol Cepat: Aktifkan Semua Kurir
    $('#btn-activate-all-couriers').on('click', function(e) {
        e.preventDefault();
        $('.js-toggle-courier').each(function() {
            if (!$(this).is(':checked')) {
                $(this).prop('checked', true).trigger('change');
            }
        });
    });
});
</script>

@endsection

