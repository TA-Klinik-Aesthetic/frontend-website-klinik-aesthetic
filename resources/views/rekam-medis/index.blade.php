@extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Rekam Medis</h1>

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

    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}

    <!-- Tabel Rekam Medis -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanRekamMedisTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none;">ID</th>
                        <th>Nama Pengguna</th>
                        <th>Total Konsultasi</th>
                        <th>Total Booking Treatment</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekamMedisData as $data)
                        <tr>
                            <td style="display:none;">{{ $data['user']['id_user'] }}</td>
                            <td>{{ $data['user']['nama_user'] }}</td>
                            <td>{{ $data['total_konsultasi'] }}</td>
                            <td>{{ $data['total_booking_treatment'] }}</td>
                            <td>
                                <!-- Tombol Detail -->
                                <a href="{{ route('rekam-medis.detail', $data['user']['id_user']) }}"
                                    class="btn btn-pale mb-3">Detail</a>

                                <!-- Tombol PDF -->
                                <a href="{{ route('rekam-medis.export-pdf', $data['user']['id_user']) }}"
                                    class="btn btn-pale mb-3">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#laporanRekamMedisTable').DataTable({
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
                ],
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
