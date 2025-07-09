<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Produk Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Logo */
        .report-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-logo img {
            display: block;
            margin: 0 auto;
            width: 200px;
            /* atur lebar sesuai kebutuhan */
            height: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="report-logo">
        <img src="{{ public_path('backend/img/new-logo.jpg') }}" alt="Logo Klinik">
    </div>
    
    <h2>Laporan Penjualan Produk Bulanan - {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }}</h2>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
                <th>Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['penjualan_produk'] as $produkName => $produkDetails)
                <tr>
                    <td>{{ $produkName }}</td>
                    <td>{{ $produkDetails['count'] }}</td>
                    <td>{{ $produkDetails['total_biaya'] }}</td>
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
                    <td>
                        @if (isset($promoDetails['tipe_potongan']) && $promoDetails['tipe_potongan'] === 'Diskon')
                            {{ rtrim(rtrim(number_format($promoDetails['potongan_harga'], 2, '.', ''), '0'), '.') }}%
                        @else
                            Rp {{ number_format($promoDetails['potongan_harga'], 0, ',', '.') }}
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total Penjualan Produk: {{ $data['total_pendapatan'] }}</h3>

</body>

</html>
