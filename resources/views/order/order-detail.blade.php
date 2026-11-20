@extends('template.main')
@section('title', 'Detail Pesanan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header pb-2">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-md-7 col-12 mb-2 mb-md-0">
                    <div class="d-flex align-items-center flex-wrap" style="gap: 12px;">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                              style="width: 44px; height: 44px; background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); color: #4f46e5; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);">
                            <i class="fa-solid fa-file-invoice" style="font-size: 18px;"></i>
                        </span>
                        <div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                <h1 class="m-0 font-weight-bold text-dark font-monospace" style="font-size: 21px; font-family: 'Outfit', sans-serif;">
                                    Invoice {{ $order->kode_order }}
                                </h1>
                                <button type="button" class="btn btn-xs btn-link text-muted p-0 js-copy-btn"
                                        data-copy="{{ $order->kode_order }}" title="Salin Kode Pesanan" style="font-size: 13px;">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-0.5">
                                Dibuat {{ $order->created_at->format('d M Y, H:i') }} WIB &middot; Pembeli: <strong>{{ $order->nama_customer }}</strong>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-12">
                    <div class="d-flex align-items-center justify-content-md-end flex-wrap" style="gap: 8px;">
                        <a href="{{ route('order.print', $order->id_order) }}" target="_blank"
                           class="btn btn-sm btn-outline-success font-weight-bold px-3 py-1.5"
                           style="border-radius: 10px;" title="Cetak Faktur Penjualan">
                            <i class="fa-solid fa-print mr-1"></i> Cetak Faktur
                        </a>
                        <a href="{{ route('order.index') }}" class="btn btn-sm btn-light border font-weight-bold px-3 py-1.5 text-muted"
                           style="border-radius: 10px;">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Visual Order Lifecycle Stepper -->
            @php
                $statusOrder = ['pending' => 1, 'diproses' => 2, 'dikirim' => 3, 'selesai' => 4, 'dibatalkan' => 0];
                $currentStep = $statusOrder[$order->status] ?? 1;
                $stepPercent = [0 => 0, 1 => 0, 2 => 33.333, 3 => 66.666, 4 => 100][$currentStep] ?? 0;
            @endphp
            <div class="card border-0 shadow-sm mb-3" style="border-radius: 16px;">
                <div class="card-body py-3 px-3 px-md-4">
                    @if($order->status === 'dibatalkan')
                        <div class="d-flex align-items-center justify-content-center p-3 rounded" style="background: #fef2f2; border: 1px solid #fecaca; gap: 10px;">
                            <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 20px;"></i>
                            <div>
                                <strong class="text-danger">Pesanan Dibatalkan</strong>
                                <small class="text-muted d-block">Pesanan ini telah dibatalkan atau kedaluwarsa. Stok barang telah dikembalikan otomatis.</small>
                            </div>
                        </div>
                    @else
                        <div class="order-stepper-container position-relative py-1">
                            <!-- Background connecting track line -->
                            <div class="stepper-track-bg d-none d-sm-block"></div>
                            <!-- Active filled progress line -->
                            <div class="stepper-track-active d-none d-sm-block" style="width: calc({{ $stepPercent }}% * 0.75);"></div>

                            <div class="row text-center position-relative" style="z-index: 3; row-gap: 12px;">
                                <!-- Step 1: Pesanan Dibuat -->
                                <div class="col-3 position-relative">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle font-weight-bold mb-1 stepper-circle {{ $currentStep >= 1 ? 'step-done' : 'step-idle' }}">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <div class="font-weight-bold text-dark stepper-label">Pesanan Dibuat</div>
                                    <small class="text-muted d-block stepper-sub">{{ $order->created_at->format('d M, H:i') }}</small>
                                </div>

                                <!-- Step 2: Pembayaran / Diproses -->
                                <div class="col-3 position-relative">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle font-weight-bold mb-1 stepper-circle {{ $currentStep >= 2 ? 'step-done-blue' : ($currentStep == 1 ? 'step-active' : 'step-idle') }}">
                                        @if($currentStep >= 2)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            <i class="fa-solid fa-hourglass-half"></i>
                                        @endif
                                    </div>
                                    <div class="font-weight-bold {{ $currentStep >= 2 ? 'text-dark' : 'text-muted' }} stepper-label">
                                        {{ $order->metode_pembayaran === 'cod' ? 'Konfirmasi COD' : 'Diproses (Lunas)' }}
                                    </div>
                                    <small class="text-muted d-block stepper-sub">
                                        @if($order->paid_at)
                                            Lunas {{ $order->paid_at->format('d M, H:i') }}
                                        @elseif($order->metode_pembayaran === 'cod')
                                            Bayar di Tempat
                                        @else
                                            Menunggu Bayar
                                        @endif
                                    </small>
                                </div>

                                <!-- Step 3: Pengiriman Kurir -->
                                <div class="col-3 position-relative">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle font-weight-bold mb-1 stepper-circle {{ $currentStep >= 3 ? 'step-done-purple' : 'step-idle' }}">
                                        @if($currentStep >= 3)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            <i class="fa-solid fa-truck-fast"></i>
                                        @endif
                                    </div>
                                    <div class="font-weight-bold {{ $currentStep >= 3 ? 'text-dark' : 'text-muted' }} stepper-label">Pengiriman</div>
                                    <small class="text-muted d-block text-truncate stepper-sub" title="{{ $order->kurir ?: ($order->ekspedisi ?: 'Kurir') }}">
                                        {{ $order->kurir ?: ($order->ekspedisi ?: 'Kurir') }}
                                    </small>
                                </div>

                                <!-- Step 4: Selesai -->
                                <div class="col-3 position-relative">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle font-weight-bold mb-1 stepper-circle {{ $currentStep >= 4 ? 'step-done' : 'step-idle' }}">
                                        @if($currentStep >= 4)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            <i class="fa-solid fa-circle-check"></i>
                                        @endif
                                    </div>
                                    <div class="font-weight-bold {{ $currentStep >= 4 ? 'text-dark' : 'text-muted' }} stepper-label">Selesai</div>
                                    <small class="text-muted d-block stepper-sub">
                                        {{ $order->completed_at ? $order->completed_at->format('d M, H:i') : 'Paket Diterima' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- KIRI: Manajemen Status, Logistik & Info Pelanggan -->
                <div class="col-lg-5 col-xl-4 mb-3">
                    <!-- Status & Logistik Card -->
                    <div class="card shadow-sm border-0 mb-3" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                      style="width: 32px; height: 32px; background: #eef2ff; color: #4f46e5;">
                                    <i class="fa-solid fa-truck-ramp-box" style="font-size: 14px;"></i>
                                </span>
                                <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">Status &amp; Logistik</h6>
                            </div>
                            <!-- Current status badge -->
                            @if($order->status == 'pending')
                                <span class="badge rounded-pill px-2.5 py-1 font-weight-bold" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 11px;">
                                    <i class="fa-solid fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($order->status == 'diproses')
                                <span class="badge rounded-pill px-2.5 py-1 font-weight-bold" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 11px;">
                                    <i class="fa-solid fa-box-open mr-1"></i> Diproses
                                </span>
                            @elseif($order->status == 'dikirim')
                                <span class="badge rounded-pill px-2.5 py-1 font-weight-bold" style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; font-size: 11px;">
                                    <i class="fa-solid fa-truck-fast mr-1"></i> Dikirim
                                </span>
                            @elseif($order->status == 'selesai')
                                <span class="badge rounded-pill px-2.5 py-1 font-weight-bold" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 11px;">
                                    <i class="fa-solid fa-circle-check mr-1"></i> Selesai
                                </span>
                            @else
                                <span class="badge rounded-pill px-2.5 py-1 font-weight-bold" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-size: 11px;">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> Dibatalkan
                                </span>
                            @endif
                        </div>

                        <div class="card-body p-3 p-md-3.5">
                            <!-- Smart Logistics / Action Box -->
                            @if($order->status === 'dikirim' && $order->no_resi)
                                <!-- Sesi Dikirim: Resi Aktif & Cetak Label -->
                                <div class="mb-3 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="text-muted small" style="font-size: 12px;">Nomor Resi Resmi:</span>
                                        <span class="badge badge-light border text-dark font-weight-bold">{{ $order->ekspedisi ?: 'Kurir' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <strong class="font-monospace text-primary font-weight-bold text-truncate mr-2" style="font-size: 14.5px;">{{ $order->no_resi }}</strong>
                                        <button type="button" class="btn btn-xs btn-link text-muted p-0 js-copy-btn flex-shrink-0" data-copy="{{ $order->no_resi }}" title="Salin Nomor Resi">
                                            <i class="fa-regular fa-copy mr-1"></i>Salin
                                        </button>
                                    </div>

                                    <div class="d-flex flex-column" style="gap: 7px;">
                                        <!-- Cetak Label Resi Thermal -->
                                        <a href="{{ route('order.shippingLabel', $order->id_order) }}" target="_blank"
                                           class="btn btn-dark btn-sm logistics-action-btn font-weight-bold shadow-sm"
                                           title="Cetak Label Pengiriman Barcode Ukuran 100x150 mm">
                                            <i class="fa-solid fa-barcode"></i>
                                            <span>Cetak Label Thermal (100x150)</span>
                                        </a>

                                        @if($order->biteship_tracking_url)
                                            <!-- Lacak Pengiriman Real-Time -->
                                            <a href="{{ $order->biteship_tracking_url }}" target="_blank"
                                               class="btn btn-outline-info btn-sm logistics-action-btn font-weight-bold"
                                               title="Pantau posisi kurir secara real-time">
                                                <i class="fa-solid fa-location-arrow"></i>
                                                <span>Lacak Pengiriman Real-Time</span>
                                            </a>
                                        @endif

                                        @php
                                            $cleanHp = preg_replace('/[^0-9]/', '', $order->no_hp);
                                            if (str_starts_with($cleanHp, '0')) {
                                                $cleanHp = '62' . substr($cleanHp, 1);
                                            }
                                            $waText = "Halo kak *" . $order->nama_customer . "*,\n\nPesanan Anda *" . $order->kode_order . "* telah kami kirimkan!\n\nKurir: *" . ($order->kurir ?? $order->ekspedisi ?? 'Ekspedisi') . "*\nNomor Resi: *" . $order->no_resi . "*\n\nLacak pesanan Anda di: " . url('/lacak-pesanan?order=' . $order->kode_order) . "\n\nTerima kasih telah berbelanja di toko kami!";
                                            $waResiUrl = "https://wa.me/" . $cleanHp . "?text=" . urlencode($waText);
                                        @endphp
                                        <!-- Kirim Resi ke WhatsApp Pembeli -->
                                        <a href="{{ $waResiUrl }}" target="_blank"
                                           class="btn btn-outline-success btn-sm logistics-action-btn font-weight-bold"
                                           title="Kirim pesan rincian resi ke WhatsApp pembeli">
                                            <i class="fa-brands fa-whatsapp"></i>
                                            <span>Kirim Resi ke WhatsApp</span>
                                        </a>

                                        <!-- Tombol Cepat: Simulasi Paket Tiba & Selesai -->
                                        <form action="{{ route('order.updateStatus', $order->id_order) }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="btn btn-success btn-sm logistics-action-btn font-weight-bold shadow-sm"
                                                    style="background: #059669; border-color: #059669;"
                                                    onclick="return confirm('Simulasikan paket telah sampai dan selesaikan pesanan ini?')">
                                                <i class="fa-solid fa-circle-check"></i>
                                                <span>Tandai Paket Sampai (Selesai)</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif($order->status === 'diproses' || ($order->status === 'pending' && $order->metode_pembayaran === 'cod'))
                                <!-- Siap Request Pick-up Kurir -->
                                <div class="mb-3 p-3 rounded" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1px solid #fde68a; border-radius: 12px;">
                                    <div class="d-flex align-items-center mb-1" style="gap: 6px;">
                                        <i class="fa-solid fa-truck-fast text-warning" style="font-size: 15px;"></i>
                                        <strong style="color: #92400e; font-size: 13.5px;">Resi Otomatis &amp; Penjemputan Kurir</strong>
                                    </div>
                                    <p class="small text-muted mb-2.5" style="font-size: 12px; line-height: 1.4;">
                                        Pesanan sudah siap dikirim. Buat resi otomatis dan jadwalkan kurir <strong>{{ $order->ekspedisi ?: 'Ekspedisi' }}</strong> menjemput paket ke toko.
                                    </p>
                                    <form action="{{ route('order.requestPickup', $order->id_order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-block font-weight-bold text-dark py-2 shadow-sm logistics-action-btn"
                                                style="background: #f59e0b; border-color: #f59e0b; font-size: 13.5px;">
                                            <i class="fa-solid fa-truck-pickup mr-1"></i>
                                            <span>Request Pick-up Kurir</span>
                                        </button>
                                    </form>
                                </div>
                            @elseif($order->status === 'pending' && $order->metode_pembayaran === 'midtrans')
                                <!-- Menunggu Pembayaran Online Midtrans -->
                                <div class="mb-3 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                    <div class="d-flex align-items-center mb-1" style="gap: 6px;">
                                        <i class="fa-solid fa-clock text-warning"></i>
                                        <strong class="text-dark" style="font-size: 13px;">Menunggu Pembayaran Pembeli</strong>
                                    </div>
                                    <p class="small text-muted mb-2" style="font-size: 11.5px; line-height: 1.4;">
                                        Pembeli belum menyelesaikan pembayaran online. Jika pembeli sudah transfer di simulator/sandbox, klik sinkronkan di bawah.
                                    </p>
                                    <a href="{{ route('order.checkMidtrans', $order->id_order) }}"
                                       class="btn btn-outline-info btn-block btn-sm font-weight-bold py-1.5 logistics-action-btn">
                                        <i class="fa-solid fa-rotate mr-1"></i>
                                        <span>Cek Status Pembayaran (Sync)</span>
                                    </a>
                                </div>
                            @endif

                            <!-- Form Update Status Manual -->
                            <form action="{{ route('order.updateStatus', $order->id_order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @php
                                    $allowedNext = $order->allowedNextStatuses();
                                    $statusOptions = [
                                        'pending' => 'Menunggu Pembayaran / Konfirmasi',
                                        'diproses' => 'Diproses (Packing & Siap Kirim)',
                                        'dikirim' => 'Dikirim (Shipping)',
                                        'selesai' => 'Selesai (Delivered)',
                                        'dibatalkan' => 'Dibatalkan',
                                    ];
                                @endphp
                                <div class="form-group mb-2">
                                    <label for="status" class="font-weight-bold text-dark small mb-1">Ubah Status Pesanan:</label>
                                    <select name="status" id="status" class="form-control custom-select" required {{ empty($allowedNext) ? 'disabled' : '' }}
                                            style="border-radius: 10px; font-size: 13px;">
                                        @foreach($statusOptions as $val => $label)
                                            @if($val === $order->status || in_array($val, $allowedNext))
                                                <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>{{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                @php
                                    $ekspedisiPresets = ['JNE', 'J&T', 'SiCepat', 'AnterAja', 'POS Indonesia', 'TIKI', 'Ninja Xpress', 'Lion Parcel', 'GoSend / Grab', 'Kurir Toko'];
                                    $currentEks = $order->ekspedisi;
                                @endphp
                                <div class="form-group mb-2">
                                    <label for="ekspedisi" class="font-weight-bold text-dark small mb-1">Ekspedisi / Kurir:</label>
                                    <select name="ekspedisi" id="ekspedisi" class="form-control custom-select" style="border-radius: 10px; font-size: 13px;">
                                        <option value="">-- Pilih Ekspedisi --</option>
                                        @if($currentEks && !in_array($currentEks, $ekspedisiPresets))
                                            <option value="{{ $currentEks }}" selected>{{ $currentEks }}</option>
                                        @endif
                                        @foreach($ekspedisiPresets as $eks)
                                            <option value="{{ $eks }}" {{ strcasecmp((string) $currentEks, $eks) === 0 ? 'selected' : '' }}>{{ $eks }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="no_resi" class="font-weight-bold text-dark small mb-1">
                                        Nomor Resi Pengiriman <span id="resi-required-mark" class="text-danger" style="display: none;">*</span>:
                                    </label>
                                    <input type="text" name="no_resi" id="no_resi" class="form-control"
                                           placeholder="Contoh: JP1234567890" value="{{ $order->no_resi }}"
                                           style="border-radius: 10px; font-size: 13px;">
                                    <small class="text-muted" style="font-size: 11px;">Wajib diisi saat status diubah ke <em>Dikirim</em>.</small>
                                </div>

                                @if(!empty($allowedNext))
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm logistics-action-btn"
                                        style="background: #4f46e5; border-color: #4f46e5; font-size: 13.5px;">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i>
                                    <span>Simpan Status Baru</span>
                                </button>
                                @else
                                <div class="p-2 rounded bg-light border text-center small text-muted">
                                    Pesanan berstatus akhir (<strong>{{ ucfirst($order->status) }}</strong>).
                                </div>
                                @endif
                            </form>

                            <script>
                                (function() {
                                    var statusEl = document.getElementById('status');
                                    var resiEl = document.getElementById('no_resi');
                                    var markEl = document.getElementById('resi-required-mark');
                                    if (!statusEl || !resiEl) return;
                                    function syncResiReq() {
                                        var needResi = statusEl.value === 'dikirim';
                                        resiEl.required = needResi;
                                        if (markEl) markEl.style.display = needResi ? 'inline' : 'none';
                                    }
                                    statusEl.addEventListener('change', syncResiReq);
                                    syncResiReq();
                                })();
                            </script>
                        </div>
                    </div>

                    <!-- Informasi Pembeli & Alamat Card -->
                    <div class="card shadow-sm border-0 mb-3" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center" style="gap: 8px;">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                  style="width: 32px; height: 32px; background: #ecfdf5; color: #059669;">
                                <i class="fa-solid fa-user-check" style="font-size: 14px;"></i>
                            </span>
                            <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">Informasi Pembeli</h6>
                        </div>

                        <div class="card-body p-3 p-md-3.5">
                            <!-- Customer Profile Header -->
                            <div class="d-flex align-items-center mb-3" style="gap: 10px;">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle font-weight-bold text-white flex-shrink-0"
                                      style="width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); font-size: 16px;">
                                    {{ strtoupper(substr($order->nama_customer ?: 'U', 0, 1)) }}
                                </span>
                                <div class="overflow-hidden">
                                    <h6 class="font-weight-bold text-dark mb-0 text-truncate">{{ $order->nama_customer }}</h6>
                                    <small class="text-muted d-block">ID Pembeli: #{{ $order->user_id ?: 'Guest' }}</small>
                                </div>
                            </div>

                            <!-- Key-Value Info List -->
                            <div class="d-flex flex-column" style="gap: 9px; font-size: 13px;">
                                <!-- WhatsApp -->
                                <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom flex-wrap" style="gap: 6px;">
                                    <span class="text-muted"><i class="fa-brands fa-whatsapp text-success mr-1"></i> WhatsApp</span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->no_hp) }}" target="_blank"
                                       class="badge rounded-pill px-2.5 py-1 font-weight-bold d-inline-flex align-items-center"
                                       style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; gap: 4px;">
                                        {{ $order->no_hp }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 9.5px;"></i>
                                    </a>
                                </div>

                                <!-- Alamat Pengiriman -->
                                <div class="py-1.5 border-bottom">
                                    <span class="text-muted d-block mb-1"><i class="fa-solid fa-location-dot text-danger mr-1"></i> Alamat Pengiriman:</span>
                                    <div class="font-weight-500 text-dark word-break">{{ $order->alamat }}</div>
                                    @if($order->destination_label)
                                        <div class="small text-primary font-weight-500 mt-1 p-2 rounded word-break" style="background: #f8faff; border: 1px dashed #c7d2fe;">
                                            <i class="fa-solid fa-map-pin mr-1"></i> {{ $order->destination_label }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Kurir Pengiriman -->
                                <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom flex-wrap" style="gap: 6px;">
                                    <span class="text-muted"><i class="fa-solid fa-truck-fast text-primary mr-1"></i> Kurir Pengiriman</span>
                                    <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                                        {{ $order->kurir ?? $order->ekspedisi ?? '-' }}
                                        @if($order->estimasi)
                                            <small class="text-muted ml-0.5">({{ $order->estimasi }})</small>
                                        @endif
                                    </span>
                                </div>

                                <!-- Metode Pembayaran -->
                                <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom flex-wrap" style="gap: 6px;">
                                    <span class="text-muted"><i class="fa-solid fa-credit-card text-info mr-1"></i> Pembayaran</span>
                                    <div class="text-right">
                                        @if($order->metode_pembayaran === 'midtrans' || $order->metode_pembayaran === 'online')
                                            <span class="badge rounded-pill px-2 py-0.5 font-weight-bold" style="background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 11px;">
                                                Online (Instan)
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-2 py-0.5 font-weight-bold" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; font-size: 11px;">
                                                COD (Bayar di Tempat)
                                            </span>
                                        @endif
                                        @if($order->paid_at)
                                            <small class="text-success d-block font-weight-bold mt-0.5"><i class="fa-solid fa-circle-check mr-0.5"></i> Lunas: {{ $order->paid_at->format('d/m/Y H:i') }}</small>
                                        @elseif($order->metode_pembayaran === 'midtrans')
                                            <small class="text-warning d-block mt-0.5"><i class="fa-regular fa-clock mr-0.5"></i> Belum dibayar</small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Catatan Pesanan -->
                                @if($order->catatan)
                                <div class="py-1.5">
                                    <span class="text-muted d-block mb-1"><i class="fa-solid fa-comment-dots text-secondary mr-1"></i> Catatan Pembeli:</span>
                                    <div class="p-2 rounded bg-light border small text-dark fst-italic word-break">"{{ $order->catatan }}"</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KANAN: Daftar Produk Dipesan & Rekap Finansial -->
                <div class="col-lg-7 col-xl-8 mb-3">
                    <div class="card shadow-sm border-0 mb-3" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                      style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7;">
                                    <i class="fa-solid fa-box-open" style="font-size: 14px;"></i>
                                </span>
                                <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">
                                    Rincian Produk Dipesan
                                    <span class="badge badge-light border text-muted font-weight-normal px-2 py-0.5 ml-1" style="font-size: 11.5px;">
                                        {{ $order->items->sum('qty') ?: ($order->items->count() ?: 1) }} item barang
                                    </span>
                                </h6>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="width: 100%; min-width: 580px;">
                                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 11px; letter-spacing: 0.04em;">
                                        <tr>
                                            <th style="width: 45px;" class="text-center text-muted font-weight-bold">#</th>
                                            <th style="width: 70px;" class="text-center text-muted font-weight-bold">FOTO</th>
                                            <th class="text-muted font-weight-bold">NAMA PRODUK</th>
                                            <th class="text-center text-muted font-weight-bold" style="width: 130px;">HARGA SATUAN</th>
                                            <th class="text-center text-muted font-weight-bold" style="width: 80px;">QTY</th>
                                            <th class="text-right text-muted font-weight-bold" style="width: 140px;">SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                        <tr>
                                            <td class="text-center align-middle text-muted small font-weight-bold">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="text-center align-middle">
                                                @if ($item->barang && $item->barang->gambar)
                                                    <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->nama_barang }}"
                                                         class="rounded" style="width: 52px; height: 52px; object-fit: cover; border: 1px solid #e2e8f0;">
                                                @else
                                                    <div class="d-inline-flex align-items-center justify-content-center rounded bg-light border text-muted"
                                                         style="width: 52px; height: 52px;">
                                                        <i class="fa-solid fa-box" style="font-size: 20px;"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold text-dark" style="font-size: 14px;">{{ $item->nama_barang }}</div>
                                                @if($item->variasi)
                                                    <span class="badge rounded-pill px-2 py-0.5 mt-1 d-inline-flex align-items-center"
                                                          style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 11px; gap: 4px;">
                                                        <i class="fa-solid fa-tag"></i> {{ $item->variasi }}
                                                    </span>
                                                @endif
                                                @if($item->barang)
                                                    <small class="text-muted d-block mt-0.5" style="font-size: 11px;">
                                                        Kategori: {{ $item->barang->kategori }} &middot; Sisa Stok: {{ $item->barang->stok }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle font-weight-500" style="font-size: 13.5px;">
                                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-light border px-2.5 py-1 font-weight-bold font-monospace" style="font-size: 12.5px;">
                                                    {{ $item->qty }}
                                                </span>
                                            </td>
                                            <td class="text-right align-middle font-weight-bold text-dark" style="font-size: 14px;">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Financial Breakdown Footer Summary -->
                            <div class="p-3 p-md-4 border-top" style="background: #fafbfc;">
                                <div class="row justify-content-end">
                                    <div class="col-md-8 col-lg-7">
                                        <div class="d-flex flex-column" style="gap: 8px; font-size: 13px;">
                                            <!-- Subtotal Barang -->
                                            <div class="d-flex justify-content-between text-muted">
                                                <span>Subtotal Produk ({{ $order->items->sum('qty') ?: 1 }} barang):</span>
                                                <strong class="text-dark">
                                                    Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}
                                                </strong>
                                            </div>

                                            <!-- Diskon Voucher -->
                                            @if($order->diskon > 0)
                                            <div class="d-flex justify-content-between text-success">
                                                <span>
                                                    <i class="fa-solid fa-ticket mr-1"></i> Diskon Voucher ({{ $order->kode_voucher }}):
                                                </span>
                                                <strong class="text-success">
                                                    - Rp {{ number_format($order->diskon, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                            @endif

                                            <!-- Ongkos Kirim -->
                                            <div class="d-flex justify-content-between text-muted">
                                                <span>
                                                    <i class="fa-solid fa-truck mr-1"></i> Ongkos Kirim ({{ $order->kurir ?? $order->ekspedisi ?? 'Kurir' }}):
                                                </span>
                                                <strong class="{{ ($order->ongkir ?? 0) > 0 ? 'text-dark' : 'text-success' }}">
                                                    {{ ($order->ongkir ?? 0) > 0 ? 'Rp ' . number_format($order->ongkir, 0, ',', '.') : 'Gratis' }}
                                                </strong>
                                            </div>

                                            <hr class="my-1 border-top">

                                            <!-- Total Pembayaran -->
                                            <div class="d-flex justify-content-between align-items-center pt-1 flex-wrap" style="gap: 6px;">
                                                <div>
                                                    <strong class="text-dark text-uppercase font-weight-bold" style="font-size: 13px; letter-spacing: 0.5px;">TOTAL PEMBAYARAN:</strong>
                                                    <small class="text-muted d-block" style="font-size: 11px;">Termasuk ongkos kirim &amp; diskon promo</small>
                                                </div>
                                                <span class="font-weight-bold text-success" style="font-size: 20px; font-family: 'Outfit', sans-serif;">
                                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Stepper Styles & Connected Line */
.order-stepper-container {
    position: relative;
}
.stepper-track-bg {
    position: absolute;
    top: 20px;
    left: 12.5%;
    right: 12.5%;
    height: 3px;
    background: #e2e8f0;
    border-radius: 99px;
    z-index: 1;
}
.stepper-track-active {
    position: absolute;
    top: 20px;
    left: 12.5%;
    height: 3px;
    background: linear-gradient(90deg, #10b981 0%, #4f46e5 50%, #7c3aed 100%);
    border-radius: 99px;
    z-index: 2;
    transition: width 0.4s ease;
}
.stepper-circle {
    width: 38px;
    height: 38px;
    border: 3px solid #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
.step-done {
    background: #10b981;
    color: #ffffff;
}
.step-done-blue {
    background: #4f46e5;
    color: #ffffff;
}
.step-done-purple {
    background: #7c3aed;
    color: #ffffff;
}
.step-active {
    background: #eef2ff;
    color: #4f46e5;
    border-color: #c7d2fe !important;
}
.step-idle {
    background: #f1f5f9;
    color: #94a3b8;
}
.stepper-label {
    font-size: 12.5px;
}
.stepper-sub {
    font-size: 11px;
}

/* Action Buttons Responsive (No Overflow) */
.logistics-action-btn {
    white-space: normal !important;
    word-break: break-word;
    line-height: 1.35;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 8.5px 12px !important;
    font-size: 12.5px !important;
    border-radius: 10px;
    gap: 6px;
    width: 100%;
}
.word-break {
    word-break: break-word;
    overflow-wrap: break-word;
}

/* Mobile Tweaks */
@media (max-width: 575.98px) {
    .stepper-circle {
        width: 30px;
        height: 30px;
        font-size: 11px;
    }
    .stepper-label {
        font-size: 11px;
    }
    .stepper-sub {
        font-size: 9.5px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $(document).on('click', '.js-copy-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var text = $(this).data('copy');
        if (!text) return;
        
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text);
        } else {
            var temp = $('<input>');
            $('body').append(temp);
            temp.val(text).select();
            document.execCommand('copy');
            temp.remove();
        }

        if (typeof Swal !== 'undefined' && Swal.mixin) {
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1800
            }).fire({
                icon: 'success',
                title: 'Tersalin: ' + text
            });
        }
    });
});
</script>

@endsection
