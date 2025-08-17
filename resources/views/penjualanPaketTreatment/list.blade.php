@extends('dashboard.index')

@section('content')
    <style>
        .dataTables_filter {
            text-align: right !important
        }

        .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            white-space: nowrap
        }

        .dataTables_filter label input {
            margin-left: .5rem
        }

        .dataTables_wrapper .dataTables_paginate .btn {
            background: #F3A14B !important;
            border-color: #F3A14B !important;
            color: #fff !important
        }

        .dataTables_wrapper .dataTables_paginate .btn:hover {
            background: #d18d3f !important;
            border-color: #d18d3f !important
        }

        .btn-pale {
            background: #F3A14B !important;
            border-color: #F3A14B !important;
            color: #fff !important
        }

        .btn-pale:hover,
        .btn-pale:focus {
            background: #d18d3f !important;
            border-color: #d18d3f !important;
            color: #fff !important
        }
    </style>

    <h1 class="h3 mb-2 text-gray-800">Penjualan Paket Treatment</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus"></i> Tambah Penjualan Paket
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="penjualanPaketTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none;">ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualanPaket as $p)
                        @php
                            $id = $p['id_penjualan_paket_treatment'] ?? ($p['id'] ?? null);
                            $nama = $p['nama_user'] ?? ($p['user']['nama_user'] ?? '-');
                            $tgl = $p['created_at'] ?? ($p['tanggal_penjualan'] ?? '-');
                            $items = $p['paket'] ?? ($p['details'] ?? ($p['pakets'] ?? []));

                            $pay = $p['pembayaran_paket_treatment'] ?? ($p['pembayaran'] ?? null);
                            // ✅ normalisasi biar aman terhadap kapitalisasi/whitespace
                            $metodeNorm = strtolower(trim($pay['metode_pembayaran'] ?? ''));
                            $statusNorm = strtolower(
                                trim($pay['status_pembayaran'] ?? ($p['status_pembayaran'] ?? '')),
                            );

                            // ID pembayaran (fallback kalau field beda)
                            $payId =
                                $pay['id_pembayaran_paket_treatment'] ??
                                ($p['id_pembayaran_paket_treatment'] ?? ($pay['id_pembayaran'] ?? null));

                            // Tampilkan tombol kalau Non Tunai & belum dibayar/berhasil
                            $showConfirm =
                                $payId &&
                                $metodeNorm === 'non tunai' &&
                                !in_array($statusNorm, ['sudah dibayar', 'berhasil']);
                        @endphp
                        <tr>
                            <td style="display:none;">{{ $id }}</td>
                            <td>{{ $nama }}</td>
                            <td>{{ $tgl }}</td>
                            <td>Rp{{ number_format((float) $p['harga_akhir'], 0, ',', '.') }}</td>
                            <td>{{ $pay['metode_pembayaran'] ?? '-' }}</td>
                            <td>{{ $pay['status_pembayaran'] ?? ($p['status_pembayaran'] ?? 'Belum Dibayar') }}</td>
                            <td>
                                <a href="{{ route('penjualanPaketTreatment.show', $id) }}"
                                    class="btn btn-pale mb-2">Detail</a>

                                @if ($showConfirm)
                                    <button type="button" class="btn btn-pale mb-2" data-toggle="modal"
                                        data-target="#confirmNonTunaiModal" data-payid="{{ $payId }}">
                                        Konfirmasi Non Tunai
                                    </button>
                                @endif

                                @php
                                    // RE-USE $payId yg sudah dihitung DI ATAS (untuk Konfirmasi Non Tunai)
                                    // dan status yg sama, supaya konsisten.
                                    $paidStatuses = ['Sudah Dibayar', 'Berhasil'];
                                    $statusNow = $pay['status_pembayaran'] ?? ($p['status_pembayaran'] ?? '');
                                    $canInvoice = $payId && in_array($statusNow, $paidStatuses, true);
                                @endphp

                                @if ($canInvoice)
                                    <a href="{{ route('penjualanPaketTreatment.invoice', ['paymentId' => $payId]) }}"
                                        class="btn btn-pale mb-2" target="_blank" rel="noopener">
                                        <i class="fas fa-file-invoice"></i> Invoice
                                    </a>
                                @else
                                    <button class="btn btn-pale mb-2" disabled>Belum Dibayar</button>
                                @endif

                                {{-- DEBUG sementara, hapus setelah beres --}}
                                {{-- <small class="text-muted d-block">
                                    pay_id={{ $payId ?? 'null' }},
                                    status={{ $statusNow ?? '-' }},
                                    canInvoice={{ $canInvoice ? '1' : '0' }}
                                </small> --}}

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Penjualan Paket --}}
    <div class="modal fade js-reset-on-show" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('penjualanPaketTreatment.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Penjualan Paket Treatment</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    {{-- Pelanggan --}}
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <select name="id_user" id="pp_user" class="form-control" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach ($pelanggan as $u)
                                <option value="{{ $u['id_user'] }}">{{ $u['nama_user'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Promo (opsional; jenis Treatment saja) --}}
                    <div class="form-group">
                        <label>Promo (Opsional)</label>
                        <select name="id_promo" id="pp_promo" class="form-control">
                            <option value="">— Tanpa Promo —</option>
                            @foreach ($promos as $pr)
                                <option value="{{ $pr['id_promo'] }}">{{ $pr['nama_promo'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    {{-- Daftar paket (dinamis; 1 baris = 1 unit paket) --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Daftar Paket</h6>
                        <button type="button" class="btn btn-pale btn-outline-pale" id="btnAddPaketRow">Tambah
                            Baris</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Paket Treatment</th>
                                    <th style="width: 90px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pp_rows"><!-- via JS --></tbody>
                        </table>
                    </div>
                    <small class="text-muted">Ulangi baris untuk menambah kuantitas paket yang sama.</small>

                    <hr>

                    {{-- Ringkasan Harga (client-side) --}}
                    <h5 class="mt-3">Ringkasan Harga</h5>
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
                    <button type="button" class="btn btn-pale mb-3" id="btnHitungHarga">Hitung Harga</button>

                    {{-- Pembayaran --}}
                    <div class="form-group mt-2">
                        <label>Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="pp_metode" class="form-control" required>
                            <option value="Tunai">Tunai</option>
                            <option value="Non Tunai">Non Tunai</option>
                        </select>
                    </div>
                    <div class="form-group" id="grp_uang">
                        <label>Uang (Tunai)</label>
                        <input type="number" step="0.01" min="0" name="uang" id="pp_uang"
                            class="form-control" placeholder="0">
                        <small class="text-muted">Wajib diisi bila metode pembayaran Tunai.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-pale">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Non-Tunai (sekali untuk setiap pembayaran tertunda) --}}
    <div class="modal fade" id="confirmNonTunaiModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="confirmNonTunaiForm" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf @method('PUT') {{-- spoof PUT --}}
                <div class="modal-header">
                    <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label>Gambar Bukti (opsional)</label>
                        <input type="file" name="gambar_bukti_pembayaran" accept=".jpg,.jpeg,.png,.gif,.webp"
                            class="form-control-file">
                        <small class="text-muted">Boleh dikosongkan bila tidak diperlukan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-pale">Konfirmasi</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // ====== DataTables ======
            $('#penjualanPaketTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pagingType: 'simple_numbers',
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
                drawCallback: function() {
                    $('.dataTables_wrapper .dataTables_paginate a')
                        .removeClass('paginate_button')
                        .addClass('btn btn-sm btn-outline-primary mx-1');
                }
            });

            // ====== Data master untuk modal ======
            const PAKETS = @json($pakets);
            const PROMOS = @json($promos);

            // ====== Row builder (1 baris = 1 paket) ======
            let ROW_IDX = 0;

            function formatRupiah(n) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(n);
            }

            function buildRow(idx) {
                const tr = $(`
    <tr data-idx="${idx}">
      <td>
        <select class="form-control sel-paket" name="paket[${idx}][id_paket_treatment]" required>
          <option value="">— Pilih Paket —</option>
        </select>
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-outline-danger btn-del">Hapus</button>
      </td>
    </tr>
  `);

                const $sel = tr.find('.sel-paket');
                PAKETS.forEach(p => {
                    const id = p.id_paket_treatment;
                    const nm = p.nama_paket_treatment;
                    const hg = Number(p.harga_paket_treatment || 0);
                    if (id && nm != null) {
                        $sel.append(`<option value="${id}">${nm} — ${formatRupiah(hg)}</option>`);
                    }
                });

                tr.on('click', '.btn-del', function() {
                    tr.remove();
                    if ($('#pp_rows tr').length === 0) addRow();
                    reindex();
                });

                return tr;
            }


            function addRow() {
                $('#pp_rows').append(buildRow(ROW_IDX++));
            }

            function reindex() {
                $('#pp_rows tr').each(function(i) {
                    $(this).attr('data-idx', i);
                    $(this).find('select.sel-paket').attr('name', `paket[${i}][id_paket_treatment]`);
                });
                ROW_IDX = $('#pp_rows tr').length;
            }

            // ====== Modal init ======
            $('#addModal').on('show.bs.modal', function() {
                $('#pp_user').val('');
                $('#pp_promo').val('');
                $('#pp_metode').val('Tunai').trigger('change');
                $('#pp_uang').val('');
                $('#calc_subtotal,#calc_potongan,#calc_pajak,#calc_total').val('');
                $('#pp_rows').empty();
                ROW_IDX = 0;
                addRow();
            });

            $('#btnAddPaketRow').on('click', addRow);

            // Toggle uang (Tunai vs Non Tunai)
            $('#pp_metode').on('change', function() {
                const isTunai = $(this).val() === 'Tunai';
                $('#grp_uang').toggle(isTunai);
                if (!isTunai) $('#pp_uang').val('');
            }).trigger('change');

            // ====== Hitung Harga (client-side, untuk info kasir) ======
            $('#btnHitungHarga').on('click', function() {
                let subtotal = 0;
                $('#pp_rows .sel-paket').each(function() {
                    const id = $(this).val();
                    if (!id) return;
                    const pkg = PAKETS.find(x => String(x.id_paket_treatment) === String(id));
                    if (pkg) subtotal += Number(pkg.harga_paket_treatment || 0);
                });

                const prId = $('#pp_promo').val();
                const promo = PROMOS.find(x => String(x.id_promo) === String(prId));
                let potongan = 0;
                if (promo) {
                    const tipe = (promo.tipe_potongan || '').toLowerCase();
                    potongan = (tipe === 'diskon') ?
                        subtotal * Number(promo.potongan_harga || 0) / 100 :
                        Number(promo.potongan_harga || 0);
                }
                if (potongan > subtotal) potongan = subtotal;

                const dpp = subtotal - potongan;
                const pajak = dpp * 0.10;
                const total = dpp + pajak;

                $('#calc_subtotal').val(formatRupiah(subtotal));
                $('#calc_potongan').val(formatRupiah(potongan));
                $('#calc_pajak').val(formatRupiah(pajak));
                $('#calc_total').val(formatRupiah(total));
            });


            // ====== Validasi submit ======
            $('#addModal form').on('submit', function(e) {
                // minimal 1 paket dan setiap baris harus terpilih
                if ($('#pp_rows tr').length === 0) {
                    alert('Tambahkan minimal 1 paket.');
                    e.preventDefault();
                    return;
                }
                let ok = true;
                $('#pp_rows .sel-paket').each(function() {
                    if (!$(this).val()) {
                        ok = false;
                        return false;
                    }
                });
                if (!ok) {
                    alert('Pilih paket pada setiap baris.');
                    e.preventDefault();
                    return;
                }

                // uang wajib jika Tunai (valid angka non-negatif)
                const isTunai = $('#pp_metode').val() === 'Tunai';
                const uangVal = $('#pp_uang').val();
                const uang = parseFloat(uangVal || 'NaN');
                if (isTunai) {
                    if (isNaN(uang) || uang < 0) {
                        alert('Isi nominal uang yang valid untuk pembayaran Tunai.');
                        e.preventDefault();
                        return;
                    }
                }

                // ⭐ BARU: hitung TOTAL (Harga Akhir) dari pilihan user, lalu pastikan uang ≥ total
                // hitung subtotal dari paket yang dipilih
                let subtotal = 0;
                $('#pp_rows .sel-paket').each(function() {
                    const id = $(this).val();
                    if (!id) return;
                    const pkg = PAKETS.find(x => String(x.id_paket_treatment) === String(id));
                    if (pkg) subtotal += Number(pkg.harga_paket_treatment || 0);
                });

                // hitung potongan dari promo (Diskon % atau Rupiah)
                const prId = $('#pp_promo').val();
                const promo = PROMOS.find(x => String(x.id_promo) === String(prId));
                let potongan = 0;
                if (promo) {
                    const tipe = (promo.tipe_potongan || '').toLowerCase(); // 'diskon' atau 'rupiah'
                    const nilai = Number(promo.potongan_harga || 0);
                    potongan = (tipe === 'diskon') ? subtotal * nilai / 100 : nilai;
                }
                if (potongan > subtotal) potongan = subtotal;

                const dpp = subtotal - potongan;
                const pajak = dpp * 0.10; // 10%
                const total = dpp + pajak;

                // jika Tunai, cek kecukupan uang
                if (isTunai) {
                    if (uang < total) {
                        const fmt = n => new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(n);
                        const kurang = Math.max(0, total - uang);
                        alert(
                            'Uang tunai kurang dari total yang harus dibayar.\n' +
                            'Total: ' + fmt(total) + '\n' +
                            'Uang: ' + fmt(uang) + '\n' +
                            'Kurang: ' + fmt(kurang)
                        );
                        e.preventDefault();
                        return;
                    }
                }
            });
            // ====== Konfirmasi Non-Tunai (set form action) ======
            $('#confirmNonTunaiModal').on('show.bs.modal', function(e) {
                const btn = $(e.relatedTarget);
                const payId = btn.data('payid');
                $('#confirmNonTunaiForm').attr('action',
                    "{{ route('penjualanPaketTreatment.confirmPayment', '__ID__') }}".replace('__ID__',
                        payId)
                );
            });
        });
    </script>
@endpush
