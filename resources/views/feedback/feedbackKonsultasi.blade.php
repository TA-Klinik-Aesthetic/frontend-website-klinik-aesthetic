@extends('dashboard.index')

@section('content')
    <h1>Feedback Konsultasi</h1>

    <!-- Feedback List -->
    <div class="mb-4">
        <h3>Daftar Feedback</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Rating</th>
                    <th>Teks Feedback</th>
                    <th>Balasan Feedback</th> <!-- Tambahkan kolom ini -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback['nama_dokter'] }}</td>
                        <td>{{ $feedback['rating'] }}</td>
                        <td>{{ $feedback['teks_feedback'] }}</td>
                        <td>{{ $feedback['balasan_feedback'] ?? '-' }}</td> <!-- Tampilkan balasan feedback -->
                        <td>
                            <a href="{{ route('feedback.feedbackKonsultasi.show', $feedback['id_feedback_konsultasi']) }}" class="btn btn-primary btn-sm">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
