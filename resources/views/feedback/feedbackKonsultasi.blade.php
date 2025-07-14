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

    <h1 class="h3 mb-2 text-gray-800">Feedback Konsultasi</h1>

    <!-- Feedback List -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="listFeedbackKonsultasi" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>Dokter</th>
                        <th>Rating</th>
                        <th>Teks Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $feedback)
                        <tr>
                            <td style="display:none">{{ $feedback['id_feedback_konsultasi'] }}</td>
                            <td>{{ $feedback['nama_dokter'] }}</td>
                            <td>{{ $feedback['rating'] }}</td>
                            <td>{{ $feedback['teks_feedback'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#listFeedbackKonsultasi').DataTable({
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
