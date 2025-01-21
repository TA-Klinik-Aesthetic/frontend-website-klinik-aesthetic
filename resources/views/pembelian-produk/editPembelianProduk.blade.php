@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Penjualan Produk</h1>
        <form action="{{ route('pembelian-produk.update', $pembelian['id_penjualan_produk']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="id_promo" class="form-label">Promo</label>
                <select name="id_promo" id="id_promo" class="form-control">
                    <option value="">-- Pilih Promo --</option>
                    @foreach ($promos as $promo)
                        <option value="{{ $promo['id_promo'] }}"
                            {{ $promo['id_promo'] == $pembelian['id_promo'] ? 'selected' : '' }}>
                            {{ $promo['nama_promo'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="produk-list">
                <h5 class="mb-3">Produk</h5>

                @if (isset($pembelian['produk']) && is_array($pembelian['produk']))
                    @foreach ($pembelian['produk'] as $index => $produk)
                        <div class="row mb-3" id="produk-{{ $index }}">
                            <div class="col-md-6">
                                <label for="produk_{{ $index }}_id_produk" class="form-label">Nama Produk</label>
                                <select name="produk[{{ $index }}][id_produk]"
                                    id="produk_{{ $index }}_id_produk" class="form-control" required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product['id_produk'] }}"
                                            {{ $product['id_produk'] == $produk['id_produk'] ? 'selected' : '' }}>
                                            {{ $product['nama_produk'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="produk_{{ $index }}_jumlah_produk" class="form-label">Jumlah</label>
                                <input type="number" name="produk[{{ $index }}][jumlah_produk]"
                                    id="produk_{{ $index }}_jumlah_produk" class="form-control"
                                    value="{{ $produk['jumlah_produk'] }}" required>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-danger">Tidak ada data produk untuk ditampilkan.</p>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

@endsection
