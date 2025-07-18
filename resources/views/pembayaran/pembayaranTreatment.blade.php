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

    <h1 class="h3 mb-2 text-gray-800">Pembayaran Treatment</h1>

    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}

    <!-- Tombol untuk Menambah Pembayaran -->
    {{-- <button class="btn btn-success mb-3" data-toggle="modal" data-target="#tambahPembayaranModal"
            onclick="clearForm()">Tambah Pembayaran</button> --}}

    <div class="card shadow mb-4">
        <div class="card-body">
            <table id="laporanPembayaranTreatmentTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama User</th>
                        <th>Total</th>
                        <th>Metode Pembayaran</th>
                        <th>Uang</th>
                        <th>Kembalian</th>
                        <th>Status Pembayaran</th>
                        <th>Waktu Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembayaranTreatmentList as $pembayaran)
                        <tr>
                            <td>{{ $pembayaran['user_name'] }}</td>
                            <td>Rp{{ number_format($pembayaran['harga_akhir'], 0, ',', '.') }}</td>
                            <td>{{ $pembayaran['metode_pembayaran'] }}</td>
                            <td>Rp{{ number_format($pembayaran['uang'], 0, ',', '.') }}
                            <td>Rp{{ number_format($pembayaran['kembalian'], 0, ',', '.') }}</td>
                            <td>{{ $pembayaran['status_pembayaran'] }}</td>
                            <td>{{ $pembayaran['waktu_pembayaran'] }}</td>
                            {{-- <td>{{ $pembayaran['booking_treatment']['status_pembayaran'] }}</td> --}}
                            <td>
                                <a href="{{ route('pembayaran-treatment.show', $pembayaran['id_pembayaran']) }}"
                                    class="btn btn-pale mb-3">
                                    Detail
                                </a>

                                <!-- Tombol Edit -->
                                @if ($pembayaran['status_pembayaran'] !== 'Sudah Dibayar')
                                    <button class="btn btn-pale mb-3" data-toggle="modal" data-target="#editPembayaranModal"
                                        data-id="{{ $pembayaran['id_pembayaran'] }}"
                                        data-metode="{{ $pembayaran['metode_pembayaran'] }}"
                                        data-uang="{{ $pembayaran['uang'] }}"
                                        data-harga="{{ $pembayaran['harga_akhir'] }}">
                                        Bayar
                                    </button>
                                @endif

                                @if ($pembayaran['metode_pembayaran'] === 'Non Tunai' && $pembayaran['status_pembayaran'] !== 'Sudah Dibayar')
                                    <button class="btn btn-pale mb-3 btn-confirm-treatment"
                                        data-id="{{ $pembayaran['id_pembayaran'] }}">
                                        Konfirmasi Non Tunai
                                    </button>
                                @endif

                                {{-- ► Tombol Invoice: hanya aktif kalau Sudah Dibayar --}}
                                @if (!empty($pembayaran['status_pembayaran']) && $pembayaran['status_pembayaran'] === 'Sudah Dibayar')
                                    <a href="{{ route('invoice.pembayaran-treatment', $pembayaran['id_pembayaran']) }}"
                                        class="btn btn-pale mb-3">
                                        <i class="fas fa-file-invoice"></i> Invoice
                                    </a>
                                @else
                                    <button class="btn btn-pale mb-3" disabled>
                                        Belum Dibayar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>



    <!-- Modal Edit Pembayaran Treatment -->
    <div class="modal fade" id="editPembayaranModal" tabindex="-1" role="dialog"
        aria-labelledby="editPembayaranModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editPembayaranForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPembayaranModalLabel">Edit Pembayaran Treatment</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <!-- Metode Pembayaran -->
                        <div class="form-group">
                            <label for="edit_metode_pembayaran">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="edit_metode_pembayaran" class="form-control" required>
                                <option value="Tunai">Tunai</option>
                                <option value="Non Tunai">Non Tunai</option>
                            </select>
                        </div>
                        <!-- Uang Dibayar -->
                        <div class="form-group" id="wrapper_edit_uang">
                            <label for="edit_uang">Uang</label>
                            <input type="number" step="0.01" name="uang" id="edit_uang" class="form-control">
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

    <!-- Modal Konfirmasi Non‑Tunai Treatment -->
    <div class="modal fade" id="confirmNonTunaiTreatmentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="confirmNonTunaiTreatmentForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- spoof PUT --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Bukti Pembayaran Treatment</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="gambarBuktiTreatment">Gambar Bukti Pembayaran</label>
                            <input type="file" name="gambar_bukti_pembayaran" accept=".jpg,.jpeg,.png" id="gambarBuktiTreatment"
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('editPembayaranForm');
            const selMetode = document.getElementById('edit_metode_pembayaran');
            const inpUang = document.getElementById('edit_uang');
            let hargaAkhir = 0;

            // Isi modal saat tombol Edit diklik
            $('#editPembayaranModal').on('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                const id = btn.getAttribute('data-id');
                const metode = btn.getAttribute('data-metode');
                const uang = btn.getAttribute('data-uang');
                hargaAkhir = parseFloat(btn.getAttribute('data-harga'));

                form.action = `/pembayaran-treatment/${id}`; // route update-mu
                selMetode.value = metode;
                inpUang.value = uang;
            });

            // Validasi sebelum submit
            form.addEventListener('submit', e => {
                const metode = selMetode.value;
                const uang = inpUang.value ? parseFloat(inpUang.value) : null;

                if (metode === 'Tunai') {
                    if (uang === null) {
                        alert('Harap isi kolom Uang untuk metode Tunai.');
                        e.preventDefault();
                        return;
                    }
                    if (uang < hargaAkhir) {
                        alert(
                            `Jumlah uang tidak boleh kurang dari Total (${hargaAkhir.toLocaleString('id-ID',{style:'currency',currency:'IDR'})}).`
                        );
                        e.preventDefault();
                    }
                }
            });
            (function() {
                const metodeSelect = document.getElementById('edit_metode_pembayaran');
                const wrapperUang = document.getElementById('wrapper_edit_uang');
                const inputUang = document.getElementById('edit_uang');

                function toggleUang() {
                    if (metodeSelect.value === 'Non Tunai') {
                        // disable & sembunyikan
                        inputUang.value = '';
                        inputUang.disabled = true;
                        wrapperUang.style.display = 'none';
                    } else {
                        // enable & tampilkan
                        inputUang.disabled = false;
                        wrapperUang.style.display = '';
                    }
                }

                // jalankan sekali saat modal dibuka
                $('#editPembayaranModal').on('show.bs.modal', function() {
                    toggleUang();
                });

                // jalankan tiap ganti pilihan
                metodeSelect.addEventListener('change', toggleUang);
            })();
        });
    </script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#laporanPembayaranTreatmentTable').DataTable({
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
                order: [
                    [6, 'desc'] // 6 = indeks kolom "Waktu Pembayaran"
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
        $(document).ready(function() {
            // ketika tombol “Konfirmasi” treatment (Non Tunai) diklik…
            $(document).on('click', '.btn-confirm-treatment', function() {
                const id = $(this).data('id');
                // set action form ke route Laravel
                $('#confirmNonTunaiTreatmentForm').attr(
                    'action',
                    `/pembayaran-treatment/${id}/konfirmasi`
                );
                // tampilkan modal
                $('#confirmNonTunaiTreatmentModal').modal('show');
            });
        });
    </script>
@endpush
