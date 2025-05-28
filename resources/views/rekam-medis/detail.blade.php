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

        <!-- Konsultasi -->
        <h3 class="mt-5">Konsultasi</h3> <!-- Menambahkan margin top untuk jarak antar bagian -->
        @foreach ($rekamMedisDetail['konsultasi'] as $konsultasi)
            <div class="mb-5"> <!-- Menambahkan margin bawah pada tiap konsultasi -->
                <p><strong>Waktu Konsultasi:</strong> {{ $konsultasi['waktu_konsultasi'] }}</p>
                <p><strong>Nama Dokter:</strong> 
                    @if($konsultasi['id_dokter'])
                        {{ $konsultasi['dokter']['nama_dokter'] }}
                    @else
                        Dokter Tidak Tersedia
                    @endif
                </p>
                <p><strong>Keluhan Pelanggan:</strong> {{ $konsultasi['keluhan_pelanggan'] }}</p>
                <h5>Detail Konsultasi:</h5>
                <table class="table table-bordered mb-4">
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
            </div>
        @endforeach

        <!-- Booking Treatment -->
        <h3 class="mt-5">Booking Treatment</h3> <!-- Menambahkan margin top untuk jarak antar bagian -->
        @foreach ($rekamMedisDetail['booking_treatment'] as $booking)
            <div class="mb-5"> <!-- Menambahkan margin bawah pada tiap booking treatment -->
                <p><strong>Waktu Treatment:</strong> {{ $booking['waktu_treatment'] }}</p>
                <p><strong>Status Pembayaran:</strong> {{ $booking['status_pembayaran'] }}</p>
                <p><strong>Harga Total:</strong> {{ $booking['harga_total'] }}</p>
                <p><strong>Potongan Harga:</strong> {{ $booking['potongan_harga'] }}</p>
                <p><strong>Harga Akhir Treatment:</strong> {{ $booking['harga_akhir_treatment'] }}</p>

                <h5>Detail Booking Treatment:</h5>
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Nama Treatment</th>
                            <th>Biaya Treatment</th>
                            <th>Dokter</th>
                            <th>Beautician</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking['detail_booking'] as $detail)
                            <tr>
                                <td>{{ $detail['treatment']['nama_treatment'] }}</td>
                                <td>{{ $detail['biaya_treatment'] }}</td>
                                <td>
                                    @if ($detail['dokter'])
                                        {{ $detail['dokter']['nama_dokter'] }}
                                    @else
                                        Dokter Tidak Tersedia
                                    @endif
                                </td>
                                <td>
                                    @if ($detail['beautician'])
                                        {{ $detail['beautician']['nama_beautician'] }}
                                    @else
                                        Beautician Tidak Tersedia
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
@endsection
