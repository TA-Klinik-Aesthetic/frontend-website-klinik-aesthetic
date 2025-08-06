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

    <h1 class="h3 mb-2 text-gray-800">Booking Treatment</h1>

    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#bookingModal">
        <i class="fas fa-plus"></i> Tambah Booking
    </button>

    <!-- Card putih dengan shadow -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanBookingTreatmentTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none;">ID</th> <!-- kolom ID -->
                        <th>Nama Pelanggan</th>
                        <th>Waktu Treatment</th>
                        <th>Status Booking</th>
                        <th>Treatment Mulai</th>
                        <th>Treatment Selesai</th>
                        <th>Estimasi Selesai</th> <!-- Kolom baru -->
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookingTreatments as $booking)
                        @php
                            $waktuAwal = $booking['treatment_mulai'] ?? $booking['waktu_treatment'];
                            $totalEstimasiMenit = 0;

                            if (!empty($booking['detail_booking'])) {
                                foreach ($booking['detail_booking'] as $detail) {
                                    $estimasiDurasi = $detail['treatment']['estimasi_treatment'] ?? null;

                                    if ($estimasiDurasi) {
                                        // Ubah estimasi_treatment (format: "HH:MM:SS") menjadi menit
                                        [$jam, $menit, $detik] = explode(':', $estimasiDurasi);
                                        $durasiMenit = $jam * 60 + $menit;
                                        $totalEstimasiMenit += $durasiMenit;
                                    }
                                }
                            }

                            $estimasiSelesai = \Carbon\Carbon::parse($waktuAwal)
                                ->addMinutes($totalEstimasiMenit)
                                ->format('Y-m-d H:i');
                        @endphp
                        <tr>
                            <td style="display:none;">{{ $booking['id_booking_treatment'] }}</td>
                            <td>{{ $booking['user_name'] }}</td>
                            <td>{{ $booking['waktu_treatment'] }}</td>
                            <td>{{ $booking['status_booking_treatment'] }}</td>
                            <td>{{ $booking['treatment_mulai'] ?? '-' }}</td>
                            <td>{{ $booking['treatment_selesai'] ?? '-' }}</td>
                            <td>{{ $totalEstimasiMenit > 0 ? $estimasiSelesai : '-' }}</td>
                            <td>Rp{{ number_format($booking['harga_akhir_treatment'], 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('booking.detail', $booking['id_booking_treatment']) }}"
                                    class="btn btn-pale mb-3">Detail</a>
                                <button type="button" class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#editModal{{ $booking['id_booking_treatment'] }}">
                                    Edit
                                </button>
                                <button class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#statusModal-{{ $booking['id_booking_treatment'] }}">
                                    Ubah Status
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($bookingTreatments as $booking)
        <div class="modal fade" id="editModal{{ $booking['id_booking_treatment'] }}"
            data-current-status="{{ $booking['status_booking_treatment'] }}" tabindex="-1" role="dialog"
            aria-labelledby="editModalLabel{{ $booking['id_booking_treatment'] }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('detailBooking.update', $booking['id_booking_treatment']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $booking['id_booking_treatment'] }}">Edit
                                Booking Treatment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Dropdown untuk Dokter -->
                            <div class="form-group">
                                <label for="id_dokter">Nama Dokter</label>
                                <select name="id_dokter" id="id_dokter" class="form-control">
                                    <option value="">Pilih Dokter</option>
                                    @foreach ($dokters as $dokter)
                                        <option value="{{ $dokter['id_dokter'] }}"
                                            @if ($dokter['id_dokter'] == $booking['id_dokter']) selected @endif>
                                            {{ $dokter['nama_dokter'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dropdown untuk Beautician -->
                            <div class="form-group">
                                <label for="id_beautician">Nama Beautician</label>
                                <select name="id_beautician" id="id_beautician" class="form-control" required>
                                    <option value="">Pilih Beautician</option>
                                    @foreach ($beauticians as $beautician)
                                        <option value="{{ $beautician['id_beautician'] }}"
                                            @if ($beautician['id_beautician'] == $booking['id_beautician']) selected @endif>
                                            {{ $beautician['nama_beautician'] }}
                                        </option>
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
        </div>
    @endforeach

    {{-- Modal Ubah Status Booking Treatment --}}
    @foreach ($bookingTreatments as $booking)
        <div class="modal fade" id="statusModal-{{ $booking['id_booking_treatment'] }}" tabindex="-1" role="dialog"
            aria-labelledby="statusModalLabel-{{ $booking['id_booking_treatment'] }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="status-update-form"
                    action="{{ route('bookingTreatment.updateStatus', $booking['id_booking_treatment']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- simpan status saat ini --}}
                    <input type="hidden" name="current_status" value="{{ $booking['status_booking_treatment'] }}">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel-{{ $booking['id_booking_treatment'] }}">
                                Ubah Status Booking Treatment
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                                <strong>Pelanggan:</strong> {{ $booking['user_name'] }}<br>
                                <strong>Waktu:</strong> {{ $booking['waktu_treatment'] }}
                            </p>
                            <div class="form-group">
                                <label for="status-{{ $booking['id_booking_treatment'] }}">Status Baru</label>
                                <select id="status-{{ $booking['id_booking_treatment'] }}" name="status_booking_treatment"
                                    class="form-control status-select"
                                    data-current="{{ $booking['status_booking_treatment'] }}"
                                    data-waktu="{{ $booking['waktu_treatment'] }}" required>
                                    <option value="">-- Pilih Status --</option>
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
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Modal Tambah Booking -->
    <div class="modal fade js-reset-on-show" id="bookingModal" tabindex="-1" role="dialog"
        aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Tambah Booking Treatment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user">Nama Pelanggan</label>
                            <select name="id_user" id="user" class="form-control" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggan as $user)
                                    <option value="{{ $user['id_user'] }}">{{ $user['nama_user'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="waktu_treatment">Waktu Treatment</label>
                            <input type="datetime-local" name="waktu_treatment" class="form-control"
                                id="waktu_treatment" required>
                        </div>

                        {{-- <div class="form-group">
                            <label for="slot">Slot Waktu</label>
                            <select name="id_detail_jadwal_treatment" id="slot" class="form-control" required>
                                <option value="">Pilih Slot</option>
                            </select>
                        </div> --}}

                        <div class="form-group">
                            <label for="dokter">Dokter</label>
                            <select name="id_dokter" class="form-control">
                                <option value="">Pilih Dokter</option>
                                @foreach ($dokters as $dokter)
                                    <option value="{{ $dokter['id_dokter'] }}">{{ $dokter['nama_dokter'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="beautician">Beautician</label>
                            <select name="id_beautician" class="form-control" required>
                                <option value="">Pilih Beautician</option>
                                @foreach ($beauticians as $beautician)
                                    <option value="{{ $beautician['id_beautician'] }}">
                                        {{ $beautician['nama_beautician'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="form-group">
                                    <label for="status_booking_treatment">Status</label>
                                    <select name="status_booking_treatment" id="status_booking_treatment"
                                        class="form-control" required>
                                        <option value="Verifikasi">Verifikasi</option>
                                        <option value="Berhasil dibooking">Berhasil dibooking</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
                                </div> --}}

                        <div class="form-group">
                            <label for="promo">Promo (Opsional)</label>
                            <select name="id_promo" id="promo" class="form-control">
                                <option value="">Pilih Promo</option>
                                @foreach ($promos as $promo)
                                    <option value="{{ $promo['id_promo'] }}">
                                        {{ $promo['nama_promo'] }}
                                        @if ($promo['tipe_potongan'] === 'Diskon')
                                            - Potongan:
                                            {{ rtrim(rtrim(number_format($promo['potongan_harga'], 2, ',', ''), '0'), ',') }}%
                                            @if ($promo['minimal_belanja'])
                                                - Min Belanja:
                                                Rp{{ number_format($promo['minimal_belanja'], 0, ',', '.') }}
                                            @endif
                                        @else
                                            - Potongan:
                                            Rp{{ number_format($promo['potongan_harga'], 0, ',', '.') }}
                                            @if ($promo['minimal_belanja'])
                                                - Min Belanja:
                                                Rp{{ number_format($promo['minimal_belanja'], 0, ',', '.') }}
                                            @endif
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div id="treatmentDetails">
                            <!-- Kolom treatment pertama -->
                            <div class="treatment-group">
                                <!-- Tambahkan di setiap treatment-group -->
                                <div class="form-group">
                                    <label for="jenis_treatment">Jenis Treatment</label>
                                    <select class="form-control jenis-treatment-select" required>
                                        <option value="">Pilih Jenis Treatment</option>
                                        @foreach ($jenisTreatments as $jenis)
                                            <option value="{{ $jenis['id_jenis_treatment'] }}">
                                                {{ $jenis['nama_jenis_treatment'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="treatment">Treatment</label>
                                    <select name="details[0][id_treatment]" class="form-control treatment" required>
                                        <option value="">Pilih Treatment</option>
                                        @foreach ($treatments as $treatment)
                                            <option value="{{ $treatment['id_treatment'] }}">
                                                {{ $treatment['nama_treatment'] }} -
                                                Rp{{ number_format($treatment['biaya_treatment'], 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="id_kompensasi_diberikan">Kode Kompensasi</label>
                                    <select name="details[0][id_kompensasi_diberikan]"
                                        class="form-control select2 kompensasi-select" style="width: 100%;">
                                        <option value="">Pilih Kode Kompensasi</option>
                                        @foreach ($kompensasis as $kompensasi)
                                            @if ($kompensasi['status_kompensasi'] === 'Belum digunakan')
                                                <option value="{{ $kompensasi['id_kompensasi_diberikan'] }}"
                                                    data-user="{{ $kompensasi['komplain']['id_user'] }}"
                                                    data-treatment="{{ $kompensasi['kompensasi']['id_treatment'] }}">
                                                    {{ $kompensasi['kode_kompensasi'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tombol tambah hanya di group terakhir -->
                                <button type="button" class="btn btn-pale mt-2 addTreatmentGroup">Tambah
                                    Treatment</button>
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
    </div>
@endsection

@push('scripts')
    <script>
        const allTreatments = @json($treatments);

        $('#treatmentDetails').on('change', '.jenis-treatment-select', function() {
            const jenisId = $(this).val();
            const group = $(this).closest('.treatment-group');
            const sel = group.find('select.treatment');

            sel.empty().append('<option value="">Pilih Treatment</option>');

            allTreatments
                .filter(t => t.id_jenis_treatment == jenisId)
                .forEach(t => {
                    const harga = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(t.biaya_treatment);
                    sel.append(`<option value="${t.id_treatment}">
                          ${t.nama_treatment} - ${harga}
                        </option>`);
                });

            sel.trigger('change'); // kalau butuh update kompensasi juga
        });
    </script>
@endpush

@push('scripts')
    <script>
        const kompensasis = @json($kompensasis);

        // 1️⃣ Initialize Select2 (definisi paling atas supaya aman)
        function initializeSelect2(container) {
            $(container).find('.select2').each(function() {
                const parentGroup = $(this).closest('.treatment-group');

                // Destroy dulu jika sudah ada select2 sebelumnya
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }

                $(this).select2({
                    dropdownParent: parentGroup,
                    width: '100%',
                    placeholder: 'Ketik atau pilih kode kompensasi',
                    allowClear: true,
                    minimumInputLength: 1
                });
            });
        }

        // 2️⃣ Filter Kompensasi (harus di atas addTreatmentGroup karena dipanggil di dalamnya)
        function filterKompensasi(selectElement, userId, treatmentId) {
            const group = $(selectElement).closest('.treatment-group');
            const kompensasiSelect = group.find('select[name$="[id_kompensasi_diberikan]"]');
            kompensasiSelect.empty().append('<option value="">Pilih Kode Kompensasi</option>');

            kompensasis.forEach(k => {
                if (
                    k.status_kompensasi === 'Belum Digunakan' &&
                    k.komplain &&
                    k.kompensasi &&
                    k.komplain.id_user == userId &&
                    k.kompensasi.id_treatment == treatmentId
                ) {
                    kompensasiSelect.append(
                        `<option value="${k.id_kompensasi_diberikan}" data-user="${k.komplain.id_user}" data-treatment="${k.kompensasi.id_treatment}">${k.kode_kompensasi}</option>`
                    );
                }
            });
        }

        let treatmentIndex = 1;

        // 3️⃣ Add Treatment Group (definisi di bawah semua function yang dipanggilnya)
        function addTreatmentGroup() {
            const container = document.getElementById('treatmentDetails');

            const html = `
            <div class="treatment-group">
                <div class="form-group">
                    <label>Jenis Treatment</label>
                    <select class="form-control jenis-treatment-select" required>
                        <option value="">Pilih Jenis Treatment</option>
                        @foreach ($jenisTreatments as $jenis)
                            <option value="{{ $jenis['id_jenis_treatment'] }}">{{ $jenis['nama_jenis_treatment'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Treatment</label>
                    <select name="details[${treatmentIndex}][id_treatment]" class="form-control treatment" required>
                        <option value="">Pilih Treatment</option>
                        @foreach ($treatments as $treatment)
                            <option value="{{ $treatment['id_treatment'] }}">
                                {{ $treatment['nama_treatment'] }} - Rp{{ number_format($treatment['biaya_treatment'], 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Kompensasi</label>
                    <select name="details[${treatmentIndex}][id_kompensasi_diberikan]" class="form-control select2 kompensasi-select" style="width: 100%;">
                        <option value="">Pilih Kode Kompensasi</option>
                        @foreach ($kompensasis as $kompensasi)
                            @if ($kompensasi['status_kompensasi'] === 'Belum digunakan')
                                <option value="{{ $kompensasi['id_kompensasi_diberikan'] }}"
                                    data-user="{{ $kompensasi['komplain']['id_user'] }}"
                                    data-treatment="{{ $kompensasi['kompensasi']['id_treatment'] }}">
                                    {{ $kompensasi['kode_kompensasi'] }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <button type="button" class="btn btn-pale mt-2 addTreatmentGroup">Tambah Treatment</button>
            </div>
            `;

            const groups = container.querySelectorAll('.treatment-group');
            const lastGroup = groups[groups.length - 1];
            const oldBtn = lastGroup.querySelector('.addTreatmentGroup');
            if (oldBtn) oldBtn.remove();

            container.insertAdjacentHTML('beforeend', html);

            initializeSelect2(container.lastElementChild);

            treatmentIndex++;
        }

        // 4️⃣ Document Ready + Event Binding
        document.addEventListener('DOMContentLoaded', function() {
            initializeSelect2(document);

            $('#user').on('change', function() {
                const selectedUserId = $(this).val();

                $('.treatment-group').each(function() {
                    const treatmentId = $(this).find('.treatment').val();
                    if (treatmentId) {
                        filterKompensasi(this, selectedUserId, treatmentId);
                    }
                });
            });

            $('#treatmentDetails').on('change', '.treatment', function() {
                const selectedTreatmentId = $(this).val();
                const userId = $('#user').val();

                if (userId) {
                    filterKompensasi(this, userId, selectedTreatmentId);
                }
            });

            $('#treatmentDetails').on('click', '.addTreatmentGroup', function(e) {
                e.preventDefault();
                addTreatmentGroup();
            });

            $('#bookingModal').on('shown.bs.modal', function() {
                initializeSelect2(this);
            });

            $('form').on('submit', function(e) {
                let isValid = true;
                const userId = $('#user').val();

                // Validasi kompensasi user & treatment
                $('.treatment-group').each(function() {
                    const treatment = $(this).find('.treatment').val();
                    const kompensasiSelect = $(this).find('.select2');
                    const kompensasiOption = kompensasiSelect.find('option:selected');

                    if (kompensasiSelect.val()) {
                        const kompensasiUser = kompensasiOption.data('user');
                        const kompensasiTreatment = kompensasiOption.data('treatment');

                        if (kompensasiUser != userId || kompensasiTreatment != treatment) {
                            alert('Kode kompensasi tidak valid untuk user atau treatment.');
                            isValid = false;
                            e.preventDefault();
                            return false;
                        }
                    }
                });

                // Cek jika seluruh treatment pakai kompensasi
                let allTreatmentUseKompensasi = true;
                $('.treatment-group').each(function() {
                    const kompensasiVal = $(this).find('.kompensasi-select').val();
                    if (!kompensasiVal) {
                        allTreatmentUseKompensasi = false;
                        return false;
                    }
                });

                const promoVal = $('#promo').val();

                // ❗ Validasi kombinasi kompensasi semua + promo
                if (allTreatmentUseKompensasi && promoVal) {
                    alert(
                        'Promo tidak bisa digunakan jika seluruh treatment sudah menggunakan kompensasi.'
                    );
                    isValid = false;
                    e.preventDefault();
                    return false;
                }

                // ❗ Validasi promo dan minimal belanja
                if (promoVal && !allTreatmentUseKompensasi) {
                    const selectedPromo = @json($promos).find(p => p.id_promo == promoVal);

                    if (selectedPromo && selectedPromo.minimal_belanja > 0) {
                        let totalWithoutKompensasi = 0;

                        $('.treatment-group').each(function() {
                            const kompensasiVal = $(this).find('.kompensasi-select').val();
                            const treatmentId = $(this).find('.treatment').val();

                            if (!kompensasiVal && treatmentId) {
                                const selectedTreatment = @json($treatments).find(t => t
                                    .id_treatment == treatmentId);
                                if (selectedTreatment) {
                                    totalWithoutKompensasi += parseFloat(selectedTreatment
                                        .biaya_treatment);
                                }
                            }
                        });

                        if (totalWithoutKompensasi < selectedPromo.minimal_belanja) {
                            const formatter = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            });

                            alert(
                                `Promo "${selectedPromo.nama_promo}" tidak bisa digunakan.\n\n` +
                                `Total belanja tanpa kompensasi: ${formatter.format(totalWithoutKompensasi)}\n` +
                                `Minimal belanja yang dibutuhkan: ${formatter.format(selectedPromo.minimal_belanja)}`
                            );

                            isValid = false;
                            e.preventDefault();
                            return false;
                        }

                    }
                }

                // ❗ Prevent jika ada yang tidak valid
                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-update-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const statusSelect = form.querySelector('.status-select');
                    const selectedStatus = statusSelect.value;
                    const currentStatus = statusSelect.dataset.current;
                    const waktuTreatment = statusSelect.dataset.waktu; // e.g. "2025-07-15 10:30"

                    // helper: normalize ke timestamp tanggal saja
                    function dateOnly(ts) {
                        const d = new Date(ts);
                        return new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
                    }
                    const todayDate = dateOnly(new Date());
                    const treatDate = dateOnly(waktuTreatment);

                    if (selectedStatus === 'Treatment dimulai' && currentStatus !==
                        'Berhasil dibooking') {
                        alert(
                            'Status "Treatment dimulai" hanya dapat dipilih jika status sebelumnya adalah "Berhasil dibooking".'
                        );
                        e.preventDefault();
                    }

                    if (selectedStatus === 'Selesai' && currentStatus !== 'Treatment dimulai') {
                        alert(
                            'Status "Selesai" hanya dapat dipilih jika status sebelumnya adalah "Treatment dimulai".'
                        );
                        e.preventDefault();
                    }


                    // 2) Cek tanggal kalau pilih "Treatment dimulai"
                    if (selectedStatus === 'Treatment dimulai') {
                        // Jika tanggal booking masih di masa depan
                        if (treatDate > todayDate) {
                            alert(
                                `Tanggal treatment adalah ${waktuTreatment.split(' ')[0]}. ` +
                                `Anda belum bisa memulai treatment sebelum tanggal tersebut.`
                            );
                            e.preventDefault();
                            return;
                        }
                        // Kalau treatDate === todayDate atau treatDate < todayDate → OK
                    }
                });
            });
        });
    </script>
@endpush

{{-- @push('scripts')
    <script>
        $('#waktu_treatment').on('change', function() {
            const date = $(this).val();
            const slotSel = $('#slot');
            slotSel.empty().append('<option value="">Pilih Slot</option>');

            if (!date) return;

            const url = `{{ url('booking-treatment/slots') }}/${date}`;

            $.getJSON(url, function(res) {
                if (res.success && res.data.details.length) {
                    res.data.details.forEach(detail => {
                        // hanya yang status_jadwal 'tersedia'
                        if (detail.status_jadwal.toLowerCase() === 'tersedia') {
                            slotSel.append(
                                `<option value="${detail.id_detail}">
                                ${detail.waktu_tersedia.replace(':00','')} 
                            </option>`
                            );
                        }
                    });
                    if (slotSel.children().length === 1) {
                        // hanya option default -> tidak ada slot tersedia
                        slotSel.append('<option disabled>— Tidak ada slot tersedia —</option>');
                    }
                } else {
                    alert(`Jadwal pada ${date} belum tersedia.`);
                }
            }).fail(function() {
                alert('Gagal mengambil data slot, silakan coba lagi.');
            });
        });
    </script>
@endpush --}}

@push('scripts')
    <script>
        $(document).ready(function() {
            // Validasi jam & tanggal saat waktu treatment berubah
            $('#waktu_treatment').on('change', function() {
                const val = $(this).val();
                if (!val) return;

                const sel = new Date(val);
                const now = new Date();

                // Cek 1: tanggal tidak boleh di masa lalu (tanpa memperhatikan jam)
                const selDate = new Date(sel.getFullYear(), sel.getMonth(), sel.getDate());
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                if (selDate < today) {
                    alert('Tidak bisa memilih tanggal yang sudah lewat.');
                    return $(this).val('');
                }

                // Cek 2: untuk tanggal hari ini, jam pun tidak boleh kurang dari sekarang
                if (selDate.getTime() === today.getTime() && sel < now) {
                    alert('Tidak bisa memilih jam yang sudah lewat hari ini.');
                    return $(this).val('');
                }

                // Cek 3: jam harus antara 10–20
                const jam = sel.getHours();
                if (jam < 10 || jam >= 20) {
                    alert('Waktu treatment harus antara jam 10:00 dan 20:00.');
                    return $(this).val('');
                }
            });

            // DataTables
            $('#laporanBookingTreatmentTable').DataTable({
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
                    } // sembunyikan kolom ID
                ],
                order: [
                    [0, 'desc'] // Urut berdasarkan kolom ke-2 (index 1)
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
        $(document).ready(function() {
            // 1) Tangani semua form ubah status
            $('.status-update-form').on('submit', function(e) {
                var sel = $(this).find('.status-select');
                var newStatus = sel.val();
                var current = sel.data('current'); // harus sama persis dengan teks option lama

                // jika mau ubah ke 'Dibatalkan' dari 'Treatment dimulai' atau 'Selesai' → blokir
                if (newStatus === 'Dibatalkan' &&
                    (current === 'Treatment dimulai' || current === 'Selesai')) {
                    e.preventDefault();
                    alert('Status "' + current + '" tidak bisa diubah menjadi "Dibatalkan".');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Tangani submit pada form di setiap modal editBooking
            $('[id^="editModal"]').each(function() {
                const modal = $(this);
                const current = modal.data('current-status'); // ambil dari data-current-status

                // form di dalam modal
                modal.find('form').on('submit', function(e) {
                    if (current !== 'Verifikasi') {
                        e.preventDefault();
                        alert(
                            'Anda hanya dapat mengedit booking treatment jika status masih “Verifikasi”.');
                    }
                });
            });
        });
    </script>
@endpush


@push('scripts')
    <script>
        const jenisTreatments = @json($jenisTreatments);

        // Ketika jenis treatment dipilih
        $('#treatmentDetails').on('change', '.jenis-treatment-select', function() {
            const selectedJenisId = $(this).val();
            const group = $(this).closest('.treatment-group');
            const treatmentSelect = group.find('.treatment');

            treatmentSelect.empty().append('<option value="">Pilih Treatment</option>');

            if (!selectedJenisId) return;

            // Cari data treatment dari jenisTreatments
            const jenis = jenisTreatments.find(j => j.id_jenis_treatment == selectedJenisId);
            if (jenis && jenis.treatment.length) {
                jenis.treatment.forEach(t => {
                    const harga = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(t.biaya_treatment);
                    treatmentSelect.append(
                        `<option value="${t.id_treatment}">${t.nama_treatment} - ${harga}</option>`);
                });
            }

            // Trigger change untuk update kompensasi
            treatmentSelect.trigger('change');
        });
    </script>
@endpush
