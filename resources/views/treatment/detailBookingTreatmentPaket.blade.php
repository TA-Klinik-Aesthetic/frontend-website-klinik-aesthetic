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
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-muted small">Pelanggan</div>
                        <div class="fw-semibold">{{ $pelanggan }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Status</div>
                        <div class="fw-semibold">{{ $status }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Waktu Treatment</div>
                        <div class="fw-semibold">{{ $waktu }}</div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="text-muted small">Treatment Mulai</div>
                        <div class="fw-semibold">{{ $mulai }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Treatment Selesai</div>
                        <div class="fw-semibold">{{ $selesai }}</div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="text-muted small">Dokter</div>
                        <div class="fw-semibold">{{ $dokter }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Beautician</div>
                        <div class="fw-semibold">{{ $beauty }}</div>
                    </div>
                </div>
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
