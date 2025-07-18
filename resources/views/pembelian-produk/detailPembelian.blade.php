@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-gray-800">Detail Penjualan Produk</h1>

        <div class="row">
            {{-- kiri: Informasi Penjualan + Detail Produk --}}
            <div class="col-lg-8">
                {{-- Informasi Umum --}}
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5>Informasi Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama User:</strong> {{ $pembelian['user']['nama_user'] }}</p>
                        <p><strong>Tanggal Pembelian:</strong> {{ $pembelian['tanggal_pembelian'] }}</p>
                        <p><strong>Subtotal:</strong> Rp{{ number_format($pembelian['harga_total'], 0, ',', '.') }}</p>
                        <p>
                            <strong>Potongan Harga:</strong>
                            @if (!empty($pembelian['promo']))
                                @php $promo = $pembelian['promo']; @endphp
                                @if ($promo['tipe_potongan'] === 'Diskon')
                                    {{ (int) $pembelian['potongan_harga'] }}%
                                @else
                                    Rp{{ number_format($pembelian['potongan_harga'], 0, ',', '.') }}
                                @endif
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>Besaran Pajak (10%):</strong>
                            Rp{{ number_format($pembelian['besaran_pajak'], 0, ',', '.') }}</p>
                        <p><strong>Total:</strong> Rp{{ number_format($pembelian['harga_akhir'], 0, ',', '.') }}</p>
                        <p><strong>Status Pengambilan Produk:</strong> {{ $pembelian['status_pengambilan_produk'] }}</p>
                        <p><strong>Waktu Pengambilan:</strong> {{ $pembelian['waktu_pengambilan'] }}</p>
                    </div>
                </div>

                {{-- Detail Produk --}}
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5>Detail Produk</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembelian['detail_pembelian'] as $d)
                                    <tr>
                                        <td>{{ $d['produk']['nama_produk'] }}</td>
                                        <td>{{ $d['jumlah_produk'] }}</td>
                                        <td>Rp{{ number_format($d['harga_penjualan_produk'], 0, ',', '.') }}</td>
                                        <td>Rp{{ number_format($d['harga_penjualan_produk'] * $d['jumlah_produk'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- kanan: Informasi Pembayaran --}}
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5>Informasi Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        @if ($pembelian['pembayaran_produk'])
                            @php $pay = $pembelian['pembayaran_produk']; @endphp
                            <p><strong>Metode:</strong> {{ $pay['metode_pembayaran'] }}</p>
                            <p><strong>Uang Bayar:</strong> Rp{{ number_format($pay['uang'], 0, ',', '.') }}</p>
                            <p><strong>Kembalian:</strong> Rp{{ number_format($pay['kembalian'], 0, ',', '.') }}</p>
                            <p><strong>Status:</strong> {{ $pay['status_pembayaran'] }}</p>
                            <p><strong>Waktu Bayar:</strong> {{ $pay['waktu_pembayaran'] }}</p>

                            @if (!empty($pay['gambar_bukti_pembayaran']))
                                <p><strong>Bukti Pembayaran:</strong></p>
                                <div class="text-center mb-4">
                                    <a href="{{ $pay['gambar_bukti_pembayaran'] }}" target="_blank">
                                        <img src="{{ $pay['gambar_bukti_pembayaran'] }}" alt="Bukti Pembayaran"
                                            class="img-fluid"
                                            style="max-width:100%; height:auto; border:1px solid #ddd; padding:4px;">
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning mb-0">
                                Belum ada pembayaran untuk penjualan ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
