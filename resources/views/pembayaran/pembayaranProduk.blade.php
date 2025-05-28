@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1>Data Pembayaran Produk</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tombol untuk Menambah Pembayaran -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#tambahPembayaranModal"
            onclick="clearForm()">Tambah Pembayaran</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Total</th>
                    <th>Metode Pembayaran</th>
                    <th>Uang</th>
                    <th>Kembalian</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaranProdukList as $pembayaran)
                    <tr>
                        <td>{{ $pembayaran['user_name'] }}</td>
                        <td>{{ $pembayaran['harga_akhir'] }}</td>
                        <td>{{ $pembayaran['metode_pembayaran'] }}</td>
                        <td>{{ $pembayaran['uang'] }}</td>
                        <td>{{ $pembayaran['kembalian'] }}</td>
                        <td>{{ $pembayaran['penjualan_produk']['status_pembayaran'] }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editPembayaranModal"
                                data-id="{{ $pembayaran['id_pembayaran_produk'] }}"
                                data-id_penjualan_produk="{{ $pembayaran['id_penjualan_produk'] }}"
                                data-total_bayar="{{ $pembayaran['uang'] }}"
                                data-metode_pembayaran="{{ $pembayaran['metode_pembayaran'] }}"
                                onclick="populateEditModal(this)">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah Pembayaran Produk -->
        <div class="modal fade" id="tambahPembayaranModal" tabindex="-1" role="dialog"
            aria-labelledby="tambahPembayaranModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('pembayaran-produk.store') }}" method="POST" id="tambahPembayaranForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahPembayaranModalLabel">Tambah Pembayaran Produk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- ID Penjualan Produk -->
                            <div class="form-group">
                                <label for="id_penjualan_produk">Penjualan Produk</label>
                                <select class="form-control" id="id_penjualan_produk" name="id_penjualan_produk" required>
                                    <option value="">Pilih User</option>
                                    @foreach ($penjualanProduk as $penjualan)
                                        <option value="{{ $penjualan['id_penjualan_produk'] }}">
                                            {{ $penjualan['user']['nama_user'] }} - {{ $penjualan['tanggal_pembelian'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="form-group">
                                <label for="metode_pembayaran">Metode Pembayaran</label>
                                <select class="form-control" id="metode_pembayaran" name="metode_pembayaran" required>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Non Tunai">Non Tunai</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Pembayaran Produk -->
        <div class="modal fade" id="editPembayaranModal" tabindex="-1" role="dialog"
            aria-labelledby="editPembayaranModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="POST" id="editPembayaranForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPembayaranModalLabel">Edit Pembayaran Produk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- ID Penjualan Produk -->
                            <div class="form-group">
                                <label for="edit_id_penjualan_produk">Penjualan Produk</label>
                                <select class="form-control" id="edit_id_penjualan_produk" name="id_penjualan_produk"
                                    disabled>
                                    @foreach ($penjualanProduk as $penjualan)
                                        <option value="{{ $penjualan['id_penjualan_produk'] }}"
                                            data-user="{{ $penjualan['user']['nama_user'] }}">
                                            {{ $penjualan['user']['nama_user'] }} -
                                            {{ $penjualan['tanggal_pembelian'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="form-group">
                                <label for="edit_metode_pembayaran">Metode Pembayaran</label>
                                <select class="form-control" id="edit_metode_pembayaran" name="metode_pembayaran" required
                                    disabled>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Non Tunai">Non Tunai</option>
                                </select>
                            </div>

                            <!-- Total Bayar -->
                            <div class="form-group">
                                <label for="edit_uang">Uang</label>
                                <input type="number" class="form-control" id="edit_uang" name="uang" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function populateEditModal(button) {
            const pembayaran = button.dataset;

            document.getElementById('editPembayaranForm').action = `/pembayaran-produk/${pembayaran.id}`;
            document.getElementById('edit_id_penjualan_produk').value = pembayaran.id_penjualan_produk;
            document.getElementById('edit_metode_pembayaran').value = pembayaran.metode_pembayaran;
            document.getElementById('edit_uang').value = pembayaran.uang;
        }

        function clearForm() {
            document.getElementById('tambahPembayaranForm').reset();
            document.getElementById('editPembayaranForm').action = '/pembayaran-produk';
        }
    </script>
@endsection
