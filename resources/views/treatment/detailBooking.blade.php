@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="my-4">Detail Booking Treatment</h1>

        <div class="card mb-3">
            <div class="card-header">
                <h4>Informasi Booking</h4>
            </div>
            <div class="card-body">
                <p><strong>Nama Pelanggan:</strong> {{ $bookingDetail['booking_treatment']['user']['nama_user'] }}</p>
                <p><strong>Waktu Treatment:</strong> {{ $bookingDetail['booking_treatment']['waktu_treatment'] }}</p>
                <p><strong>Dokter:</strong>
                    @php
                        $dokter = collect($dokters)->firstWhere('id_dokter', $bookingDetail['booking_treatment']['id_dokter']);
                    @endphp
                    {{ $dokter ? $dokter['nama_dokter'] : 'Tidak ada Dokter' }}
                </p>
                <p><strong>Beautician:</strong>
                    @php
                        $beautician = collect($beauticians)->firstWhere('id_beautician', $bookingDetail['booking_treatment']['id_beautician']);
                    @endphp
                    {{ $beautician ? $beautician['nama_beautician'] : 'Tidak ada Beautician' }}
                </p>
                <p><strong>Status Booking:</strong> {{ $bookingDetail['booking_treatment']['status_booking_treatment'] }}</p>

                @php
                    $promoDipakai = collect($promos)->firstWhere('id_promo', $bookingDetail['booking_treatment']['id_promo']);
                    $hargaTotal = $bookingDetail['booking_treatment']['harga_total'];
                    $hargaAkhir = $bookingDetail['booking_treatment']['harga_akhir_treatment'];
                    $besaranPajak = $bookingDetail['booking_treatment']['besaran_pajak'];
                @endphp

                <p><strong>Harga Total:</strong> Rp{{ number_format($hargaTotal, 0, ',', '.') }}</p>

                <p><strong>Potongan Harga:</strong>
                    @if ($promoDipakai)
                        @if ($promoDipakai['tipe_potongan'] === 'Diskon')
                            {{ (int) $bookingDetail['booking_treatment']['potongan_harga'] }}%
                        @else
                            Rp{{ number_format($bookingDetail['booking_treatment']['potongan_harga'], 0, ',', '.') }}
                        @endif
                    @else
                        -
                    @endif
                </p>

                <p><strong>Besaran Pajak:</strong> Rp{{ number_format($besaranPajak, 0, ',', '.') }}</p>

                <p><strong>Harga Akhir:</strong> Rp{{ number_format($hargaAkhir, 0, ',', '.') }}</p>

                <h5>Detail Treatment:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Treatment</th>
                            <th>Biaya Treatment</th>
                            <th>Kompensasi yang digunakan</th> {{-- Kolom baru --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookingDetail['booking_treatment']['detail_booking'] as $detail)
                            <tr>
                                <td>
                                    @php
                                        $treatment = collect($treatments)->firstWhere('id_treatment', $detail['id_treatment']);
                                    @endphp
                                    {{ $treatment ? $treatment['nama_treatment'] : 'Treatment tidak ditemukan' }}
                                </td>
                                <td>Rp{{ number_format($detail['biaya_treatment'], 0, ',', '.') }}</td>
                                <td>
                                    @if (!empty($detail['kompensasi_diberikan']) && !empty($detail['kompensasi_diberikan']['kompensasi']))
                                        {{ $detail['kompensasi_diberikan']['kompensasi']['nama_kompensasi'] }}
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
    </div>
@endsection
