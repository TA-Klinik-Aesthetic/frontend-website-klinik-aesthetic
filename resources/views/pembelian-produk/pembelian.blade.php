@extends('dashboard.index')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Penjualan Produk</h1>
    <a href="{{ route('pembelian-produk.create') }}" class="btn btn-success mb-4">Add</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                {{-- <th>No</th> --}}
                <th>Nama User</th>
                <th>Tanggal Pembelian</th>
                <th>Harga Total</th>
                <th>Potongan Harga</th>
                <th>Harga Akhir</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembelianProduk as $index => $pembelian)
            <tr>
                {{-- <td>{{ $index + 1 }}</td> --}}
                <td>{{ $pembelian['nama_user'] }}</td>
                <td>{{ $pembelian['tanggal_pembelian'] }}</td>
                <td>Rp{{ number_format($pembelian['harga_total'], 2, ',', '.') }}</td>
                <td>Rp{{ number_format($pembelian['potongan_harga'], 2, ',', '.') }}</td>
                <td>Rp{{ number_format($pembelian['harga_akhir'], 2, ',', '.') }}</td>
                <td>
                    <a href="{{ route('pembelian-produk.show', $pembelian['id_penjualan_produk']) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('pembelian-produk.edit', $pembelian['id_penjualan_produk']) }}" class="btn btn-warning btn-sm">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Data tidak tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
