<!DOCTYPE html>
<html>

<head>
    <title>Laporan Bulanan</title>
    <style>
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
    <h2>Laporan Bulanan Treatment - {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }}</h2>
    {{-- <table>
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
                    <td>{{ $item['biaya_treatment'] }}</td>
                    <td>{{ $item['dokter'] }}</td>
                    <td>{{ $item['beautician'] }}</td>
                    <td>{{ $item['kompensasi'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
    <table>
        <thead>
            <tr>
                <th>Nama Treatment</th>
                <th>Jumlah Terjual</th>
                <th>Total Biaya Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['penjualan_treatment'] as $treatmentName => $treatmentDetails)
                <tr>
                    <td>{{ $treatmentName }}</td>
                    <td>{{ $treatmentDetails['count'] }}</td>
                    <td>{{ $treatmentDetails['total_biaya'] }}</td>
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
                            @if(isset($promoDetails['tipe_potongan']) && $promoDetails['tipe_potongan'] === 'Diskon')
                                {{ rtrim(rtrim(number_format($promoDetails['potongan_harga'], 2, '.', ''), '0'), '.') }}%
                            @else
                                Rp {{ number_format($promoDetails['potongan_harga'], 0, ',', '.') }}
                            @endif
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h3>Total Pendapatan: {{ $data['total_pendapatan'] }}</h3>
</body>

</html>
