@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="my-4">Booking Treatment List</h1>
        <!-- Tombol trigger modal -->
        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#bookingModal">
            <i class="fas fa-plus"></i> Tambah Booking
        </button>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama User</th>
                        <th>Waktu Treatment</th>
                        <th>Status Booking</th>
                        <th>Harga Total</th>
                        <th>Potongan Harga</th>
                        <th>Harga Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookingTreatments as $booking)
                        <tr>
                            <td>{{ $booking['user_name'] }}</td>
                            <td>{{ $booking['waktu_treatment'] }}</td>
                            <td>{{ $booking['status_booking_treatment'] }}</td>
                            <td>{{ $booking['harga_total'] }}</td>
                            <td>{{ $booking['potongan_harga'] }}</td>
                            <td>{{ $booking['harga_akhir_treatment'] }}</td>
                            <td>
                                <a href="{{ route('booking.detail', $booking['id_booking_treatment']) }}"
                                    class="btn btn-info">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Modal Tambah Booking -->
            <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
                aria-hidden="true">
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
                                    <label for="user">User</label>
                                    <select name="id_user" id="user" class="form-control" required>
                                        <option value="">Pilih User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user['id_user'] }}">{{ $user['nama_user'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="waktu_treatment">Waktu Treatment</label>
                                    <input type="datetime-local" name="waktu_treatment" id="waktu_treatment"
                                        class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="status_booking_treatment">Status</label>
                                    <select name="status_booking_treatment" id="status_booking_treatment"
                                        class="form-control" required>
                                        <option value="Verifikasi">Verifikasi</option>
                                        <option value="Berhasil dibooking">Berhasil dibooking</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="promo">Promo (Opsional)</label>
                                    <select name="id_promo" id="promo" class="form-control">
                                        <option value="">Pilih Promo</option>
                                        @foreach ($promos as $promo)
                                            <option value="{{ $promo['id_promo'] }}">{{ $promo['nama_promo'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="treatmentDetails">
                                    <!-- Kolom treatment pertama -->
                                    <div class="treatment-group">
                                        <div class="form-group">
                                            <label for="treatment">Treatment</label>
                                            <select name="details[0][id_treatment]" class="form-control treatment" required>
                                                <option value="">Pilih Treatment</option>
                                                @foreach ($treatments as $treatment)
                                                    <option value="{{ $treatment['id_treatment'] }}">
                                                        {{ $treatment['nama_treatment'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="dokter">Dokter</label>
                                            <select name="details[0][id_dokter]" class="form-control dokter">
                                                <option value="">Pilih Dokter</option>
                                                @foreach ($dokters as $dokter)
                                                    <option value="{{ $dokter['id_dokter'] }}">
                                                        {{ $dokter['nama_dokter'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="beautician">Beautician</label>
                                            <select name="details[0][id_beautician]" class="form-control beautician">
                                                <option value="">Pilih Beautician</option>
                                                @foreach ($beauticians as $beautician)
                                                    <option value="{{ $beautician['id_beautician'] }}">
                                                        {{ $beautician['nama_beautician'] }}</option>
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
                                        <button type="button" class="btn btn-success mt-2 addTreatmentGroup">+ Tambah
                                            Treatment</button>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const kompensasis = @json($kompensasis);

        function initializeSelect2(container) {
            $(container).find('.select2').each(function() {
                const parentGroup = $(this).closest('.treatment-group');

                $(this).select2({
                    dropdownParent: parentGroup,
                    width: '100%'
                });
            });
        }

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

        function addTreatmentGroup() {
            const container = document.getElementById('treatmentDetails');

            const html = `
    <div class="treatment-group">
        <div class="form-group">
            <label>Treatment</label>
            <select name="details[${treatmentIndex}][id_treatment]" class="form-control treatment" required>
                <option value="">Pilih Treatment</option>
                @foreach ($treatments as $treatment)
                    <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Dokter</label>
            <select name="details[${treatmentIndex}][id_dokter]" class="form-control dokter">
                <option value="">Pilih Dokter</option>
                @foreach ($dokters as $dokter)
                    <option value="{{ $dokter['id_dokter'] }}">{{ $dokter['nama_dokter'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Beautician</label>
            <select name="details[${treatmentIndex}][id_beautician]" class="form-control beautician">
                <option value="">Pilih Beautician</option>
                @foreach ($beauticians as $beautician)
                    <option value="{{ $beautician['id_beautician'] }}">{{ $beautician['nama_beautician'] }}</option>
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

        <button type="button" class="btn btn-success mt-2 addTreatmentGroup">+ Tambah Treatment</button>
    </div>
    `;

            // Hapus tombol tambah dari group sebelumnya
            const groups = container.querySelectorAll('.treatment-group');
            const lastGroup = groups[groups.length - 1];
            const oldBtn = lastGroup.querySelector('.addTreatmentGroup');
            if (oldBtn) oldBtn.remove();

            // Tambahkan group baru
            container.insertAdjacentHTML('beforeend', html);

            // Reinisialisasi Select2
            initializeSelect2(container.lastElementChild);

            treatmentIndex++;
        }


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
                if (allTreatmentUseKompensasi && promoVal) {
                    alert(
                    'Promo tidak bisa digunakan jika seluruh treatment sudah menggunakan kompensasi.');
                    isValid = false;
                }

                if (!isValid) e.preventDefault();
            });
        });
    </script>
@endpush
