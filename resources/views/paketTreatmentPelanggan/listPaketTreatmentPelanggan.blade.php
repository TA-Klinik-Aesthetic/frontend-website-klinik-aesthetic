@extends('dashboard.index')

@section('content')
    <style>
        .dataTables_filter { text-align: right !important; }
        .dataTables_filter label { display: inline-flex; align-items: center; white-space: nowrap; }
        .dataTables_filter label input { margin-left: .5rem; }
        .dataTables_wrapper .dataTables_paginate .btn {
            background-color: #F3A14B !important; border-color: #F3A14B !important; color: #fff !important;
        }
        .dataTables_wrapper .dataTables_paginate .btn:hover {
            background-color: #d18d3f !important; border-color: #d18d3f !important;
        }
        .btn-pale { background: #F3A14B !important; border-color:#F3A14B !important; color:#fff !important; }
        .btn-pale:hover { background:#d18d3f !important; border-color:#d18d3f !important; color:#fff !important; }
    </style>

    <h1 class="h3 mb-2 text-gray-800">Paket Treatment Pelanggan</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="listPaketTreatmentPelangganTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>Pelanggan</th>
                        <th>Nama Paket</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $p)
                        @php
                            $id    = $p['id_paket_treatment_pelanggan'] ?? $p['id'] ?? null;

                            $pelanggan = $p['pelanggan']['nama_user']
                                ?? $p['user']['nama_user']
                                ?? $p['nama_pelanggan']
                                ?? '-';

                            $namaPaket = $p['paket']['nama_paket_treatment']
                                ?? $p['nama_paket_treatment']
                                ?? $p['paket']['nama_paket']
                                ?? $p['nama_paket']
                                ?? '-';
                        @endphp
                        <tr>
                            <td style="display:none">{{ $id }}</td>
                            <td>{{ $pelanggan }}</td>
                            <td>{{ $namaPaket }}</td>
                            <td>
                                <a href="{{ route('ptp.show', $id) }}" class="btn btn-pale mb-2">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#listPaketTreatmentPelangganTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100],[10, 25, 50, 100]],
                    pagingType: 'simple_numbers',
                    dom: "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'f>>" +
                         "<'row'<'col-sm-12'tr>>" +
                         "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-right'p>>",
                    columnDefs: [{ targets: 0, visible:false, searchable:false }],
                    order: [[0, 'desc']],
                    drawCallback: function() {
                        $('.dataTables_wrapper .dataTables_paginate a').each(function() {
                            $(this).removeClass('paginate_button').addClass('btn btn-sm btn-outline-primary mx-1');
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
