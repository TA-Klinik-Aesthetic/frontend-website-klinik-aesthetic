<!DOCTYPE html>
<html>

<head>
    <title>Laporan Harian</title>
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

    <h2>Laporan Harian Treatment - {{ $tanggal }}</h2>
    <table>
        <thead>
            <tr>
                <th>Waktu Treatment</th>
                <th>Nama Treatment</th>
                <th>Biaya Treatment</th>
                <th>Dokter</th>
                <th>Beautician</th>
                <th>Kompensasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['data'] as $item)
                <tr>
                    <td>{{ $item['waktu_treatment'] }}</td>
                    <td>{{ $item['nama_treatment'] }}</td>
                    <td>Rp{{ number_format($item['biaya_treatment'], 0, ',', '.') }}</td>
                    <td>{{ $item['dokter'] }}</td>
                    <td>{{ $item['beautician'] }}</td>
                    <td>{{ $item['kompensasi'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Promo Table -->
    <div class="promo-table">
        <h3>Penggunaan Promo</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Promo</th>
                    <th>Total</th>
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
                                Rp{{ number_format($promoDetails['potongan_harga'], 0, ',', '.') }}
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h3>Total Pendapatan: Rp{{ number_format($data['total_pendapatan'], 0, ',', '.') }}</h3>
</body>

</html>
