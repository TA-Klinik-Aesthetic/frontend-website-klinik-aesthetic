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

    <h1 class="h3 mb-2 text-gray-800">Kompensasi Pelanggan</h1>

    {{-- <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahKompensasiDiberikanModal">
        <i class="fas fa-plus"></i> Tambah Kompensasi Diberikan
    </button> --}}

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanKompensasiPelangganTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Kompensasi</th>
                        <th>Kode Kompensasi</th>
                        <th>Status Kompensasi</th>
                        <th>Tanggal Berakhir</th>
                        <th>Tanggal Pemakaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kompensasiDiberikan as $item)
                        <tr>
                            <td style="display:none">{{ $item['id_kompensasi_diberikan'] }}</td>
                            <td>{{ $item['komplain']['user']['nama_user'] ?? '-' }}</td>
                            <td>{{ $item['kompensasi']['nama_kompensasi'] ?? '-' }}</td>
                            <td>{{ $item['kode_kompensasi'] }}</td>
                            <td>{{ $item['status_kompensasi'] }}</td>
                            <td>{{ $item['tanggal_berakhir_kompensasi'] }}</td>
                            <td>{{ $item['tanggal_pemakaian_kompensasi'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#laporanKompensasiPelangganTable').DataTable({
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
@endsection

{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tambahKolomBtn = document.getElementById('tambah-kolom');
        tambahKolomBtn.addEventListener('click', function () {
            const container = document.getElementById('kompensasi-container');
            const group = container.querySelector('.kompensasi-group').cloneNode(true);

            // Reset nilai field
            group.querySelectorAll('input, select').forEach(el => el.value = '');

            container.appendChild(group);
        });
    });
</script> --}}
