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

        #wrapper_tanggal_berakhir_kompensasi {
            display: none;
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

    <h1 class="h3 mb-2 text-gray-800">Komplain Pelanggan</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanKomplainTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Teks Komplain</th>
                        <th>Balasan Komplain</th>
                        <th>Pemberian Kompensasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($komplainList as $komplain)
                        <tr>
                            <td style="display:none">{{ $komplain['id_komplain'] }}</td>
                            <td>{{ $komplain['user']['nama_user'] }}</td>
                            <td>{{ $komplain['teks_komplain'] }}</td>
                            <td>{{ $komplain['balasan_komplain'] }}</td>
                            <td>{{ $komplain['pemberian_kompensasi'] ?? 'Menunggu pengiriman' }}</td>
                            <td>
                                <button type="button" class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#editKomplainModal" data-komplain='@json($komplain)'
                                    onclick="populateEditModalFromButton(this)">
                                    Balas
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Komplain -->
    <div class="modal fade" id="editKomplainModal" tabindex="-1" role="dialog" aria-labelledby="editKomplainModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editKomplainForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKomplainModalLabel">Edit Komplain</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Nama Pengguna -->
                        <div class="form-group">
                            <label for="edit_nama_user">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="edit_nama_user" disabled>
                        </div>

                        <!-- Waktu Treatment -->
                        <div class="form-group">
                            <label for="edit_waktu_treatment">Waktu Treatment</label>
                            <input type="text" class="form-control" id="edit_waktu_treatment" disabled>
                        </div>

                        <!-- Treatment -->
                        <div class="form-group">
                            <label for="edit_treatment">Treatment</label>
                            <input type="text" class="form-control" id="edit_treatment" disabled>
                        </div>

                        <!-- Teks Komplain -->
                        <div class="form-group">
                            <label for="edit_teks_komplain">Teks Komplain</label>
                            <textarea name="teks_komplain" class="form-control" id="edit_teks_komplain" rows="3" disabled></textarea>
                        </div>

                        <!-- Link Download Gambar Komplain -->
                        <div class="form-group">
                            <label>Unduh Gambar Komplain</label><br>
                            <div id="gambar_komplain_links"></div>
                        </div>

                        <!-- Balasan Komplain -->
                        <div class="form-group">
                            <label for="edit_balasan_komplain">Balasan Komplain</label>
                            <textarea name="balasan_komplain" class="form-control" id="edit_balasan_komplain" rows="3" required></textarea>
                        </div>

                        <!-- Input Kompensasi -->
                        <div class="form-group">
                            <label for="edit_id_kompensasi">Kompensasi</label>
                            <select class="form-control" id="edit_id_kompensasi" name="id_kompensasi">
                                <option value="">Pilih Kompensasi</option>
                                @foreach ($kompensasiList as $k)
                                    <option value="{{ $k['id_kompensasi'] }}" data-treatment-id="{{ $k['id_treatment'] }}">
                                        {{ $k['nama_kompensasi'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="wrapper_tanggal_berakhir_kompensasi">
                            <label for="edit_tanggal_berakhir_kompensasi">Tanggal Berakhir Kompensasi</label>
                            <input type="date" class="form-control" id="edit_tanggal_berakhir_kompensasi"
                                name="tanggal_berakhir_kompensasi">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-pale">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const kompSelect = document.getElementById('edit_id_kompensasi');
                const tanggalWrapper = document.getElementById('wrapper_tanggal_berakhir_kompensasi');
                const tanggalInput = document.getElementById('edit_tanggal_berakhir_kompensasi');

                function toggleTanggal() {
                    if (kompSelect.value) {
                        // ada kompensasi terpilih → tampilkan
                        tanggalWrapper.style.display = 'block';
                    } else {
                        // tidak ada kompensasi → sembunyikan dan kosongkan
                        tanggalWrapper.style.display = 'none';
                        tanggalInput.value = '';
                    }
                }

                // Saat modal Komplain dibuka, jalankan sekali untuk set initial visibility
                $('#editKomplainModal').on('show.bs.modal', toggleTanggal);

                // Saat pilihan kompensasi berubah
                kompSelect.addEventListener('change', toggleTanggal);
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            // ← TAMBAHAN: bawa daftar kompensasi ke JS
            const kompensasiList = @json($kompensasiList);

            function populateEditModalFromButton(button) {
                const komplain = JSON.parse(button.getAttribute('data-komplain'));
                populateEditModal(komplain);
            }

            function populateEditModal(komplain) {
                const form = document.getElementById('editKomplainForm');
                form.action = `/komplain/${komplain.id_komplain}`;

                // pasang detail-treatment-id agar skrip validasi tahu mana yang dibandingkan
                form.dataset.detailTreatmentId = komplain.detail_booking_treatment.id_treatment;

                document.getElementById('edit_nama_user').value = komplain.user.nama_user;
                // 2) Waktu Treatment dari booking_treatment
                document.getElementById('edit_waktu_treatment').value = komplain.booking_treatment.waktu_treatment; // ← DIUBAH

                // 3) Nama Treatment dari detail_booking_treatment.treatment.nama_treatment
                document.getElementById('edit_treatment').value = komplain.detail_booking_treatment.treatment
                    .nama_treatment; // ← DIUBAH

                document.getElementById('edit_teks_komplain').value = komplain.teks_komplain;
                document.getElementById('edit_balasan_komplain').value = komplain.balasan_komplain ?? '';

                // —————— ▶️ TAMBAHAN: disable form jika kompensasi sudah diberikan
                const submitBtn = form.querySelector('button[type="submit"]');
                if (komplain.kompensasi_diberikan) {
                    document.getElementById('edit_balasan_komplain').disabled = true;
                    document.getElementById('edit_id_kompensasi').disabled = true;
                    document.getElementById('edit_tanggal_berakhir_kompensasi').disabled = true;
                    submitBtn.disabled = true;
                } else {
                    document.getElementById('edit_balasan_komplain').disabled = false;
                    document.getElementById('edit_id_kompensasi').disabled = false;
                    document.getElementById('edit_tanggal_berakhir_kompensasi').disabled = false;
                    submitBtn.disabled = false;
                }
                // ◀️ SELESAI TAMBAHAN

                // Jika ada kompensasi, set input kompensasi
                if (komplain.kompensasi_diberikan) {
                    document.getElementById('edit_id_kompensasi').value = komplain.kompensasi_diberikan.id_kompensasi;
                    document.getElementById('edit_tanggal_berakhir_kompensasi').value =
                        komplain.kompensasi_diberikan.tanggal_berakhir_kompensasi;
                }

                // Set Treatment List
                // const treatmentListContainer = document.getElementById('edit_treatment_list');
                // treatmentListContainer.innerHTML = ''; // Clear previous treatment list

                // komplain.treatments.forEach(function(treatment, index) {
                //     const treatmentItem = document.createElement('li');
                //     treatmentItem.classList.add('list-group-item');
                //     treatmentItem.innerHTML = treatment; // Menampilkan nama treatment
                //     treatmentListContainer.appendChild(treatmentItem);
                // });

                const baseUrl = 'https://klinikneshnavya.com/';

                // Debugging gambar_komplain
                console.log('gambar_komplain:', komplain.gambar_komplain);

                // Pastikan gambar_komplain adalah array yang valid
                let gambarKomplainArray = [];
                try {
                    gambarKomplainArray = JSON.parse(komplain.gambar_komplain); // Mengonversi string menjadi array
                } catch (e) {
                    console.error('Gagal parsing gambar_komplain:', e);
                }

                // Kosongkan dulu link download sebelumnya
                const gambarKomplainContainer = document.getElementById('gambar_komplain_links');
                gambarKomplainContainer.innerHTML = '';

                if (Array.isArray(gambarKomplainArray) && gambarKomplainArray.length > 0) {
                    gambarKomplainArray.forEach(function(gambarPath, index) {
                        // Menghapus tanda kutip ganda atau escape karakter dalam path gambar
                        gambarPath = gambarPath.replace(/['"]+/g, '');

                        const link = document.createElement('a');
                        link.href = baseUrl + gambarPath;
                        link.className = 'btn btn-outline-pale btn-sm m-1';
                        link.target = '_blank';
                        link.download = '';
                        link.innerHTML = `<i class="fas fa-download"></i> Gambar Komplain ${index + 1}`;
                        gambarKomplainContainer.appendChild(link);
                    });
                } else {
                    gambarKomplainContainer.innerHTML = '<p class="text-muted">Tidak ada gambar komplain.</p>';
                }

                // document.getElementById('download_gambar_bukti_transaksi').href = komplain.gambar_bukti_transaksi ? (baseUrl +
                //     komplain.gambar_bukti_transaksi) : '#';
            }
            // ← TAMBAHAN: validasi sebelum submit form
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('editKomplainForm');
                form.addEventListener('submit', function(e) {
                    // 1) Validasi tanggal berakhir kompensasi
                    const endDateStr = document.getElementById('edit_tanggal_berakhir_kompensasi').value;
                    if (endDateStr) {
                        // parse jadi year, month, day
                        const [y, m, d] = endDateStr.split('-').map(Number);
                        // buat Date lokal (monthIndex = m-1)
                        const endDate = new Date(y, m - 1, d);
                        // buat objek hari ini jam 00:00:00
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (endDate <= today) {
                            alert('Tanggal berakhir kompensasi harus setelah hari ini.');
                            e.preventDefault();
                            return;
                        }
                    }

                    // 2) Validasi kecocokan kompensasi ↔ treatment
                    const detailTreatId = parseInt(this.dataset.detailTreatmentId, 10);
                    const kompSelect = document.getElementById('edit_id_kompensasi');
                    const opt = kompSelect.options[kompSelect.selectedIndex];
                    const kompTreatId = parseInt(opt.dataset.treatmentId, 10); // ⬅️ TAMBAHAN

                    if (kompSelect.value && (isNaN(kompTreatId) || kompTreatId !== detailTreatId)) {
                        alert('Kompensasi yang Anda pilih tidak cocok dengan treatment yang dikomplain.');
                        e.preventDefault();
                    }
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#laporanKomplainTable').DataTable({
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
                    ], // urutkan berdasarkan kolom ID (index 0) descending        
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
@endsection
