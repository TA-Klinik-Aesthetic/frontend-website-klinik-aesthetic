@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="my-4">Detail Booking Treatment Paket</h1>

        @php
            $pelanggan =
                $booking['user']['nama_user'] ??
                ($booking['pelanggan']['nama_user'] ?? ($booking['nama_pelanggan'] ?? '-'));

            $waktu = $booking['waktu_treatment'] ?? '-';
            $mulai = $booking['treatment_mulai'] ?? '-';
            $selesai = $booking['treatment_selesai'] ?? '-';
            $status = $booking['status_booking_treatment'] ?? '-';

            $dokter = $booking['dokter']['nama_dokter'] ?? ($booking['dokter']['nama_user'] ?? '–');

            $beauty = $booking['beautician']['nama_beautician'] ?? ($booking['beautician']['nama_user'] ?? '–');

            // detail dari JSON: $booking['details']
            $details = $booking['details'] ?? [];
        @endphp

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Informasi Booking</h4>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <th style="width:260px;">Pelanggan</th>
                            <td>{{ $pelanggan }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $status }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Treatment</th>
                            <td>{{ $waktu }}</td>
                        </tr>
                        <tr>
                            <th>Treatment Mulai</th>
                            <td>{{ $mulai }}</td>
                        </tr>
                        <tr>
                            <th>Treatment Selesai</th>
                            <td>{{ $selesai }}</td>
                        </tr>
                        <tr>
                            <th>Dokter</th>
                            <td>{{ $dokter }}</td>
                        </tr>
                        <tr>
                            <th>Beautician</th>
                            <td>{{ $beauty }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>

        {{-- Detail sesuai JSON: Paket, Treatment, Jumlah Dipakai --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Treatment dari Paket</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Paket</th>
                            <th>Treatment</th>
                            <th>Jumlah Dipakai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $d)
                            @php
                                $paketName = $d['paket_pelanggan']['paket']['nama_paket_treatment'] ?? '—';
                                $treatName = $d['treatment']['nama_treatment'] ?? '—';
                                $dipakai = $d['jumlah_dipakai'] ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $paketName }}</td>
                                <td>{{ $treatName }}</td>
                                <td>{{ $dipakai }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada detail</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
