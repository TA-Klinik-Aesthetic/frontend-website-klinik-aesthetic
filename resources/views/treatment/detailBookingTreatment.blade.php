{{-- @extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="mb-4">Detail Booking Treatment</h1>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Informasi Booking -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>Informasi Booking</h5>
                <p><strong>ID Booking:</strong> {{ $detail['booking']['id_booking_treatment'] }}</p>
                <p><strong>User ID:</strong> {{ $detail['booking']['id_user'] }}</p>
                <p><strong>Waktu Treatment:</strong>
                    {{ date('d-m-Y H:i:s', strtotime($detail['booking']['waktu_treatment'])) }}</p>
                <p><strong>Status Booking:</strong> {{ $detail['booking']['status_booking_treatment'] }}</p>
                <p><strong>Harga Total:</strong> {{ number_format($detail['booking']['harga_total'], 0, ',', '.') }}</p>
                <p><strong>Potongan Harga:</strong> {{ number_format($detail['booking']['potongan_harga'], 0, ',', '.') }}
                </p>
                <p><strong>Harga Akhir Treatment:</strong>
                    {{ number_format($detail['booking']['harga_akhir_treatment'], 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabel Detail Treatment -->
        <div class="card">
            <div class="card-header">
                <h5>Detail Treatment</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Treatment</th>
                            <th>Biaya Treatment</th>
                            <th>Dokter</th>
                            <th>Beautician</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ $detail['treatment']['nama_treatment'] ?? '-' }}</td>
                            <td>{{ number_format($detail['treatment']['biaya_treatment'], 0, ',', '.') }}</td>
                            <td>{{ $detail['dokter']['nama_dokter'] ?? '-' }}</td>
                            <td>{{ $detail['beautician']['nama_beautician'] ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#editModal">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('detailBooking.update', $detail['id_detail_booking_treatment']) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Detail Treatment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Dropdown untuk Dokter -->
                            <div class="form-group">
                                <label for="status_booking_treatment">Nama Dokter</label>
                                <select name="id_dokter" id="id_dokter" class="form-control">
                                    <option value="">Pilih Dokter</option>
                                    @foreach ($dokters as $dokter)
                                        <option value="{{ $dokter['id_dokter'] }}"
                                            @if ($dokter['id_dokter'] == $detail['id_dokter']) selected @endif>
                                            {{ $dokter['nama_dokter'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dropdown untuk Beautician -->
                            <div class="form-group">
                                <label for="status_booking_treatment">Nama Beautician</label>
                                <select name="id_beautician" id="id_beautician" class="form-control">
                                    <option value="">Pilih Beautician</option>
                                    @foreach ($beauticians as $beautician)
                                        <option value="{{ $beautician['id_beautician'] }}"
                                            @if ($beautician['id_beautician'] == $detail['id_beautician']) selected @endif>
                                            {{ $beautician['nama_beautician'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Booking -->
                            <div class="form-group">
                                <label for="status_booking_treatment">Status Booking</label>
                                <select name="status_booking_treatment" id="status_booking_treatment" class="form-control"
                                    required>
                                    <option value="Verifikasi" @if ($detail['booking']['status_booking_treatment'] == 'Verifikasi') selected @endif>Verifikasi
                                    </option>
                                    <option value="Berhasil dibooking" @if ($detail['booking']['status_booking_treatment'] == 'Berhasil dibooking') selected @endif>
                                        Berhasil dibooking</option>
                                    <option value="Dibatalkan" @if ($detail['booking']['status_booking_treatment'] == 'Dibatalkan') selected @endif>Dibatalkan
                                    </option>
                                    <option value="Selesai" @if ($detail['booking']['status_booking_treatment'] == 'Selesai') selected @endif>Selesai
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection --}}
