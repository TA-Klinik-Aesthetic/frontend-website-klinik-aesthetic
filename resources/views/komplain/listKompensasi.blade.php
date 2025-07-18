@extends('dashboard.index')

@section('content')
    <!-- Custom CSS untuk DataTables filter -->
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

    <h1 class="h3 mb-2 text-gray-800">Kompensasi</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#tambahKompensasiModal">
        <i class="fas fa-plus"></i> Tambah Kompensasi
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="listKompensasiTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>Nama Kompensasi</th>
                        <th>Nama Treatment</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kompensasiList as $kompensasi)
                        <tr>
                            <td style="display:none">{{ $kompensasi['id_kompensasi'] }}</td>
                            <td>{{ $kompensasi['nama_kompensasi'] }}</td>
                            <td>{{ $kompensasi['nama_treatment'] }}</td>
                            <td>{{ $kompensasi['deskripsi_kompensasi'] }}</td>
                            <td>
                                <button type="button" class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#editKompensasiModal"
                                    onclick="populateEditModal({{ json_encode($kompensasi) }})">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Kompensasi -->
    <div class="modal fade js-reset-on-show" id="tambahKompensasiModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahKompensasiModalLabel" aria-hidden="true">
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
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}
                                    </option>
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
                        <button type="submit" class="btn btn-pale">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kompensasi -->
    <div class="modal fade" id="editKompensasiModal" tabindex="-1" role="dialog"
        aria-labelledby="editKompensasiModalLabel" aria-hidden="true">
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
                            <input type="text" name="nama_kompensasi" class="form-control" id="edit_nama_kompensasi"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_treatment">Treatment</label>
                            <select name="id_treatment" class="form-control" id="edit_id_treatment" required>
                                <option value="">Pilih Treatment</option>
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}
                                    </option>
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
                        <button type="submit" class="btn btn-pale">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
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
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#listKompensasiTable').DataTable({
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
                    }],
                    order: [
                        [0, 'desc']
                    ], // urutkan berdasarkan kolom ID (index 0) descending    
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
@endsection
