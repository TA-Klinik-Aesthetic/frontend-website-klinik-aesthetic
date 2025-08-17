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

    <h1 class="h3 mb-2 text-gray-800">Booking Treatment Paket</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#modalTambah">
        <i class="fas fa-plus"></i> Tambah Booking Paket
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="tableBookingPaket" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none;">ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Waktu Treatment</th>
                        <th>Status</th>
                        <th>Treatment Mulai</th>
                        <th>Treatment Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $b)
                        @php
                            $id = $b['id_booking_treatment_paket'] ?? ($b['id'] ?? null);
                            $nama = $b['user']['nama_user'] ?? ($b['user_name'] ?? '-');
                            $waktu = $b['waktu_treatment'] ?? '-';
                            $status = $b['status_booking_treatment'] ?? '-';
                            $mulai = $b['treatment_mulai'] ?? '-';
                            $selesai = $b['treatment_selesai'] ?? '-';
                        @endphp
                        <tr>
                            <td style="display:none;">{{ $id }}</td>
                            <td>{{ $nama }}</td>
                            <td>{{ $waktu }}</td>
                            <td>{{ $status }}</td>
                            <td>{{ $mulai }}</td>
                            <td>{{ $selesai }}</td>
                            <td>
                                <a href="{{ route('bookingTreatmentPaket.show', $id) }}"
                                    class="btn btn-pale mb-2">Detail</a>

                                <button type="button" class="btn btn-pale mb-2" data-toggle="modal"
                                    data-target="#modalEdit-{{ $id }}">
                                    Edit
                                </button>

                                <button class="btn btn-pale mb-2" data-toggle="modal"
                                    data-target="#modalStatus-{{ $id }}">
                                    Ubah Status
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Booking Paket --}}
    <div class="modal fade js-reset-on-show" id="modalTambah" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('bookingTreatmentPaket.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Booking Paket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Pelanggan --}}
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <select name="id_user" id="btp_pelanggan" class="form-control" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach ($pelanggan as $u)
                                <option value="{{ $u['id_user'] }}">{{ $u['nama_user'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Waktu --}}
                    <div class="form-group">
                        <label>Waktu Treatment</label>
                        <input type="datetime-local" name="waktu_treatment" id="btp_waktu" class="form-control" required>
                    </div>

                    <hr>

                    {{-- Detail Paket (dinamis) --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Detail Paket</h6>
                        <button type="button" class="btn btn-pale btn-outline-pale" id="btnAddDetailRow">Tambah
                            Baris</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:40%">Paket Milik Pelanggan</th>
                                    <th style="width:40%">Treatment dalam Paket (Sisa &gt; 0)</th>
                                    <th style="width:14%">Jumlah Dipakai</th>
                                    <th style="width:6%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="btp_details_container"><!-- baris diisi via JS --></tbody>
                        </table>
                    </div>
                    <small class="text-muted">Minimal 1 baris detail. Paket & treatment tampil setelah memilih
                        pelanggan.</small>

                    <hr>

                    {{-- Petugas (opsional) --}}
                    <div class="form-group">
                        <label>Dokter (opsional)</label>
                        <select name="id_dokter" class="form-control">
                            <option value="">— tidak ada —</option>
                            @foreach ($dokters as $d)
                                <option value="{{ $d['id_dokter'] ?? $d['id_user'] }}">
                                    {{ $d['nama_dokter'] ?? $d['nama_user'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Beautician</label>
                        <select name="id_beautician" class="form-control" required>
                            <option value="">— tidak ada —</option>
                            @foreach ($beauticians as $b)
                                <option value="{{ $b['id_beautician'] ?? $b['id_user'] }}">
                                    {{ $b['nama_beautician'] ?? $b['nama_user'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-pale">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Petugas --}}
    @foreach ($bookings as $b)
        @php
            $id = $b['id_booking_treatment_paket'] ?? ($b['id'] ?? null);
            $curr = $b['status_booking_treatment'] ?? '';
        @endphp
        <div class="modal fade" id="modalEdit-{{ $id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditLabel-{{ $id }}" aria-hidden="true"
            data-current-status="{{ $curr }}">
            <div class="modal-dialog" role="document">
                <form action="{{ route('bookingTreatmentPaket.update', $id) }}" method="POST" class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel-{{ $id }}">Edit Petugas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Dokter</label>
                            <select name="id_dokter" class="form-control">
                                <option value="">— tidak ada —</option>
                                @foreach ($dokters as $d)
                                    <option value="{{ $d['id_dokter'] ?? $d['id_user'] }}"
                                        @if (($b['id_dokter'] ?? null) == ($d['id_dokter'] ?? $d['id_user'])) selected @endif>
                                        {{ $d['nama_dokter'] ?? $d['nama_user'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Beautician</label>
                            <select name="id_beautician" class="form-control">
                                <option value="">— tidak ada —</option>
                                @foreach ($beauticians as $bt)
                                    <option value="{{ $bt['id_beautician'] ?? $bt['id_user'] }}"
                                        @if (($b['id_beautician'] ?? null) == ($bt['id_beautician'] ?? $bt['id_user'])) selected @endif>
                                        {{ $bt['nama_beautician'] ?? $bt['nama_user'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pale">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Modal Ubah Status --}}
    @foreach ($bookings as $b)
        @php
            $id = $b['id_booking_treatment_paket'] ?? ($b['id'] ?? null);
            $curr = $b['status_booking_treatment'] ?? '';
        @endphp
        <div class="modal fade" id="modalStatus-{{ $id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalStatusLabel-{{ $id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('bookingTreatmentPaket.updateStatus', $id) }}" method="POST"
                    class="modal-content status-update-form">
                    @csrf @method('PUT')
                    <input type="hidden" name="current_status" value="{{ $curr }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStatusLabel-{{ $id }}">Ubah Status Booking Paket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">
                            <strong>Pelanggan:</strong> {{ $b['user']['nama_user'] ?? ($b['user_name'] ?? '-') }}<br>
                            <strong>Waktu:</strong> {{ $b['waktu_treatment'] ?? '-' }}
                        </p>
                        <div class="form-group">
                            <label>Status Baru</label>
                            <select name="status_booking_treatment" class="form-control status-select"
                                data-current="{{ $curr }}" data-waktu="{{ $b['waktu_treatment'] ?? '' }}"
                                required>
                                <option value="">— Pilih Status —</option>
                                <option value="Treatment dimulai">Treatment dimulai</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pale">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(function() {
            // ===== DataTables =====
            $('#tableBookingPaket').DataTable({
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
                    $('.dataTables_wrapper .dataTables_paginate a').each(function() {
                        $(this).removeClass('paginate_button').addClass(
                            'btn btn-sm btn-outline-primary mx-1')
                    })
                }
            });

            // ===== Validasi status & tanggal =====
            $('.status-update-form').on('submit', function(e) {
                var sel = $(this).find('.status-select').val();
                var curr = $(this).find('.status-select').data('current');
                var waktu = $(this).find('.status-select').data('waktu') || '';

                if (sel === 'Treatment dimulai' && curr !== 'Berhasil dibooking') {
                    alert('Status "Treatment dimulai" hanya dari "Berhasil dibooking".');
                    e.preventDefault();
                }
                if (sel === 'Selesai' && curr !== 'Treatment dimulai') {
                    alert('Status "Selesai" hanya dari "Treatment dimulai".');
                    e.preventDefault();
                }
                if (sel === 'Dibatalkan' && (curr === 'Treatment dimulai' || curr === 'Selesai')) {
                    alert('Tidak bisa membatalkan setelah dimulai/selesai.');
                    e.preventDefault();
                }

                if (sel === 'Treatment dimulai' && waktu) {
                    const d = new Date(waktu),
                        t = new Date();
                    const d0 = new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
                    const t0 = new Date(t.getFullYear(), t.getMonth(), t.getDate()).getTime();
                    if (d0 > t0) {
                        alert('Tidak bisa memulai sebelum tanggal booking.');
                        e.preventDefault();
                    }
                }
            });

            // ===== Validasi waktu input (10–20) =====
            $('#btp_waktu').on('change', function() {
                const val = $(this).val();
                if (!val) return;
                const sel = new Date(val),
                    now = new Date();
                const selDate = new Date(sel.getFullYear(), sel.getMonth(), sel.getDate());
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                if (selDate < today) {
                    alert('Tanggal sudah lewat.');
                    return $(this).val('');
                }
                if (selDate.getTime() === today.getTime() && sel < now) {
                    alert('Jam sudah lewat.');
                    return $(this).val('');
                }
                const jam = sel.getHours();
                if (jam < 10 || jam >= 20) {
                    alert('Waktu harus 10:00–20:00.');
                    return $(this).val('');
                }
            });
        });
    </script>
    <script>
        $(function() {
            // Endpoint proxy (hindari CORS)
            function urlPaketByUser(userId) {
                return "{{ route('bookingTreatmentPaket.paketByUser', ['id' => '__ID__']) }}".replace('__ID__',
                    userId);
            }

            function urlPaketDetail(pkgId) {
                return "{{ route('bookingTreatmentPaket.paketDetail', ['id' => '__ID__']) }}".replace('__ID__',
                    pkgId);
            }

            // State
            let ROW_INDEX = 0;
            let CURRENT_PACKAGES = [];
            const PKG_DETAILS_CACHE = {};

            // ===== Row builder =====
            function buildRow(idx) {
                const tr = $(`
              <tr data-index="${idx}">
                <td>
                  <select class="form-control sel-paket" name="details[${idx}][id_paket_treatment_pelanggan]" required>
                    <option value="">— pilih paket —</option>
                  </select>
                </td>
                <td>
                  <select class="form-control sel-treatment" name="details[${idx}][id_treatment]" required>
                    <option value="">— pilih treatment paket —</option>
                  </select>
                </td>
                <td>
                  <input type="number" class="form-control inp-jumlah" name="details[${idx}][jumlah_dipakai]" min="1" placeholder="1" required>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-outline-danger btn-del">Hapus</button>
                </td>
              </tr>
            `);

                // Isi opsi paket dari state
                const $paket = tr.find('.sel-paket');
                CURRENT_PACKAGES.forEach(p => {
                    $paket.append(`<option value="${p.id}">${p.label}</option>`);
                });

                // Hapus baris
                tr.on('click', '.btn-del', function() {
                    tr.remove();
                    reindexRows();
                    if ($('#btp_details_container tr').length === 0) addRow();
                });

                // Paket berubah -> load treatment
                tr.on('change', '.sel-paket', function() {
                    const pkgId = $(this).val();
                    const $treat = tr.find('.sel-treatment');
                    const $qty = tr.find('.inp-jumlah');

                    $treat.empty().append('<option value="">Memuat...</option>');
                    $qty.val('').attr('max', null);

                    if (!pkgId) {
                        $treat.html('<option value="">— pilih treatment paket —</option>');
                        return;
                    }

                    const fillTreat = (rows) => {
                        $treat.empty().append('<option value="">— pilih treatment paket —</option>');
                        rows.forEach(r => {
                            $treat.append(
                                `<option value="${r.id_treatment}" data-sisa="${r.sisa}">${r.nama} — sisa ${r.sisa}</option>`
                                );
                        });
                        if (rows.length === 0) {
                            $treat.append('<option disabled>— semua kuota habis —</option>');
                        }
                    };

                    if (PKG_DETAILS_CACHE[pkgId]) {
                        fillTreat(PKG_DETAILS_CACHE[pkgId]);
                    } else {
                        $.getJSON(urlPaketDetail(pkgId))
                            .done(function(res) {
                                const rows = res.data || [];
                                PKG_DETAILS_CACHE[pkgId] = rows;
                                fillTreat(rows);
                            })
                            .fail(function() {
                                $treat.html('<option value="">Gagal memuat detail</option>');
                            });
                    }
                });

                // Treatment berubah -> set batas jumlah
                tr.on('change', '.sel-treatment', function() {
                    const sisa = parseInt($(this).find('option:selected').data('sisa') || 0, 10);
                    const $qty = tr.find('.inp-jumlah');
                    if (sisa > 0) {
                        $qty.attr('max', sisa);
                        let v = parseInt($qty.val() || '0', 10);
                        if (!v || v < 1) v = 1;
                        if (v > sisa) v = sisa;
                        $qty.val(v);
                    } else {
                        $qty.val('').attr('max', null);
                    }
                });

                // Jaga nilai jumlah valid
                tr.on('input', '.inp-jumlah', function() {
                    const max = parseInt($(this).attr('max') || '0', 10);
                    let val = parseInt($(this).val() || '0', 10);
                    if (val < 1) val = 1;
                    if (max > 0 && val > max) val = max;
                    $(this).val(val);
                });

                return tr;
            }

            function addRow() {
                const idx = ROW_INDEX++;
                $('#btp_details_container').append(buildRow(idx));
            }

            function reindexRows() {
                // pakai konkatenasi untuk aman
                $('#btp_details_container tr').each(function(i) {
                    $(this).attr('data-index', i);
                    $(this).find('select.sel-paket').attr('name', 'details[' + i +
                        '][id_paket_treatment_pelanggan]');
                    $(this).find('select.sel-treatment').attr('name', 'details[' + i + '][id_treatment]');
                    $(this).find('input.inp-jumlah').attr('name', 'details[' + i + '][jumlah_dipakai]');
                });
                ROW_INDEX = $('#btp_details_container tr').length;
            }

            function resetRows() {
                $('#btp_details_container').empty();
                ROW_INDEX = 0;
                addRow();
            }

            // Tombol tambah baris
            $('#btnAddDetailRow').on('click', function() {
                if (!$('#btp_pelanggan').val()) {
                    alert('Pilih pelanggan terlebih dahulu.');
                    return;
                }
                if (CURRENT_PACKAGES.length === 0) {
                    alert('Pelanggan belum memiliki paket aktif.');
                    return;
                }
                addRow();
            });

            // Pilih pelanggan -> load paket, reset baris, clear cache
            $('#btp_pelanggan').on('change', function() {
                const userId = $(this).val();
                CURRENT_PACKAGES = [];
                for (const k in PKG_DETAILS_CACHE) delete PKG_DETAILS_CACHE[k];
                resetRows();

                if (!userId) return;

                $.getJSON(urlPaketByUser(userId))
                    .done(function(res) {
                        CURRENT_PACKAGES = res.data || [];
                        // refresh opsi paket pada semua baris
                        $('#btp_details_container tr').each(function() {
                            const $paket = $(this).find('.sel-paket');
                            $paket.empty().append('<option value="">— pilih paket —</option>');
                            CURRENT_PACKAGES.forEach(p => {
                                $paket.append(
                                    `<option value="${p.id}">${p.label}</option>`);
                            });
                            $(this).find('.sel-treatment').html(
                                '<option value="">— pilih treatment paket —</option>');
                            $(this).find('.inp-jumlah').val('').attr('max', null);
                        });
                        if (CURRENT_PACKAGES.length === 0) {
                            $('#btp_details_container tr .sel-paket').append(
                                '<option disabled>Tidak ada paket aktif</option>');
                        }
                    })
                    .fail(function() {
                        $('#btp_details_container tr .sel-paket').html(
                            '<option value="">Gagal memuat paket</option>');
                    });
            });

            // Reset isi modal setiap dibuka
            $('#modalTambah').on('show.bs.modal', function() {
                CURRENT_PACKAGES = [];
                for (const k in PKG_DETAILS_CACHE) delete PKG_DETAILS_CACHE[k];
                resetRows();
                $('#btp_pelanggan').val('');
                $('#btp_waktu').val('');
            });

            // Validasi submit
            $('#modalTambah form').on('submit', function(e) {
                const rows = $('#btp_details_container tr');
                if (!rows.length) {
                    alert('Tambahkan minimal 1 baris detail paket.');
                    e.preventDefault();
                    return;
                }
                let ok = true;
                rows.each(function() {
                    const pkg = $(this).find('.sel-paket').val();
                    const trt = $(this).find('.sel-treatment').val();
                    const qty = parseInt($(this).find('.inp-jumlah').val() || '0', 10);
                    const max = parseInt($(this).find('.inp-jumlah').attr('max') || '0', 10);
                    if (!pkg || !trt || qty < 1 || (max > 0 && qty > max)) {
                        ok = false;
                        return false;
                    }
                });
                if (!ok) {
                    alert('Lengkapi paket, treatment, dan jumlah dipakai (tidak melebihi sisa kuota).');
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
