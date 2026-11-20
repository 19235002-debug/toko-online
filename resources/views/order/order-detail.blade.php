@extends('template.main')
@section('title', 'Detail Pesanan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fa-solid fa-file-invoice mr-2"></i>Invoice {{ $order->kode_order }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Pesanan</a></li>
                        <li class="breadcrumb-item active">{{ $order->kode_order }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- KIRI: Detail Pelanggan & Form Status -->
                <div class="col-lg-4">
                    <!-- Status Card -->
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-tasks mr-1"></i> Status Pesanan
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 text-center">
                                @if($order->status == 'pending')
                                    <span class="badge badge-warning text-white px-3 py-2 fs-14"><i class="fas fa-clock mr-1"></i> Menunggu Proses (Pending)</span>
                                @elseif($order->status == 'diproses')
                                    <span class="badge badge-info px-3 py-2 fs-14"><i class="fas fa-spinner fa-spin mr-1"></i> Sedang Diproses</span>
                                @elseif($order->status == 'dikirim')
                                    <span class="badge badge-primary px-3 py-2 fs-14"><i class="fas fa-truck mr-1"></i> Sedang Dikirim</span>
                                @elseif($order->status == 'selesai')
                                    <span class="badge badge-success px-3 py-2 fs-14"><i class="fas fa-check-circle mr-1"></i> Selesai</span>
                                @else
                                    <span class="badge badge-danger px-3 py-2 fs-14"><i class="fas fa-times-circle mr-1"></i> Dibatalkan</span>
                                @endif
                            </div>

                            @if(in_array($order->status, ['diproses', 'pending']) && $order->status !== 'dibatalkan')
                            <div class="mb-3 p-3 rounded" style="background: linear-gradient(135deg, #fef3c7, #fef9c3); border: 1px solid #fde047;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-bolt text-warning mr-2" style="font-size: 16px;"></i>
                                    <strong style="color: #92400e; font-size: 13px;">Resi Otomatis &amp; Penjemputan Biteship</strong>
                                </div>
                                <p class="small text-muted mb-2">Klik tombol di bawah untuk membuat resi otomatis dan menjadwalkan kurir <strong>{{ $order->ekspedisi ?: 'JNE/J&T' }}</strong> menjemput paket ke toko.</p>
                                <form action="{{ route('order.requestPickup', $order->id_order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-block font-weight-bold shadow-sm" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #fff;">
                                        <i class="fa-solid fa-truck-pickup mr-1"></i> Request Pick-up Kurir (Otomatis)
                                    </button>
                                </form>
                            </div>
                            @endif

                            @if($order->no_resi)
                            <div class="mb-3">
                                <a href="{{ route('order.shippingLabel', $order->id_order) }}" target="_blank" class="btn btn-dark btn-block font-weight-bold">
                                    <i class="fa fa-barcode mr-1"></i> Cetak Label Resi Thermal (100x150)
                                </a>
                                @if($order->biteship_tracking_url)
                                <a href="{{ $order->biteship_tracking_url }}" target="_blank" class="btn btn-outline-info btn-block btn-sm mt-1">
                                    <i class="fa fa-location-arrow mr-1"></i> Lacak Pengiriman di Portal Biteship
                                </a>
                                @endif
                            </div>
                            @endif

                            <form action="{{ route('order.updateStatus', $order->id_order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @php
                                    $allowedNext = $order->allowedNextStatuses();
                                    $statusOptions = [
                                        'pending' => 'Menunggu Pembayaran / Konfirmasi',
                                        'diproses' => 'Diproses (Packing)',
                                        'dikirim' => 'Dikirim (Shipping)',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                    ];
                                @endphp
                                <div class="form-group">
                                    <label for="status" class="font-weight-bold">Ubah Status:</label>
                                    <select name="status" id="status" class="form-control" required {{ empty($allowedNext) ? 'disabled' : '' }}>
                                        @foreach($statusOptions as $val => $label)
                                            @if($val === $order->status || in_array($val, $allowedNext))
                                                <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>{{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if(empty($allowedNext))
                                        <small class="text-muted d-block mt-1">Pesanan sudah berstatus akhir (<strong>{{ ucfirst($order->status) }}</strong>).</small>
                                    @else
                                        <small class="text-muted d-block mt-1">
                                            *Alur: Pending → Diproses → Dikirim → Selesai. Jika dibatalkan, stok produk dikembalikan otomatis.
                                        </small>
                                    @endif
                                </div>

                                @php
                                    $ekspedisiPresets = ['JNE', 'J&T', 'SiCepat', 'AnterAja', 'POS Indonesia', 'TIKI', 'Ninja Xpress', 'Lion Parcel', 'GoSend / Grab', 'Kurir Toko'];
                                    $currentEks = $order->ekspedisi;
                                @endphp
                                <div class="form-group mb-2">
                                    <label for="ekspedisi" class="font-weight-bold">Ekspedisi / Kurir:</label>
                                    <select name="ekspedisi" id="ekspedisi" class="form-control form-control-sm">
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
                                    <label for="no_resi" class="font-weight-bold">Nomor Resi Pengiriman <span id="resi-required-mark" class="text-danger" style="display: none;">*</span>:</label>
                                    <input type="text" name="no_resi" id="no_resi" class="form-control form-control-sm" placeholder="Contoh: JP1234567890" value="{{ $order->no_resi }}">
                                    <small class="text-muted">Wajib diisi saat mengubah status ke <em>Dikirim</em>.</small>
                                </div>

                                @if(!empty($allowedNext))
                                <button type="submit" class="btn btn-primary btn-block btn-action">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Status Baru
                                </button>
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

                            @if($order->status == 'dikirim' && $order->no_resi)
                            @php
                                $cleanHp = preg_replace('/[^0-9]/', '', $order->no_hp);
                                if (str_starts_with($cleanHp, '0')) {
                                    $cleanHp = '62' . substr($cleanHp, 1);
                                }
                                $waText = "Halo kak *" . $order->nama_customer . "*,\n\nPesanan Anda *" . $order->kode_order . "* telah kami kirimkan!\n\nKurir: *" . ($order->ekspedisi ?? 'Ekspedisi') . "*\nNomor Resi: *" . $order->no_resi . "*\n\nLacak pesanan Anda di: " . url('/lacak-pesanan?order=' . $order->kode_order) . "\n\nTerima kasih telah berbelanja di toko kami!";
                                $waResiUrl = "https://wa.me/" . $cleanHp . "?text=" . urlencode($waText);
                            @endphp
                            <div class="mt-2">
                                <a href="{{ $waResiUrl }}" target="_blank" class="btn btn-success btn-sm btn-block">
                                    <i class="fab fa-whatsapp mr-1"></i> Kirim Resi ke WhatsApp Pembeli
                                </a>
                            </div>
                            @endif

                            @if($order->metode_pembayaran == 'midtrans')
                            <div class="mt-3 pt-2 border-top text-center">
                                <small class="text-muted d-block">
                                    <i class="fa-solid fa-cloud-arrow-down text-info mr-1"></i> Terhubung & sinkron otomatis dengan Midtrans
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info Pelanggan Card -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-user mr-1"></i> Informasi Pembeli
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th style="width: 35%;" class="text-muted">Nama:</th>
                                    <td class="font-weight-bold">{{ $order->nama_customer }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">WhatsApp:</th>
                                    <td>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->no_hp) }}" target="_blank" class="btn btn-xs btn-outline-success">
                                            <i class="fab fa-whatsapp mr-1"></i> {{ $order->no_hp }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Alamat:</th>
                                    <td>
                                        {{ $order->alamat }}
                                        @if($order->destination_label)
                                            <div class="small text-primary font-weight-bold mt-1"><i class="fas fa-map-marker-alt mr-1"></i> {{ $order->destination_label }}</div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Kurir Dipilih:</th>
                                    <td>
                                        <span class="badge badge-light border text-dark px-2 py-1">{{ $order->kurir ?? $order->ekspedisi ?? '-' }}</span>
                                        @if($order->estimasi)
                                            <small class="text-muted ml-1">({{ $order->estimasi }})</small>
                                        @endif
                                        @if($order->berat)
                                            <small class="text-muted d-block">Berat estimasi: {{ number_format($order->berat / 1000, 2) }} kg</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Catatan:</th>
                                    <td>{{ $order->catatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Pembayaran:</th>
                                    <td>
                                        @if($order->metode_pembayaran == 'midtrans')
                                            <span class="badge badge-primary px-2 py-1"><i class="fa fa-credit-card mr-1"></i> Midtrans</span>
                                            @if($order->payment_type)
                                                <small class="text-muted text-uppercase ml-1">({{ str_replace('_', ' ', $order->payment_type) }})</small>
                                            @endif
                                        @else
                                            <span class="badge badge-info text-uppercase px-2 py-1">{{ $order->metode_pembayaran }}</span>
                                        @endif
                                        @if($order->paid_at)
                                            <small class="text-success d-block"><i class="fas fa-check-circle mr-1"></i> Lunas: {{ $order->paid_at->format('d M Y, H:i') }}</small>
                                        @elseif($order->metode_pembayaran == 'midtrans')
                                            <small class="text-warning d-block"><i class="fas fa-clock mr-1"></i> Belum dibayar</small>
                                        @endif
                                    </td>
                                </tr>
                                @if($order->snap_token)
                                <tr>
                                    <th class="text-muted">Snap Token:</th>
                                    <td><code class="text-xs" title="{{ $order->snap_token }}">{{ Str::limit($order->snap_token, 18) }}</code></td>
                                </tr>
                                @endif
                                @if($order->no_resi)
                                <tr>
                                    <th class="text-muted">Ekspedisi:</th>
                                    <td>
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-truck mr-1"></i> {{ $order->ekspedisi ?? 'Kurir' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nomor Resi:</th>
                                    <td class="font-weight-bold font-monospace text-primary" style="font-size: 15px;">
                                        {{ $order->no_resi }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="text-muted">Waktu Order:</th>
                                    <td>{{ $order->created_at->format('d M Y, H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- KANAN: Daftar Produk Dipesan -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fa-solid fa-box-open mr-1"></i> Rincian Produk Dipesan
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('order.print', $order->id_order) }}" target="_blank" class="btn btn-success btn-sm btn-action mr-1">
                                    <i class="fa-solid fa-print mr-1"></i> Cetak Faktur
                                </a>
                                {!! btn_back(route('order.index')) !!}
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Foto</th>
                                            <th class="text-left">Nama Produk</th>
                                            <th>Harga Satuan</th>
                                            <th>Qty</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle" style="width: 70px;">
                                                @if ($item->barang && $item->barang->gambar)
                                                    <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->nama_barang }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted"><i class="fa fa-box fa-2x"></i></span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold">{{ $item->nama_barang }}</div>
                                                @if($item->variasi)
                                                    <span class="badge badge-info mb-1" style="font-size: 11px;">
                                                        <i class="fa fa-tags mr-1"></i> {{ $item->variasi }}
                                                    </span>
                                                @endif
                                                @if($item->barang)
                                                    <small class="text-muted d-block">Kategori: {{ $item->barang->kategori }} | Sisa Stok: {{ $item->barang->stok }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-light border px-2 py-1 font-weight-bold">{{ $item->qty }}</span>
                                            </td>
                                            <td class="text-right align-middle font-weight-bold">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        @if($order->diskon > 0)
                                        <tr class="bg-white">
                                            <th colspan="5" class="text-right text-success">
                                                <i class="fa fa-ticket mr-1"></i> Diskon Voucher ({{ $order->kode_voucher }}):
                                            </th>
                                            <th class="text-right text-success font-weight-bold">
                                                - Rp {{ number_format($order->diskon, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                        @endif
                                        <tr class="bg-white">
                                            <th colspan="5" class="text-right text-muted">
                                                <i class="fas fa-truck mr-1"></i> Ongkos Kirim ({{ $order->kurir ?? $order->ekspedisi ?? 'Kurir' }}):
                                            </th>
                                            <th class="text-right font-weight-bold {{ ($order->ongkir ?? 0) > 0 ? 'text-dark' : 'text-success' }}">
                                                {{ ($order->ongkir ?? 0) > 0 ? 'Rp ' . number_format($order->ongkir, 0, ',', '.') : 'Gratis' }}
                                            </th>
                                        </tr>
                                        <tr class="bg-light">
                                            <th colspan="5" class="text-right font-weight-bold fs-15">TOTAL PEMBAYARAN:</th>
                                            <th class="text-right font-weight-bold text-success fs-16">
                                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
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

