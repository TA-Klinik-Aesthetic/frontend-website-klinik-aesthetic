@extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Detail Konsultasi</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('konsultasi.simpanDetail', $id) }}" method="POST" id="detail-form">
        @csrf

        <div id="detail-container">
            <!-- Detail Konsultasi awal -->
            {{-- <div class="detail-item form-group">
                <label for="keluhan_pelanggan_1">Keluhan Pelanggan</label>
                <textarea class="form-control" name="details[0][keluhan_pelanggan]" required></textarea>
            </div> --}}

            <div class="detail-item form-group">
                <label for="saran_tindakan_1">Saran Tindakan</label>
                <textarea class="form-control" name="details[0][saran_tindakan]" required></textarea>
            </div>

            <div class="detail-item form-group">
                <label for="id_treatment_1">Pilih Treatment</label>
                <select class="form-control" name="details[0][id_treatment]">
                    <option value="">Pilih Treatment</option>
                    @foreach ($treatments as $treatment)
                        <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tombol untuk menambah detail -->
        <button type="button" class="btn btn-info" id="add-detail-btn">Tambah Detail Konsultasi</button>

        <br><br>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>

    <script>
        document.getElementById('add-detail-btn').addEventListener('click', function() {
            // Dapatkan jumlah detail yang sudah ada
            var detailCount = document.querySelectorAll('.detail-item').length / 3; // 3 form per detail

            // Buat elemen baru untuk detail konsultasi
            var newDetailHTML = `
                <div class="detail-item form-group">
                    <label for="saran_tindakan_${detailCount + 1}">Saran Tindakan</label>
                    <textarea class="form-control" name="details[${detailCount}][saran_tindakan]" required></textarea>
                </div>
                <div class="detail-item form-group">
                    <label for="id_treatment_${detailCount + 1}">Pilih Treatment</label>
                    <select class="form-control" name="details[${detailCount}][id_treatment]">
                        <option value="">Pilih Treatment</option>
                        @foreach ($treatments as $treatment)
                            <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}</option>
                        @endforeach
                    </select>
                </div>
            `;

            // Tambahkan detail baru ke form
            var detailContainer = document.getElementById('detail-container');
            detailContainer.insertAdjacentHTML('beforeend', newDetailHTML);
        });
    </script>
        {{-- <div class="detail-item form-group">
            <label for="keluhan_pelanggan_${detailCount + 1}">Keluhan Pelanggan</label>
            <textarea class="form-control" name="details[${detailCount}][keluhan_pelanggan]" required></textarea>
        </div> --}}
@endsection
