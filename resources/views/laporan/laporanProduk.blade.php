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

    <h1 class="h3 mb-2 text-gray-800">Laporan Penjualan Produk</h1>

    <!-- Filter Form for Daily Report -->
    <form action="{{ route('laporan-produk.harian') }}" method="get" class="d-flex align-items-center mb-4">
        <div class="form-group mb-0 mr-2">
            <label for="tanggal" class="sr-only">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" style="width: 150px;"
                value="{{ request('tanggal') }}" {{-- repopulate --}}>
        </div>
        <button type="submit" class="btn btn-pale">Filter Harian</button>

        <!-- Export Harian -->
        <a href="{{ route('laporan-produk.export-harian', ['tanggal' => request('tanggal')]) }}"
            class="btn btn-pale ml-2 export-harian">
            Export Harian to PDF
        </a>
    </form>

    <!-- Filter Form for Monthly Report -->
    <form action="{{ route('laporan-produk.bulanan') }}" method="get" class="d-flex align-items-center mb-4">
        <div class="form-group mb-0 mr-2">
            <label for="bulan" class="sr-only">Bulan:</label>
            <input type="month" name="bulan" id="bulan" class="form-control" style="width: 180px;"
                value="{{ request('bulan') }}" {{-- repopulate --}}>
        </div>
        <button type="submit" class="btn btn-pale">Filter Bulanan</button>

        <!-- Export Bulanan -->
        <a href="{{ route('laporan-produk.export-bulanan', ['bulan' => request('bulan')]) }}"
            class="btn btn-pale ml-2 export-bulanan">
            Export Bulanan to PDF
        </a>
    </form>

    <h3>Data Laporan:</h3>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanProdukTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal Pembelian</th>
                        <th>Nama Produk</th>
                        <th>Jumlah Produk</th>
                        <th>Harga Penjualan Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['data'] as $item)
                        <tr>
                            <td>{{ $item['tanggal_pembelian'] }}</td>
                            <td>{{ $item['nama_produk'] }}</td>
                            <td>{{ $item['jumlah_produk'] }}</td>
                            <td>{{ $item['harga_penjualan_produk'] }}</td>
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
            $('#laporanProdukTable').DataTable({
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
        $(function() {
            function hasQueryParam(param) {
                return new URLSearchParams(window.location.search).has(param);
            }

            // Export Harian
            $('.export-harian').on('click', function(e) {
                const t = $('#tanggal').val();
                if (!t) {
                    alert('Silakan pilih tanggal sebelum Export Harian.');
                    e.preventDefault();
                    return;
                }
                // sudah pilih tanggal, tapi belum tekan Filter Harian?
                if (!hasQueryParam('tanggal')) {
                    alert('Setelah memilih tanggal, tekan tombol "Filter Harian" terlebih dahulu.');
                    e.preventDefault();
                }
            });

            // Export Bulanan
            $('.export-bulanan').on('click', function(e) {
                const b = $('#bulan').val();
                if (!b) {
                    alert('Silakan pilih bulan sebelum Export Bulanan.');
                    e.preventDefault();
                    return;
                }
                // sudah pilih bulan, tapi belum tekan Filter Bulanan?
                if (!hasQueryParam('bulan')) {
                    alert('Setelah memilih bulan, tekan tombol "Filter Bulanan" terlebih dahulu.');
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
