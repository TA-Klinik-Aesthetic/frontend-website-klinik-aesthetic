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
        {{-- <button class="btn btn-success mb-3" data-toggle="modal" data-target="#tambahPembayaranModal"
            onclick="clearForm()">Tambah Pembayaran</button> --}}

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Total</th>
                    <th>Metode Pembayaran</th>
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
                        <td>Rp{{ number_format($pembayaran['harga_akhir'], 0, ',', '.') }}</td>
                        <td>{{ $pembayaran['metode_pembayaran'] }}</td>
                        <td>{{ $pembayaran['uang'] }}</td>
                        <td>{{ $pembayaran['kembalian'] }}</td>
                        <td>{{ $pembayaran['status_pembayaran'] }}</td>
                        <td>{{ $pembayaran['waktu_pembayaran'] }}</td>
                        {{-- <td>{{ $pembayaran['booking_treatment']['status_pembayaran'] }}</td> --}}
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPembayaranModal"
                                data-id="{{ $pembayaran['id_pembayaran'] }}"
                                data-metode="{{ $pembayaran['metode_pembayaran'] }}" data-uang="{{ $pembayaran['uang'] }}">
                                Edit
                            </button>

                            <!-- Tombol Buat Invoice -->
                            <a href="{{ route('invoice.pembayaran-treatment', $pembayaran['id_pembayaran']) }}"
                                class="btn btn-info btn-sm">
                                Buat Invoice
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah Pembayaran Treatment -->
        {{-- <!-- Modal Tambah Pembayaran Treatment -->
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
        </div> --}}

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
                            <div class="form-group">
                                <label for="edit_uang">Uang</label>
                                <input type="number" step="0.01" name="uang" id="edit_uang" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
        function populateEditModal(button) {
            const id = button.getAttribute('data-id');
            const metode = button.getAttribute('data-metode');
            const uang = button.getAttribute('data-uang');

            const form = document.getElementById('editPembayaranForm');
            form.action = `/pembayaran-treatment/${id}`; // sesuaikan route-mu
            document.getElementById('edit_metode_pembayaran').value = metode;
            document.getElementById('edit_uang').value = uang;
        }

        // Pasang event listener ke tombol Edit
        document.querySelectorAll('button[data-target="#editPembayaranModal"]').forEach(btn => {
            btn.addEventListener('click', () => populateEditModal(btn));
        });
    </script>
@endpush
