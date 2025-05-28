@extends('dashboard.index')

@section('content')
    <h1>List Promo</h1>

    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#tambahPromoModal">
        <i class="fas fa-plus"></i> Tambah Promo
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Promo</th>
                <th>Potongan Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($promos as $promo)
                <tr>
                    <td>{{ $promo['nama_promo'] }}</td>
                    <td>Rp {{ number_format($promo['potongan_harga']) }}</td>
                    <td>{{ ucfirst($promo['status_promo']) }}</td>
                    <td>
                        <a href="{{ route('promo.show', $promo['id_promo']) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Tambah Promo -->
    <div class="modal fade" id="tambahPromoModal" tabindex="-1" role="dialog" aria-labelledby="tambahPromoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('promo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPromoModalLabel">Tambah Promo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_promo">Nama Promo</label>
                            <input type="text" name="nama_promo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_promo">Deskripsi</label>
                            <textarea name="deskripsi_promo" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="potongan_harga">Potongan Harga</label>
                            <input type="number" name="potongan_harga" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_berakhir">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_berakhir" class="form-control" required>
                        </div>
                        <div>
                            <label for="gambar_promo">Upload Gambar</label>
                            <input type="file" name="gambar_promo" required>
                        </div>
                        <div class="form-group">
                            <label for="status_promo">Status</label>
                            <select name="status_promo" class="form-control" required>
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
