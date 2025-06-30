@extends('dashboard.index')

@section('content')
    <style>
        /* custom pale-orange button */
        .btn-pale {
            background-color: #F3A14B !important;
            border-color: #F3A14B !important;
            color: #fff !important;
        }

        .btn-pale:hover,
        .btn-pale:focus {
            background-color: #d18d3f !important;
            /* varian gelap */
            border-color: #d18d3f !important;
            color: #fff !important;
        }
    </style>

    <h1 class="h3 mb-2 text-gray-800">List Jenis Treatment</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#tambahJenisTreatmentModal">
        <i class="fas fa-plus"></i> Tambah Jenis Treatment
    </button>

    <!-- Card putih dengan shadow -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Jenis Treatment</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jenisTreatments as $index => $jenis)
                            <tr>
                                <td>{{ $jenis['nama_jenis_treatment'] }}</td>
                                <td>
                                    <button type="button" class="btn btn-pale mb-3" title="Edit" data-toggle="modal"
                                        data-target="#editJenisTreatmentModal-{{ $index }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('jenisTreatment.destroy', $jenis['id_jenis_treatment']) }}"
                                        method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-pale mb-3" title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus jenis treatment ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahJenisTreatmentModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahJenisTreatmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('jenisTreatment.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahJenisTreatmentModalLabel">Tambah Jenis Treatment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_jenis_treatment">Nama Jenis Treatment</label>
                            <input type="text" name="nama_jenis_treatment" class="form-control" id="nama_jenis_treatment"
                                placeholder="Contoh: PISIKAL ATACK" required>
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

    @foreach ($jenisTreatments as $index => $jenis)
        <div class="modal fade" id="editJenisTreatmentModal-{{ $index }}" tabindex="-1" role="dialog"
            aria-labelledby="editJenisTreatmentModalLabel-{{ $index }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('jenisTreatment.update', $jenis['id_jenis_treatment']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editJenisTreatmentModalLabel-{{ $index }}">Edit Jenis
                                Treatment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_jenis_treatment-{{ $index }}">Nama Jenis Treatment</label>
                                <input type="text" name="nama_jenis_treatment" class="form-control"
                                    id="nama_jenis_treatment-{{ $index }}"
                                    value="{{ $jenis['nama_jenis_treatment'] }}" required>
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
    @endforeach
@endsection
