{{-- @extends('dashboard.index')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ isset($jadwalDokter) ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h1>

    <form action="{{ isset($jadwalDokter) ? route('jadwal-dokter.update', $jadwalDokter->id) : route('jadwal-dokter.store') }}" method="POST">
        @csrf
        @if (isset($jadwalDokter))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="id_dokter" class="form-label">ID Dokter</label>
            <input type="number" class="form-control" id="id_dokter" name="id_dokter" value="{{ $jadwalDokter->id_dokter ?? old('id_dokter') }}" required>
        </div>
        <div class="mb-3">
            <label for="hari" class="form-label">Hari</label>
            <input type="text" class="form-control" id="hari" name="hari" value="{{ $jadwalDokter->hari ?? old('hari') }}" required>
        </div>
        <div class="mb-3">
            <label for="tgl_kerja" class="form-label">Tanggal Kerja</label>
            <input type="date" class="form-control" id="tgl_kerja" name="tgl_kerja" value="{{ $jadwalDokter->tgl_kerja ?? old('tgl_kerja') }}" required>
        </div>
        <div class="mb-3">
            <label for="jam_mulai" class="form-label">Jam Mulai</label>
            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="{{ $jadwalDokter->jam_mulai ?? old('jam_mulai') }}" required>
        </div>
        <div class="mb-3">
            <label for="jam_selesai" class="form-label">Jam Selesai</label>
            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="{{ $jadwalDokter->jam_selesai ?? old('jam_selesai') }}" required>
        </div>
        <button type="submit" class="btn btn-success">{{ isset($jadwalDokter) ? 'Update' : 'Simpan' }}</button>
    </form>
</div>
@endsection --}}
