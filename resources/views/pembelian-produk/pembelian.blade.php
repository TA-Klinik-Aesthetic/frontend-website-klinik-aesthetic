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

    <h1 class="h3 mb-2 text-gray-800">Daftar Penjualan Produk</h1>
    <!-- Tombol untuk buka modal -->
    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#addModal">
        Tambah Penjualan Produk
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanPenjualanTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        {{-- <th>No</th> --}}
                        <th>Nama User</th>
                        <th>Tanggal Pembelian</th>
                        <th>Harga Total</th>
                        <th>Potongan Harga</th>
                        <th>Harga Akhir</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembelianProduk as $index => $pembelian)
                        <tr>
                            {{-- <td>{{ $index + 1 }}</td> --}}
                            <td>{{ $pembelian['nama_user'] }}</td>
                            <td>{{ $pembelian['tanggal_pembelian'] }}</td>
                            <td>Rp{{ number_format($pembelian['harga_total'], 2, ',', '.') }}</td>
                            <td>
                                @if ($pembelian['promo_dipakai'])
                                    @if ($pembelian['promo_dipakai']['tipe_potongan'] === 'Diskon')
                                        {{ (int) $pembelian['potongan_harga'] }}%
                                    @else
                                        Rp{{ number_format($pembelian['potongan_harga'], 2, ',', '.') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>Rp{{ number_format($pembelian['harga_akhir'], 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('pembelian-produk.show', $pembelian['id_penjualan_produk']) }}"
                                    class="btn btn-pale mb-3">Detail</a>
                                {{-- <button class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editModal{{ $pembelian['id_penjualan_produk'] }}">
                                Edit
                            </button> --}}
                                <form action="{{ route('pembelian-produk.destroy', $pembelian['id_penjualan_produk']) }}"
                                    method="POST" style="display:inline-block;"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-pale mb-3">
                                        Hapus
                                    </button>
                                    @if ($pembelian['id_pembayaran'] && $pembelian['status_pembayaran'] === 'Sudah Dibayar')
                                        <a href="{{ route('pembelian-produk.invoice', $pembelian['id_pembayaran']) }}"
                                            class="btn btn-pale mb-3">
                                            <i class="fas fa-file-invoice"></i> Invoice
                                        </a>
                                    @else
                                        <span class="text-muted">Belum Dibayar</span>
                                    @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Data tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($pembelianProduk as $pembelian)
        <div class="modal fade" id="editModal{{ $pembelian['id_penjualan_produk'] }}" tabindex="-1" role="dialog"
            aria-labelledby="editModalLabel{{ $pembelian['id_penjualan_produk'] }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('pembelian-produk.update', $pembelian['id_penjualan_produk']) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Penjualan Produk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <!-- Promo -->
                            <div class="form-group mb-3">
                                <label for="id_promo">Promo</label>
                                <select name="id_promo" class="form-control">
                                    <option value="">-- Pilih Promo --</option>
                                    @foreach ($promos as $promo)
                                        <option value="{{ $promo['id_promo'] }}"
                                            {{ $promo['id_promo'] == $pembelian['id_promo'] ? 'selected' : '' }}>
                                            {{ $promo['nama_promo'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Daftar Produk -->
                            <div id="produk-list-{{ $pembelian['id_penjualan_produk'] }}">
                                @php $produkList = $pembelian['produk'] ?? []; @endphp
                                @foreach ($produkList as $index => $produk)
                                    <div class="row mb-3"
                                        id="edit-produk-{{ $pembelian['id_penjualan_produk'] }}-{{ $index }}">
                                        <div class="col-md-6">
                                            <label>Nama Produk</label>
                                            <select name="produk[{{ $index }}][id_produk]" class="form-control"
                                                required>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product['id_produk'] }}"
                                                        {{ $product['id_produk'] == $produk['id_produk'] ? 'selected' : '' }}>
                                                        {{ $product['nama_produk'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Jumlah</label>
                                            <input type="number" name="produk[{{ $index }}][jumlah_produk]"
                                                class="form-control" value="{{ $produk['jumlah_produk'] }}" required>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-produk"
                                                onclick="removeProduk('{{ $pembelian['id_penjualan_produk'] }}', {{ $index }})">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Tombol Tambah Produk -->
                            <button type="button" class="btn btn-secondary btn-sm"
                                onclick="addProduk('{{ $pembelian['id_penjualan_produk'] }}')">
                                Tambah Produk
                            </button>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    <!-- Modal Tambah -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('pembelian-produk/store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Penjualan Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Select User -->
                        <div class="form-group">
                            <label for="id_user">User</label>
                            <select name="id_user" id="id_user" class="form-control" required>
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user['id_user'] }}">{{ $user['nama_user'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Produk List -->
                        <div id="produk-list-tambah">
                            <div class="row mb-3" id="produk-tambah-0">
                                <div class="col-md-6">
                                    <label>Nama Produk</label>
                                    <select name="produk[0][id_produk]" class="form-control" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product['id_produk'] }}">{{ $product['nama_produk'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Jumlah</label>
                                    <input type="number" name="produk[0][jumlah_produk]" class="form-control" required>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="removeProdukTambah(0)">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah Produk -->
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addProdukTambah()">Tambah
                            Produk</button>

                        <!-- Promo -->
                        <div class="form-group mt-3">
                            <label for="id_promo">Promo</label>
                            <select name="id_promo" id="id_promo" class="form-control">
                                <option value="">-- Pilih Promo --</option>
                                @foreach ($promos as $promo)
                                    <option value="{{ $promo['id_promo'] }}">{{ $promo['nama_promo'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- **Status Pengambilan Produk** -->
                        <div class="form-group">
                            <label for="status_pengambilan_produk">Status Pengambilan Produk</label>
                            <select name="status_pengambilan_produk" id="status_pengambilan_produk" class="form-control">
                                <option value="Belum diambil" selected>Belum diambil</option>
                                <option value="Sudah diambil">Sudah diambil</option>
                            </select>
                        </div>

                        <!-- ➤ Ringkasan Harga -->
                        <hr>
                        <h5>Ringkasan Harga</h5>
                        <div class="form-group">
                            <label>Subtotal</label>
                            <input type="text" id="calc_subtotal" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Potongan</label>
                            <input type="text" id="calc_potongan" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Pajak (10%)</label>
                            <input type="text" id="calc_pajak" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Harga Akhir</label>
                            <input type="text" id="calc_total" class="form-control" readonly>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" id="btnHitungHarga">
                            Hitung Harga
                        </button>

                        <!-- Metode Pembayaran -->
                        <div class="form-group mt-3">
                            <label for="metode_pembayaran">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                                <option value="Tunai">Tunai</option>
                                <option value="Non Tunai">Non Tunai</option>
                            </select>
                        </div>

                        <!-- Uang Bayar -->
                        <div class="form-group">
                            <label for="uang">Jumlah Bayar</label>
                            <input type="number" name="uang" id="uang" class="form-control" min="0">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function removeProduk(penjualanId, index) {
            const element = document.getElementById(`edit-produk-${penjualanId}-${index}`);
            if (element) {
                element.remove();
            }
        }

        function addProduk(penjualanId) {
            const list = document.getElementById(`produk-list-${penjualanId}`);
            const count = list.querySelectorAll('.row').length;

            const html = `
        <div class="row mb-3" id="edit-produk-${penjualanId}-${count}">
            <div class="col-md-6">
                <label>Nama Produk</label>
                <select name="produk[${count}][id_produk]" class="form-control" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product['id_produk'] }}">{{ $product['nama_produk'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Jumlah</label>
                <input type="number" name="produk[${count}][jumlah_produk]" class="form-control" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="removeProduk('${penjualanId}', ${count})">Hapus</button>
            </div>
        </div>`;
            list.insertAdjacentHTML('beforeend', html);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const promos = @json($promos);
            const products = @json($products);

            @foreach ($pembelianProduk as $pembelian)
                const editForm{{ $pembelian['id_penjualan_produk'] }} = document.querySelector(
                    `#editModal{{ $pembelian['id_penjualan_produk'] }} form`
                );

                if (editForm{{ $pembelian['id_penjualan_produk'] }}) {
                    editForm{{ $pembelian['id_penjualan_produk'] }}.addEventListener('submit', function(e) {
                        const promoSelect = editForm{{ $pembelian['id_penjualan_produk'] }}.querySelector(
                            'select[name="id_promo"]');
                        const promoId = promoSelect ? promoSelect.value : null;
                        if (!promoId) return;

                        const selectedPromo = promos.find(p => p.id_promo == promoId);
                        if (!selectedPromo) return;

                        // ❗ Validasi minimal belanja
                        if (selectedPromo.minimal_belanja > 0) {
                            let totalBelanja = 0;

                            const rows = document.querySelectorAll(
                                `#produk-list-{{ $pembelian['id_penjualan_produk'] }} .row`);
                            rows.forEach(row => {
                                const idProduk = row.querySelector('select')?.value;
                                const jumlah = parseInt(row.querySelector('input')?.value) || 0;
                                const produk = products.find(p => p.id_produk == idProduk);
                                if (produk) {
                                    totalBelanja += produk.harga_produk * jumlah;
                                }
                            });

                            if (totalBelanja < selectedPromo.minimal_belanja) {
                                const formatRupiah = new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                });

                                alert(
                                    `Promo "${selectedPromo.nama_promo}" tidak dapat digunakan.\n\n` +
                                    `Total belanja Anda: ${formatRupiah.format(totalBelanja)}\n` +
                                    `Minimal belanja: ${formatRupiah.format(selectedPromo.minimal_belanja)}`
                                );
                                e.preventDefault();
                            }
                        }
                    });
                }
            @endforeach
        });
    </script>
@endpush


@push('scripts')
    <script>
        let produkTambahCounter = 1;

        function addProdukTambah() {
            const list = document.getElementById('produk-list-tambah');
            const index = produkTambahCounter++;

            const html = `
        <div class="row mb-3" id="produk-tambah-${index}">
            <div class="col-md-6">
                <label>Nama Produk</label>
                <select name="produk[${index}][id_produk]" class="form-control" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product['id_produk'] }}">{{ $product['nama_produk'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Jumlah</label>
                <input type="number" name="produk[${index}][jumlah_produk]" class="form-control" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeProdukTambah(${index})">Hapus</button>
            </div>
        </div>`;
            list.insertAdjacentHTML('beforeend', html);
        }

        function removeProdukTambah(index) {
            const el = document.getElementById(`produk-tambah-${index}`);
            if (el) el.remove();
        }

        // ✅ Tambahan validasi minimal belanja promo
        document.addEventListener('DOMContentLoaded', function() {
            const tambahForm = document.querySelector('#addModal form');
            if (!tambahForm) return;

            tambahForm.addEventListener('submit', function(e) {
                const promoSelect = document.getElementById('id_promo');
                const promoId = promoSelect ? promoSelect.value : null;
                if (!promoId) return;

                const promos = @json($promos);
                const products = @json($products);
                const selectedPromo = promos.find(p => p.id_promo == promoId);
                if (!selectedPromo) return;

                // ❗ Validasi minimal belanja
                if (selectedPromo.minimal_belanja > 0) {
                    let totalBelanja = 0;

                    document.querySelectorAll('#produk-list-tambah .row').forEach(row => {
                        const idProduk = row.querySelector('select')?.value;
                        const jumlah = parseInt(row.querySelector('input')?.value) || 0;
                        const produk = products.find(p => p.id_produk == idProduk);
                        if (produk) {
                            totalBelanja += produk.harga_produk * jumlah;
                        }
                    });

                    if (totalBelanja < selectedPromo.minimal_belanja) {
                        const formatRupiah = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });

                        alert(
                            `Promo "${selectedPromo.nama_promo}" tidak dapat digunakan.\n\n` +
                            `Total belanja Anda: ${formatRupiah.format(totalBelanja)}\n` +
                            `Minimal belanja: ${formatRupiah.format(selectedPromo.minimal_belanja)}`
                        );
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari backend
            const products = @json($products);
            const promos = @json($promos);

            // Utility: format rupiah
            function formatRupiah(val) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(val);
            }

            document.getElementById('btnHitungHarga').addEventListener('click', function() {
                // 1) Hitung subtotal
                let subtotal = 0;
                document.querySelectorAll('#produk-list-tambah .row').forEach(row => {
                    const sel = row.querySelector('select');
                    const qty = parseFloat(row.querySelector('input[type="number"]').value) || 0;
                    const prod = products.find(p => p.id_produk == sel.value);
                    if (prod) subtotal += prod.harga_produk * qty;
                });

                // 2) Ambil promo
                const promoId = document.getElementById('id_promo').value;
                const promo = promos.find(p => p.id_promo == promoId);
                let potonganValue = 0;
                if (promo) {
                    if (promo.tipe_potongan === 'Diskon') {
                        potonganValue = subtotal * promo.potongan_harga / 100;
                    } else {
                        potonganValue = promo.potongan_harga;
                    }
                }

                // 3) Pajak 10%
                const netBeforeTax = subtotal - potonganValue;
                const pajak = netBeforeTax * 0.10;

                // 4) Harga akhir
                const hargaAkhir = netBeforeTax + pajak;

                // Tampilkan ke form
                document.getElementById('calc_subtotal').value = formatRupiah(subtotal);
                document.getElementById('calc_potongan').value = formatRupiah(potonganValue);
                document.getElementById('calc_pajak').value = formatRupiah(pajak);
                document.getElementById('calc_total').value = formatRupiah(hargaAkhir);
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#laporanPenjualanTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pagingType: 'simple_numbers',
                order: [
                    [1, 'desc']
                ],
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
