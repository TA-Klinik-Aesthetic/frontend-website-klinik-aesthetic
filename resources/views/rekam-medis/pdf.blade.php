<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekam Medis {{ $data['user']['nama_user'] }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h1,
        h2 {
            margin-bottom: .5em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1em;
        }

        th,
        td {
            border: 1px solid #333;
            padding: .4em;
        }

        th {
            background: #f0f0f0;
            text-align: left;
        }

        .no-border {
            border: none;
        }
    </style>
</head>

<body>
    <h1>Rekam Medis: {{ $data['user']['nama_user'] }}</h1>
    <table class="no-border">
        <tr>
            <td class="no-border"><strong>No. Telp:</strong> {{ $data['user']['no_telp'] }}</td>
        </tr>
        <tr>
            <td class="no-border"><strong>Email:</strong> {{ $data['user']['email'] }}</td>
        </tr>
    </table>

    <h2>Riwayat Konsultasi</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Dokter</th>
                <th>Diagnosis</th>
                <th>Saran</th>
                <th>Treatment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['konsultasi'] as $konsul)
                @if (count($konsul['detail_konsultasi']) > 0)
                    @foreach ($konsul['detail_konsultasi'] as $det)
                        <tr>
                            <td>{{ $konsul['waktu_konsultasi'] }}</td>
                            <td>{{ $konsul['dokter']['nama_dokter'] }}</td>
                            <td>{{ $det['diagnosis'] }}</td>
                            <td>{{ $det['saran_tindakan'] }}</td>
                            <td>{{ $det['treatment']['nama_treatment'] ?? '–' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $konsul['waktu_konsultasi'] }}</td>
                        <td>{{ $konsul['dokter']['nama_dokter'] }}</td>
                        <td colspan="3" style="text-align:center">— Belum ada detail —</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" style="text-align:center">Tidak ada riwayat konsultasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Riwayat Booking Treatment</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Treatment Mulai</th>
                <th>Treatment Selesai</th>
                <th>Nama Treatment</th>
                <th>Dokter</th>
                <th>Beautician</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['booking_treatment'] as $book)
                <tr>
                    <td>{{ $book['waktu_treatment'] }}</td>
                    <td>{{ $book['treatment_mulai'] ?? '-' }}</td>
                    <td>{{ $book['treatment_selesai'] ?? '-' }}</td>
                    <td>
                        @if (!empty($book['detail_booking']))
                            @foreach ($book['detail_booking'] as $d)
                                • {{ $d['treatment']['nama_treatment'] }}<br>
                            @endforeach
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $book['dokter']['nama_dokter'] ?? '–' }}</td>
                    <td>{{ $book['beautician']['nama_beautician'] }}</td>
                    <td>{{ $book['status_booking_treatment'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center">Tidak ada riwayat booking treatment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p><em>Dicetak pada {{ now()->format('Y-m-d H:i') }}</em></p>
</body>

</html>
