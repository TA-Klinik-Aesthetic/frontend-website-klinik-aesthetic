@extends('dashboard.index')

@section('content')
<div class="container">
    <h1 class="my-4">Booking Treatment List</h1>
    <a href="{{ route('booking.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Tambah Booking
    </a>

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
                        <a href="{{ route('booking.detail', $booking['id_booking_treatment']) }}" class="btn btn-info">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
