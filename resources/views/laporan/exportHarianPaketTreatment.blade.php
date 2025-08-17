<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Paket Treatment (Harian)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .report-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-logo img {
            display: block;
            margin: 0 auto;
            width: 200px;
            height: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
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
        <img src="{{ public_path('backend/img/new-logo.jpg') }}" alt="Logo">
    </div>

    <h2>Laporan Penjualan Paket Treatment (Harian) — {{ $tanggal }}</h2>

    <table>
        <thead>
            <tr>
                <th>Tanggal Pembelian</th>
                <th>Nama Paket</th>
                <th>Harga Paket</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['data'] ?? [] as $item)
                <tr>
                    <td>{{ $item['tanggal_pembelian'] ?? '-' }}</td>
                    <td>{{ $item['nama_paket_treatment'] ?? '-' }}</td>
                    <td>Rp{{ number_format($item['harga_paket_treatment'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!empty($data['promo_usage_count']))
        <h4>Penggunaan Promo</h4>
        <table>
            <thead>
                <tr>
                    <th>Nama Promo</th>
                    <th>Total</th>
                    <th>Potongan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['promo_usage_count'] as $promoName => $promoDetails)
                    <tr>
                        <td>{{ $promoName }}</td>
                        <td>{{ $promoDetails['count'] ?? 0 }}</td>
                        <td>
                            @if (($promoDetails['tipe_potongan'] ?? '') === 'Diskon')
                                {{ rtrim(rtrim(number_format($promoDetails['potongan_harga'] ?? 0, 2, '.', ''), '0'), '.') }}%
                            @else
                                Rp{{ number_format($promoDetails['potongan_harga'] ?? 0, 0, ',', '.') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (isset($data['total_pendapatan']))
        <h3>Total Penjualan Paket: Rp{{ number_format($data['total_pendapatan'], 0, ',', '.') }}</h3>
    @endif
</body>

</html>
