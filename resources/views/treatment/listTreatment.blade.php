@extends('dashboard.index')

@section('content')

    <style>
        /* pastikan kontainer filter benar-benar rata-kanan */
        .dataTables_filter {
            text-align: right !important;
        }

        /* label cukup inline-flex, tidak full-width */
        .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        /* jarak antara teks “Search:” dan input */
        .dataTables_filter label input {
            margin-left: 0.5rem;
        }

        /* Contoh: semua paginate button jadi merah solid */
        .dataTables_wrapper .dataTables_paginate .btn {
            background-color: #F3A14B !important;
            /* merah */
            border-color: #F3A14B !important;
            color: #fff !important;
        }

        /* Hover */
        .dataTables_wrapper .dataTables_paginate .btn:hover {
            background-color: #F3A14B !important;
            border-color: #F3A14B !important;
        }
    </style>

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

    <h1 class="h3 mb-2 text-gray-800">Treatment</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#tambahTreatmentModal">
        <i class="fas fa-plus"></i> Tambah Treatment
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="listTreatmentTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>Nama Treatment</th>
                        <th>Jenis Treatment</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($treatments as $treatment)
                        <tr>
                            <td style="display:none">{{ $treatment['id_treatment'] }}</td>
                            <td>{{ $treatment['nama_treatment'] }}</td>
                            <td>{{ $treatment['jenis_treatment']['nama_jenis_treatment'] }}</td>
                            <td>
                                <a href="{{ route('treatment.show', $treatment['id_treatment']) }}"
                                    class="btn btn-pale mb-3">
                                    Detail
                                </a>

                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#editTreatmentModal"
                                    onclick="populateEditModal({{ json_encode($treatment) }})">
                                    Edit
                                </button>

                                <form action="{{ route('treatment.destroy', $treatment['id_treatment']) }}" method="POST"
                                    class="delete-treatment-form" data-used="{{ $treatment['detail_booking_treatment_count'] }}"
                                    style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-pale mb-3">
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

    <!-- Modal Tambah Treatment -->
    <div class="modal fade" id="tambahTreatmentModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahTreatmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('treatment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahTreatmentModalLabel">Tambah Treatment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_jenis_treatment">Jenis Treatment</label>
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
                            <label for="estimasi_treatment">Estimasi Treatment (Jam:Menit)</label>
                            <input type="time" name="estimasi_treatment" class="form-control" id="estimasi_treatment"
                                required>
                        </div>
                        <div>
                            <label for="gambar_treatment">Upload Gambar:</label>
                            <input type="file" name="gambar_treatment" id="gambar_treatment" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-pale">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Treatment -->
    <div class="modal fade" id="editTreatmentModal" tabindex="-1" role="dialog" aria-labelledby="editTreatmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editTreatmentForm" method="POST" enctype="multipart/form-data" style="display: contents;">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTreatmentModalLabel">Edit Treatment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- Jenis Treatment --}}
                        <div class="form-group">
                            <label for="edit_id_jenis_treatment">Jenis Treatment</label>
                            <select name="id_jenis_treatment" id="edit_id_jenis_treatment" class="form-control" required>
                                <option value="">— Pilih Jenis —</option>
                                @foreach ($jenisTreatments as $jt)
                                    <option value="{{ $jt['id_jenis_treatment'] }}">
                                        {{ $jt['nama_jenis_treatment'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nama --}}
                        <div class="form-group">
                            <label for="edit_nama_treatment">Nama Treatment</label>
                            <input type="text" name="nama_treatment" class="form-control" id="edit_nama_treatment"
                                required>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label for="edit_deskripsi_treatment">Deskripsi</label>
                            <textarea name="deskripsi_treatment" class="form-control" id="edit_deskripsi_treatment" rows="3"></textarea>
                        </div>

                        {{-- Biaya --}}
                        <div class="form-group">
                            <label for="edit_biaya_treatment">Biaya Treatment</label>
                            <input type="number" name="biaya_treatment" class="form-control" id="edit_biaya_treatment"
                                required>
                        </div>

                        {{-- Estimasi --}}
                        <div class="form-group">
                            <label for="edit_estimasi_treatment">Estimasi Treatment</label>
                            <input type="time" name="estimasi_treatment" class="form-control"
                                id="edit_estimasi_treatment" required>
                        </div>

                        {{-- Gambar (opsional) --}}
                        <div class="form-group">
                            <label for="edit_gambar_treatment">Ganti Gambar Treatment</label>
                            <input type="file" name="gambar_treatment" id="edit_gambar_treatment"
                                class="form-control-file">
                            <small class="form-text text-muted">
                                Kosongkan jika tidak ingin mengubah gambar.
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-pale">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function populateEditModal(treatment) {
                const form = document.getElementById('editTreatmentForm');
                form.action = `/treatment/${treatment.id_treatment}`;

                document.getElementById('edit_id_jenis_treatment').value = treatment.id_jenis_treatment;
                document.getElementById('edit_nama_treatment').value = treatment.nama_treatment;
                document.getElementById('edit_deskripsi_treatment').value = treatment.deskripsi_treatment || '';
                document.getElementById('edit_biaya_treatment').value = treatment.biaya_treatment;
                document.getElementById('edit_estimasi_treatment').value = treatment.estimasi_treatment;
                // file input tidak bisa di‐prefill, tapi keterangan sudah cukup
            }
        </script>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#listTreatmentTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    pagingType: 'simple_numbers',
                    dom: "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-right'p>>",
                    columnDefs: [{
                            targets: 0,
                            visible: false,
                            searchable: false
                        } // sembunyikan kolom ID
                    ],
                    order: [
                        [0, 'desc']
                    ], // urutkan berdasarkan ID treatment menurun
                    drawCallback: function(settings) {
                        // styling ulang pagination setiap draw
                        $('.dataTables_wrapper .dataTables_paginate a').each(function() {
                            $(this)
                                .removeClass('paginate_button')
                                .addClass('btn btn-sm btn-outline-primary mx-1');
                        });
                    }
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.delete-treatment-form').forEach(form => {
                    form.addEventListener('submit', e => {
                        const used = parseInt(form.dataset.used, 10);
                        if (used > 0) {
                            e.preventDefault();
                            alert('Tidak dapat menghapus: treatment ini sudah ada yang booking.');
                            return;
                        }
                        if (!confirm('Apakah Anda yakin ingin menghapus treatment ini?')) {
                            e.preventDefault();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
