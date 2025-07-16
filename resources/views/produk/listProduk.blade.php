@extends('dashboard.index')

@section('content')
    @if (session('error'))
        <script>
            alert(@json(session('error')));
        </script>
    @endif

    <style>
        /* pastikan kontainer filter benar-benar rata-kanan */
        .dataTables_filter {
            text-align: right !important;
        }

        /* label cukup inline-flex, tidak full-width */
        .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        /* jarak antara teks “Search:” dan input */
        .dataTables_filter label input {
            margin-left: 0.5rem;
        }

        /* Contoh: semua paginate button jadi merah solid */
        .dataTables_wrapper .dataTables_paginate .btn {
            background-color: #F3A14B !important;
            /* merah */
            border-color: #F3A14B !important;
            color: #fff !important;
        }

        /* Hover */
        .dataTables_wrapper .dataTables_paginate .btn:hover {
            background-color: #F3A14B !important;
            border-color: #F3A14B !important;
        }
    </style>

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

    <h1 class="h3 mb-2 text-gray-800">Produk</h1>
    <!-- Tombol Tambah Produk (trigger modal) -->
    <button class="btn btn-pale mb-3" data-toggle="modal" data-target="#addProdukModal">
        <i class="fas fa-plus"></i> Tambah Produk
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanProdukTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none;">ID</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produkList as $produk)
                        <tr>
                            <td style="display:none">{{ $produk['id_produk'] }}</td>
                            <td>{{ $produk['nama_produk'] }}</td>
                            <td>Rp{{ number_format($produk['harga_produk'], 2, ',', '.') }}</td>
                            <td>{{ $produk['status_produk'] }}</td>
                            <td>{{ $produk['kategori']['nama_kategori'] }}</td>
                            <td>
                                <a href="{{ route('produk.show', $produk['id_produk']) }}"
                                    class="btn btn-pale mb-3">Detail</a>

                                <!-- Tombol Edit (trigger modal) -->
                                <button class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#editProdukModal-{{ $produk['id_produk'] }}"
                                    onclick="populateEditModal({{ json_encode($produk) }})">
                                    Edit
                                </button>

                                <form action="{{ route('produk.destroy', $produk['id_produk']) }}" method="POST"
                                    class="delete-produk-form" data-used="{{ $produk['detail_pembelian_produk_count'] }}"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-pale mb-3">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="addProdukModal" tabindex="-1" role="dialog" aria-labelledby="addProdukModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProdukModalLabel">Tambah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Nama Produk -->
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control" required>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label for="deskripsi_produk">Deskripsi Produk</label>
                            <textarea name="deskripsi_produk" id="deskripsi_produk" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- Harga -->
                        <div class="form-group">
                            <label for="harga_produk">Harga Produk</label>
                            <input type="number" name="harga_produk" id="harga_produk" class="form-control" required>
                        </div>

                        <!-- Stok -->
                        <div class="form-group">
                            <label for="stok_produk">Stok Produk</label>
                            <input type="number" name="stok_produk" id="stok_produk" class="form-control" required>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status_produk">Status Produk</label>
                            <select name="status_produk" id="status_produk" class="form-control" required>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Habis">Habis</option>
                            </select>
                        </div>

                        <!-- Kategori -->
                        <div class="form-group">
                            <label for="id_kategori">Kategori Produk</label>
                            <select name="id_kategori" id="id_kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoriList as $kat)
                                    <option value="{{ $kat['id_kategori'] }}">{{ $kat['nama_kategori'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gambar Produk -->
                        <div class="form-group">
                            <label for="gambar_produk">Upload Gambar Produk</label>
                            <input type="file" name="gambar_produk" id="gambar_produk" class="form-control-file"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-pale">Simpan Produk</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================
         MODAL Edit Produk
       ============================ --}}
    @foreach ($produkList as $produk)
        <div class="modal fade" id="editProdukModal-{{ $produk['id_produk'] }}" tabindex="-1" role="dialog"
            aria-labelledby="editProdukModalLabel-{{ $produk['id_produk'] }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form id="editProdukForm-{{ $produk['id_produk'] }}"
                    action="{{ route('produk.update', $produk['id_produk']) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProdukModalLabel-{{ $produk['id_produk'] }}">
                                Edit Produk
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Hidden ID -->
                            <input type="hidden" name="id_produk" value="{{ $produk['id_produk'] }}">

                            <!-- Nama Produk -->
                            <div class="form-group">
                                <label for="edit_nama_produk_{{ $produk['id_produk'] }}">Nama Produk</label>
                                <input type="text" name="nama_produk"
                                    id="edit_nama_produk_{{ $produk['id_produk'] }}" class="form-control" required>
                            </div>
                            <!-- Deskripsi -->
                            <div class="form-group">
                                <label for="edit_deskripsi_produk_{{ $produk['id_produk'] }}">Deskripsi Produk</label>
                                <textarea name="deskripsi_produk" id="edit_deskripsi_produk_{{ $produk['id_produk'] }}" class="form-control"
                                    rows="3" required></textarea>
                            </div>
                            <!-- Harga -->
                            <div class="form-group">
                                <label for="edit_harga_produk_{{ $produk['id_produk'] }}">Harga Produk</label>
                                <input type="number" name="harga_produk"
                                    id="edit_harga_produk_{{ $produk['id_produk'] }}" class="form-control" required>
                            </div>
                            <!-- Stok -->
                            <div class="form-group">
                                <label for="edit_stok_produk_{{ $produk['id_produk'] }}">Stok Produk</label>
                                <input type="number" name="stok_produk"
                                    id="edit_stok_produk_{{ $produk['id_produk'] }}" class="form-control" required>
                            </div>
                            <!-- Status -->
                            <div class="form-group">
                                <label for="edit_status_produk_{{ $produk['id_produk'] }}">Status Produk</label>
                                <select name="status_produk" id="edit_status_produk_{{ $produk['id_produk'] }}"
                                    class="form-control" required>
                                    <option value="Tersedia">Tersedia</option>
                                    <option value="Habis">Habis</option>
                                </select>
                            </div>
                            <!-- Kategori -->
                            <div class="form-group">
                                <label for="edit_id_kategori_{{ $produk['id_produk'] }}">Kategori Produk</label>
                                <select name="id_kategori" id="edit_id_kategori_{{ $produk['id_produk'] }}"
                                    class="form-control" required>
                                    @foreach ($kategoriList as $kat)
                                        <option value="{{ $kat['id_kategori'] }}">{{ $kat['nama_kategori'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Gambar Produk -->
                            <div class="form-group">
                                {{-- <label>Gambar Produk Saat Ini</label><br>
                                @if (!empty($produk['gambar_produk']))
                                    <img src="{{ 'http://127.0.0.1:8080/storage/' . $produk['gambar_produk'] }}"
                                        alt="" style="max-width:100px; display:block; margin-bottom:10px;">
                                @endif --}}
                                <label for="edit_gambar_produk_{{ $produk['id_produk'] }}">
                                    Ubah Gambar Produk
                                </label>
                                <input type="file" name="gambar_produk"
                                    id="edit_gambar_produk_{{ $produk['id_produk'] }}" class="form-control-file">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-pale">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        function populateEditModal(data) {
            const id = data.id_produk;
            document.getElementById(`edit_nama_produk_${id}`).value = data.nama_produk;
            document.getElementById(`edit_deskripsi_produk_${id}`).value = data.deskripsi_produk;
            document.getElementById(`edit_harga_produk_${id}`).value = data.harga_produk;
            document.getElementById(`edit_stok_produk_${id}`).value = data.stok_produk;
            document.getElementById(`edit_status_produk_${id}`).value = data.status_produk;
            document.getElementById(`edit_id_kategori_${id}`).value = data.id_kategori;
            // file input tidak diisi lewat JS
        }
    </script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#laporanProdukTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pagingType: 'simple_numbers',
                dom: "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-right'p>>",
                columnDefs: [{
                    targets: 0,
                    visible: false,
                    searchable: false
                }],
                order: [
                    [0, 'desc']
                ],
                drawCallback: function(settings) {
                    // styling ulang pagination setiap draw
                    $('.dataTables_wrapper .dataTables_paginate a').each(function() {
                        $(this)
                            .removeClass('paginate_button')
                            .addClass('btn btn-sm btn-outline-primary mx-1');
                    });
                }
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-produk-form').forEach(form => {
                form.addEventListener('submit', e => {
                    const used = parseInt(form.dataset.used, 10);

                    if (used > 0) {
                        // sudah dipakai di penjualan
                        e.preventDefault();
                        alert('Tidak dapat menghapus: produk ini sudah dipakai di penjualan.');
                        return;
                    }

                    // minta konfirmasi jika belum dipakai
                    if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
