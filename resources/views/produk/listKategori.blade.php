@extends('dashboard.index')

@section('content')
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

    <h1 class="h3 mb-2 text-gray-800">List Kategori Produk</h1>
    <a href="{{ route('kategori.create') }}" class="btn btn-pale mb-3">Tambah Kategori</a>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriProduk as $item)
                            <tr>
                                <td>{{ $item['nama_kategori'] }}</td>
                                <td>

                                    <a href="{{ route('kategori.edit', $item['id_kategori']) }}"
                                    class="btn btn-pale mb-3">Edit</a>

                                    <form action="{{ route('kategori.destroy', $item['id_kategori']) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-pale mb-3"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">Tidak ada data kategori tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
