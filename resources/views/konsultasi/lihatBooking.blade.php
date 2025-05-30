{{-- @extends('dashboard.index')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Daftar Booking Konsultasi Pelanggan</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Waktu Konsultasi</th>
                            <th>Dokter</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item['user']['nama_user'] ?? 'Tidak ada nama pelanggan' }}</td>
                                <td>{{ $item['waktu_konsultasi'] }}</td>
                                <td>{{ $item['dokter']['nama_dokter'] ?? 'Tidak ada dokter' }}</td>
                                <td>
                                    <a href="{{ route('konsultasi.edit', $item['id_konsultasi']) }}" class="btn btn-warning">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection --}}
