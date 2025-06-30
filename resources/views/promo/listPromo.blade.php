@extends('dashboard.index')

@section('content')
    <!-- Custom CSS untuk DataTables filter -->
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


    <h1 class="h3 mb-2 text-gray-800">List Promo</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#tambahPromoModal">
        <i class="fas fa-plus"></i> Tambah Promo
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanPromoTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Promo</th>
                        <th>Potongan Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promos as $promo)
                        <tr>
                            <td>{{ $promo['nama_promo'] }}</td>
                            <td>
                                @if ($promo['tipe_potongan'] === 'Diskon')
                                    {{ number_format($promo['potongan_harga']) }}%
                                @else
                                    Rp {{ number_format($promo['potongan_harga']) }}
                                @endif
                            </td>
                            <td>{{ ucfirst($promo['status_promo']) }}</td>
                            <td>
                                <a href="{{ route('promo.show', $promo['id_promo']) }}" class="btn btn-pale mb-3">
                                    Detail
                                </a>
                                <!-- Tombol Edit -->
                                <button class="btn btn-pale mb-3" data-toggle="modal" data-target="#editPromoModal"
                                    onclick="populateEditPromo({{ json_encode($promo) }})">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Promo -->
    <div class="modal fade" id="tambahPromoModal" tabindex="-1" role="dialog" aria-labelledby="tambahPromoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="tambahPromoForm" action="{{ route('promo.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label for="jenis_promo">Jenis Promo</label>
                            <select name="jenis_promo" id="jenis_promo" class="form-control" required>
                                <option value="Treatment">Treatment</option>
                                <option value="Produk">Produk</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_promo">Deskripsi</label>
                            <textarea name="deskripsi_promo" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tipe Potongan</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_potongan" id="diskon"
                                    value="Diskon" required>
                                <label class="form-check-label" for="diskon">Diskon (%)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_potongan" id="rupiah"
                                    value="Rupiah" required>
                                <label class="form-check-label" for="rupiah">Rupiah (Rp)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="potongan_harga">Potongan Harga</label>
                            <input type="number" name="potongan_harga" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="minimal_belanja">Minimal Belanja</label>
                            <input type="number" name="minimal_belanja" class="form-control">
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
    <!-- Modal Edit Promo -->
    <div class="modal fade" id="editPromoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editPromoForm" method="POST" action="" enctype="multipart/form-data" class="modal-content">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Promo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{-- sama persis dengan Tambah, tapi nilai diisi via JS --}}
                    <div class="form-group">
                        <label>Nama Promo</label>
                        <input type="text" name="nama_promo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Promo</label>
                        <select name="jenis_promo" class="form-control" required>
                            <option value="Treatment">Treatment</option>
                            <option value="Produk">Produk</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi_promo" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Tipe Potongan</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipe_potongan" value="Diskon" required>
                            <label class="form-check-label">Diskon (%)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipe_potongan" value="Rupiah">
                            <label class="form-check-label">Rupiah</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Potongan Harga</label>
                        <input type="number" name="potongan_harga" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Minimal Belanja</label>
                        <input type="number" name="minimal_belanja" class="form-control" min="0">
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="form-group col">
                            <label>Tanggal Berakhir</label>
                            <input type="date" name="tanggal_berakhir" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ganti Gambar</label>
                        <input type="file" name="gambar_promo" class="form-control-file">
                        <small class="form-text text-muted">Kosongkan jika tidak diubah</small>
                    </div>
                    <div class="form-group">
                        <label>Status Promo</label>
                        <select name="status_promo" class="form-control" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 1) Tunggu sampai DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            // a) Validasi Tambah Promo
            const tambah = document.getElementById('tambahPromoForm');
            if (tambah) tambah.addEventListener('submit', function(e) {
                const tipe = tambah.querySelector('input[name="tipe_potongan"]:checked');
                const val = parseFloat(tambah.querySelector('input[name="potongan_harga"]').value) || 0;
                if (tipe?.value === 'Diskon' && val > 99) {
                    alert('Potongan diskon tidak valid. Nilai maksimal 99%.');
                    e.preventDefault();
                }
            });

            // b) Validasi Edit Promo
            const edit = document.getElementById('editPromoForm');
            if (edit) edit.addEventListener('submit', function(e) {
                const tipe = edit.querySelector('input[name="tipe_potongan"]:checked');
                const val = parseFloat(edit.querySelector('input[name="potongan_harga"]').value) || 0;
                if (tipe?.value === 'Diskon' && val > 99) {
                    alert('Potongan diskon tidak valid. Nilai maksimal 99%.');
                    e.preventDefault();
                }
            });
        });

        // 2) Fungsi untuk isi modal edit (tidak mengikat event lagi)
        function populateEditPromo(p) {
            const form = document.getElementById('editPromoForm');
            form.action = `/promo/${p.id_promo}`;
            form.querySelector('input[name="nama_promo"]').value = p.nama_promo;
            form.querySelector('select[name="jenis_promo"]').value = p.jenis_promo;
            form.querySelector('textarea[name="deskripsi_promo"]').value = p.deskripsi_promo;
            form.querySelectorAll('input[name="tipe_potongan"]').forEach(r => {
                r.checked = (r.value === p.tipe_potongan);
            });
            form.querySelector('input[name="potongan_harga"]').value = p.potongan_harga;
            form.querySelector('input[name="minimal_belanja"]').value = p.minimal_belanja ?? '';
            form.querySelector('input[name="tanggal_mulai"]').value = p.tanggal_mulai;
            form.querySelector('input[name="tanggal_berakhir"]').value = p.tanggal_berakhir;
            form.querySelector('select[name="status_promo"]').value = p.status_promo;
        }
    </script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#laporanPromoTable').DataTable({
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
