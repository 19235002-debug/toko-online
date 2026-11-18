<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Pesanan - {{ $order->kode_order }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            margin: 20px;
            font-size: 13px;
            line-height: 1.5;
        }
        .header-invoice {
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-invoice table {
            width: 100%;
            border-collapse: collapse;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            color: #222;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            padding: 4px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer-note {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header-invoice">
        <table>
            <tr>
                <td>
                    <div class="title">COZA STORE</div>
                    <small>Official Online Store - Kelompok 3</small>
                </td>
                <td class="text-right">
                    <h2 style="margin: 0; color: #555;">FAKTUR PENJUALAN</h2>
                    <div>No. Pesanan: <strong>{{ $order->kode_order }}</strong></div>
                    <div>Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <strong>Kepada Yth:</strong><br>
                <strong>{{ $order->nama_customer }}</strong><br>
                No. HP/WA: {{ $order->no_hp }}<br>
                Alamat Pengiriman:<br>
                {{ $order->alamat }}
            </td>
            <td style="width: 50%;" class="text-right">
                <strong>Status Pesanan:</strong> <span style="text-transform: uppercase;">{{ $order->status }}</span><br>
                <strong>Metode Pembayaran:</strong> <span style="text-transform: uppercase;">{{ $order->metode_pembayaran }}</span><br>
                @if($order->no_resi)
                    <strong>Ekspedisi & Resi:</strong> {{ $order->ekspedisi ?? 'Kurir' }} ({{ $order->no_resi }})<br>
                @endif
                @if($order->catatan)
                    <strong>Catatan:</strong> {{ $order->catatan }}<br>
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Nama Produk</th>
                <th class="text-right" style="width: 140px;">Harga Satuan</th>
                <th class="text-center" style="width: 60px;">Qty</th>
                <th class="text-right" style="width: 150px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $item->nama_barang }}</strong>
                    @if($item->variasi)
                        <div style="font-size: 11px; color: #555; margin-top: 2px;">{{ $item->variasi }}</div>
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            @if($order->diskon > 0)
            <tr>
                <th colspan="4" class="text-right" style="color: #198754;">Diskon Voucher ({{ $order->kode_voucher }}):</th>
                <th class="text-right" style="color: #198754;">- Rp {{ number_format($order->diskon, 0, ',', '.') }}</th>
            </tr>
            @endif
            <tr>
                <th colspan="4" class="text-right" style="color: #555;">Ongkos Kirim ({{ $order->kurir ?? $order->ekspedisi ?? 'Reguler' }}):</th>
                <th class="text-right" style="color: {{ ($order->ongkir ?? 0) > 0 ? '#222' : '#198754' }};">
                    {{ ($order->ongkir ?? 0) > 0 ? 'Rp ' . number_format($order->ongkir, 0, ',', '.') : 'Gratis' }}
                </th>
            </tr>
            <tr>
                <th colspan="4" class="text-right">TOTAL PEMBAYARAN:</th>
                <th class="text-right" style="font-size: 15px;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer-note">
        Terima kasih atas pesanan Anda di COZA STORE!<br>
        Simpan bukti faktur ini sebagai bukti pembelian yang sah.
    </div>

</body>
</html>

