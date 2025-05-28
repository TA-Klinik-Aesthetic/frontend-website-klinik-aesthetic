<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Produk</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Produk</h2>
    
    <table>
        <thead>
            <tr>
                <th>Tanggal Pembelian</th>
                <th>Nama Produk</th>
                <th>Jumlah Produk</th>
                <th>Harga Penjualan Produk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['data'] as $item)
                <tr>
                    <td>{{ $item['tanggal_pembelian'] }}</td>
                    <td>{{ $item['nama_produk'] }}</td>
                    <td>{{ $item['jumlah_produk'] }}</td>
                    <td>{{ $item['harga_penjualan_produk'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Promo Usage</h4>
    <table>
        <thead>
            <tr>
                <th>Promo Name</th>
                <th>Count</th>
                <th>Potongan Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['promo_usage_count'] as $promoName => $promoDetails)
                <tr>
                    <td>{{ $promoName }}</td>
                    <td>{{ $promoDetails['count'] }}</td>
                    <td>{{ $promoDetails['potongan_harga'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total Penjualan Produk: {{ $data['total_pendapatan'] }}</h3>
    
</body>
</html>
