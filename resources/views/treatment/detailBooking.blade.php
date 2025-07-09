@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="my-4">Detail Booking Treatment</h1>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Informasi Booking</h4>
            </div>
            <div class="card-body">
                <p><strong>Nama Pelanggan:</strong> {{ $booking['user']['nama_user'] }}</p>
                <p><strong>Waktu Treatment:</strong> {{ $booking['waktu_treatment'] }}</p>
                <p><strong>Treatment Mulai:</strong> {{ $booking['treatment_mulai'] }}</p>
                <p><strong>Treatment Selesai:</strong> {{ $booking['treatment_selesai'] }}</p>
                <p><strong>Dokter:</strong> {{ $booking['dokter']['nama_dokter'] }}</p>
                <p><strong>Beautician:</strong> {{ $booking['beautician']['nama_beautician'] }}</p>
                <p><strong>Status Booking:</strong> {{ $booking['status_booking_treatment'] }}</p>

                <hr>
                @php
                    $promo = $booking['promo'];
                    $total = $booking['harga_total'];
                    $pot = $booking['potongan_harga'];
                    $tax = $booking['besaran_pajak'];
                    $akhir = $booking['harga_akhir_treatment'];
                @endphp

                <p><strong>Harga Total:</strong> Rp{{ number_format($total, 0, ',', '.') }}</p>
                <p><strong>Potongan Harga:</strong>
                    @if ($promo)
                        @if ($promo['tipe_potongan'] === 'Diskon')
                            {{ (int) $pot }}%
                        @else
                            Rp{{ number_format($pot, 0, ',', '.') }}
                        @endif
                    @else
                        –
                    @endif
                </p>
                <p><strong>Besaran Pajak ({{ number_format(($tax / $total) * 100, 0) }}%):</strong>
                    Rp{{ number_format($tax, 0, ',', '.') }}
                </p>
                <p><strong>Harga Akhir:</strong> Rp{{ number_format($akhir, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Detail Treatment</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Treatment</th>
                            <th>Biaya</th>
                            <th>Kompensasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking['detail_booking'] as $d)
                            <tr>
                                <td>{{ $d['treatment']['nama_treatment'] }}</td>
                                <td>Rp{{ number_format($d['biaya_treatment'], 0, ',', '.') }}</td>
                                <td>
                                    @if (!empty($d['kompensasi_diberikan']['kompensasi']))
                                        {{ $d['kompensasi_diberikan']['kompensasi']['nama_kompensasi'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('detailBooking.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@endsection
