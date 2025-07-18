@extends('dashboard.index')

@section('content')
    <div class="container">
        <h2>Detail Promo</h2>

        <div class="card">
            <div class="card-body">
                <!-- Judul Promo -->
                <h5 class="card-title">{{ $promo['nama_promo'] }}</h5>

                <!-- Jenis Promo -->
                <p class="card-text">
                    <strong>Jenis Promo:</strong>
                    {{ $promo['jenis_promo'] }}
                </p>

                <!-- Deskripsi -->
                <p class="card-text">
                    <strong>Deskripsi:</strong>
                    {{ $promo['deskripsi_promo'] }}
                </p>

                <!-- Tipe & Potongan -->
                <p class="card-text">
                    <strong>Potongan:</strong>
                    @if ($promo['tipe_potongan'] === 'Diskon')
                        {{ number_format($promo['potongan_harga']) }}%
                    @else
                        Rp{{ number_format($promo['potongan_harga'], 0, ',', '.') }}
                    @endif
                    <small class="text-muted">({{ $promo['tipe_potongan'] }})</small>
                </p>

                <!-- Minimal Belanja -->
                <p class="card-text">
                    <strong>Minimal Belanja:</strong>
                    Rp{{ number_format($promo['minimal_belanja'], 0, ',', '.') }}
                </p>

                <!-- Tanggal Mulai & Berakhir -->
                <p class="card-text">
                    <strong>Periode:</strong>
                    {{ \Carbon\Carbon::parse($promo['tanggal_mulai'])->format('d M Y') }}
                    &mdash;
                    {{ \Carbon\Carbon::parse($promo['tanggal_berakhir'])->format('d M Y') }}
                </p>

                <!-- Status -->
                <p class="card-text">
                    <strong>Status:</strong>
                    {{ ucfirst($promo['status_promo']) }}
                </p>

                <!-- Gambar Promo -->
                <div class="text-center my-3">
                    @if (!empty($promo['gambar_promo']))
                        <img
                          src="{{ $promo['gambar_promo'] }}"
                          alt="{{ $promo['nama_promo'] }}"
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
