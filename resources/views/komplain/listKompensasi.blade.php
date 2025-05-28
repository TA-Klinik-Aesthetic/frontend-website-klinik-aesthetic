@extends('dashboard.index')

@section('content')
    <h1>List Kompensasi</h1>

    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#tambahKompensasiModal">
        <i class="fas fa-plus"></i> Tambah Kompensasi
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Kompensasi</th>
                <th>Nama Treatment</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kompensasiList as $kompensasi)
                <tr>
                    <td>{{ $kompensasi['nama_kompensasi'] }}</td>
                    <td>{{ $kompensasi['nama_treatment'] }}</td>
                    <td>{{ $kompensasi['deskripsi_kompensasi'] }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" 
                                data-toggle="modal" 
                                data-target="#editKompensasiModal" 
                                onclick="populateEditModal({{ json_encode($kompensasi) }})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Tambah Kompensasi -->
    <div class="modal fade" id="tambahKompensasiModal" tabindex="-1" role="dialog" aria-labelledby="tambahKompensasiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('kompensasi.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahKompensasiModalLabel">Tambah Kompensasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_kompensasi">Nama Kompensasi</label>
                            <input type="text" name="nama_kompensasi" class="form-control" id="nama_kompensasi" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_treatment">Treatment</label>
                            <select name="id_treatment" class="form-control" id="create_id_treatment" required>
                                <option value="">Pilih Treatment</option>
                                @foreach($treatments as $treatment)
                                    <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_kompensasi">Deskripsi</label>
                            <textarea name="deskripsi_kompensasi" class="form-control" id="deskripsi_kompensasi" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kompensasi -->
    <div class="modal fade" id="editKompensasiModal" tabindex="-1" role="dialog" aria-labelledby="editKompensasiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editKompensasiForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKompensasiModalLabel">Edit Kompensasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nama_kompensasi">Nama Kompensasi</label>
                            <input type="text" name="nama_kompensasi" class="form-control" id="edit_nama_kompensasi" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_treatment">Treatment</label>
                            <select name="id_treatment" class="form-control" id="edit_id_treatment" required>
                                <option value="">Pilih Treatment</option>
                                @foreach($treatments as $treatment)
                                    <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_deskripsi_kompensasi">Deskripsi</label>
                            <textarea name="deskripsi_kompensasi" class="form-control" id="edit_deskripsi_kompensasi" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function populateEditModal(kompensasi) {
            const form = document.getElementById('editKompensasiForm');
            form.action = `/kompensasi/${kompensasi.id_kompensasi}`;
            document.getElementById('edit_nama_kompensasi').value = kompensasi.nama_kompensasi;
            document.getElementById('edit_deskripsi_kompensasi').value = kompensasi.deskripsi_kompensasi;

                // Set selected value pada dropdown treatment
    document.getElementById('edit_id_treatment').value = kompensasi.id_treatment;
        }
    </script>
@endsection
