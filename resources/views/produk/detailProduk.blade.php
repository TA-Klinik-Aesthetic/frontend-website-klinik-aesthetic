@extends('dashboard.index')

@section('content')
    <div class="container">
        <h2>Detail Produk</h2>

        <!-- Menampilkan pesan sukses atau error -->
        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif --}}

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $produk['nama_produk'] }}</h5>
                <p class="card-text"><strong>Deskripsi:</strong> {{ $produk['deskripsi_produk'] }}</p>
                <p class="card-text"><strong>Harga:</strong> Rp{{ number_format($produk['harga_produk'], 0, ',', '.') }}</p>
                <p class="card-text"><strong>Status:</strong> {{ $produk['status_produk'] }}</p>
                <p class="card-text"><strong>Kategori:</strong> {{ $produk['kategori']['nama_kategori'] }}</p>
                <p class="card-text"><strong>Stok:</strong> {{ $produk['stok_produk'] }}</p>

                <!-- Gambar Produk -->
                @if (!empty($produk['gambar_produk']))
                    <p><strong>Gambar Produk:</strong></p>
                    <div class="text-center my-3">
                        <img 
                          src="{{ $produk['gambar_produk'] }}" 
                          alt="{{ $produk['nama_produk'] }}" 
                          class="img-fluid mx-auto d-block" 
                          style="max-width: 400px; height: auto;"
                        >
                    </div>
                @else
                    <p>Gambar tidak tersedia.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
