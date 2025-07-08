{{-- @extends('dashboard.index')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Jadwal Praktik Dokter</h1>

    <a href="{{ route('jadwal-dokter.create') }}" class="btn btn-primary mb-3">Tambah Jadwal</a>

    @foreach ($jadwals as $hari => $jadwalList)
        <h3>{{ $hari }}</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Dokter</th>
                    <th>Nama Dokter</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwalList as $jadwal)
                    <tr>
                        <td>{{ $jadwal->id_dokter }}</td>
                        <td>{{ $jadwal->dokter->nama_dokter ?? 'Tidak Diketahui' }}</td>
                        <td>{{ $jadwal->tgl_kerja }}</td>
                        <td>{{ $jadwal->jam_mulai }}</td>
                        <td>{{ $jadwal->jam_selesai }}</td>
                        <td>
                            <a href="{{ route('jadwal-dokter.edit', $jadwal->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('jadwal-dokter.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
@endsection --}}
