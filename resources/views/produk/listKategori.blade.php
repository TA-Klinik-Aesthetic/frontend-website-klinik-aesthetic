@extends('dashboard.index')

@section('content')
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

    <h1 class="h3 mb-2 text-gray-800">Kategori Produk</h1>

    <!-- Tombol buka modal Tambah -->
    <button class="btn btn-pale mb-3" data-toggle="modal" data-target="#addKategoriModal">
        <i class="fas fa-plus"></i> Tambah Kategori
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="kategoriTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none;">ID</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategoriProduk as $item)
                        <tr>
                            <td style="display:none">{{ $item['id_kategori'] }}</td>
                            <td>{{ $item['nama_kategori'] }}</td>
                            <td>

                                <button class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#editKategoriModal-{{ $item['id_kategori'] }}">
                                    Edit
                                </button>

                                <form action="{{ route('kategori.destroy', $item['id_kategori']) }}" method="POST"
                                    class="delete-kategori-form" data-used="{{ $item['produk_count'] }}"
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

    <!-- Modal Tambah Kategori -->
    <div class="modal fade js-reset-on-show" id="addKategoriModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('kategori.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Produk</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-pale">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit per Kategori -->
    @foreach ($kategoriProduk as $item)
        <div class="modal fade" id="editKategoriModal-{{ $item['id_kategori'] }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('kategori.update', $item['id_kategori']) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kategori Produk</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nama_kategori_{{ $item['id_kategori'] }}">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="edit_nama_kategori_{{ $item['id_kategori'] }}"
                                class="form-control" value="{{ $item['nama_kategori'] }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-pale">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#kategoriTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pagingType: 'simple_numbers',
                // sembunyikan kolom ID tapi pakai untuk sort desc
                columnDefs: [{
                    targets: 0,
                    visible: false,
                    searchable: false
                }],
                order: [
                    [0, 'desc']
                ],
                dom: "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-right'p>>",
                drawCallback: function(settings) {
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
        $(document).ready(function() {
            $('#kategoriTable').DataTable( /* ... */ );

            document.querySelectorAll('.delete-kategori-form').forEach(form => {
                form.addEventListener('submit', e => {
                    const used = parseInt(form.dataset.used, 10);
                    if (used > 0) {
                        e.preventDefault();
                        alert('Tidak dapat menghapus: kategori ini sudah dipakai di ' + used +
                            ' produk.');
                        return;
                    }
                    if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
