<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Pengiriman - {{ $order->no_resi ?: $order->kode_order }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            color: #111;
        }
        body {
            background-color: #f3f4f6;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .no-print {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
        }
        .btn-print {
            background: #2563eb;
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-print:hover {
            background: #1d4ed8;
        }
        .btn-back {
            background: #fff;
            color: #374151;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Standard 100mm x 150mm Shipping Label Canvas */
        .shipping-label {
            width: 100mm;
            min-height: 145mm;
            background: #fff;
            border: 2px solid #000;
            padding: 8mm;
            position: relative;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .label-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .courier-badge {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .service-badge {
            background: #000;
            color: #fff;
            padding: 4px 10px;
            font-size: 13px;
            font-weight: 800;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .barcode-section {
            text-align: center;
            padding: 8px 0;
            border-bottom: 2px dashed #000;
            margin-bottom: 10px;
        }
        /* CSS Simulated Code128 Barcode */
        .barcode-stripes {
            height: 48px;
            display: inline-flex;
            align-items: stretch;
            justify-content: center;
            width: 82%;
            background: repeating-linear-gradient(
                90deg,
                #000 0px,
                #000 2px,
                #fff 2px,
                #fff 4px,
                #000 4px,
                #000 7px,
                #fff 7px,
                #fff 9px,
                #000 9px,
                #000 13px,
                #fff 13px,
                #fff 15px
            );
            margin-bottom: 4px;
        }
        .waybill-code {
            font-size: 17px;
            font-weight: 900;
            letter-spacing: 2px;
            font-family: monospace;
        }
        .order-ref {
            font-size: 11px;
            color: #4b5563;
            margin-top: 2px;
        }

        .payment-banner {
            background: {{ $order->metode_pembayaran === 'cod' ? '#dc2626' : '#16a34a' }};
            color: #fff;
            text-align: center;
            font-weight: 900;
            font-size: 13px;
            padding: 5px 0;
            border-radius: 4px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .address-box {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .addr-block {
            font-size: 11.5px;
            line-height: 1.45;
        }
        .addr-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            color: #4b5563;
            margin-bottom: 2px;
        }
        .addr-name {
            font-size: 13px;
            font-weight: 800;
        }
        .addr-phone {
            font-weight: 700;
        }

        .items-section {
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .items-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            color: #4b5563;
            margin-bottom: 4px;
        }
        .items-table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }
        .items-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .items-table td.qty {
            text-align: right;
            font-weight: 700;
            white-space: nowrap;
        }

        .footer-note {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #374151;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .shipping-label {
                border: 2px solid #000;
                box-shadow: none;
                margin: 0 auto;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="javascript:window.print();" class="btn-print">
            <i class="fa fa-print"></i> Cetak Label (100x150 / A6)
        </a>
        <a href="{{ route('order.show', $order->id_order) }}" class="btn-back">
            <i class="fa fa-arrow-left"></i> Kembali ke Pesanan
        </a>
    </div>

    <div class="shipping-label">
        <!-- Header: Ekspedisi & Layanan -->
        <div class="label-header">
            <div>
                <span class="courier-badge">{{ $order->kurir_kode ? strtoupper($order->kurir_kode) : $order->ekspedisi }}</span>
            </div>
            <div>
                <span class="service-badge">{{ $order->layanan_kurir ?: 'REGULER' }}</span>
            </div>
        </div>

        <!-- Barcode & Nomor Resi -->
        <div class="barcode-section">
            <div class="barcode-stripes"></div>
            <div class="waybill-code">{{ $order->no_resi ?: 'BELUM-GENERATE' }}</div>
            <div class="order-ref">Ref: #{{ $order->kode_order }} &bull; Berat: {{ $order->berat ?: 350 }}g</div>
        </div>

        <!-- Indikator Pembayaran -->
        <div class="payment-banner">
            @if($order->metode_pembayaran === 'cod')
                BAYAR DI TEMPAT (COD): Rp {{ number_format($order->total_harga, 0, ',', '.') }}
            @else
                NON-COD / CASHLESS &mdash; ONGKIR SUDAH LUNAS
            @endif
        </div>

        <!-- Detail Alamat Penerima & Pengirim -->
        <div class="address-box">
            <div class="addr-block">
                <div class="addr-title">PENERIMA:</div>
                <div class="addr-name">{{ $order->nama_customer }}</div>
                <div class="addr-phone">{{ $order->no_hp }}</div>
                <div>{{ $order->alamat }}</div>
                @if($order->destination_label)
                    <div style="font-weight: 700; margin-top: 2px;">{{ $order->destination_label }}</div>
                @endif
                @if($order->catatan)
                    <div style="font-style: italic; color: #4b5563; margin-top: 2px;">Catatan: "{{ $order->catatan }}"</div>
                @endif
            </div>

            <div class="addr-block" style="border-top: 1px dashed #9ca3af; padding-top: 6px;">
                <div class="addr-title">PENGIRIM:</div>
                <div class="addr-name">{{ $setting->nama_toko ?? 'Kelompok 3 Fashion Store' }}</div>
                <div class="addr-phone">{{ $setting->no_whatsapp ?? '081234567890' }}</div>
                <div>{{ $setting->alamat_toko ?? 'Jakarta Pusat, DKI Jakarta' }}</div>
            </div>
        </div>

        <!-- Ringkasan Isi Paket -->
        <div class="items-section">
            <div class="items-title">ISI PAKET ({{ $order->items->sum('qty') ?: $order->items->sum('jumlah') }} BARANG):</div>
            <table class="items-table">
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            &bull; {{ $item->nama_barang ?? ($item->barang->nama ?? 'Produk') }}
                            @if($item->variasi)
                                <small style="color: #4b5563;">({{ $item->variasi }})</small>
                            @endif
                        </td>
                        <td class="qty">&times; {{ $item->qty ?? $item->jumlah }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <!-- Footer Info -->
        <div class="footer-note">
            <div>Auto-Generated Official Shipping Label</div>
            <div>{{ now()->format('d/m/Y H:i') }} WIB</div>
        </div>
    </div>

</body>
</html>
