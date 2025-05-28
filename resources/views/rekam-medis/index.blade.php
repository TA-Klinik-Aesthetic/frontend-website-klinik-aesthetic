@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1>Rekam Medis - Data Konsultasi dan Booking Treatment</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tabel Rekam Medis -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Total Konsultasi</th>
                    <th>Total Booking Treatment</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekamMedisData as $data)
                    <tr>
                        <td>{{ $data['user']['nama_user'] }}</td>
                        <td>{{ $data['total_konsultasi'] }}</td>
                        <td>{{ $data['total_booking_treatment'] }}</td>
                        <td>
                            <!-- Tombol Detail -->
                            <a href="{{ route('rekam-medis.detail', $data['user']['id_user']) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
