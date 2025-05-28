@extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Produk</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Masukkan nama produk" required>
                </div>

                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label">Deskripsi Produk</label>
                    <textarea class="form-control" id="deskripsi_produk" name="deskripsi_produk" rows="3" placeholder="Masukkan deskripsi produk" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="harga_produk" class="form-label">Harga Produk</label>
                    <input type="number" class="form-control" id="harga_produk" name="harga_produk" placeholder="Masukkan harga produk" required>
                </div>

                <div class="mb-3">
                    <label for="stok_produk" class="form-label">Stok Produk</label>
                    <input type="number" class="form-control" id="stok_produk" name="stok_produk" placeholder="Masukkan stok produk" required>
                </div>

                <div class="mb-3">
                    <label for="status_produk" class="form-label">Status Produk</label>
                    <select class="form-control" id="status_produk" name="status_produk" required>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Habis">Habis</option>
                    </select>
                </div>

                <div>
                    <label for="gambar_produk">Upload Gambar:</label>
                    <input type="file" name="gambar_produk" id="gambar_produk" required>
                </div>
                
                <div class="mb-3">
                    <label for="id_kategori" class="form-label">Kategori Produk</label>
                    <select class="form-control" id="id_kategori" name="id_kategori" required>
                        @foreach($kategoriList as $kategoriList)
                            <option value="{{ $kategoriList['id_kategori'] }}">{{ $kategoriList['nama_kategori'] }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection