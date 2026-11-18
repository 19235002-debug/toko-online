<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Produk</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        img {
            max-width: 50px;
        }
    </style>
</head>

<body onload="window.print()">
    <h2 style="text-align: center;">Laporan Data Produk</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Stok</th>
                <th>Harga</th>

            </tr>
        </thead>
        <tbody>
            @foreach($barang as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    @if($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Foto">
                    @else
                    -
                    @endif
                </td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ $item->stok }}</td>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>