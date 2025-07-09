<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice Pembayaran Treatment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Logo */
        .invoice-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-logo img {
            display: block;
            margin: 0 auto 20px;
            width: 200px;  /* Lebar logo */
            height: auto;  /* Agar proporsional */
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

        /* rata kanan-kiri label/value */
        .invoice-meta {
            margin-top: 20px;
        }

        .invoice-meta p {
            margin: 4px 0;
        }

        .invoice-meta .label {
            display: inline-block;
            width: 150px; /* sesuaikan dengan panjang label terpanjang */
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
    {{-- Logo di atas judul --}}
    <div class="invoice-logo">
        <img src="{{ public_path('backend/img/new-logo.jpg') }}" alt="Logo Klinik">
    </div>

    <h1>Invoice Pembayaran Treatment</h1>

    <div class="invoice-meta">
        <p><span class="label">Pelanggan:</span><span class="value">{{ $user_name }}</span></p>
        <p><span class="label">No. Telp:</span><span class="value">{{ $no_telp }}</span></p>
        <p><span class="label">Email:</span><span class="value">{{ $email }}</span></p>
        <p><span class="label">Waktu Treatment:</span><span class="value">{{ $waktu_treatment }}</span></p>
        <p><span class="label">Treatment Mulai:</span><span class="value">{{ $treatment_mulai }}</span></p>
    <p><span class="label">Treatment Selesai:</span><span class="value">{{ $treatment_selesai }}</span></p>
        <p><span class="label">Waktu Bayar:</span><span class="value">{{ $waktu_pembayaran }}</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Treatment</th>
                <th>Biaya Treatment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail_booking as $d)
                <tr>
                    <td>{{ $d['treatment']['nama_treatment'] }}</td>
                    <td>Rp{{ number_format($d['treatment']['biaya_treatment'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Subtotal:</strong> Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
        <p><strong>Potongan Harga:</strong>
            @if($tipe_potongan === 'Diskon' && $potongan_harga > 0)
                {{ (int) $potongan_harga }}%
            @elseif($potongan_harga > 0)
                Rp{{ number_format($potongan_harga, 0, ',', '.') }}
            @else
                -
            @endif
        </p>
        <p><strong>Pajak (10%):</strong> Rp{{ number_format($pajak, 0, ',', '.') }}</p>
        <p><strong>Total:</strong> Rp{{ number_format($total, 0, ',', '.') }}</p>
        <p><strong>Uang:</strong> Rp{{ number_format($uang, 0, ',', '.') }}</p>
        <p><strong>Kembalian:</strong> Rp{{ number_format($kembalian, 0, ',', '.') }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ $metode_pembayaran }}</p>
    </div>
</body>

</html>
