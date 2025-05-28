{{-- @extends('dashboard.index')

@section('content')
    <div class="container">
        <h3>Data Inventaris Stok Produk</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Status Perubahan</th>
                    <th>Jumlah</th>
                    <th>Waktu Perubahan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inventaris as $item)
                    <tr>
                        <td>{{ $item['produk']['nama_produk'] ?? '-' }}</td>
                        <td>{{ ucfirst($item['status_perubahan']) }}</td>
                        <td>{{ $item['jumlah_perubahan'] }}</td>
                        <td>{{ $item['waktu_perubahan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data inventaris stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- class="table-dark" --}}
@endsection --}}
