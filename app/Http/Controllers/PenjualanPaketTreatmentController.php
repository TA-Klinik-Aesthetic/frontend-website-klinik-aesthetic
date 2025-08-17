<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;


class PenjualanPaketTreatmentController extends Controller
{
    // === Endpoint utama (lokal) ===
    protected $apiPenjualan   = 'https://klinikneshnavya.com/api/penjualan-paket-treatment';
    protected $apiPembayaran  = 'https://klinikneshnavya.com/api/pembayaran-paket-treatment';
    protected $baseHost       = 'https://klinikneshnavya.com';

    // === Data pendukung (konsisten dgn project-mu) ===
    protected $apiUsers = 'https://klinikneshnavya.com/api/user';
    protected $apiPaket = 'https://klinikneshnavya.com/api/paket-treatment';
    protected $apiPromo = 'https://klinikneshnavya.com/api/promo';

    public function index()
    {
        // 1) Ambil semua penjualan
        $respSale  = Http::get($this->apiPenjualan);
        $rawSales  = $respSale->json() ?? [];
        $penjualan = collect(is_array($rawSales) ? $rawSales : ($rawSales['data'] ?? []));
    
        // 2) Data pendukung
        $allUsers  = collect(Http::get($this->apiUsers)->json('data') ?? []);
        $pelanggan = $allUsers->where('role', 'pelanggan')->values()->all();
        $pakets    = Http::get($this->apiPaket)->json('data') ?? [];
    
        $promos = collect(Http::get($this->apiPromo)->json('data') ?? [])
            ->where('jenis_promo', 'Treatment')
            ->where('status_promo', 'Aktif')
            ->values();
    
        // 3) Semua pembayaran paket (PASTIKAN ambil 'data' kalau ada)
        $respPays   = Http::get($this->apiPembayaran);
        $allPaysRaw = $respPays->json('data') ?? $respPays->json() ?? [];
        $allPaysCol = collect($allPaysRaw);
    
        // 4) Map baris penjualan + injeksi info pembayaran ke level atas
        $penjualanPaket = $penjualan->map(function ($p) use ($pelanggan, $promos, $allPaysCol) {
            // nama user
            $u = collect($pelanggan)->firstWhere('id_user', $p['id_user'] ?? null);
            $p['nama_user'] = $p['user']['nama_user'] ?? ($u['nama_user'] ?? 'Tidak Diketahui');
    
            // jumlah paket
            $items = $p['paket'] ?? $p['pakets'] ?? $p['details'] ?? [];
            $p['jumlah_paket'] = is_array($items) ? count($items) : 0;
    
            // promo yang dipakai (opsional)
            if (isset($p['id_promo'])) {
                $p['promo_dipakai'] = $promos->firstWhere('id_promo', $p['id_promo']) ?: null;
            }
    
            // cari pembayaran berdasar id_penjualan_paket_treatment (cast ke string biar aman)
            $saleId = (string)($p['id_penjualan_paket_treatment'] ?? $p['id'] ?? '');
            $pay = $allPaysCol->first(function ($row) use ($saleId) {
                return (string)($row['id_penjualan_paket_treatment'] ?? '') === $saleId;
            });
    
            // injeksi KE LEVEL ATAS (biar mudah dipakai di Blade)
            $p['pembayaran']                     = $pay ?: null; // ← optional: kalau mau dipakai di detail
            $p['id_pembayaran_paket_treatment']  = data_get($pay, 'id_pembayaran_paket_treatment')
                                                 ?? data_get($pay, 'id'); // fallback
            $p['status_pembayaran']              = data_get($pay, 'status_pembayaran', 'Belum Dibayar');
            $p['metode_pembayaran']              = data_get($pay, 'metode_pembayaran'); // buat tombol konfirmasi
    
            return $p;
        })
        ->sortByDesc(fn($x) => $x['id_penjualan_paket_treatment'] ?? $x['id'] ?? 0)
        ->values();
    
        return view('penjualanPaketTreatment.list', compact('penjualanPaket', 'pelanggan', 'pakets', 'promos'));
    }
    

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user'                      => 'required|integer',
            'paket'                        => 'required|array|min:1',
            'paket.*.id_paket_treatment'   => 'required|integer',
            'id_promo'                     => 'nullable|integer',
            'metode_pembayaran'            => 'required|string|in:Tunai,Non Tunai',
            'uang'                         => 'required_if:metode_pembayaran,Tunai|nullable|numeric|min:0',
        ]);

        // 1) Buat penjualan paket
        $payloadSale = [
            'id_user' => (int) $data['id_user'],
            'paket'   => array_map(fn($p) => ['id_paket_treatment' => (int) $p['id_paket_treatment']], $data['paket']),
        ];
        if (!empty($data['id_promo'])) $payloadSale['id_promo'] = (int) $data['id_promo'];

        $respSale = Http::post($this->apiPenjualan, $payloadSale);
        if (!$respSale->successful()) {
            return back()->withErrors($respSale->json('message') ?? 'Gagal menyimpan penjualan paket.')->withInput();
        }

        $saleBody = $respSale->json();
        $sale     = is_array($saleBody) ? ($saleBody['data'] ?? $saleBody) : null;
        $saleId   = $sale['id_penjualan_paket_treatment'] ?? $sale['id'] ?? null;
        if (!$saleId) {
            return back()->withErrors('Penjualan berhasil, tetapi ID tidak ditemukan.')->withInput();
        }

        // 2) Buat pembayaran (❗tanpa status_pembayaran; kirim 'uang' hanya jika Tunai)
        $payloadPay = [
            'id_penjualan_paket_treatment' => (int) $saleId,
            'metode_pembayaran'            => $data['metode_pembayaran'],
        ];
        if ($data['metode_pembayaran'] === 'Tunai') {
            $payloadPay['uang'] = (float) $data['uang'];
        }

        $respPay = Http::post($this->apiPembayaran, $payloadPay);
        if (!$respPay->successful()) {
            return redirect()->route('penjualanPaketTreatment.index')
                ->with('error', 'Penjualan tersimpan, tetapi gagal membuat pembayaran.');
        }

        return redirect()
            ->route('penjualanPaketTreatment.index')
            ->with('success', 'Penjualan paket & pembayaran berhasil disimpan. '
                . ($data['metode_pembayaran'] === 'Non Tunai' ? 'Silakan konfirmasi pembayaran non tunai.' : ''));
    }

    public function show($id)
    {
        $response = Http::get("https://klinikneshnavya.com/api/penjualan-paket-treatment/{$id}");

        if (! $response->successful()) {
            return redirect()->back()->with('error', 'Gagal mengambil data detail penjualan paket treatment.');
        }

        $penjualan = $response->json();

        // Samakan logika: jika ada gambar bukti, jadikan URL absolut ke host backend lokal
        $payKey = isset($penjualan['pembayaran_paket_treatment']) ? 'pembayaran_paket_treatment'
            : (isset($penjualan['pembayaran']) ? 'pembayaran' : null);

        if ($payKey && !empty($penjualan[$payKey]['gambar_bukti_pembayaran'] ?? null)) {
            $path = $penjualan[$payKey]['gambar_bukti_pembayaran'];

            // prefix hanya jika path masih relatif
            if (!preg_match('~^https?://~i', $path)) {
                $penjualan[$payKey]['gambar_bukti_pembayaran'] =
                    rtrim('https://klinikneshnavya.com', '/') . '/' . ltrim($path, '/');
            }
        }

        return view('penjualanPaketTreatment.detail', compact('penjualan'));
    }
    /**
     * Konfirmasi Non Tunai secara manual (PUT) — tanpa upload file.
     * Panggil ini jika status pembayaran masih menunggu.
     */
    public function confirmPayment(Request $request, $id)
    {
        // 1) Validasi: WAJIB pakai nama 'gambar_bukti_pembayaran'
        $request->validate([
            'gambar_bukti_pembayaran' => 'required|image|max:5120', // 5MB
        ]);

        $file = $request->file('gambar_bukti_pembayaran');

        // 2) Siapkan multipart client dan attach file dengan NAMA FIELD yang benar
        $http = Http::asMultipart()->acceptJson()
            ->attach(
                'gambar_bukti_pembayaran',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            );

        $endpoint = "https://klinikneshnavya.com/api/pembayaran-paket-treatment/{$id}/konfirmasi";

        // 3) Coba PUT multipart (sesuai instruksi “method_put”)
        $resp = $http->put($endpoint, []);

        // 4) Fallback: jika gagal (mis. 405), coba POST dengan spoof _method=PUT
        if (! $resp->successful()) {
            $httpFallback = Http::asMultipart()->acceptJson()
                ->attach(
                    'gambar_bukti_pembayaran',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );

            $resp = $httpFallback->post($endpoint, ['_method' => 'PUT']);
        }

        if ($resp->successful()) {
            return back()->with('success', 'Pembayaran non-tunai berhasil dikonfirmasi.');
        }

        // Tampilkan pesan error backend agar mudah dilacak
        return back()->with('error', 'Gagal konfirmasi pembayaran: ' . $resp->body());
    }

    public function generateInvoice($paymentId)
    {
        // 1) Ambil pembayaran
        $respPay = Http::get("https://klinikneshnavya.com/api/pembayaran-paket-treatment/{$paymentId}");
        if (!$respPay->successful()) {
            return response(
                "API pembayaran tidak sukses.\nHTTP {$respPay->status()}\nBody: " . $respPay->body(),
                404
            );
        }
        $dataPay = $respPay->json('data') ?? $respPay->json();
        if (empty($dataPay)) {
            return response("Struktur API pembayaran kosong/tidak sesuai untuk ID: {$paymentId}", 404);
        }
    
        // 2) Ambil penjualan
        $saleId = $dataPay['id_penjualan_paket_treatment'] ?? null;
        if (!$saleId) return response("ID penjualan tidak ada pada pembayaran ID: {$paymentId}", 404);
    
        $respSale = Http::get("https://klinikneshnavya.com/api/penjualan-paket-treatment/{$saleId}");
        if (!$respSale->successful()) {
            return response(
                "API penjualan tidak sukses.\nHTTP {$respSale->status()}\nBody: " . $respSale->body(),
                404
            );
        }
        $dataSale = $respSale->json('data') ?? $respSale->json();
        if (empty($dataSale)) {
            return response("Struktur API penjualan kosong/tidak sesuai untuk ID: {$saleId}", 404);
        }
    
        // 3) SUSUN DETAIL (tanpa kolom 'jumlah'):
        //    - Ambil dari penjualan.details (tiap baris = 1 unit)
        //    - Kelompokkan per id_paket_treatment => qty = hitungan baris
        $rawDetails = $dataSale['details']
            ?? $dataSale['detail_penjualan']
            ?? $dataSale['paket']
            ?? [];
    
        $group = [];
        foreach ($rawDetails as $d) {
            // cari info paket & harga
            $pkg   = $d['paket'] ?? $d['paket_treatment'] ?? $d;
            $id    = $d['id_paket_treatment'] ?? ($pkg['id_paket_treatment'] ?? null);
            $nama  = $pkg['nama_paket_treatment'] ?? $d['nama_paket_treatment'] ?? 'Paket';
            $harga = (int)($d['harga_paket_treatment'] ?? ($pkg['harga_paket_treatment'] ?? 0));
    
            if (!$id && $nama) {
                // fallback kalau ID tidak ada (jarang)
                $id = md5($nama);
            }
    
            if (!isset($group[$id])) {
                $group[$id] = ['nama' => $nama, 'harga' => $harga, 'qty' => 0];
            }
            $group[$id]['qty'] += 1; // TIAP BARIS = 1 UNIT
        }
        $detailPaket = array_values($group);
    
        // 4) Data invoice
        $invoiceData = [
            'user_name'         => data_get($dataSale, 'user.nama_user', data_get($dataSale, 'nama_user', '-')),
            'no_telp'           => data_get($dataSale, 'user.no_telp', '-'),
            'email'             => data_get($dataSale, 'user.email', '-'),
            'tanggal_penjualan' => $dataSale['tanggal_penjualan'] ?? $dataSale['created_at'] ?? '-',
            'waktu_pembayaran'  => $dataPay['waktu_pembayaran'] ?? '-',
            'metode_pembayaran' => $dataPay['metode_pembayaran'] ?? '-',
            'subtotal'          => (int)($dataSale['harga_total'] ?? 0),
            'potongan_harga'    => (int)($dataSale['potongan_harga'] ?? 0),
            'tipe_potongan'     => data_get($dataSale, 'promo.tipe_potongan', data_get($dataSale, 'promo_dipakai.tipe_potongan')),
            'pajak'             => (int)($dataSale['besaran_pajak'] ?? 0),
            'total'             => (int)($dataSale['harga_akhir'] ?? 0),
            'uang'              => (float)($dataPay['uang'] ?? 0),
            'kembalian'         => (float)($dataPay['kembalian'] ?? 0),
            'detail_paket'      => $detailPaket,
        ];
    
        // (opsional) preview HTML
        if (request()->boolean('preview')) {
            return view('invoice.pembayaranPaketTreatment', $invoiceData);
        }
    
        // 5) Render PDF
        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoice.pembayaranPaketTreatment', $invoiceData);
            return $pdf->download("invoice_pembayaran_paket_{$paymentId}.pdf");
        } catch (\Throwable $e) {
            return response('Gagal membuat PDF: ' . $e->getMessage(), 500);
        }
    }
}
