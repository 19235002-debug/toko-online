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

                            <form action="{{ route('order.updateStatus', $order->id_order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="status" class="font-weight-bold">Ubah Status:</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu Proses (Pending)</option>
                                        <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses (Packing)</option>
                                        <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim (Shipping)</option>
                                        <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    <small class="text-muted d-block mt-1">
                                        *Jika status diubah ke <em>Dibatalkan</em>, stok produk akan otomatis dikembalikan.
                                    </small>
                                </div>

                                <div class="form-group mb-2">
                                    <label for="ekspedisi" class="font-weight-bold">Ekspedisi / Kurir:</label>
                                    <select name="ekspedisi" id="ekspedisi" class="form-control form-control-sm">
                                        <option value="">-- Pilih Ekspedisi --</option>
                                        <option value="JNE" {{ $order->ekspedisi == 'JNE' ? 'selected' : '' }}>JNE Express</option>
                                        <option value="J&T" {{ $order->ekspedisi == 'J&T' ? 'selected' : '' }}>J&T Express</option>
                                        <option value="SiCepat" {{ $order->ekspedisi == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                                        <option value="Anteraja" {{ $order->ekspedisi == 'Anteraja' ? 'selected' : '' }}>Anteraja</option>
                                        <option value="Pos Indonesia" {{ $order->ekspedisi == 'Pos Indonesia' ? 'selected' : '' }}>Pos Indonesia</option>
                                        <option value="GoSend / Grab" {{ $order->ekspedisi == 'GoSend / Grab' ? 'selected' : '' }}>GoSend / Grab Express</option>
                                        <option value="Kurir Toko" {{ $order->ekspedisi == 'Kurir Toko' ? 'selected' : '' }}>Kurir Toko / Internal</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="no_resi" class="font-weight-bold">Nomor Resi Pengiriman:</label>
                                    <input type="text" name="no_resi" id="no_resi" class="form-control form-control-sm" placeholder="Contoh: JP1234567890" value="{{ $order->no_resi }}">
                                </div>

                                <button type="submit" class="btn btn-primary btn-block btn-action">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Status Baru
                                </button>
                            </form>

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
                                    <td>{{ $order->alamat }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Catatan:</th>
                                    <td>{{ $order->catatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Pembayaran:</th>
                                    <td>
                                        @if($order->metode_pembayaran == 'midtrans')
                                            <span class="badge badge-primary px-2 py-1"><i class="fa fa-credit-card mr-1"></i> Midtrans Sandbox</span>
                                        @else
                                            <span class="badge badge-info text-uppercase px-2 py-1">{{ $order->metode_pembayaran }}</span>
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

