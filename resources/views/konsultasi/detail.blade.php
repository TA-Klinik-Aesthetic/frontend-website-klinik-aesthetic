@extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Detail Konsultasi</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $konsultasi['nama_user'] }}</td>
                </tr>
                <tr>
                    <th>Nama Dokter</th>
                    <td>{{ $konsultasi['nama_dokter'] }}</td>
                </tr>
                <tr>
                    <th>Waktu Konsultasi</th>
                    <td>{{ $konsultasi['waktu_konsultasi'] ?? 'Tidak tersedia' }}</td>
                </tr>
                <tr>
                    <th>Keluhan Pelanggan</th>
                    <td>{{ $konsultasi['keluhan_pelanggan'] ?? 'Tidak ada keluhan' }}</td>
                </tr>
            </table>

            <h4 class="mt-4">Detail Konsultasi</h4>
            
            <!-- Menampilkan pesan jika tidak ada detail konsultasi -->
            @if(count($konsultasi['detail_konsultasi']) === 0)
                <p class="text-muted">Belum ada detail konsultasi yang ditambahkan.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Saran Tindakan</th>
                            <th>Treatment</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($konsultasi['detail_konsultasi'] as $detail)
                            <tr>                               
                                <td>{{ $detail['saran_tindakan'] ?? 'Tidak ada saran' }}</td>
                                <td>
                                    {{ $detail['treatment']['nama_treatment'] ?? 'Tidak ada treatment' }}
                                </td>
                                <td>
                                    @if ($detail['treatment'])
                                        <a href="{{ route('treatment.show', ['id' => $detail['treatment']['id_treatment']]) }}"
                                            class="btn btn-primary btn-sm">
                                            Lihat Detail
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <a href="{{ route('konsultasi.with-doctor') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
@endsection
