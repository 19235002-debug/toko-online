<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Laporan Penjualan | Coza Store</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: #666;
        }
        .meta-info {
            margin-bottom: 15px;
        }
        .meta-info table {
            width: 100%;
        }
        .summary-boxes {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-boxes td {
            border: 1px solid #ddd;
            padding: 10px;
            background: #fdfdfd;
            text-align: center;
        }
        .summary-boxes strong {
            display: block;
            font-size: 16px;
            color: #28a745;
            margin-top: 4px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #999;
            padding: 7px 8px;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .signature-section {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 200px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>COZA STORE - KELOMPOK 3</h2>
        <p>Laporan Rekap Transaksi & Penjualan Toko</p>
        <p style="font-size: 11px;">Periode: {{ date('d F Y', strtotime($tglMulai)) }} s/d {{ date('d F Y', strtotime($tglSelesai)) }}</p>
    </div>

    <!-- Summary Box -->
    <table class="summary-boxes">
        <tr>
            <td>
                <span>Total Transaksi</span>
                <strong style="color: #007bff;">{{ $orders->count() }} Pesanan</strong>
            </td>
            <td>
                <span>Produk Terjual</span>
                <strong style="color: #6f42c1;">{{ $totalProdukTerjual }} Pcs</strong>
            </td>
            <td>
                <span>Total Omset (Lunas/Proses)</span>
                <strong style="color: #28a745;">Rp {{ number_format($totalOmset, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 130px;">Invoice</th>
                <th style="width: 90px;">Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>No. HP</th>
                <th>Metode Bayar</th>
                <th>Status</th>
                <th style="width: 120px;" class="text-right">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $i => $o)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-center font-weight-bold">{{ $o->kode_order }}</td>
                <td class="text-center">{{ $o->created_at->format('d/m/Y') }}</td>
                <td>{{ $o->nama_customer }}</td>
                <td>{{ $o->no_hp }}</td>
                <td class="text-center" style="text-transform: uppercase;">{{ $o->metode_pembayaran }}</td>
                <td class="text-center" style="text-transform: uppercase;">{{ $o->status }}</td>
                <td class="text-right">Rp {{ number_format($o->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td colspan="7" class="text-right">TOTAL PENDAPATAN (OMSET):</td>
                <td class="text-right">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}<br>Admin Pengelola,</p>
        <br><br><br>
        <p><strong>( {{ auth()->user()->nama ?? 'Administrator' }} )</strong></p>
    </div>

</body>
</html>

