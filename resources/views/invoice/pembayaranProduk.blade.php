<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice Pembayaran Produk</title>
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
  <h1>Invoice Pembayaran Produk</h1>

  <div>
    <strong>Pelanggan:</strong> {{ $user_name }}<br>
    <strong>No. Telp:</strong> {{ $no_telp }}<br>
    <strong>Email:</strong> {{ $email }}<br>
    <strong>Tanggal Pembelian:</strong> {{ $tanggal_pembelian }}<br>
    <strong>Waktu Bayar:</strong> {{ $waktu_pembayaran }}<br>
  </div>

  <table>
    <thead>
      <tr>
        <th>Nama Produk</th>
        <th>Jumlah</th>
        <th>Harga Satuan</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($detail_pembelian as $d)
        <tr>
          <td>{{ $d['produk']['nama_produk'] ?? '–' }}</td>
          <td>{{ $d['jumlah_produk'] }}</td>
          <td>Rp{{ number_format($d['harga_penjualan_produk'],0,',','.') }}</td>
          <td>Rp{{ number_format($d['harga_penjualan_produk'] * $d['jumlah_produk'],0,',','.') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="footer">
    <p><strong>Subtotal:</strong> Rp{{ number_format($subtotal,0,',','.') }}</p>
    <p><strong>Potongan Harga:</strong> 
      @if($potongan_harga>0)
        Rp{{ number_format($potongan_harga,0,',','.') }}
      @else
        –
      @endif
    </p>
    <p><strong>Pajak (10%):</strong> Rp{{ number_format($pajak,0,',','.') }}</p>
    <p><strong>Total Bayar:</strong> Rp{{ number_format($total,0,',','.') }}</p>
    <p><strong>Uang:</strong> Rp{{ number_format($uang,0,',','.') }}</p>
    <p><strong>Kembalian:</strong> Rp{{ number_format($kembalian,0,',','.') }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $metode_pembayaran }}</p>
  </div>
</body>
</html>
