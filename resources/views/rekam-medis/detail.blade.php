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
        <h3 class="mt-5">Riwayat Konsultasi & Booking Treatment</h3>

        @php
            use Carbon\Carbon;

            $dataGabungan = [];

            // 1) Kelompokkan konsultasi per tanggal
            foreach ($rekamMedisDetail['konsultasi'] ?? [] as $konsultasi) {
                $tanggal = Carbon::parse($konsultasi['waktu_konsultasi'])->format('Y-m-d');
                $dataGabungan[$tanggal]['konsultasi'][] = $konsultasi;
            }

            // 2) Ambil booking reguler & paket dari struktur baru
            $bk = $rekamMedisDetail['booking_treatment'] ?? [];
            $bookReg = $bk['reguler'] ?? [];
            $bookPkg = $bk['paket'] ?? [];

            // 3) Kelompokkan REGULER per tanggal
            foreach ($bookReg as $booking) {
                $tanggal = Carbon::parse($booking['waktu_treatment'])->format('Y-m-d');
                $dataGabungan[$tanggal]['booking_reguler'][] = $booking;
            }

            // 4) Kelompokkan PAKET per tanggal
            foreach ($bookPkg as $booking) {
                $tanggal = Carbon::parse($booking['waktu_treatment'])->format('Y-m-d');
                $dataGabungan[$tanggal]['booking_paket'][] = $booking;
            }

            ksort($dataGabungan); // urut tanggal ASC
        @endphp

        @forelse ($dataGabungan as $tanggal => $item)
            <div class="card p-3 mb-4">
                <h5 class="mb-3">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h5>

                {{-- ========== Konsultasi ========== --}}
                @if (!empty($item['konsultasi']))
                    <h6>Konsultasi</h6>
                    @foreach ($item['konsultasi'] as $konsultasi)
                        <p><strong>Waktu Konsultasi:</strong> {{ $konsultasi['waktu_konsultasi'] }}</p>
                        <p><strong>Nama Dokter:</strong> {{ data_get($konsultasi, 'dokter.nama_dokter', '-') }}</p>
                        <p><strong>Keluhan Pelanggan:</strong> {{ $konsultasi['keluhan_pelanggan'] }}</p>

                        <h6>Detail Hasil Konsultasi:</h6>
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
                                        <td>{{ data_get($detail, 'treatment.nama_treatment', 'Tidak ada treatment') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                @endif

                {{-- ========== Booking Treatment (Reguler) ========== --}}
                @if (!empty($item['booking_reguler']))
                    <h6 class="mt-3">Booking Treatment (Reguler)</h6>
                    @foreach ($item['booking_reguler'] as $booking)
                        <p><strong>Waktu Treatment:</strong> {{ $booking['waktu_treatment'] }}</p>
                        <p><strong>Dokter:</strong> {{ data_get($booking, 'dokter.nama_dokter', 'Tidak ada dokter') }}</p>
                        <p><strong>Beautician:</strong>
                            {{ data_get($booking, 'beautician.nama_beautician', 'Tidak ada beautician') }}</p>

                        <h6>Detail Booking:</h6>
                        <table class="table table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th>Nama Treatment</th>
                                    <th>Biaya Treatment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($booking['detail_booking'] ?? [] as $detail)
                                    <tr>
                                        <td>{{ data_get($detail, 'treatment.nama_treatment', '-') }}</td>
                                        <td>Rp{{ number_format((float) data_get($detail, 'biaya_treatment', 0), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                @endif

                {{-- ========== Booking Treatment (Paket) ========== --}}
                @if (!empty($item['booking_paket']))
                    <h6 class="mt-3">Booking Treatment (Paket)</h6>
                    @foreach ($item['booking_paket'] as $booking)
                        <p><strong>Waktu Treatment:</strong> {{ $booking['waktu_treatment'] }}</p>
                        <p><strong>Dokter:</strong> {{ data_get($booking, 'dokter.nama_dokter', 'Tidak ada dokter') }}</p>
                        <p><strong>Beautician:</strong>
                            {{ data_get($booking, 'beautician.nama_beautician', 'Tidak ada beautician') }}</p>

                        <h6>Detail Booking Paket:</h6>
                        @php
                            // Ambil array detail dari beberapa kemungkinan key
                            $detailsPaket =
                                $booking['details'] ??
                                ($booking['detail_booking_paket'] ?? ($booking['detail_booking'] ?? []));
                        @endphp
                        <table class="table table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th>Nama Paket Treatment</th>
                                    <th>Nama Treatment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detailsPaket as $detail)
                                    @php
                                        $namaPaket =
                                            data_get($detail, 'paket_pelanggan.paket.nama_paket_treatment') ??
                                            (data_get($detail, 'paket.nama_paket_treatment') ??
                                                (data_get($detail, 'paket_pelanggan.nama_paket_treatment') ?? '—'));
                                        $namaTreatment =
                                            data_get($detail, 'treatment.nama_treatment') ??
                                            data_get($detail, 'nama_treatment', '—');
                                    @endphp
                                    <tr>
                                        <td>{{ $namaPaket }}</td>
                                        <td>{{ $namaTreatment }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">— Tidak ada detail —</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endforeach
                @endif
            </div>
        @empty
            <div class="alert alert-info">Belum ada data konsultasi/booking.</div>
        @endforelse
    </div>
@endsection
