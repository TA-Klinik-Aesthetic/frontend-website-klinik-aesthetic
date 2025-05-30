{{-- @extends('dashboard.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Update Dokter</h1>

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

    <!-- Form -->
    <form action="{{ route('konsultasi.update', $konsultasi['id_konsultasi']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id_dokter">Nama Dokter</label>
            <select class="form-control" id="id_dokter" name="id_dokter" required>
                <option value="">Pilih Dokter</option>
                @foreach ($dokters as $dokter)
                    <option value="{{ $dokter['id_dokter'] }}" 
                        {{ $dokter['id_dokter'] == $konsultasi['id_dokter'] ? 'selected' : '' }}>
                        {{ $dokter['nama_dokter'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection --}}
