<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran Treatment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .invoice-header {
            margin-bottom: 20px;
        }
        .invoice-header div {
            margin: 5px 0;
        }
        .invoice-footer {
            text-align: right;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice Pembayaran Treatment</h1>
        <div><strong>Nama Pelanggan:</strong> {{ $user_name }}</div>
        <div><strong>No. Telp:</strong> {{ $no_telp }}</div>
        <div><strong>Email:</strong> {{ $email }}</div>
        <div><strong>Waktu Treatment:</strong> {{ $waktu_treatment }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Treatment</th>
                <th>Biaya Treatment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail_booking as $detail)
                <tr>
                    <td>{{ $detail['treatment']['nama_treatment'] }}</td>
                    <td>{{ number_format($detail['treatment']['biaya_treatment'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="invoice-footer">
        <div><strong>Promo (Potongan Harga):</strong> {{ number_format($potongan_harga, 0, ',', '.') }}</div>
        <div><strong>Subtotal:</strong> {{ number_format($subtotal, 0, ',', '.') }}</div>
        <div><strong>Pajak:</strong> {{ number_format($pajak, 0) }}%</div>
        <div><strong>Total:</strong> {{ number_format($total, 0, ',', '.') }}</div>
        <div><strong>Uang:</strong> {{ number_format($uang, 0, ',', '.') }}</div>
        <div><strong>Kembalian:</strong> {{ number_format($kembalian, 0, ',', '.') }}</div>
        <div><strong>Metode Pembayaran:</strong> {{ $metode_pembayaran }}</div>
    </div>
</body>
</html>
