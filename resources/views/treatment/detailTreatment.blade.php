@extends('dashboard.index')

@section('content')
    <h1>Detail Treatment</h1>

    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <td>{{ $treatment['nama_treatment'] }}</td>
        </tr>
        <tr>
            <th>Jenis</th>
            <td>{{ $treatment['jenis_treatment']['nama_jenis_treatment'] }}</td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td>{{ $treatment['deskripsi_treatment'] }}</td>
        </tr>
        <tr>
            <th>Biaya</th>
            <td>Rp {{ number_format($treatment['biaya_treatment']) }}</td>
        </tr>
        <tr>
            <th>Estimasi</th>
            <td>
                @php
                    // Cek apakah estimasi ada dan valid
                    if ($treatment['estimasi_treatment']) {
                        // Gunakan Carbon untuk memformat waktu
                        $estimasi = \Carbon\Carbon::createFromFormat('H:i:s', $treatment['estimasi_treatment']);
                        $hours = $estimasi->hour;
                        $minutes = $estimasi->minute;

                        // Format estimasi dalam jam dan menit
                        $formatted_estimasi = '';
                        if ($hours > 0) {
                            $formatted_estimasi .= $hours . ' jam';
                        }
                        if ($minutes > 0) {
                            $formatted_estimasi .= ' ' . $minutes . ' menit';
                        }

                        echo $formatted_estimasi;
                    } else {
                        echo 'Tidak ada estimasi';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th>Gambar</th>
            <td>
                @if (!empty($treatment['gambar_treatment']))
                    <img src="{{ $treatment['gambar_treatment'] }}" alt="Gambar Treatment" class="img-fluid">
                @else
                    <p>Gambar tidak tersedia.</p>
                @endif
            </td>
        </tr>
    </table>
@endsection
