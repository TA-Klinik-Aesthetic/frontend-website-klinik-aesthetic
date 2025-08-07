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

    <h1 class="h3 mb-2 text-gray-800">Penjualan Produk</h1>
    <!-- Tombol untuk buka modal -->
    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i>Tambah Penjualan Produk
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanPenjualanTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        {{-- <th>No</th> --}}
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Pembelian</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status Pembayaran</th>
                        <th>Status Pengambilan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembelianProduk as $index => $pembelian)
                        <tr>
                            {{-- <td>{{ $index + 1 }}</td> --}}
                            <td>{{ $pembelian['nama_user'] }}</td>
                            <td>{{ $pembelian['tanggal_pembelian'] }}</td>
                            <td>Rp{{ number_format($pembelian['harga_akhir'], 0, ',', '.') }}</td>
                            @php $pay = $pembelian['pembayaran_produk'] ?? null; @endphp
                            <td>{{ $pay['metode_pembayaran'] ?? '-' }}</td>
                            <td>{{ $pay['status_pembayaran'] ?? '-' }}</td>
                            <td>{{ $pembelian['status_pengambilan_produk'] }}</td>
                            <td>
                                <a href="{{ route('pembelian-produk.show', $pembelian['id_penjualan_produk']) }}"
                                    class="btn btn-pale mb-3">Detail</a>

                                {{-- Hanya tampilkan tombol jika belum diambil --}}
                                @if ($pembelian['status_pengambilan_produk'] !== 'Sudah diambil')
                                    <button class="btn btn-pale mb-3" data-toggle="modal"
                                        data-target="#statusModal{{ $pembelian['id_penjualan_produk'] }}">
                                        Update Status
                                    </button>
                                @else
                                    {{-- tombol sudah disable --}}
                                    <button class="btn btn-pale mb-3" disabled>
                                        Sudah diambil
                                    </button>
                                @endif
                                {{-- <button class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editModal{{ $pembelian['id_penjualan_produk'] }}">
                                Edit
                            </button> --}}
                                {{-- <form action="{{ route('pembelian-produk.destroy', $pembelian['id_penjualan_produk']) }}"
                                    method="POST" style="display:inline-block;"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-pale mb-3">
                                        Hapus
                                    </button> --}}
                                @if (
                                    $pembelian['id_pembayaran'] &&
                                        $pembelian['status_pembayaran'] === 'Belum Dibayar' &&
                                        optional($pembelian['pembayaran_produk'])['metode_pembayaran'] === 'Non Tunai')
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-pale mb-3 btn-confirm-non-tunai"
                                        data-id="{{ $pembelian['id_pembayaran'] }}">
                                        Konfirmasi Non Tunai
                                    </button>
                                @endif

                                @php
                                    $pay = $pembelian['pembayaran_produk'] ?? null;
                                    $pendingStatuses = [
                                        'Belum Dibayar',
                                        'Pending',
                                        'Menunggu Pembayaran',
                                        // dsb...
                                    ];
                                @endphp

                                @if ($pay && $pay['metode_pembayaran'] === 'Tunai' && in_array($pay['status_pembayaran'], $pendingStatuses))
                                    <button class="btn btn-pale mb-3 btn-edit-payment" data-id="{{ $pay['id_pembayaran'] }}"
                                        data-uang="{{ $pay['uang'] }}" data-harga="{{ $pembelian['harga_akhir'] }}"
                                        data-toggle="modal" data-target="#editPaymentModal">
                                        Bayar Tunai
                                    </button>
                                @endif


                                @php
                                    // boleh ditarik ke Controller kalau mau
                                    $paidStatuses = ['Sudah Dibayar', 'Berhasil'];
                                @endphp

                                @if ($pembelian['id_pembayaran'] && in_array($pembelian['status_pembayaran'], $paidStatuses))
                                    <a href="{{ route('pembelian-produk.invoice', $pembelian['id_pembayaran']) }}"
                                        class="btn btn-pale mb-3">
                                        <i class="fas fa-file-invoice"></i> Invoice
                                    </a>
                                @else
                                    <button class="btn btn-pale mb-3" disabled>
                                        Belum dibayar
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Uang Pembayaran Tunai -->
    <div class="modal fade js-reset-on-show" id="editPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editPaymentForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bayar Tunai</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="payment_uang">Jumlah Uang</label>
                            <input type="number" step="0.01" class="form-control" id="payment_uang" name="uang"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pale">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Non‑Tunai (sekali saja) -->
    <div class="modal fade" id="confirmNonTunaiModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="confirmNonTunaiForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT'){{-- spoof PUT --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmNonTunaiLabel">Upload Bukti Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="gambarBukti">Gambar Bukti Pembayaran</label>
                            <input type="file" name="gambar_bukti_pembayaran" accept=".jpg,.jpeg,.png" id="gambarBukti"
                                class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pale">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Status Update Modal --}}
    @foreach ($pembelianProduk as $pembelian)
        <div class="modal fade" id="statusModal{{ $pembelian['id_penjualan_produk'] }}" tabindex="-1" role="dialog"
            aria-labelledby="statusModalLabel{{ $pembelian['id_penjualan_produk'] }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('pembelian-produk.updateStatus', $pembelian['id_penjualan_produk']) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel{{ $pembelian['id_penjualan_produk'] }}">
                                Update Status Pengambilan
                            </h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nama User:</strong> {{ $pembelian['nama_user'] }}</p>
                            <div class="form-group">
                                <label for="status_pengambilan_produk_{{ $pembelian['id_penjualan_produk'] }}">
                                    Status Pengambilan
                                </label>
                                <select name="status_pengambilan_produk"
                                    id="status_pengambilan_produk_{{ $pembelian['id_penjualan_produk'] }}"
                                    class="form-control">
                                    <option value="Belum diambil"
                                        {{ $pembelian['status_pengambilan_produk'] == 'Belum diambil' ? 'selected' : '' }}>
                                        Belum diambil
                                    </option>
                                    <option value="Sudah diambil"
                                        {{ $pembelian['status_pengambilan_produk'] == 'Sudah diambil' ? 'selected' : '' }}>
                                        Sudah diambil
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-pale">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach


    <!-- Modal Tambah -->
    <div class="modal fade js-reset-on-show" id="addModal" tabindex="-1" role="dialog"
        aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('penjualan-produk/store') }}" method="POST">
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
                            <label for="id_user">Nama Pelanggan</label>
                            <select name="id_user" id="id_user" class="form-control" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggan as $user)
                                    <option value="{{ $user['id_user'] }}">{{ $user['nama_user'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Produk List -->
                        <div id="produk-list-tambah">
                            <div class="row mb-2" id="produk-tambah-0">
                                {{-- Baris 1: kategori (full-width) --}}
                                <div class="col-12">
                                    <label>Kategori</label>
                                    <select class="form-control kategori-select" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat['id_kategori'] }}">{{ $cat['nama_kategori'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Baris 2: produk (full-width) --}}
                                <div class="col-12 mt-2">
                                    <label>Nama Produk</label>
                                    <select name="produk[0][id_produk]" class="form-control produk-select" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product['id_produk'] }}">
                                                {{ $product['nama_produk'] }} —
                                                Rp{{ number_format($product['harga_produk'], 0, ',', '.') }} —
                                                Stok: {{ $product['stok_produk'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Baris 3: jumlah + hapus --}}
                                <div class="col-md-3 mt-2">
                                    <label>Jumlah</label>
                                    <input type="number" name="produk[0][jumlah_produk]" class="form-control" required>
                                </div>
                                <div class="col-md-2 mt-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm w-100"
                                        onclick="removeProdukTambah(0)">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah Produk -->
                        <button type="button" class="btn btn-pale mb-3" onclick="addProdukTambah()">Tambah
                            Produk</button>

                        <!-- Promo -->
                        <div class="form-group mt-3">
                            <label for="id_promo">Promo (Opsional)</label>
                            <select name="id_promo" id="id_promo" class="form-control">
                                <option value="">-- Pilih Promo --</option>
                                @foreach ($promos as $promo)
                                    <option value="{{ $promo['id_promo'] }}">
                                        {{ $promo['nama_promo'] }}
                                        @if ($promo['tipe_potongan'] === 'Diskon')
                                            — Potongan:
                                            {{ rtrim(rtrim(number_format($promo['potongan_harga'], 2, ',', ''), '0'), ',') }}%
                                        @else
                                            — Potongan: Rp{{ number_format($promo['potongan_harga'], 0, ',', '.') }}
                                        @endif

                                        @if (!empty($promo['minimal_belanja']))
                                            — Min. Belanja: Rp{{ number_format($promo['minimal_belanja'], 0, ',', '.') }}
                                        @endif
                                    </option>
                                @endforeach
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

                        <button type="button" class="btn btn-pale mb-3" id="btnHitungHarga">
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
                        <div class="form-group" id="wrapper_uang_tambah">
                            <label for="uang">Jumlah Bayar</label>
                            <input type="number" name="uang" id="uang" class="form-control" min="0">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pale">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        const categories = @json($categories);
        const products = @json($products);

        let produkTambahCounter = 1;

        function addProdukTambah() {
            const list = document.getElementById('produk-list-tambah');
            const index = produkTambahCounter++;
            const categoryOptions = categories.map(c => `<option value="${c.id_kategori}">${c.nama_kategori}</option>`)
                .join('');
            const html = `
            <div class="row mb-2" id="produk-tambah-${index}">
                <!-- Baris 1: kategori -->
                <div class="col-12">
                <label>Kategori</label>
                <select class="form-control kategori-select" required>
                    <option value="">Pilih Kategori</option>
                    ${categoryOptions}
                </select>
                </div>
                <!-- Baris 2: produk -->
                <div class="col-12 mt-2">
                <label>Nama Produk</label>
                <select name="produk[${index}][id_produk]" class="form-control produk-select" required>
                    <option value="">Pilih Produk</option>
                </select>
                </div>
                <!-- Baris 3: jumlah + hapus -->
                <div class="col-md-3 mt-2">
                <label>Jumlah</label>
                <input type="number" name="produk[${index}][jumlah_produk]" class="form-control" required>
                </div>
                <div class="col-md-2 mt-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm w-100"
                        onclick="removeProdukTambah(${index})">
                    Hapus
                </button>
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
                    // ambil SELECT yang benar-benar produk
                    const prodSel = row.querySelector('select.produk-select');
                    // ambil INPUT jumlah
                    const qtyInput = row.querySelector('input[name$="[jumlah_produk]"]');
                    const prodId = prodSel ? prodSel.value : null;
                    const qty = parseFloat(qtyInput?.value) || 0;

                    const prod = products.find(p => p.id_produk == prodId);
                    if (prod) {
                        subtotal += prod.harga_produk * qty;
                    }
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
        document.addEventListener('DOMContentLoaded', function() {
            const tambahForm = document.querySelector('#addModal form');
            tambahForm.addEventListener('submit', function(e) {
                const metode = document.getElementById('metode_pembayaran').value;
                if (metode === 'Tunai') {
                    const uangInput = document.getElementById('uang').value;
                    // pastikan pengguna sudah menghitung harga akhir
                    const hargaAkhirText = document.getElementById('calc_total').value;
                    // ubah "Rp12.345,00" → 12345
                    const hargaAkhir = parseFloat(
                        hargaAkhirText
                        .replace(/[^0-9\,]/g, '') // buang 'Rp' dan titik
                        .replace(',', '.') // koma jadi titik
                    );
                    const uang = parseFloat(uangInput);

                    if (isNaN(uang) || uangInput.trim() === '') {
                        alert('Mohon isi jumlah uang saat memilih Metode Tunai.');
                        e.preventDefault();
                        return;
                    }
                    if (uang < hargaAkhir) {
                        alert(`Jumlah uang harus minimal Rp${hargaAkhir.toLocaleString('id-ID')}.`);
                        e.preventDefault();
                        return;
                    }
                }
            });
        });
    </script>
@endpush

{{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form.form-confirm').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    if (form.dataset.metode === 'Tunai') {
                        e.preventDefault();
                        alert('Pembayaran Tunai yang belum dibayar tidak dikonfirmasi di sini.');
                    }
                });
            });
        });
    </script>
@endpush --}}

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metodeSel = document.getElementById('metode_pembayaran');
            const wrapper = document.getElementById('wrapper_uang_tambah');
            const inputU = document.getElementById('uang');

            function toggleUang() {
                if (metodeSel.value === 'Non Tunai') {
                    wrapper.style.display = 'none';
                    inputU.disabled = true;
                    inputU.value = '';
                } else {
                    wrapper.style.display = '';
                    inputU.disabled = false;
                }
            }

            // Saat pilihan metode berubah
            metodeSel.addEventListener('change', toggleUang);

            // Saat modal dibuka, langsung jalankan sekali
            $('#addModal').on('show.bs.modal', toggleUang);

            // Inisialisasi default (misal: jika ada repopulate value)
            toggleUang();
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // data produk dari backend
            const products = @json($products);

            // delegasi event: tangani perubahan <select class="kategori-select">
            document.getElementById('produk-list-tambah').addEventListener('change', e => {
                if (!e.target.classList.contains('kategori-select')) return;

                const catId = e.target.value;
                const row = e.target.closest('.row');
                const prodSelect = row.querySelector('.produk-select');

                // reset opsi produk
                prodSelect.innerHTML = '<option value="">-- Pilih Produk --</option>';

                // isikan hanya produk sesuai kategori
                products
                    .filter(p => p.id_kategori == catId)
                    .forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id_produk;
                        opt.textContent =
                            `${p.nama_produk} — ` +
                            `Rp${(Number(p.harga_produk)).toLocaleString('id-ID')} — ` +
                            `Stok: ${p.stok_produk}`;
                        prodSelect.appendChild(opt);
                    });
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = $('#editPaymentModal');
            const form = document.getElementById('editPaymentForm');
            const uangInput = document.getElementById('payment_uang');

            // when “Bayar Tunai” clicked…
            $('.btn-edit-payment').on('click', function() {
                const id = this.dataset.id;
                const uang = this.dataset.uang || '';
                const harga = this.dataset.harga; // grab the total due

                // set action to your PUT route
                form.action = `/penjualan-produk/${id}/pembayaran`;
                // remember the total for validation
                form.dataset.hargaAkhir = harga;
                // prefill
                uangInput.value = uang;
            });

            // on form submit, ensure cash >= total
            form.addEventListener('submit', function(e) {
                const entered = parseFloat(uangInput.value) || 0;
                const due = parseFloat(this.dataset.hargaAkhir);

                if (entered < due) {
                    alert(
                        `Jumlah uang tidak boleh kurang dari total tagihan ` +
                        `Rp${due.toLocaleString('id-ID')}.`
                    );
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // bila tombol “Konfirmasi Non‑Tunai” diklik…
            $(document).on('click', '.btn-confirm-non-tunai', function() {
                const id = $(this).data('id');

                // set form action sesuai route Laravel
                $('#confirmNonTunaiForm').attr('action',
                    `{{ url('pembayaran-produk') }}/${id}/konfirmasi`
                );

                // buka modal
                $('#confirmNonTunaiModal').modal('show');
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('produk-list-tambah');

            // Saat user memilih produk
            wrapper.addEventListener('change', e => {
                if (!e.target.classList.contains('produk-select')) return;
                const prodId = e.target.value;
                const prod = products.find(p => p.id_produk == prodId);
                if (!prod) return;

                // jika stok habis
                if (prod.stok_produk <= 0) {
                    alert(`Stok produk "${prod.nama_produk}" sedang habis.`);
                    e.target.value = ''; // batalkan pilihan
                }
            });

            // Saat user memasukkan jumlah
            wrapper.addEventListener('input', e => {
                if (e.target.tagName !== 'INPUT' || e.target.type !== 'number') return;
                const row = e.target.closest('.row');
                const sel = row.querySelector('select.produk-select');
                const prodId = sel ? sel.value : null;
                const prod = products.find(p => p.id_produk == prodId);
                if (!prod) return;

                const qty = parseInt(e.target.value) || 0;
                if (qty > prod.stok_produk) {
                    alert(`Jumlah tidak boleh lebih dari stok (${prod.stok_produk}).`);
                    e.target.value = prod.stok_produk; // set ke stok maksimal
                }
            });

            // Tambahan: validasi sekali lagi saat submit form tambah
            const tambahForm = document.querySelector('#addModal form');
            if (tambahForm) {
                tambahForm.addEventListener('submit', e => {
                    let valid = true;
                    wrapper.querySelectorAll('.row').forEach(row => {
                        const sel = row.querySelector('select.produk-select');
                        const input = row.querySelector('input[type="number"]');
                        const prod = products.find(p => p.id_produk == sel.value);
                        if (prod) {
                            if (prod.stok_produk <= 0) {
                                alert(`Produk "${prod.nama_produk}" stoknya habis.`);
                                valid = false;
                            }
                            if (parseInt(input.value) > prod.stok_produk) {
                                alert(`Jumlah untuk produk "${prod.nama_produk}" melebihi stok.`);
                                valid = false;
                            }
                        }
                    });
                    if (!valid) e.preventDefault();
                });
            }
        });
    </script>
@endpush
