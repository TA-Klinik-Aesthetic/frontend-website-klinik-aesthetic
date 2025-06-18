@extends('dashboard.index')

@section('content')
    <h1>Detail Promo</h1>

    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <td>{{ $promo['nama_promo'] }}</td>
        </tr>
        <tr>
            <th>Jenis Promo</th>
            <td>{{ $promo['jenis_promo'] }}</td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td>{{ $promo['deskripsi_promo'] }}</td>
        </tr>
        <tr>
            <th>Tipe Potongan</th>
            <td>{{ $promo['tipe_potongan'] }}</td>
        </tr>
        <tr>
            <th>Potongan Harga</th>
            <td>
                @if ($promo['tipe_potongan'] === 'Diskon')
                    {{ number_format($promo['potongan_harga']) }}%
                @else
                    Rp {{ number_format($promo['potongan_harga']) }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Minimal Belanja</th>
            <td>Rp {{ number_format($promo['minimal_belanja']) }}</td>
        </tr>
        <tr>
            <th>Tanggal Mulai</th>
            <td>{{ $promo['tanggal_mulai'] }}</td>
        </tr>
        <tr>
            <th>Tanggal Berakhir</th>
            <td>{{ $promo['tanggal_berakhir'] }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst($promo['status_promo']) }}</td>
        </tr>
        <tr>
            <th>Gambar</th>
            <td>
                <img src="{{ $promo['gambar_promo'] }}" alt="Gambar Promo" class="img-fluid">
            </td>
        </tr>
    </table>
@endsection
