@extends('dashboard.index')

@section('content')
    <div class="container">
        <h2>Detail Treatment</h2>

        <div class="card">
            <div class="card-body">
                <!-- Judul Treatment -->
                <h5 class="card-title">{{ $treatment['nama_treatment'] }}</h5>

                <!-- Jenis Treatment -->
                <p class="card-text">
                    <strong>Jenis:</strong>
                    {{ $treatment['jenis_treatment']['nama_jenis_treatment'] }}
                </p>

                <!-- Deskripsi -->
                <p class="card-text">
                    <strong>Deskripsi:</strong>
                    {{ $treatment['deskripsi_treatment'] }}
                </p>

                <!-- Biaya -->
                <p class="card-text">
                    <strong>Biaya:</strong>
                    Rp{{ number_format($treatment['biaya_treatment'], 0, ',', '.') }}
                </p>

                <!-- Estimasi -->
                <p class="card-text">
                    <strong>Estimasi:</strong>
                    @php
                        if ($treatment['estimasi_treatment']) {
                            $estimasi = \Carbon\Carbon::createFromFormat('H:i:s', $treatment['estimasi_treatment']);
                            $hours   = $estimasi->hour;
                            $minutes = $estimasi->minute;
                            $fmt     = '';
                            if ($hours > 0)   $fmt .= $hours . ' jam';
                            if ($minutes > 0) $fmt .= ' ' . $minutes . ' menit';
                            echo trim($fmt);
                        } else {
                            echo 'Tidak ada estimasi';
                        }
                    @endphp
                </p>

                <!-- Gambar Treatment -->
                <div class="text-center my-3">
                    @if (!empty($treatment['gambar_treatment']))
                        <img
                          src="{{ $treatment['gambar_treatment'] }}"
                          alt="{{ $treatment['nama_treatment'] }}"
                          class="img-fluid mx-auto d-block"
                          style="max-width: 400px; height: auto;"
                        >
                    @else
                        <p>Gambar tidak tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
