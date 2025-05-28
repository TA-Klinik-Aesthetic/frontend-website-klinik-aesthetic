@extends('dashboard.index')

@section('content')
    <h1>Detail Feedback Konsultasi</h1>

    <form action="{{ route('feedback.feedbackKonsultasi.update', $feedback['id_feedback_konsultasi']) }}" method="POST">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label for="rating">Rating</label>
            <input type="number" id="rating" class="form-control" value="{{ $feedback['rating'] }}" min="1" max="5" disabled>
            <input type="hidden" name="rating" value="{{ $feedback['rating'] }}">
        </div>
        
        <div class="form-group">
            <label for="teks_feedback">Teks Feedback</label>
            <textarea id="teks_feedback" class="form-control" disabled>{{ $feedback['teks_feedback'] }}</textarea>
            <input type="hidden" name="teks_feedback" value="{{ $feedback['teks_feedback'] }}">
        </div>
        
        <div class="form-group">
            <label for="balasan_feedback">Balasan Feedback</label>
            <textarea name="balasan_feedback" id="balasan_feedback" class="form-control" required>{{ $feedback['balasan_feedback'] }}</textarea>
        </div>
    
        <button type="submit" class="btn btn-success">Kirim</button>
    </form>
    
    
@endsection
