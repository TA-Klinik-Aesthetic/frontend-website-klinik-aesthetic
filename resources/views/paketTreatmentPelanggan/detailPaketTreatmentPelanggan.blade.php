@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="my-4">Detail Paket Treatment Pelanggan</h1>

        @php
            $pelanggan =
                $paket['pelanggan']['nama_user'] ?? ($paket['user']['nama_user'] ?? ($paket['nama_pelanggan'] ?? '-'));

            $namaPaket =
                $paket['paket']['nama_paket_treatment'] ??
                ($paket['nama_paket_treatment'] ?? ($paket['paket']['nama_paket'] ?? ($paket['nama_paket'] ?? '-')));

            $details = $paket['details'] ?? ($paket['detail_paket'] ?? []);
        @endphp

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Informasi Paket</h4>
                <a href="{{ route('ptp.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Pelanggan</div>
                        <div class="fw-semibold">{{ $pelanggan }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Nama Paket</div>
                        <div class="fw-semibold">{{ $namaPaket }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail isi paket: hanya Treatment & Jumlah Penggunaan/Kuota --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Isi Paket</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Treatment</th>
                            <th>Jumlah Penggunaan / Kuota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $d)
                            @php
                                $namaT = $d['treatment']['nama_treatment'] ?? ($d['nama_treatment'] ?? 'Treatment');
                                // Ambil nilai kuota/jumlah penggunaan dari beberapa kemungkinan field
                                $kuota =
                                    $d['jumlah_penggunaan_max'] ?? ($d['jumlah_penggunaan'] ?? ($d['kuota'] ?? null));
                            @endphp
                            <tr>
                                <td>{{ $namaT }}</td>
                                <td>{{ is_null($kuota) ? '–' : $kuota }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Tidak ada detail</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
