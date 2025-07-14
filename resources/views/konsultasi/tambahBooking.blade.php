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

    <h1 class="h3 mb-2 text-gray-800">Daftar Booking Konsultasi</h1>
    <button type="button" class="btn btn-pale mb-3" data-toggle="modal" data-target="#tambahKonsultasiModal">
        <i class="fas fa-plus"></i> Tambah Booking
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanKonsultasiTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="display:none">ID</th> {{-- kolom tersembunyi --}}
                        <th>Nama Pelanggan</th>
                        <th>Waktu Konsultasi</th>
                        <th>Dokter</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->reverse() as $item)
                        <tr>
                            <td style="display:none">{{ $item['id_konsultasi'] }}</td>
                            <td>{{ $item['user']['nama_user'] ?? 'Tidak ada nama pelanggan' }}</td>
                            <td>{{ $item['waktu_konsultasi'] }}</td>
                            <td>{{ $item['dokter']['nama_dokter'] }}</td>
                            <td>{{ $item['status_booking_konsultasi'] }}</td>
                            <td>
                                <!-- Tombol Detail -->
                                <a href="{{ route('konsultasi.show', ['id' => $item['id_konsultasi']]) }}"
                                    class="btn btn-pale mb-3">Detail</a>

                                {{-- @if (!empty($item['detail_konsultasi']) && isset($item['detail_konsultasi']['id_detail_konsultasi']))
                                        <a href="{{ route('konsultasi.editKeluhan', $item['detail_konsultasi']['id_detail_konsultasi']) }}"
                                            class="btn btn-warning">Edit</a>
                                    @else
                                        <button class="btn btn-secondary" disabled>Edit</button>
                                    @endif --}}

                                <!-- Tombol Tambah Detail -->
                                <button type="button" class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#tambahDetailModal-{{ $item['id_konsultasi'] }}">
                                    Tambah Detail

                                </button>

                                <!-- Tombol Ubah Status -->
                                <button class="btn btn-pale mb-3" data-toggle="modal"
                                    data-target="#statusModal-{{ $item['id_konsultasi'] }}">
                                    Ubah Status
                                </button>


                                {{-- <form action="{{ route('konsultasi.destroy', $item['id_konsultasi']) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</button>
                                    </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($data->reverse() as $item)
        <!-- Modal Tambah Detail Konsultasi -->
        <div class="modal fade" id="tambahDetailModal-{{ $item['id_konsultasi'] }}" tabindex="-1" role="dialog"
            aria-labelledby="tambahDetailModalLabel-{{ $item['id_konsultasi'] }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('konsultasi.simpanDetail', $item['id_konsultasi']) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahDetailModalLabel-{{ $item['id_konsultasi'] }}">
                                Tambah Detail Konsultasi untuk {{ $item['user']['nama_user'] ?? 'Pelanggan' }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div id="detail-container-{{ $item['id_konsultasi'] }}">

                                <div class="form-group">
                                    <label>Diagnosis</label>
                                    <textarea class="form-control" name="details[0][diagnosis]" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Saran Tindakan</label>
                                    <textarea class="form-control" name="details[0][saran_tindakan]" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Pilih Treatment</label>
                                    <select class="form-control" name="details[0][id_treatment]">
                                        <option value="">Pilih Treatment</option>
                                        @foreach ($treatments as $treatment)
                                            <option value="{{ $treatment['id_treatment'] }}">
                                                {{ $treatment['nama_treatment'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-pale"
                                onclick="addDetail('{{ $item['id_konsultasi'] }}')">Tambah Detail</button>
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

    {{-- Modal-Modal Ubah Status --}}
    @foreach ($data->reverse() as $item)
        <div class="modal fade" id="statusModal-{{ $item['id_konsultasi'] }}" tabindex="-1" role="dialog"
            aria-labelledby="statusModalLabel-{{ $item['id_konsultasi'] }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('konsultasi.updateStatus', $item['id_konsultasi']) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel-{{ $item['id_konsultasi'] }}">
                                Ubah Status Konsultasi
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                                <strong>Pelanggan:</strong> {{ $item['user']['nama_user'] ?? '—' }}<br>
                                <strong>Waktu:</strong> {{ $item['waktu_konsultasi'] }}
                            </p>
                            <div class="form-group">
                                <label for="status_booking_konsultasi-{{ $item['id_konsultasi'] }}">Status Baru</label>
                                <select id="status_booking_konsultasi-{{ $item['id_konsultasi'] }}"
                                    name="status_booking_konsultasi" class="form-control" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Berhasil Dibooking">Berhasil Dibooking</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-pale">Simpan Status</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Modal Tambah Konsultasi -->
    <div class="modal fade" id="tambahKonsultasiModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahKonsultasiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('konsultasi.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahKonsultasiModalLabel">Tambah Booking Konsultasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Nama Pelanggan -->
                        <div class="form-group">
                            <label for="id_user">Nama Pelanggan</label>
                            <select class="form-control" id="id_user" name="id_user" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user['id_user'] }}">{{ $user['nama_user'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Waktu Konsultasi -->
                        <div class="form-group">
                            <label for="waktu_konsultasi">Waktu Konsultasi</label>
                            <input type="datetime-local" class="form-control" id="waktu_konsultasi"
                                name="waktu_konsultasi" required>
                        </div>

                        <!-- Nama Dokter -->
                        <div class="form-group">
                            <label for="id_dokter">Nama Dokter</label>
                            <select class="form-control" id="id_dokter" name="id_dokter">
                                <option value="">Pilih Dokter</option>
                                @foreach ($dokters as $dokter)
                                    <option value="{{ $dokter['id_dokter'] }}">{{ $dokter['nama_dokter'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Keluhan Pelanggan -->
                        <div class="form-group">
                            <label for="keluhan_pelanggan">Keluhan Pelanggan</label>
                            <textarea class="form-control" id="keluhan_pelanggan" name="keluhan_pelanggan" required></textarea>
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
@endsection

@push('scripts')
    <script>
        function addDetail(konsultasiId) {
            const container = document.getElementById('detail-container-' + konsultasiId);
            const index = container.querySelectorAll('.form-group').length / 2; // dua input per detail

            const html = `
            <div class="form-group">
                <label>Diagnosis</label>
                <textarea class="form-control" name="details[${index}][diagnosis]" required></textarea>
            </div>

            <div class="form-group">
                <label>Saran Tindakan</label>
                <textarea class="form-control" name="details[${index}][saran_tindakan]" required></textarea>
            </div>

            <div class="form-group">
                <label>Pilih Treatment</label>
                <select class="form-control" name="details[${index}][id_treatment]">
                    <option value="">Pilih Treatment</option>
                    @foreach ($treatments as $treatment)
                        <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}</option>
                    @endforeach
                </select>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#laporanKonsultasiTable').DataTable({
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
                // sembunyikan kolom pertama (ID) dan gunakan untuk sorting
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

@push('scripts')
    <script>
        $(document).ready(function() {
            // Validasi waktu konsultasi
            $('#waktu_konsultasi').on('change', function() {
                const val = $(this).val();
                if (!val) return;

                const sel = new Date(val);
                const now = new Date();

                // Normalisasi tanggal ke 00:00:00
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime();
                const selDateOnly = new Date(sel.getFullYear(), sel.getMonth(), sel.getDate()).getTime();

                // 1) Tanggal sudah lewat
                if (selDateOnly < today) {
                    alert('Tidak bisa memilih tanggal yang sudah lewat.');
                    return $(this).val('');
                }

                // 2) Jika tanggal sama dengan hari ini, jam tidak boleh kurang dari sekarang
                if (selDateOnly === today && sel < now) {
                    alert('Tidak bisa memilih jam yang sudah lewat hari ini.');
                    return $(this).val('');
                }

                // 3) Jam harus antara 10:00–20:00
                const jam = sel.getHours();
                if (jam < 10 || jam >= 20) {
                    alert('Waktu konsultasi harus antara jam 10:00 dan 20:00.');
                    return $(this).val('');
                }
            });
        });
    </script>
@endpush
