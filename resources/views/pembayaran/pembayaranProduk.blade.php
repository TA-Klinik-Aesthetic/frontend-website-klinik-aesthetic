@extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Data Pembayaran Produk</h1>

    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}

    <!-- Tombol untuk Menambah Pembayaran -->
    {{-- <button class="btn btn-success mb-3" data-toggle="modal" data-target="#tambahPembayaranModal"
            onclick="clearForm()">Tambah Pembayaran</button> --}}

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Total</th>
                            <th>Metode Pembayaran</th>
                            <th>Uang</th>
                            <th>Kembalian</th>
                            <th>Status Pembayaran</th>
                            <th>Waktu Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pembayaranProdukList as $pembayaran)
                            <tr>
                                <td>{{ $pembayaran['user_name'] }}</td>
                                <td>Rp{{ number_format($pembayaran['harga_akhir'], 0, ',', '.') }}</td>
                                <td>{{ $pembayaran['metode_pembayaran'] }}</td>
                                <td>{{ $pembayaran['uang'] }}</td>
                                <td>{{ $pembayaran['kembalian'] }}</td>
                                <td>{{ $pembayaran['status_pembayaran'] }}</td>
                                <td>{{ $pembayaran['waktu_pembayaran'] }}</td>
                                {{-- <td>{{ $pembayaran['penjualan_produk']['status_pembayaran'] }}</td> --}}
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#editPembayaranModal" data-id="{{ $pembayaran['id_pembayaran'] }}"
                                        data-metode="{{ $pembayaran['metode_pembayaran'] }}"
                                        data-uang="{{ $pembayaran['uang'] }}"
                                        data-harga="{{ $pembayaran['harga_akhir'] }}">
                                        Bayar
                                    </button>

                                    {{-- @if ($pembayaran['metode_pembayaran'] === 'Non Tunai' && $pembayaran['status_pembayaran'] === 'Belum Dibayar')
                                        <form
                                            action="{{ route('pembayaran-produk.confirm', $pembayaran['id_pembayaran']) }}"
                                            method="POST" class="d-inline-block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @endif --}}

                                    <a href="{{ route('invoice.pembayaran-produk', $pembayaran['id_pembayaran']) }}"
                                        class="btn btn-info btn-sm">
                                        Buat Invoice
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- <!-- Modal Tambah Pembayaran Produk -->
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
        </div> --}}

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
                        {{-- <div class="form-group">
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
                            </div> --}}

                        <!-- Metode Pembayaran -->
                        <div class="form-group">
                            <label for="edit_metode_pembayaran">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="edit_metode_pembayaran" class="form-control" disabled>
                                <option value="Tunai">Tunai</option>
                                <option value="Non Tunai">Non Tunai</option>
                            </select>
                            <input type="hidden" name="metode_pembayaran" id="hidden_metode_pembayaran">
                        </div>

                        <!-- Total bayar (untuk validasi) -->
                        <input type="hidden" id="hidden_harga_akhir">

                        <!-- Total Bayar -->
                        <div class="form-group">
                            <label for="edit_uang">Uang</label>
                            <input type="number" class="form-control" id="edit_uang" name="uang">
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

    @push('scripts')
        <script>
            function populateEditModal(button) {
                const id = button.getAttribute('data-id');
                const metode = button.getAttribute('data-metode');
                const uang = button.getAttribute('data-uang');

                const form = document.getElementById('editPembayaranForm');
                form.action = `/pembayaran-produk/${id}`; // sesuaikan route-mu
                document.getElementById('edit_metode_pembayaran').value = metode;
                document.getElementById('edit_uang').value = uang;
            }

            // Pasang event listener ke tombol Edit
            document.querySelectorAll('button[data-target="#editPembayaranModal"]').forEach(btn => {
                btn.addEventListener('click', () => populateEditModal(btn));
            });
        </script>
        <script>
            // populate ketika klik Edit
            function populateEditModal(btn) {
                const id = btn.dataset.id;
                const metode = btn.dataset.metode;
                const uang = btn.dataset.uang;
                const harga = btn.dataset.harga;

                const form = document.getElementById('editPembayaranForm');
                form.action = `/pembayaran-produk/${id}`; // sesuaikan route-mu
                form.dataset.hargaAkhir = harga; // simpan untuk validasi

                // set tampilan select & hidden
                document.getElementById('edit_metode_pembayaran').value = metode;
                document.getElementById('hidden_metode_pembayaran').value = metode;
                document.getElementById('hidden_harga_akhir').value = harga;

                document.getElementById('edit_uang').value = uang ?? '';
            }

            // attach event ke semua tombol Edit
            document.querySelectorAll('button[data-target="#editPembayaranModal"]')
                .forEach(btn => btn.addEventListener('click', () => populateEditModal(btn)));

            // validasi sebelum submit
            document.getElementById('editPembayaranForm').addEventListener('submit', function(e) {
                const metode = document.getElementById('hidden_metode_pembayaran').value;
                const uang = parseFloat(document.getElementById('edit_uang').value) || 0;
                const harga = parseFloat(this.dataset.hargaAkhir);

                if (metode === 'Tunai') {
                    if (!uang) {
                        alert('Masukkan jumlah uang untuk pembayaran Tunai.');
                        e.preventDefault();
                        return;
                    }
                    if (uang < harga) {
                        alert(
                            `Jumlah uang kurang: total tagihan Rp${harga.toLocaleString('id-ID')}.\n\nSilakan masukkan minimal Rp${harga.toLocaleString('id-ID')}.`);
                        e.preventDefault();
                    }
                }
            });
        </script>
    @endpush
@endsection
