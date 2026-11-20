<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Pengiriman - {{ $order->no_resi ?: $order->kode_order }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            color: #000;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            padding: 30px 15px 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Top Modern Preview Toolbar */
        .preview-toolbar {
            width: 100%;
            max-width: 580px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 18px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
        }

        .toolbar-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .toolbar-title {
            font-size: 13.5px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .toolbar-sub {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action-print {
            background: #2563eb;
            color: #fff;
            padding: 9px 18px;
            border-radius: 10px;
            border: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
            text-decoration: none;
        }

        .btn-action-print:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-action-back {
            background: #f1f5f9;
            color: #334155;
            padding: 9px 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-action-back:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Paper Canvas Preview Container */
        .label-stage {
            position: relative;
            padding: 10px;
        }

        /* 100mm x 150mm Standard Thermal Shipping Label */
        .shipping-label {
            width: 100mm;
            min-height: 148mm;
            max-height: 150mm;
            background: #ffffff;
            border: 2px solid #000;
            padding: 5mm 6mm;
            position: relative;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* SECTION 1: HEADER & ROUTING HUB */
        .label-header {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 6px;
            align-items: center;
            gap: 6px;
        }

        .courier-brand-box {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .courier-name {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            line-height: 1;
        }

        .service-pill {
            background: #000;
            color: #fff;
            padding: 4px 8px;
            font-size: 13px;
            font-weight: 900;
            border-radius: 3px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-block;
        }

        .routing-hub-box {
            border: 1.5px solid #000;
            padding: 3px 6px;
            text-align: right;
            border-radius: 3px;
            background: #fafafa;
        }

        .routing-hub-label {
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #333;
            line-height: 1;
        }

        .routing-hub-code {
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.5px;
            line-height: 1.2;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* SECTION 2: BARCODE & TRACKING CODE */
        .barcode-section {
            border-bottom: 2px dashed #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
            text-align: center;
        }

        .barcode-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .barcode-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #barcode-svg {
            width: 100%;
            max-width: 66mm;
            height: 44px;
            display: block;
        }

        /* Fallback CSS stripes if JS / SVG is not loaded */
        .fallback-barcode-stripes {
            height: 44px;
            width: 95%;
            background: repeating-linear-gradient(
                90deg,
                #000 0px, #000 2px, #fff 2px, #fff 4px,
                #000 4px, #000 7px, #fff 7px, #fff 9px,
                #000 9px, #000 12px, #fff 12px, #fff 14px
            );
            margin-bottom: 2px;
        }

        .qrcode-box {
            width: 48px;
            height: 48px;
            border: 1px solid #000;
            padding: 2px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fff;
            flex-shrink: 0;
            border-radius: 2px;
        }

        #qrcode canvas, #qrcode img {
            width: 42px !important;
            height: 42px !important;
            display: block;
        }

        .waybill-number {
            font-family: 'Space Mono', monospace, Courier, sans-serif;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-top: 3px;
            line-height: 1;
        }

        .meta-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 10px;
            font-weight: 700;
            margin-top: 4px;
            color: #222;
        }

        /* SECTION 3: PAYMENT BANNER (COD vs CASHLESS) */
        .payment-banner {
            border: 2px solid #000;
            padding: 4px 6px;
            margin-bottom: 6px;
            text-align: center;
            border-radius: 3px;
        }

        .payment-banner.is-cod {
            background: #000;
            color: #fff;
        }

        .payment-banner.is-cod * {
            color: #fff !important;
        }

        .payment-banner.is-cashless {
            background: #fff;
            color: #000;
        }

        .payment-banner-title {
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .payment-banner-sub {
            font-size: 9.5px;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 1px;
        }

        /* SECTION 4: ADDRESS SECTION */
        .address-section {
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .recipient-box {
            border: 1.5px solid #000;
            padding: 5px 7px;
            border-radius: 3px;
            background: #fff;
        }

        .sender-box {
            border: 1px dashed #555;
            padding: 4px 7px;
            border-radius: 3px;
            background: #fafafa;
        }

        .section-tag {
            font-size: 8.5px;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #444;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 2px;
        }

        .recipient-name {
            font-size: 13.5px;
            font-weight: 900;
            line-height: 1.2;
        }

        .recipient-phone {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 2px;
        }

        .recipient-address {
            font-size: 11px;
            line-height: 1.35;
            color: #111;
        }

        .recipient-highlight {
            margin-top: 3px;
            padding: 2.5px 5px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            font-size: 10px;
            font-weight: 800;
            border-radius: 2px;
            line-height: 1.3;
        }

        .order-note-box {
            margin-top: 3px;
            font-size: 9.5px;
            font-weight: 700;
            font-style: italic;
            border-left: 2px solid #000;
            padding-left: 4px;
            color: #222;
        }

        .sender-row {
            font-size: 10.5px;
            line-height: 1.35;
        }

        .sender-row strong {
            font-weight: 800;
        }

        /* SECTION 5: ITEMS & MANIFEST */
        .items-section {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8.5px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .items-table td {
            padding: 1.5px 0;
            vertical-align: top;
            line-height: 1.25;
        }

        .items-table td.qty-col {
            text-align: right;
            font-weight: 800;
            white-space: nowrap;
            width: 32px;
        }

        /* SECTION 6: FOOTER & FRAGILE NOTICE */
        .label-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 3px;
            font-size: 8.5px;
            font-weight: 700;
            color: #333;
        }

        .fragile-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1px solid #000;
            padding: 2px 5px;
            border-radius: 2px;
            font-size: 8.5px;
            font-weight: 900;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        /* PRINT MEDIA CALIBRATION (100mm x 150mm Exact Thermal Output) */
        @page {
            size: 100mm 150mm;
            margin: 0;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                min-height: auto !important;
            }

            .preview-toolbar {
                display: none !important;
            }

            .label-stage {
                padding: 0 !important;
            }

            .shipping-label {
                width: 100mm !important;
                height: 150mm !important;
                max-height: 150mm !important;
                border: 2px solid #000 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
                margin: 0 auto !important;
            }

            .payment-banner.is-cod {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: #000000 !important;
                color: #ffffff !important;
            }

            .service-pill {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: #000000 !important;
                color: #ffffff !important;
            }
        }
    </style>
</head>
<body>

    @php
        // Formatter nomor telepon Indonesia
        $formatHp = function($hp) {
            $clean = preg_replace('/[^0-9]/', '', (string)$hp);
            if (strlen($clean) >= 10) {
                return substr($clean, 0, 4) . '-' . substr($clean, 4, 4) . '-' . substr($clean, 8);
            }
            return $hp;
        };

        // Ekstraksi Kode Sortir / Hub Tujuan
        $destKec = '';
        $destCity = '';
        if (!empty($order->destination_label)) {
            if (preg_match('/Kec\.\s*([^,]+)/i', $order->destination_label, $m)) {
                $destKec = trim($m[1]);
            }
            if (preg_match('/(?:Kota|Kab\.|Kabupaten)\s*([^,]+)/i', $order->destination_label, $m)) {
                $destCity = trim($m[1]);
            }
        }
        $postal = $order->destination_postal_code;
        if (!$postal && preg_match('/\b\d{5}\b/', (string)$order->alamat . ' ' . (string)$order->destination_label, $m)) {
            $postal = $m[0];
        }

        $hubCode = strtoupper($destKec ?: ($destCity ?: 'DOMESTIK'));
        if ($postal) {
            $hubCode .= ' • ' . $postal;
        }

        $totalItems = $order->items->sum('qty') ?: $order->items->sum('jumlah');
        $weightGram = $order->berat ?: 350;
        $weightKg = number_format($weightGram / 1000, 2, ',', '.');
        $courierName = $order->kurir_kode ? strtoupper($order->kurir_kode) : ($order->ekspedisi ?: 'KURIR');
        $serviceName = strtoupper($order->layanan_kurir ?: 'REG');
        $isCod = ($order->metode_pembayaran === 'cod');
        $waybill = $order->no_resi ?: 'BS-' . strtoupper($courierName) . '-' . substr(md5($order->kode_order), 0, 10);
    @endphp

    <!-- Top Modern Preview Toolbar -->
    <div class="preview-toolbar">
        <div class="toolbar-info">
            <div class="toolbar-icon">
                <i class="fa-solid fa-barcode"></i>
            </div>
            <div>
                <div class="toolbar-title">Label Pengiriman Thermal</div>
                <div class="toolbar-sub">Ukuran 100×150 mm (4×6″ / A6 Sticker)</div>
            </div>
        </div>
        <div class="toolbar-actions">
            <button onclick="window.print()" class="btn-action-print" title="Cetak label ke printer thermal">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Label</span>
            </button>
            <a href="{{ route('order.show', $order->id_order) }}" class="btn-action-back" title="Kembali ke halaman rincian pesanan">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Paper Canvas Preview Container -->
    <div class="label-stage">
        <div class="shipping-label">

            <!-- SECTION 1: HEADER & ROUTING HUB -->
            <div>
                <div class="label-header">
                    <div class="courier-brand-box">
                        <span class="courier-name">{{ $courierName }}</span>
                        <span class="service-pill">{{ $serviceName }}</span>
                    </div>
                    <div class="routing-hub-box">
                        <div class="routing-hub-label">KODE SORTIR / HUB TUJUAN</div>
                        <div class="routing-hub-code" title="{{ $hubCode }}">{{ $hubCode }}</div>
                    </div>
                </div>

                <!-- SECTION 2: BARCODE & TRACKING CODE -->
                <div class="barcode-section">
                    <div class="barcode-row">
                        <div class="barcode-container">
                            <svg id="barcode-svg"></svg>
                            <div id="barcode-fallback" class="fallback-barcode-stripes" style="display: none;"></div>
                            <div class="waybill-number">{{ $waybill }}</div>
                        </div>
                        <div class="qrcode-box" title="Scan untuk melacak pengiriman paket">
                            <div id="qrcode"></div>
                        </div>
                    </div>
                    <div class="meta-badges">
                        <span><i class="fa-solid fa-file-invoice mr-1"></i>Ref: #{{ $order->kode_order }}</span>
                        <span>•</span>
                        <span><i class="fa-solid fa-weight-hanging mr-1"></i>Berat: {{ $weightKg }} Kg ({{ $weightGram }}g)</span>
                        <span>•</span>
                        <span><i class="fa-solid fa-calendar mr-1"></i>{{ $order->created_at ? $order->created_at->format('d/m/Y') : now()->format('d/m/Y') }}</span>
                    </div>
                </div>

                <!-- SECTION 3: PAYMENT BANNER (COD vs CASHLESS) -->
                @if($isCod)
                    <div class="payment-banner is-cod">
                        <div class="payment-banner-title">
                            ★ COD (BAYAR DI TEMPAT): Rp {{ number_format($order->total_harga, 0, ',', '.') }} ★
                        </div>
                        <div class="payment-banner-sub">
                            *Kurir WAJIB menagih uang tunai sebelum paket diserahkan ke penerima
                        </div>
                    </div>
                @else
                    <div class="payment-banner is-cashless">
                        <div class="payment-banner-title">
                            ✔ NON-COD / CASHLESS &mdash; ONGKIR SUDAH LUNAS
                        </div>
                        <div class="payment-banner-sub">
                            Dilarang menagih biaya apapun kepada penerima
                        </div>
                    </div>
                @endif

                <!-- SECTION 4: ADDRESS SECTION -->
                <div class="address-section">
                    <!-- Recipient -->
                    <div class="recipient-box">
                        <div class="section-tag">
                            <i class="fa-solid fa-location-dot"></i> PENERIMA:
                        </div>
                        <div class="recipient-name">{{ $order->nama_customer }}</div>
                        <div class="recipient-phone">{{ $formatHp($order->no_hp) }}</div>
                        <div class="recipient-address">{{ $order->alamat }}</div>
                        @if($order->destination_label)
                            <div class="recipient-highlight">
                                <i class="fa-solid fa-map-pin mr-1"></i>{{ $order->destination_label }}
                            </div>
                        @endif
                        @if($order->catatan)
                            <div class="order-note-box">
                                Catatan: "{{ $order->catatan }}"
                            </div>
                        @endif
                    </div>

                    <!-- Sender -->
                    <div class="sender-box">
                        <div class="section-tag">
                            <i class="fa-solid fa-store"></i> PENGIRIM:
                        </div>
                        <div class="sender-row">
                            <strong>{{ $setting->nama_toko ?? 'Coza Store' }}</strong>
                            &bull; {{ $formatHp($setting->no_whatsapp ?? '081234567890') }}
                        </div>
                        <div class="sender-row" style="color: #444; font-size: 10px;">
                            {{ $setting->alamat_toko ?? 'Jakarta Pusat, DKI Jakarta' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: ITEMS & MANIFEST -->
            <div>
                <div class="items-section">
                    <div class="items-header">
                        <span>ISI PAKET (MANIFEST)</span>
                        <span>TOTAL: {{ $totalItems }} BARANG</span>
                    </div>
                    <table class="items-table">
                        @php
                            $shownItems = $order->items->take(4);
                            $remainingCount = $order->items->count() - $shownItems->count();
                        @endphp
                        @foreach($shownItems as $item)
                            <tr>
                                <td>
                                    &bull; <strong>{{ $item->nama_barang ?? ($item->barang->nama ?? 'Produk') }}</strong>
                                    @if($item->variasi)
                                        <span style="color: #444;">({{ $item->variasi }})</span>
                                    @endif
                                </td>
                                <td class="qty-col">&times; {{ $item->qty ?? $item->jumlah }}</td>
                            </tr>
                        @endforeach
                        @if($remainingCount > 0)
                            <tr>
                                <td colspan="2" style="font-style: italic; color: #555; padding-top: 2px;">
                                    ... dan {{ $remainingCount }} produk lainnya
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

                <!-- SECTION 6: FOOTER & FRAGILE NOTICE -->
                <div class="label-footer">
                    <div class="fragile-badge">
                        <i class="fa-solid fa-wine-glass-empty"></i>
                        <span>FRAGILE - JANGAN DIBANTING</span>
                    </div>
                    <div>
                        {{ now()->format('d/m/Y H:i') }} WIB &bull; Standard Thermal
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script: High-Resolution Code128 Barcode & QR Code Generator -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var waybillText = "{{ $waybill }}";
            var trackUrl = "{{ url('/lacak-pesanan?order=' . $order->kode_order) }}";

            // 1. Generate Sharp Vector Code128 Barcode
            try {
                if (typeof JsBarcode === 'function') {
                    JsBarcode("#barcode-svg", waybillText, {
                        format: "CODE128",
                        width: 1.85,
                        height: 40,
                        displayValue: false,
                        margin: 0,
                        lineColor: "#000000"
                    });
                } else {
                    document.getElementById('barcode-fallback').style.display = 'block';
                }
            } catch (err) {
                console.warn('Barcode error, fallback to CSS:', err);
                document.getElementById('barcode-fallback').style.display = 'block';
            }

            // 2. Generate Sharp 2D QR Code
            try {
                if (typeof QRCode === 'function') {
                    new QRCode(document.getElementById("qrcode"), {
                        text: trackUrl,
                        width: 42,
                        height: 42,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                }
            } catch (err) {
                console.warn('QR code error:', err);
            }
        });
    </script>
</body>
</html>
