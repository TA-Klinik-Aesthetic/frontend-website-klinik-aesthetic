@extends('dashboard.index')

@section('content')
<div class="container">
    <h1>Detail Rekam Medis</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Informasi User -->
    <h3>Informasi User</h3>
    <p><strong>Nama:</strong> {{ $rekamMedisDetail['user']['nama_user'] }}</p>
    <p><strong>No. Telp:</strong> {{ $rekamMedisDetail['user']['no_telp'] }}</p>
    <p><strong>Email:</strong> {{ $rekamMedisDetail['user']['email'] }}</p>

    <!-- Gabungan Konsultasi & Booking -->
    <h3 class="mt-5">Riwayat Konsultasi & Booking</h3>

    @php
        $dataGabungan = [];

        foreach ($rekamMedisDetail['konsultasi'] as $konsultasi) {
            $tanggal = \Carbon\Carbon::parse($konsultasi['waktu_konsultasi'])->format('Y-m-d');
            $dataGabungan[$tanggal]['konsultasi'][] = $konsultasi;
        }

        foreach ($rekamMedisDetail['booking_treatment'] as $booking) {
            $tanggal = \Carbon\Carbon::parse($booking['waktu_treatment'])->format('Y-m-d');
            $dataGabungan[$tanggal]['booking'][] = $booking;
        }

        ksort($dataGabungan); // Urutkan berdasarkan tanggal
    @endphp

    @foreach ($dataGabungan as $tanggal => $item)
        <div class="card p-3 mb-4">
            <h5 class="mb-3">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h5>

            {{-- Konsultasi --}}
            @if (!empty($item['konsultasi']))
                <h6>Konsultasi</h6>
                @foreach ($item['konsultasi'] as $konsultasi)
                    <p><strong>Waktu Konsultasi:</strong> {{ $konsultasi['waktu_konsultasi'] }}</p>
                    <p><strong>Nama Dokter:</strong> {{ $konsultasi['dokter']['nama_dokter'] }}</p>
                    <p><strong>Keluhan Pelanggan:</strong> {{ $konsultasi['keluhan_pelanggan'] }}</p>
                    <h6>Detail Konsultasi:</h6>
                    <table class="table table-bordered mb-3">
                        <thead>
                            <tr>
                                <th>Saran Tindakan</th>
                                <th>Nama Treatment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($konsultasi['detail_konsultasi'] as $detail)
                                <tr>
                                    <td>{{ $detail['saran_tindakan'] }}</td>
                                    <td>{{ $detail['treatment']['nama_treatment'] ?? 'Tidak ada treatment' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endif

            {{-- Booking Treatment --}}
            @if (!empty($item['booking']))
                <h6>Booking Treatment</h6>
                @foreach ($item['booking'] as $booking)
                    <p><strong>Waktu Treatment:</strong> {{ $booking['waktu_treatment'] }}</p>
                    <p><strong>Dokter:</strong> {{ $booking['dokter']['nama_dokter'] ?? 'Tidak ada dokter' }}</p>
                    <p><strong>Beautician:</strong> {{ $booking['beautician']['nama_beautician'] ?? 'Tidak ada beautician' }}</p>
                    <p><strong>Status Pembayaran:</strong> {{ $booking['status_pembayaran'] }}</p>
                    <p><strong>Harga Total:</strong> {{ $booking['harga_total'] }}</p>
                    <p><strong>Potongan Harga:</strong> {{ $booking['potongan_harga'] }}</p>
                    <p><strong>Harga Akhir Treatment:</strong> {{ $booking['harga_akhir_treatment'] }}</p>

                    <h6>Detail Booking:</h6>
                    <table class="table table-bordered mb-3">
                        <thead>
                            <tr>
                                <th>Nama Treatment</th>
                                <th>Biaya Treatment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking['detail_booking'] as $detail)
                                <tr>
                                    <td>{{ $detail['treatment']['nama_treatment'] }}</td>
                                    <td>{{ $detail['biaya_treatment'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    @endforeach
</div>
@endsection
