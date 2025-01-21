@extends('dashboard.index')

@section('content')
    <h1>List Treatment</h1>

    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#tambahTreatmentModal">
        <i class="fas fa-plus"></i> Tambah Treatment
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Treatment</th>
                <th>Jenis Treatment</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($treatments as $treatment)
                <tr>
                    <td>{{ $treatment['nama_treatment'] }}</td>
                    <td>{{ $treatment['jenis_treatment']['nama_jenis_treatment'] }}</td>
                    <td>
                        <a href="{{ route('treatment.show', $treatment['id_treatment']) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Tombol Edit -->
                        <button type="button" class="btn btn-warning btn-sm" 
                                data-toggle="modal" 
                                data-target="#editTreatmentModal" 
                                onclick="populateEditModal({{ json_encode($treatment) }})">
                            <i class="fas fa-edit"></i>
                        </button>

                        <form action="{{ route('treatment.destroy', $treatment['id_treatment']) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus treatment ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Tambah Treatment -->
    <div class="modal fade" id="tambahTreatmentModal" tabindex="-1" role="dialog" aria-labelledby="tambahTreatmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('treatment.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahTreatmentModalLabel">Tambah Treatment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_jenis_treatment">ID Jenis Treatment</label>
                            <select name="id_jenis_treatment" class="form-control" id="id_jenis_treatment" required>
                                <option value="">Pilih Jenis Treatment</option>
                                @foreach ($jenisTreatments as $jenisTreatment)
                                    <option value="{{ $jenisTreatment['id_jenis_treatment'] }}">
                                        {{ $jenisTreatment['nama_jenis_treatment'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_treatment">Nama Treatment</label>
                            <input type="text" name="nama_treatment" class="form-control" id="nama_treatment" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_treatment">Deskripsi Treatment</label>
                            <textarea name="deskripsi_treatment" class="form-control" id="deskripsi_treatment" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="biaya_treatment">Biaya Treatment</label>
                            <input type="number" name="biaya_treatment" class="form-control" id="biaya_treatment" required>
                        </div>
                        <div class="form-group">
                            <label for="estimasi_treatment">Estimasi Treatment (HH:MM)</label>
                            <input type="time" name="estimasi_treatment" class="form-control" id="estimasi_treatment" required>
                        </div>
                        <div class="form-group">
                            <label for="gambar_treatment">Gambar Treatment (URL)</label>
                            <input type="url" name="gambar_treatment" class="form-control" id="gambar_treatment" required>
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

    <!-- Modal Edit Treatment -->
    <div class="modal fade" id="editTreatmentModal" tabindex="-1" role="dialog" aria-labelledby="editTreatmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editTreatmentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTreatmentModalLabel">Edit Treatment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nama_treatment">Nama Treatment</label>
                            <input type="text" name="nama_treatment" class="form-control" id="edit_nama_treatment" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_deskripsi_treatment">Deskripsi Treatment</label>
                            <textarea name="deskripsi_treatment" class="form-control" id="edit_deskripsi_treatment" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_biaya_treatment">Biaya Treatment</label>
                            <input type="number" name="biaya_treatment" class="form-control" id="edit_biaya_treatment" required>
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
        function populateEditModal(treatment) {
            const form = document.getElementById('editTreatmentForm');
            form.action = `/treatment/${treatment.id_treatment}`;
            document.getElementById('edit_nama_treatment').value = treatment.nama_treatment;
            document.getElementById('edit_deskripsi_treatment').value = treatment.deskripsi_treatment;
            document.getElementById('edit_biaya_treatment').value = treatment.biaya_treatment;
        }
    </script>
@endsection
