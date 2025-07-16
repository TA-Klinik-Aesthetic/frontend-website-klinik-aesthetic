<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianProdukController extends Controller
{
    public function index()
    {
        // 1) Ambil semua penjualan produk (API mereturn array)
        $respSale    = Http::get('https://klinikneshnavya.com/api/penjualan-produk');
        $rawSales    = $respSale->json();          // <-- gunakan ini saja
        $pembelian   = collect($rawSales);

        // 2) Data pendukung
        $categories = Http::get('https://klinikneshnavya.com/api/kategori')->json();
        $allUsers   = collect(Http::get('https://klinikneshnavya.com/api/user')->json('data') ?? []);
    
        // **Filter hanya pelanggan**
        $pelanggan  = $allUsers
            ->where('role', 'pelanggan')
            ->values()
            ->all();


        $products = Http::get('https://klinikneshnavya.com/api/produk')->json('data') ?? [];
        $promos   = collect(Http::get('https://klinikneshnavya.com/api/promo')
            ->json('data') ?? [])
            ->where('jenis_promo', 'Produk')
            ->where('status_promo', 'Aktif')
            ->values();

        // 3) Semua pembayaran
        $allPays = collect(Http::get('https://klinikneshnavya.com/api/pembayaran-produk')
            ->json() ?? []);

        // 4) Map: tambahkan nama_user, daftar produk, promo dan id_pembayaran
        $pembelianProduk = $pembelian->map(function ($p) use ($pelanggan, $products, $promos, $allPays) {
            // nama user
            $u = collect($pelanggan)->firstWhere('id_user', $p['id_user']);
            $p['nama_user'] = $u['nama_user'] ?? 'Tidak Diketahui';

            // list produk (modal edit)
            $p['produk'] = collect($p['detail_pembelian'] ?? [])
                ->map(fn($d) => [
                    'id_produk' => $d['id_produk'],
                    'jumlah_produk' => $d['jumlah_produk'],
                ])->toArray();

            // promo
            $p['promo_dipakai'] = $promos->firstWhere('id_promo', $p['id_promo']);

            // id_pembayaran yang cocok
            $pay = $allPays->firstWhere('id_penjualan_produk', $p['id_penjualan_produk']);
            $p['id_pembayaran'] = data_get($pay, 'id_pembayaran');
            $p['status_pembayaran'] = data_get($pay, 'status_pembayaran', 'Belum Dibayar');
            return $p;
        });

        return view(
            'pembelian-produk.pembelian',
            compact('pembelianProduk', 'pelanggan', 'products', 'promos', 'categories')
        );
    }




    // public function create()
    // {
    //     // Fetch users
    //     $userResponse = Http::get('https://klinikneshnavya.com/api/users');
    //     $users = $userResponse->json()['data'];

    //     return view('pembelian-produk.createPembelian', compact('users', 'products', 'promos'));
    // }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer',
            'produk' => 'required|array',
            'produk.*.id_produk' => 'required|integer',
            'produk.*.jumlah_produk' => 'required|integer',
            'id_promo' => 'nullable|integer',
            // baru:
            'metode_pembayaran'        => 'required|string|in:Tunai,Non Tunai',
            'uang'              => 'required_if:metode_pembayaran,Tunai|nullable|numeric|min:0',
        ]);

        // 1) Buat penjualan
        $resp = Http::post('https://klinikneshnavya.com/api/penjualan-produk/kasir', $data);

        if (! $resp->successful()) {
            return back()->withErrors('Gagal menyimpan penjualan.');
        }

        // ambil ID penjualan yang baru
        $penjId = $resp->json('data.id_penjualan_produk');

        // 2) Tentukan $uang: null kalau non tunai
        $uang = $data['metode_pembayaran'] === 'Tunai'
            ? $data['uang']
            : null;

        // 3) Buat pembayaran
        $payResp = Http::post('https://klinikneshnavya.com/api/pembayaran-produk', [
            'id_penjualan_produk' => $penjId,
            'metode_pembayaran'   => $data['metode_pembayaran'],
            'uang'                => $uang,
        ]);

        if (! $payResp->successful()) {
            // rollback di backend? minimal beri tahu user
            return back()->withErrors('Penjualan tersimpan, tapi gagal membuat pembayaran.');
        }

        return redirect()
            ->route('pembelianProduk.index')
            ->with('success', 'Penjualan & Pembayaran berhasil disimpan!');
    }


    public function show($id)
    {
        $response = Http::get("https://klinikneshnavya.com/api/penjualan-produk/{$id}");

        if (! $response->successful()) {
            return redirect()->back()->with('error', 'Gagal mengambil data detail penjualan.');
        }

        $pembelian = $response->json();

        if (! empty($pembelian['pembayaran_produk']['gambar_bukti_pembayaran'] ?? null)) {
            $path = $pembelian['pembayaran_produk']['gambar_bukti_pembayaran'];
            $pembelian['pembayaran_produk']['gambar_bukti_pembayaran'] =
                'https://klinikneshnavya.com/' . ltrim($path, '/');
        }

        return view('pembelian-produk.detailPembelian', compact('pembelian'));
    }



    // public function edit($id)
    // {
    //     $purchaseResponse = Http::get("https://klinikneshnavya.com/api/penjualan-produk/$id");
    //     $pembelian = $purchaseResponse->json();

    //     // Map detail_pembelian ke produk
    //     $pembelian['produk'] = collect($pembelian['detail_pembelian'])->map(function ($detail) {
    //         return [
    //             'id_produk' => $detail['id_produk'],
    //             'jumlah_produk' => $detail['jumlah_produk']
    //         ];
    //     })->toArray();

    //     $productResponse = Http::get('https://klinikneshnavya.com/api/produk');
    //     $products = $productResponse->json('data');

    //     $promoResponse = Http::get('https://klinikneshnavya.com/api/promos');
    //     $promos = $promoResponse->json('data');

    //     if ($purchaseResponse->successful() && $productResponse->successful()) {
    //         return view('pembelian-produk.editPembelianProduk', [
    //             'pembelian' => $pembelian,
    //             'products' => $products,
    //             'promos' => $promos
    //         ]);
    //     }

    //     return redirect()->back()->with('error', 'Gagal mengambil data pembelian, pengguna, atau produk.');
    // }

    // public function update(Request $request, $id)
    // {
    //     $data = $request->validate([
    //         'id_promo' => 'nullable|integer',
    //         'produk' => 'required|array',
    //         'produk.*.id_produk' => 'required|integer',
    //         'produk.*.jumlah_produk' => 'required|integer',
    //     ]);

    //     // Kirim data ke API
    //     $response = Http::put("https://klinikneshnavya.com/api/penjualan-produk/$id", $data);

    //     if ($response->ok()) {
    //         return redirect()->route('pembelianProduk.index')->with('success', 'Data berhasil diperbarui!');
    //     } else {
    //         return back()->withErrors('Gagal memperbarui data. Silakan coba lagi.');
    //     }
    // }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengambilan_produk' => 'required|in:Belum diambil,Sudah diambil',
        ]);



        $payload = ['status_pengambilan_produk' => $request->status_pengambilan_produk];

        $res = Http::put("https://klinikneshnavya.com/api/penjualan-produk/{$id}/status-pengambilan", $payload);

        if ($res->successful()) {
            return back()->with('success', 'Status pengambilan berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal memperbarui status pengambilan.');
    }

    // public function destroy($id)
    // {
    //     // Panggil endpoint API DELETE
    //     $response = Http::delete("https://klinikneshnavya.com/api/penjualan-produk/{$id}");

    //     if ($response->successful()) {
    //         return redirect()
    //             ->route('pembelianProduk.index')  // sesuaikan nama route index-mu
    //             ->with('success', 'Penjualan produk berhasil dihapus.');
    //     }

    //     return back()
    //         ->with('error', 'Gagal menghapus penjualan produk: ' . $response->body());
    // }

    public function generateInvoice($paymentId)
    {
        // 1) Ambil data pembayaran
        $respPay = Http::get("https://klinikneshnavya.com/api/pembayaran-produk/{$paymentId}");
        // kalau API single return { data: {...} }:
        $dataPay = $respPay->json('data')
            ?? $respPay->json()
            ?? abort(404, 'Pembayaran tidak ditemukan');

        // 2) Ambil data penjualan yang terkait
        $saleId   = $dataPay['id_penjualan_produk'];
        $respSale = Http::get("https://klinikneshnavya.com/api/penjualan-produk/{$saleId}");
        $dataSale = $respSale->json('data')
            ?? $respSale->json()
            ?? abort(404, 'Penjualan tidak ditemukan');

        // 3) Siapkan data invoice
        $invoiceData = [
            'user_name'         => data_get($dataSale, 'user.nama_user', '-'),
            'no_telp'           => data_get($dataSale, 'user.no_telp', '-'),
            'email'             => data_get($dataSale, 'user.email', '-'),
            'tanggal_pembelian' => $dataSale['tanggal_pembelian'],
            'metode_pembayaran' => $dataPay['metode_pembayaran'],
            'subtotal'          => $dataSale['harga_total'],
            'potongan_harga'    => $dataSale['potongan_harga'],
            'tipe_potongan'     => data_get($dataSale, 'promo.tipe_potongan'),
            'pajak'             => $dataSale['besaran_pajak'],
            'total'             => $dataSale['harga_akhir'],
            'uang'              => $dataPay['uang'],
            'kembalian'         => $dataPay['kembalian'],
            'detail_pembelian'  => $dataSale['detail_pembelian'],
            'waktu_pembayaran'  => $dataPay['waktu_pembayaran'],
        ];

        // 4) Render & download PDF
        $pdf = Pdf::loadView('invoice.pembayaranProduk', $invoiceData);
        return $pdf->download("invoice_pembayaran_{$paymentId}.pdf");
    }

    public function confirmPayment(Request $request, $id)
    {
        $request->validate([
            'gambar_bukti_pembayaran' => 'required|image',
        ]);
    
        // bangun Multipart client
        $http = Http::withHeaders(['Accept' => 'application/json'])
                    ->asMultipart();   // <<< penting
    
        // attach file
        $http->attach(
            'gambar_bukti_pembayaran',
            file_get_contents($request->file('gambar_bukti_pembayaran')->getRealPath()),
            $request->file('gambar_bukti_pembayaran')->getClientOriginalName()
        );
    
        // spoof PUT via _method dan kirim dengan POST
        $response = $http->post(
            "https://klinikneshnavya.com/api/pembayaran-produk/{$id}/konfirmasi",
            ['_method' => 'PUT']
        );
    
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
        }
    
        return redirect()->back()->with('error', 'Gagal konfirmasi: ' . $response->body());
    }
    

    public function updatePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'uang' => 'required|numeric|min:0',
        ]);

        try {
            $response = Http::put("https://klinikneshnavya.com/api/pembayaran-produk/{$id}", [
                'metode_pembayaran' => 'Tunai',
                'uang'              => $validated['uang'],
            ]);

            if ($response->successful()) {
                return redirect()
                    ->route('pembelianProduk.index')
                    ->with('success', 'Pembayaran tunai berhasil diperbarui.');
            }

            return redirect()
                ->route('pembelianProduk.index')
                ->with('error', 'Gagal memperbarui pembayaran tunai.');
        } catch (\Exception $e) {
            return redirect()
                ->route('pembelianProduk.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
