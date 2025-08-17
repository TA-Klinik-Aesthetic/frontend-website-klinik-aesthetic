@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="my-4">Detail Paket Treatment</h1>

        @php
            $nama = $paket['nama_paket_treatment'] ?? ($paket['nama_paket'] ?? '-');
            $harga = $paket['harga_paket_treatment'] ?? ($paket['harga_paket'] ?? 0);
            $desc = $paket['deskripsi_paket_treatment'] ?? ($paket['deskripsi'] ?? '-');
            $detail = $paket['details'] ?? [];
        @endphp

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Informasi Paket</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-muted small">Nama Paket</div>
                        <div class="fw-semibold">{{ $nama }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Harga Paket</div>
                        <div class="fw-semibold">Rp{{ number_format($harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Jumlah Item</div>
                        <div class="fw-semibold">{{ count($detail) }}</div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="text-muted small mb-1">Deskripsi</div>
                    <div>{{ $desc ?: '–' }}</div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Isi Paket</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Treatment</th>
                            <th>Jumlah Penggunaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detail as $d)
                            <tr>
                                <td>{{ $d['treatment']['nama_treatment'] ?? ($d['nama_treatment'] ?? 'Treatment') }}</td>
                                <td>{{ $d['jumlah_penggunaan'] ?? '-' }}</td>
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
