@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1>Laporan Penjualan Produk</h1>

        <!-- Filter Form for Daily Report -->
        <form action="{{ route('laporan-produk.harian') }}" method="get" class="d-flex align-items-center mb-4">
            <div class="form-group mb-0 mr-2">
                <label for="tanggal" class="sr-only">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" style="width: 150px;">
            </div>
            <button type="submit" class="btn btn-primary">Filter Harian</button>
            <!-- Button to Export Daily Report to PDF -->
            <a href="{{ route('laporan-produk.export-harian', ['tanggal' => request()->input('tanggal')]) }}"
                class="btn btn-success ml-2">
                Export Harian to PDF
            </a>
        </form>

        <!-- Filter Form for Monthly Report -->
        <form action="{{ route('laporan-produk.bulanan') }}" method="get" class="d-flex align-items-center mb-4">
            <div class="form-group mb-0 mr-2">
                <label for="bulan" class="sr-only">Bulan:</label>
                <input type="month" name="bulan" id="bulan" class="form-control" style="width: 180px;">
                <!-- Increased width here -->
            </div>
            <button type="submit" class="btn btn-primary">Filter Bulanan</button>
            <!-- Button to Export Monthly Report to PDF -->
            <a href="{{ route('laporan-produk.export-bulanan', ['bulan' => request()->input('bulan'), 'tahun' => request()->input('tahun')]) }}"
                class="btn btn-success ml-2">
                Export Bulanan to PDF
            </a>
        </form>

        <h3>Data Laporan:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal Pembelian</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Produk</th>
                    <th>Harga Penjualan Produk</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['data'] as $item)
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
@endsection
