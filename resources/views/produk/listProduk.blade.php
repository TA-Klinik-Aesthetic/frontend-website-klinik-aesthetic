@extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">List Produk</h1>
    <a href="{{ route('produk.create') }}" class="btn btn-success mb-4">Tambah Produk</a>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produkList as $produk)
                            <tr>
                                <td>{{ $produk['nama_produk'] }}</td>
                                <td>Rp{{ number_format($produk['harga_produk'], 2, ',', '.') }}</td>
                                <td>{{ $produk['status_produk'] }}</td>
                                <td>{{ $produk['kategori']['nama_kategori'] }}</td>
                                <td>
                                    <a href="{{ route('produk.show', $produk['id_produk']) }}" class="btn btn-info btn-sm">Detail</a>
                                    <a href="{{ route('produk.edit', $produk['id_produk']) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('produk.destroy', $produk['id_produk']) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data produk tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
