<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice Pembayaran Treatment</title>
  <style>
    body { font-family: Arial, sans-serif; }
    h1 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table, th, td { border: 1px solid #000; }
    th, td { padding: 8px; text-align: left; }
    .footer { text-align: right; margin-top: 20px; }
  </style>
</head>
<body>
  <h1>Invoice Pembayaran Treatment</h1>
  <div>
    <strong>Pelanggan:</strong> {{ $user_name }}<br>
    <strong>No. Telp:</strong> {{ $no_telp }}<br>
    <strong>Email:</strong> {{ $email }}<br>
    <strong>Waktu Treatment:</strong> {{ $waktu_treatment }}<br>
    <strong>Waktu Bayar:</strong> {{ $waktu_pembayaran }}<br>
  </div>

  <table>
    <thead>
      <tr>
        <th>Nama Treatment</th>
        <th>Biaya Treatment</th>
      </tr>
    </thead>
    <tbody>
      @foreach($detail_booking as $d)
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
      @if($potongan_harga > 0)
        Rp{{ number_format($potongan_harga, 0, ',', '.') }}
      @else
        -
      @endif
    </p>
    <p><strong>Pajak (10%):</strong> Rp{{ number_format($pajak, 0, ',', '.') }}</p>
    <p><strong>Total:</strong> Rp{{ number_format($total, 0, ',', '.') }}</p>
    <p><strong>Uang:</strong> Rp{{ number_format($uang, 0, ',', '.') }}</p>
    <p><strong>Kembalian:</strong> Rp{{ number_format($kembalian, 0, ',', '.') }}</p>
    <p><strong>Metode Bayar:</strong> {{ $metode_pembayaran }}</p>
  </div>
</body>
</html>
