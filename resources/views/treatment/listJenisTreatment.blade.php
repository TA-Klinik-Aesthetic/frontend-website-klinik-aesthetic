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

    <h1 class="h3 mb-2 text-gray-800">Jenis Treatment</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#tambahJenisTreatmentModal">
        <i class="fas fa-plus"></i> Tambah Jenis Treatment
    </button>

    <!-- Card putih dengan shadow -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="jenisTreatmentTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>Nama Jenis Treatment</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jenisTreatments as $index => $jenis)
                        <tr>
                            <td style="display:none">{{ $jenis['id_jenis_treatment'] }}</td>
                            <td>{{ $jenis['nama_jenis_treatment'] }}</td>
                            <td>
                                <button type="button" class="btn btn-pale mb-3" title="Edit" data-toggle="modal"
                                    data-target="#editJenisTreatmentModal-{{ $index }}">
                                    Edit
                                </button>

                                <form action="{{ route('jenisTreatment.destroy', $jenis['id_jenis_treatment']) }}"
                                    method="POST" class="delete-jenis-form" data-used="{{ $jenis['treatment_count'] }}"
                                    style="display:inline-block">
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

    <div class="modal fade js-reset-on-show" id="tambahJenisTreatmentModal" tabindex="-1" role="dialog"
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
                            <input type="text" name="nama_jenis_treatment" class="form-control" id="nama_jenis_treatment" required>
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
                            <button type="submit" class="btn btn-pale">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#jenisTreatmentTable').DataTable({
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
                ], // urutkan berdasarkan ID menurun
                drawCallback: function(settings) {
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
        $(document).ready(function() {
            $('#jenisTreatmentTable').DataTable( /* ... */ );

            // intercept delete
            document.querySelectorAll('.delete-jenis-form').forEach(form => {
                form.addEventListener('submit', e => {
                    const used = parseInt(form.dataset.used, 10);
                    if (used > 0) {
                        e.preventDefault();
                        alert('Tidak dapat menghapus: jenis treatment ini sudah dipakai di ' +
                            used + ' treatment.');
                        return;
                    }
                    if (!confirm('Apakah Anda yakin ingin menghapus jenis treatment ini?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
