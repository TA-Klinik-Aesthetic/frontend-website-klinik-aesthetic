@extends('dashboard.index')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Jadwal Praktik Beautician</h1>

    {{-- <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addJadwalModal" onclick="showAddModal()">Tambah Jadwal</button> --}}

    @if (isset($grouped) && $grouped->isNotEmpty())
        @foreach ($grouped as $hari => $jadwals)
            <h3>{{ ucfirst($hari) }}</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Beautician</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        {{-- <th>Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwals as $jadwal)
                        <tr>
                            <td>{{ $jadwal['beautician']['nama_beautician'] ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $jadwal['tgl_kerja'] }}</td>
                            <td>{{ $jadwal['jam_mulai'] }}</td>
                            <td>{{ $jadwal['jam_selesai'] }}</td>
                            {{-- <td>
                                <button class="btn btn-warning btn-sm" onclick="showEditModal({{ json_encode($jadwal) }})">Edit</button>
                                <form action="{{ route('jadwal-beautician.destroy', $jadwal['id_jadwal_praktik_beautician']) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
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
    @else
        <p class="text-muted">Belum ada jadwal yang tersedia.</p>
    @endif
</div>
    {{-- <!-- Modal Tambah -->
    <div class="modal fade" id="addJadwalModal" tabindex="-1" aria-labelledby="addJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('jadwal-beautician.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addJadwalModalLabel">Tambah Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addIdBeautician" class="form-label">ID Beautician</label>
                            <input type="number" class="form-control" name="id_beautician" id="addIdBeautician" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="addHari" class="form-label">Hari</label>
                            <input type="text" class="form-control" name="hari" id="addHari" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="addTglKerja" class="form-label">Tanggal Kerja</label>
                            <input type="date" class="form-control" name="tgl_kerja" id="addTglKerja" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="addJamMulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" id="addJamMulai" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="addJamSelesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" id="addJamSelesai" required autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    {{-- <!-- Modal Edit -->
    <div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="editJadwalForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editJadwalModalLabel">Edit Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editJadwalId" name="id">
                        <div class="mb-3">
                            <label for="editIdBeautician" class="form-label">ID Beautician</label>
                            <input type="number" class="form-control" name="id_beautician" id="editIdBeautician" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="editHari" class="form-label">Hari</label>
                            <input type="text" class="form-control" name="hari" id="editHari" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="editTglKerja" class="form-label">Tanggal Kerja</label>
                            <input type="date" class="form-control" name="tgl_kerja" id="editTglKerja" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="editJamMulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" id="editJamMulai" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="editJamSelesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" id="editJamSelesai" required autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

{{-- <script>
function showEditModal(jadwal) {
    document.getElementById('editJadwalId').value = jadwal.id_jadwal_praktik_beautician;
    document.getElementById('editIdBeautician').value = jadwal.id_beautician;
    document.getElementById('editHari').value = jadwal.hari;
    document.getElementById('editTglKerja').value = jadwal.tgl_kerja;
    document.getElementById('editJamMulai').value = jadwal.jam_mulai;
    document.getElementById('editJamSelesai').value = jadwal.jam_selesai;

    const form = document.getElementById('editJadwalForm');
    form.action = `/jadwal-beautician/${jadwal.id_jadwal_praktik_beautician}`;

    new bootstrap.Modal(document.getElementById('editJadwalModal')).show();
}

function showAddModal() {
    // Reset all input fields in the "Tambah Jadwal" modal
    document.querySelector('#addJadwalModal input[name="id_beautician"]').value = '';
    document.querySelector('#addJadwalModal input[name="hari"]').value = '';
    document.querySelector('#addJadwalModal input[name="tgl_kerja"]').value = '';
    document.querySelector('#addJadwalModal input[name="jam_mulai"]').value = '';
    document.querySelector('#addJadwalModal input[name="jam_selesai"]').value = '';

    // Show the "Tambah Jadwal" modal
    new bootstrap.Modal(document.getElementById('addJadwalModal')).show();
}
</script> --}}
@endsection
