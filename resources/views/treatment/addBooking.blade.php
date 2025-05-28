{{-- @extends('dashboard.index')

@section('content')
<div class="container">
    <h1 class="my-4">Tambah Booking Treatment</h1>

    <form action="{{ route('booking.store') }}" method="POST">
        @csrf
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
            <input type="datetime-local" name="waktu_treatment" id="waktu_treatment" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="status_booking_treatment">Status</label>
            <select name="status_booking_treatment" id="status_booking_treatment" class="form-control" required>
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
                            <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="dokter">Dokter</label>
                    <select name="details[0][id_dokter]" class="form-control dokter">
                        <option value="">Pilih Dokter</option>
                        @foreach ($dokters as $dokter)
                            <option value="{{ $dokter['id_dokter'] }}">{{ $dokter['nama_dokter'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="beautician">Beautician</label>
                    <select name="details[0][id_beautician]" class="form-control beautician">
                        <option value="">Pilih Beautician</option>
                        @foreach ($beauticians as $beautician)
                            <option value="{{ $beautician['id_beautician'] }}">{{ $beautician['nama_beautician'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="clearfix"></div> <!-- Memastikan tombol berada di bawah dengan penggunaan clearfix -->

        <button type="button" id="addTreatment" class="btn btn-secondary">Tambah Treatment</button>
        
        <!-- Tombol simpan sekarang ada di bawah tombol tambah treatment -->
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pastikan tombol "Tambah Treatment" hanya berfungsi setelah DOM siap
        document.getElementById('addTreatment').addEventListener('click', function() {
            // Ambil elemen treatment group pertama untuk dijadikan template
            let treatmentGroup = document.querySelector('.treatment-group');
            
            // Clone elemen treatment group
            let newGroup = treatmentGroup.cloneNode(true);

            // Temukan elemen select untuk treatment, dokter, dan beautician
            let treatmentSelect = newGroup.querySelector('.treatment');
            let dokterSelect = newGroup.querySelector('.dokter');
            let beauticianSelect = newGroup.querySelector('.beautician');

            // Update nama input agar setiap kolom memiliki indeks unik
            let groupIndex = document.querySelectorAll('.treatment-group').length;
            treatmentSelect.name = `details[${groupIndex}][id_treatment]`;
            dokterSelect.name = `details[${groupIndex}][id_dokter]`;
            beauticianSelect.name = `details[${groupIndex}][id_beautician]`;

            // Masukkan elemen yang baru di-clone ke dalam container
            document.getElementById('treatmentDetails').appendChild(newGroup);
        });
    });
</script> --}}