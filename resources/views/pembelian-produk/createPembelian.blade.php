@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Penjualan Produk</h1>

        <form action="{{ url('pembelian-produk/store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="id_user">Pilih User</label>
                <select name="id_user" id="id_user" class="form-control" required>
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user['id_user'] }}">{{ $user['nama_user'] }}</option>
                    @endforeach
                </select>
            </div>

            <div id="product-list">
                <div class="form-group mb-3 product-item">
                    <label>Produk 1</label>
                    <div class="row">
                        <div class="col-md-8">
                            <select name="produk[0][id_produk]" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product['id_produk'] }}">{{ $product['nama_produk'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="produk[0][jumlah_produk]" class="form-control" placeholder="Jumlah"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="add-product">Tambah Produk</button>

            <div class="form-group mb-3">
                <label for="id_promo">Pilih Promo</label>
                <select name="id_promo" id="id_promo" class="form-control">
                    <option value="">-- Pilih Promo --</option>
                    @foreach ($promos as $promo)
                        <option value="{{ $promo['id_promo'] }}">{{ $promo['nama_promo'] }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let productCount = 1; // Counter for dynamic fields
            const productList = document.getElementById('product-list');
            const addProductBtn = document.getElementById('add-product');

            addProductBtn.addEventListener('click', function() {
                const productItem = document.createElement('div');
                productItem.classList.add('form-group', 'mb-3', 'product-item');
                productItem.innerHTML = `
                <label>Produk ${productCount + 1}</label>
                <div class="row">
                    <div class="col-md-8">
                        <select name="produk[${productCount}][id_produk]" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                            <option value="{{ $product['id_produk'] }}">{{ $product['nama_produk'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="produk[${productCount}][jumlah_produk]" class="form-control" placeholder="Jumlah" required>
                    </div>
                </div>
            `;
                productList.appendChild(productItem);
                productCount++;
            });
        });
    </script>
@endsection
