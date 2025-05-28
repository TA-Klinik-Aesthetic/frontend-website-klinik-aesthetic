@extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Add Booking</h1>

    <!-- Flash Messages -->
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

    <form action="{{ route('konsultasi.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="id_user">Nama Pelanggan</label>
            <select class="form-control" id="id_user" name="id_user" required>
                <option value="">Pilih Pelanggan</option>
                @foreach ($users as $user)
                    <option value="{{ $user['id_user'] }}">{{ $user['nama_user'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="waktu_konsultasi">Waktu Konsultasi</label>
            <input type="datetime-local" class="form-control" id="waktu_konsultasi" name="waktu_konsultasi" required>
        </div>
        <div class="form-group">
            <label for="id_dokter">Nama Dokter</label>
            <select class="form-control" id="id_dokter" name="id_dokter">
                <option value="">Pilih Dokter</option>
                @foreach ($dokters as $dokter)
                    <option value="{{ $dokter['id_dokter'] }}">{{ $dokter['nama_dokter'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="keluhan_pelanggan">Keluhan Pelanggan</label>
            <textarea class="form-control" id="keluhan_pelanggan" name="keluhan_pelanggan" required></textarea>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('konsultasi.with-doctor') }}" class="btn btn-primary">Kembali</a>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
@endsection
