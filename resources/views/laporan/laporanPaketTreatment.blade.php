@extends('dashboard.index')

@section('content')
    <style>
        .dataTables_filter {
            text-align: right !important
        }

        .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            white-space: nowrap
        }

        .dataTables_filter label input {
            margin-left: .5rem
        }

        .dataTables_wrapper .dataTables_paginate .btn {
            background: #F3A14B !important;
            border-color: #F3A14B !important;
            color: #fff !important
        }

        .dataTables_wrapper .dataTables_paginate .btn:hover {
            background: #d18d3f !important;
            border-color: #d18d3f !important
        }

        .btn-pale {
            background: #F3A14B !important;
            border-color: #F3A14B !important;
            color: #fff !important
        }

        .btn-pale:hover,
        .btn-pale:focus {
            background: #d18d3f !important;
            border-color: #d18d3f !important;
            color: #fff !important
        }
    </style>

    <h1 class="h3 mb-2 text-gray-800">Laporan Penjualan Paket Treatment</h1>

    {{-- Filter Harian --}}
    <form action="{{ route('laporan-paket.harian') }}" method="get" class="d-flex align-items-center mb-3">
        <div class="form-group mb-0 mr-2">
            <label for="tanggal" class="sr-only">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" style="width:150px;"
                value="{{ request('tanggal') }}">
        </div>
        <button type="submit" class="btn btn-pale">Filter Harian</button>

        {{-- Export Harian --}}
        <a href="{{ route('laporan-paket.export-harian', ['tanggal' => request('tanggal')]) }}"
            class="btn btn-pale ml-2 export-harian">Export Harian to PDF</a>
    </form>

    {{-- Filter Bulanan --}}
    <form action="{{ route('laporan-paket.bulanan') }}" method="get" class="d-flex align-items-center mb-4">
        <div class="form-group mb-0 mr-2">
            <label for="bulan" class="sr-only">Bulan:</label>
            <input type="month" name="bulan" id="bulan" class="form-control" style="width:180px;"
                value="{{ request('bulan') }}">
        </div>
        <button type="submit" class="btn btn-pale">Filter Bulanan</button>

        {{-- Export Bulanan --}}
        <a href="{{ route('laporan-paket.export-bulanan', ['bulan' => request('bulan')]) }}"
            class="btn btn-pale ml-2 export-bulanan">Export Bulanan to PDF</a>
    </form>

    <h3>Data Laporan:</h3>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanPaketTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal Pembelian</th>
                        <th>Nama Paket</th>
                        <th>Harga Paket</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['data'] ?? [] as $item)
                        <tr>
                            <td>{{ $item['tanggal_pembelian'] ?? '-' }}</td>
                            <td>{{ $item['nama_paket_treatment'] ?? '-' }}</td>
                            <td>Rp{{ number_format($item['harga_paket_treatment'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>           
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#laporanPaketTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pagingType: 'simple_numbers',
                order: [
                    [0, 'desc']
                ],
                dom: "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-right'p>>",
                drawCallback: function() {
                    $('.dataTables_wrapper .dataTables_paginate a')
                        .removeClass('paginate_button')
                        .addClass('btn btn-sm btn-outline-primary mx-1');
                }
            });

            function hasQueryParam(param) {
                return new URLSearchParams(window.location.search).has(param);
            }

            // Export guards
            $('.export-harian').on('click', function(e) {
                const t = $('#tanggal').val();
                if (!t) {
                    alert('Silakan pilih tanggal sebelum Export Harian.');
                    e.preventDefault();
                    return;
                }
                if (!hasQueryParam('tanggal')) {
                    alert('Setelah memilih tanggal, tekan "Filter Harian" dahulu.');
                    e.preventDefault();
                }
            });

            $('.export-bulanan').on('click', function(e) {
                const b = $('#bulan').val();
                if (!b) {
                    alert('Silakan pilih bulan sebelum Export Bulanan.');
                    e.preventDefault();
                    return;
                }
                if (!hasQueryParam('bulan')) {
                    alert('Setelah memilih bulan, tekan "Filter Bulanan" dahulu.');
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
