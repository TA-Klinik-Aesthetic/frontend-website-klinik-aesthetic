@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-gray-800">Detail Pembayaran Treatment</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <p><strong>Nama Pelanggan:</strong>
                    {{ $payment['booking_treatment']['user']['nama_user'] ?? '-' }}
                </p>
                <p><strong>Total:</strong>
                    Rp{{ number_format($payment['booking_treatment']['harga_akhir_treatment'], 0, ',', '.') }}
                </p>
                <p><strong>Metode:</strong> {{ $payment['metode_pembayaran'] }}</p>
                <p><strong>Uang Bayar:</strong>
                    Rp{{ number_format($payment['uang'], 0, ',', '.') }}
                </p>
                <p><strong>Kembalian:</strong>
                    Rp{{ number_format($payment['kembalian'], 0, ',', '.') }}
                </p>
                <p><strong>Status Pembayaran:</strong> {{ $payment['status_pembayaran'] }}</p>
                <p><strong>Waktu Pembayaran:</strong> {{ $payment['waktu_pembayaran'] }}</p>

                @if (!empty($payment['gambar_bukti_pembayaran']))
                    <p><strong>Gambar Bukti Pembayaran:</strong></p>
                    <div class="text-center mb-4">
                        <a href="{{ $payment['gambar_bukti_pembayaran'] }}" target="_blank">
                            <img src="{{ $payment['gambar_bukti_pembayaran'] }}" alt="Bukti Pembayaran" class="img-fluid"
                                style="max-width:400px; border:1px solid #ddd; padding:4px;">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
