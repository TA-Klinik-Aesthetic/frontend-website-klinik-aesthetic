@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1>Data Pembayaran Treatment</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tombol untuk Menambah Pembayaran -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#tambahPembayaranModal"
            onclick="clearForm()">Tambah Pembayaran</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Subtotal</th>
                    <th>Metode Pembayaran</th>
                    <th>Pajak</th>
                    <th>Total</th>
                    <th>Uang</th>
                    <th>Kembalian</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaranTreatmentList as $pembayaran)
                    <tr>
                        <td>{{ $pembayaran['user_name'] }}</td>
                        <td>{{ $pembayaran['harga_akhir_treatment'] }}</td>
                        <td>{{ $pembayaran['metode_pembayaran'] }}</td>
                        <td>{{ number_format($pembayaran['pajak'], 0) }}%</td>
                        <td>{{ $pembayaran['total'] }}</td>
                        <td>{{ $pembayaran['uang'] }}</td>
                        <td>{{ $pembayaran['kembalian'] }}</td>
                        <td>{{ $pembayaran['booking_treatment']['status_pembayaran'] }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editPembayaranModal"
                                data-id="{{ $pembayaran['id_pembayaran_treatment'] }}"
                                data-id_booking_treatment="{{ $pembayaran['id_booking_treatment'] }}"
                                data-total_bayar="{{ $pembayaran['total'] }}"
                                data-metode_pembayaran="{{ $pembayaran['metode_pembayaran'] }}"
                                onclick="populateEditModal(this)">
                                Edit
                            </button>

                            <!-- Tombol Buat Invoice -->
                            <a href="{{ route('invoice.pembayaran-treatment', $pembayaran['id_pembayaran_treatment']) }}"
                                class="btn btn-info btn-sm">
                                Buat Invoice
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah Pembayaran Treatment -->
        <!-- Modal Tambah Pembayaran Treatment -->
        <div class="modal fade" id="tambahPembayaranModal" tabindex="-1" role="dialog"
            aria-labelledby="tambahPembayaranModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('pembayaran-treatment.store') }}" method="POST" id="tambahPembayaranForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahPembayaranModalLabel">Tambah Pembayaran Treatment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- ID Booking Treatment -->
                            <div class="form-group">
                                <label for="id_booking_treatment">Booking Treatment</label>
                                <select class="form-control" id="id_booking_treatment" name="id_booking_treatment" required>
                                    <option value="">Pilih User</option>
                                    @foreach ($bookingTreatments as $booking)
                                        <option value="{{ $booking['id_booking_treatment'] }}">
                                            {{ $booking['user']['nama_user'] }} - {{ $booking['waktu_treatment'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="form-group">
                                <label for="metode_pembayaran">Metode Pembayaran</label>
                                <select class="form-control" id="metode_pembayaran" name="metode_pembayaran" required>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Non Tunai">Non Tunai</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pajak">Pajak (%)</label>
                                <input type="number" class="form-control" id="pajak" name="pajak" required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Pembayaran Treatment -->
        <div class="modal fade" id="editPembayaranModal" tabindex="-1" role="dialog"
            aria-labelledby="editPembayaranModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="POST" id="editPembayaranForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPembayaranModalLabel">Edit Pembayaran Treatment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- ID Booking Treatment -->
                            <div class="form-group">
                                <label for="edit_id_booking_treatment">Booking Treatment</label>
                                <select class="form-control" id="edit_id_booking_treatment" name="id_booking_treatment"
                                    disabled>
                                    @foreach ($bookingTreatments as $booking)
                                        <option value="{{ $booking['id_booking_treatment'] }}"
                                            data-user="{{ $booking['user']['nama_user'] }}">
                                            {{ $booking['user']['nama_user'] }} - {{ $booking['waktu_treatment'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="form-group">
                                <label for="edit_metode_pembayaran">Metode Pembayaran</label>
                                <select class="form-control" id="edit_metode_pembayaran" name="metode_pembayaran" disabled>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Non Tunai">Non Tunai</option>
                                </select>
                            </div>

                            <!-- Total Bayar -->
                            <div class="form-group">
                                <label for="edit_uang">Uang</label>
                                <input type="number" class="form-control" id="edit_uang" name="uang" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function populateEditModal(button) {
            const pembayaran = button.dataset;

            document.getElementById('editPembayaranForm').action = `/pembayaran-treatment/${pembayaran.id}`;
            document.getElementById('edit_id_booking_treatment').value = pembayaran.id_booking_treatment;
            document.getElementById('edit_metode_pembayaran').value = pembayaran.metode_pembayaran;
            document.getElementById('edit_uang').value = pembayaran.uang;
        }

        function clearForm() {
            document.getElementById('tambahPembayaranForm').reset();
            document.getElementById('editPembayaranForm').action = '/pembayaran-treatment';
        }
    </script>
@endsection
