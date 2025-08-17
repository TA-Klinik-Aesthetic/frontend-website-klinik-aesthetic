<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice Pembayaran Paket Treatment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-logo img {
            display: block;
            margin: 0 auto 20px;
            width: 200px;
            height: auto;
        }

        h1 {
            text-align: center;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .invoice-meta {
            margin-top: 20px;
        }

        .invoice-meta p {
            margin: 4px 0;
        }

        .invoice-meta .label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        .invoice-meta .value {
            display: inline-block;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice-logo">
        <img src="{{ public_path('backend/img/new-logo.jpg') }}" alt="Logo">
    </div>

    <h1>Invoice Pembayaran Paket Treatment</h1>

    <div class="invoice-meta">
        <p><span class="label">Pelanggan:</span><span class="value">{{ $user_name }}</span></p>
        <p><span class="label">No. Telp:</span><span class="value">{{ $no_telp }}</span></p>
        <p><span class="label">Email:</span><span class="value">{{ $email }}</span></p>
        <p><span class="label">Tanggal Penjualan:</span><span class="value">{{ $tanggal_penjualan }}</span></p>
        <p><span class="label">Waktu Bayar:</span><span class="value">{{ $waktu_pembayaran }}</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Paket</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail_paket as $row)
                <tr>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['qty'] }}</td>
                    <td>Rp{{ number_format($row['harga'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($row['harga'] * $row['qty'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Subtotal:</strong> Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
        <p><strong>Potongan Harga:</strong>
            @if (($tipe_potongan === 'Diskon' || strtolower($tipe_potongan ?? '') === 'diskon') && $potongan_harga > 0)
                {{ (int) $potongan_harga }}%
            @elseif ($potongan_harga > 0)
                Rp{{ number_format($potongan_harga, 0, ',', '.') }}
            @else
                -
            @endif
        </p>
        <p><strong>Pajak (10%):</strong> Rp{{ number_format($pajak, 0, ',', '.') }}</p>
        <p><strong>Total Bayar:</strong> Rp{{ number_format($total, 0, ',', '.') }}</p>
        <p><strong>Uang:</strong> Rp{{ number_format($uang, 0, ',', '.') }}</p>
        <p><strong>Kembalian:</strong> Rp{{ number_format($kembalian, 0, ',', '.') }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ $metode_pembayaran }}</p>
    </div>
</body>

</html>
