@extends('dashboard.index')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-gray-800">Detail Penjualan Paket Treatment</h1>

        @php
            // --- Helper aman untuk ambil nilai ---
            $get = fn($arr, $path, $default = null) => data_get($arr, $path, $default);

            // Root objek
            $sale = $penjualan ?? [];

            // Informasi umum
            $namaUser = $get($sale, 'user.nama_user', $get($sale, 'nama_user', '-'));
            $tanggal  = $get($sale, 'tanggal_penjualan', $get($sale, 'created_at', '-'));

            $subtotal = (int) $get($sale, 'harga_total', 0);
            $potongan = $get($sale, 'potongan_harga', 0);
            $pajak    = (int) $get($sale, 'besaran_pajak', 0);
            $total    = (int) $get($sale, 'harga_akhir', 0);

            // Promo (opsional)
            $promo   = $get($sale, 'promo') ?? $get($sale, 'promo_dipakai');
            $tipePot = $get($promo, 'tipe_potongan');
            $namaPro = $get($promo, 'nama_promo');

            // Detail baris paket — dukung beberapa bentuk payload
            $rawDetails = $get($sale, 'detail_penjualan', $get($sale, 'paket', $get($sale, 'details', [])));

            // Agregasi per-paket (kalau backend mengirim 1 baris per unit, kita gabungkan jadi qty)
            $rows = [];
            foreach ($rawDetails as $d) {
                // Ambil objek paket di berbagai kemungkinan struktur
                $pkg   = $d['paket'] ?? $d['paket_treatment'] ?? $d;
                $id    = $pkg['id_paket_treatment'] ?? $d['id_paket_treatment'] ?? $d['id'] ?? null;
                $nama  = $pkg['nama_paket_treatment'] ?? $d['nama_paket_treatment'] ?? 'Paket';
                $harga = (int) ($d['harga_jual'] ?? $pkg['harga_paket_treatment'] ?? $d['harga_paket_treatment'] ?? 0);
                $qty   = (int) ($d['jumlah'] ?? 1);

                $key = $id ?: $nama;
                if (! isset($rows[$key])) {
                    $rows[$key] = ['nama' => $nama, 'harga' => $harga, 'qty' => 0];
                }
                $rows[$key]['qty'] += max($qty, 1);
            }

            // Pembayaran
            $pay = $get($sale, 'pembayaran_paket_treatment') ?? $get($sale, 'pembayaran');
            $metode   = $get($pay, 'metode_pembayaran', '-');
            $uang     = (float) $get($pay, 'uang', 0);
            $kemb     = (float) $get($pay, 'kembalian', 0);
            $status   = $get($pay, 'status_pembayaran', '-');
            $waktuBay = $get($pay, 'waktu_pembayaran', '-');

            // Bukti pembayaran — jika controller belum mem-absolute-kan URL, tetap tampilkan apa adanya
            $bukti = $get($pay, 'gambar_bukti_pembayaran');
        @endphp

        <div class="row">
            {{-- Kiri: Informasi Penjualan + Detail Paket --}}
            <div class="col-lg-8">
                {{-- Informasi Umum --}}
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5>Informasi Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama Pelanggan:</strong> {{ $namaUser }}</p>
                        <p><strong>Tanggal Penjualan:</strong> {{ $tanggal }}</p>

                        <p><strong>Subtotal:</strong> Rp{{ number_format($subtotal, 0, ',', '.') }}</p>

                        <p>
                            <strong>Potongan Harga:</strong>
                            @if ($promo)
                                @if (strtolower($tipePot ?? '') === 'diskon')
                                    {{-- Potongan disimpan backend sebagai nominal (%), tampilkan % --}}
                                    {{ (int) $potongan }}%
                                    <small class="text-muted d-block">Promo: {{ $namaPro }}</small>
                                @else
                                    Rp{{ number_format((int) $potongan, 0, ',', '.') }}
                                    <small class="text-muted d-block">Promo: {{ $namaPro }}</small>
                                @endif
                            @else
                                -
                            @endif
                        </p>

                        <p><strong>Besaran Pajak (10%):</strong> Rp{{ number_format($pajak, 0, ',', '.') }}</p>
                        <p><strong>Total:</strong> Rp{{ number_format($total, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Detail Paket --}}
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5>Detail Paket</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <colgroup>
                                <col style="width:50%;"> {{-- Nama Paket --}}
                                <col style="width:10%;"> {{-- Jumlah --}}
                                <col style="width:20%;"> {{-- Harga Satuan --}}
                                <col style="width:20%;"> {{-- Subtotal --}}
                            </colgroup>
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Paket</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $r)
                                    <tr>
                                        <td>{{ $r['nama'] }}</td>
                                        <td>{{ $r['qty'] }}</td>
                                        <td>Rp{{ number_format($r['harga'], 0, ',', '.') }}</td>
                                        <td>Rp{{ number_format($r['harga'] * $r['qty'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada detail paket.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kanan: Informasi Pembayaran --}}
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5>Informasi Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        @if ($pay)
                            <p><strong>Metode:</strong> {{ $metode }}</p>
                            <p><strong>Uang Bayar:</strong> Rp{{ number_format($uang, 0, ',', '.') }}</p>
                            <p><strong>Kembalian:</strong> Rp{{ number_format($kemb, 0, ',', '.') }}</p>
                            <p><strong>Status:</strong> {{ $status }}</p>
                            <p><strong>Waktu Bayar:</strong> {{ $waktuBay }}</p>

                            @if (!empty($bukti))
                                <p><strong>Bukti Pembayaran:</strong></p>
                                <div class="text-center mb-3">
                                    <a href="{{ $bukti }}" target="_blank" rel="noopener">
                                        <img src="{{ $bukti }}" alt="Bukti Pembayaran" class="img-fluid"
                                            style="max-width:100%; height:auto; border:1px solid #ddd; padding:4px;">
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning mb-0">
                                Belum ada pembayaran untuk penjualan ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
