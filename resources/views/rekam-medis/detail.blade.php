@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1>Detail Rekam Medis</h1>

        <h3>Informasi Pelanggan</h3>
        <div class="card p-3 mb-4">
            <!-- Informasi User -->
            <p><strong>Nama:</strong> {{ $rekamMedisDetail['user']['nama_user'] }}</p>
            <p><strong>No. Telp:</strong> {{ $rekamMedisDetail['user']['no_telp'] }}</p>
            <p><strong>Email:</strong> {{ $rekamMedisDetail['user']['email'] }}</p>
        </div>

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
                                    <th>Diagnosis</th>
                                    <th>Saran Tindakan</th>
                                    <th>Nama Treatment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($konsultasi['detail_konsultasi'] as $detail)
                                    <tr>
                                        <td>{{ $detail['diagnosis'] }}</td>
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
                        <p><strong>Beautician:</strong>
                            {{ $booking['beautician']['nama_beautician'] ?? 'Tidak ada beautician' }}</p>

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
                                        <td>Rp{{ number_format($detail['biaya_treatment'], 0, ',', '.') }}</td>
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
