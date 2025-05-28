@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1>Laporan Penjualan Treatment</h1>

        <!-- Filter Form for Daily Report -->
        <form action="{{ route('laporan-treatment.harian') }}" method="get" class="d-flex align-items-center mb-4">
            <div class="form-group mb-0 mr-2">
                <label for="tanggal" class="sr-only">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" style="width: 150px;">
            </div>
            <button type="submit" class="btn btn-primary">Filter Harian</button>
            <!-- Button to Export Daily Report to PDF -->
            <a href="{{ route('laporan-treatment.export-harian', ['tanggal' => request()->input('tanggal')]) }}"
                class="btn btn-success ml-2">
                Export Harian to PDF
            </a>
        </form>

        <!-- Filter Form for Monthly Report -->
        <form action="{{ route('laporan-treatment.bulanan') }}" method="get" class="d-flex align-items-center mb-4">
            <div class="form-group mb-0 mr-2">
                <label for="bulan" class="sr-only">Bulan:</label>
                <input type="month" name="bulan" id="bulan" class="form-control" style="width: 180px;">
                <!-- Increased width here -->
            </div>
            <button type="submit" class="btn btn-primary">Filter Bulanan</button>
            <!-- Button to Export Monthly Report to PDF -->
            <a href="{{ route('laporan-treatment.export-bulanan', ['bulan' => request()->input('bulan'), 'tahun' => request()->input('tahun')]) }}"
                class="btn btn-success ml-2">
                Export Bulanan to PDF
            </a>
        </form>

        <h3>Data Laporan:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Waktu Treatment</th>
                    <th>Nama Treatment</th>
                    <th>Biaya Treatment</th>
                    <th>Dokter</th>
                    <th>Beautician</th>
                    <th>Kompensasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['data'] as $item)
                    <tr>
                        <td>{{ $item['waktu_treatment'] }}</td>
                        <td>{{ $item['nama_treatment'] }}</td>
                        <td>{{ $item['biaya_treatment'] }}</td>
                        <td>{{ $item['dokter'] }}</td>
                        <td>{{ $item['beautician'] }}</td>
                        <td>{{ $item['kompensasi'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <h4>Total Penjualan Treatment: {{ $data['subtotal'] }}</h4>
        <h4>Pajak (10%): {{ $data['pajak'] }}</h4>
        <h4>Total Pendapatan: {{ $data['total_pendapatan'] }}</h4> --}}

    </div>
@endsection
