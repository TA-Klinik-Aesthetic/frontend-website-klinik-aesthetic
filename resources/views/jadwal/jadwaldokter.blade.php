@extends('dashboard.index')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Jadwal Praktik Dokter</h1>

    {{-- <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#jadwalModal"  onclick="showAddModal()">Tambah Jadwal</button> --}}

    @if (isset($jadwals) && $jadwals->count() > 0)
        <div id="jadwalTable">
            @foreach ($jadwals as $hari => $jadwalPerHari)
                <h3>{{ ucfirst($hari) }}</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Dokter</th>
                            <th>Tanggal</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            {{-- <th>Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwalPerHari as $jadwal)
                            <tr>
                                <td>{{ $jadwal['dokter']['nama_dokter'] ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $jadwal['tgl_kerja'] }}</td>
                                <td>{{ $jadwal['jam_mulai'] }}</td>
                                <td>{{ $jadwal['jam_selesai'] }}</td>
                                {{-- <td>
                                    <button class="btn btn-warning btn-sm" onclick="showEditModal({{ json_encode($jadwal) }})">Edit</button>
                                    <form action="{{ route('jadwal-dokter.destroy', $jadwal['id_jadwal_praktik_dokter']) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    @else
        <p>Tidak ada jadwal ditemukan.</p>
    @endif

    {{-- <!-- Modal Tambah -->
    <div class="modal fade" id="jadwalModal" tabindex="-1" aria-labelledby="jadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jadwalModalLabel" >Tambah Jadwal</h5>
                    <!-- <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#jadwalModal">Tambah Jadwal</button> -->
                </div>
                <form action="{{ route('jadwal-dokter.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="idDokter" class="form-label">ID Dokter</label>
                            <input type="number" class="form-control" name="id_dokter" required>
                        </div>
                        <div class="mb-3">
                            <label for="hari" class="form-label">Hari</label>
                            <input type="text" class="form-control" name="hari" required>
                        </div>
                        <div class="mb-3">
                            <label for="tglKerja" class="form-label">Tanggal Kerja</label>
                            <input type="date" class="form-control" name="tgl_kerja" required>
                        </div>
                        <div class="mb-3">
                            <label for="jamMulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="jamSelesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJadwalModalLabel">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editJadwalForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="editIdJadwal" name="id_jadwal">
                        <div class="mb-3">
                            <label for="editIdDokter" class="form-label">ID Dokter</label>
                            <input type="number" class="form-control" id="editIdDokter" name="id_dokter" required>
                        </div>
                        <div class="mb-3"> 
                            <label for="editHari" class="form-label">Hari</label>
                            <input type="text" class="form-control" id="editHari" name="hari" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTglKerja" class="form-label">Tanggal Kerja</label>
                            <input type="date" class="form-control" id="editTglKerja" name="tgl_kerja" required>
                        </div>
                        <div class="mb-3">
                            <label for="editJamMulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" id="editJamMulai" name="jam_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="editJamSelesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" id="editJamSelesai" name="jam_selesai" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

{{-- <script>  
function showEditModal(jadwal) {
    document.getElementById('editIdJadwal').value = jadwal.id_jadwal_praktik_dokter;
    document.getElementById('editIdDokter').value = jadwal.id_dokter;
    document.getElementById('editHari').value = jadwal.hari;
    document.getElementById('editTglKerja').value = jadwal.tgl_kerja;
    document.getElementById('editJamMulai').value = jadwal.jam_mulai;
    document.getElementById('editJamSelesai').value = jadwal.jam_selesai;

    const form = document.getElementById('editJadwalForm');
    form.action = `/jadwal-dokter/${jadwal.id_jadwal_praktik_dokter}`;

    new bootstrap.Modal(document.getElementById('editJadwalModal')).show();
}

function showAddModal() {
    // Reset semua input di form "Tambah Jadwal"
    document.querySelector('#jadwalModal input[name="id_dokter"]').value = '';
    document.querySelector('#jadwalModal input[name="hari"]').value = '';
    document.querySelector('#jadwalModal input[name="tgl_kerja"]').value = '';
    document.querySelector('#jadwalModal input[name="jam_mulai"]').value = '';
    document.querySelector('#jadwalModal input[name="jam_selesai"]').value = '';

    // Tampilkan modal "Tambah Jadwal"
    new bootstrap.Modal(document.getElementById('jadwalModal')).show();
}

</script> --}}
@endsection
