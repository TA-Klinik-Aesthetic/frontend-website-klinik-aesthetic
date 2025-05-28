{{-- @extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Edit Keluhan dan Saran Tindakan</h1>

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

    <h5 class="mb-3">Detail Konsultasi Saat Ini</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Keluhan Pelanggan</th>
                <th>Saran Tindakan</th>
                <th>Nama Treatment</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $detail)
                <tr>
                    <td>{{ $detail['keluhan_pelanggan'] }}</td>
                    <td>{{ $detail['saran_tindakan'] }}</td>
                    <td>{{ $detail['treatment']['nama_treatment'] ?? 'Tidak ada treatment' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Belum ada detail konsultasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h5 class="mt-4">Tambahkan Detail Konsultasi Baru</h5>
    <form action="{{ route('konsultasi.updateKeluhan', $id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="keluhan_pelanggan">Keluhan Pelanggan</label>
            <textarea class="form-control" id="keluhan_pelanggan" name="keluhan_pelanggan" required></textarea>
        </div>

        <div class="form-group">
            <label for="saran_tindakan">Saran Tindakan</label>
            <textarea class="form-control" id="saran_tindakan" name="saran_tindakan" required></textarea>
        </div>

        <div class="form-group">
            <label for="id_treatment">Pilih Treatment</label>
            <select class="form-control" id="id_treatment" name="id_treatment" required>
                <option value="">-- Pilih Treatment --</option>
                @foreach ($treatments as $treatment)
                    <option value="{{ $treatment['id_treatment'] }}">{{ $treatment['nama_treatment'] }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Tambahkan</button>
    </form>
@endsection --}}
