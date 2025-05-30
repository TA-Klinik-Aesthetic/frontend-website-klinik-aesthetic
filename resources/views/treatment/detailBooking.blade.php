@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="my-4">Detail Booking Treatment</h1>

        <div class="card mb-3">
            <div class="card-header">
                <h4>Informasi Booking</h4>
            </div>
            <div class="card-body">
                <p><strong>Nama Pelanggan:</strong> {{ $bookingDetail['booking_treatment']['user']['nama_user'] }}</p>
                <p><strong>Waktu Treatment:</strong> {{ $bookingDetail['booking_treatment']['waktu_treatment'] }}</p>
                <p><strong>Dokter:</strong>
                    @php
                        $dokter = collect($dokters)->firstWhere('id_dokter', $bookingDetail['booking_treatment']['id_dokter']);
                    @endphp
                    {{ $dokter ? $dokter['nama_dokter'] : 'Tidak ada Dokter' }}
                </p>
                <p><strong>Beautician:</strong>
                    @php
                        $beautician = collect($beauticians)->firstWhere('id_beautician', $bookingDetail['booking_treatment']['id_beautician']);
                    @endphp
                    {{ $beautician ? $beautician['nama_beautician'] : 'Tidak ada Beautician' }}
                </p>
                <p><strong>Status Booking:</strong> {{ $bookingDetail['booking_treatment']['status_booking_treatment'] }}
                </p>
                <p><strong>Harga Total:</strong> {{ $bookingDetail['booking_treatment']['harga_total'] }}</p>
                <p><strong>Potongan Harga:</strong> {{ $bookingDetail['booking_treatment']['potongan_harga'] }}</p>
                <p><strong>Harga Akhir:</strong> {{ $bookingDetail['booking_treatment']['harga_akhir_treatment'] }}</p>

                <h5>Detail Treatment:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Treatment</th>
                            <th>Biaya Treatment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookingDetail['booking_treatment']['detail_booking'] as $detail)
                            <tr>
                                <td>
                                    @php
                                        $treatment = collect($treatments)->firstWhere(
                                            'id_treatment',
                                            $detail['id_treatment'],
                                        );
                                    @endphp
                                    {{ $treatment ? $treatment['nama_treatment'] : 'Treatment tidak ditemukan' }}
                                </td>
                                <td>{{ $detail['biaya_treatment'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- @foreach ($bookingDetail['booking_treatment']['detail_booking'] as $detail)
    <div class="modal fade" id="editModal{{ $detail['id_detail_booking_treatment'] }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $detail['id_detail_booking_treatment'] }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('detailBooking.update', $detail['id_detail_booking_treatment']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $detail['id_detail_booking_treatment'] }}">Edit Detail Treatment</h5>
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
                                    <option value="{{ $dokter['id_dokter'] }}" @if ($dokter['id_dokter'] == $detail['id_dokter']) selected @endif>
                                        {{ $dokter['nama_dokter'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown untuk Beautician -->
                        <div class="form-group">
                            <label for="id_beautician">Nama Beautician</label>
                            <select name="id_beautician" id="id_beautician" class="form-control">
                                <option value="">Pilih Beautician</option>
                                @foreach ($beauticians as $beautician)
                                    <option value="{{ $beautician['id_beautician'] }}" @if ($beautician['id_beautician'] == $detail['id_beautician']) selected @endif>
                                        {{ $beautician['nama_beautician'] }}
                                    </option>
                                @endforeach
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
@endforeach

@endsection

@section('scripts')
    <script>
        document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
            console.log('Tombol ditemukan:', button);
            console.log('Data Target:', button.getAttribute('data-target'));
        });

        document.querySelectorAll('.modal').forEach(modal => {
            console.log('Modal ditemukan:', modal);
            console.log('Modal ID:', modal.id);
        });
    </script> --}}
@endsection
