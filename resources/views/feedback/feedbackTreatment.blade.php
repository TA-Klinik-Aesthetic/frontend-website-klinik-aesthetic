@extends('dashboard.index')

@section('content')
    <h1>Feedback Treatment</h1>

    <!-- Feedback List -->
    <div class="mb-4">
        <h3>Daftar Feedback</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Beautician</th>
                    <th>Treatment</th>
                    <th>Rating</th>
                    <th>Teks Feedback</th>
                    {{-- <th>Balasan Feedback</th>
                    <th>Aksi</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback['nama_dokter'] ?? '-' }}</td>
                        <td>{{ $feedback['nama_beautician'] ?? '-' }}</td>
                        <td>{{ $feedback['nama_treatment'] }}</td>  <!-- Menampilkan nama treatment -->
                        <td>{{ $feedback['rating'] }}</td>
                        <td>{{ $feedback['teks_feedback'] }}</td>
                        {{-- <td>{{ $feedback['balasan_feedback'] ?? '-' }}</td>
                        <td>
                            <a href="{{ route('feedback.feedbackTreatment.show', $feedback['id_feedback_treatment']) }}" class="btn btn-primary btn-sm">Detail</a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
