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
        $respSale    = Http::get('http://127.0.0.1:8080/api/penjualan-produk');
        $rawSales    = $respSale->json();          // <-- gunakan ini saja
        $pembelian   = collect($rawSales);
    
        // 2) Data pendukung
        $users    = collect(Http::get('http://127.0.0.1:8080/api/users')->json('data') ?? []);
        $products = Http::get('http://127.0.0.1:8080/api/produk')->json('data') ?? [];
        $promos   = collect(Http::get('http://127.0.0.1:8080/api/promo')
                       ->json('data') ?? [])
                       ->where('jenis_promo','Produk')
                       ->values();
    
        // 3) Semua pembayaran
        $allPays = collect(Http::get('http://127.0.0.1:8080/api/pembayaran-produk')
        ->json() ?? []);
    
        // 4) Map: tambahkan nama_user, daftar produk, promo dan id_pembayaran
        $pembelianProduk = $pembelian->map(function($p) use($users,$products,$promos,$allPays){
            // nama user
            $u = $users->firstWhere('id_user',$p['id_user']);
            $p['nama_user'] = $u['nama_user'] ?? 'Tidak Diketahui';
    
            // list produk (modal edit)
            $p['produk'] = collect($p['detail_pembelian'] ?? [])
                ->map(fn($d)=>[
                    'id_produk'=>$d['id_produk'],
                    'jumlah_produk'=>$d['jumlah_produk'],
                ])->toArray();
    
            // promo
            $p['promo_dipakai'] = $promos->firstWhere('id_promo',$p['id_promo']);
    
            // id_pembayaran yang cocok
            $pay = $allPays->firstWhere('id_penjualan_produk',$p['id_penjualan_produk']);
            $p['id_pembayaran'] = data_get($pay,'id_pembayaran');
            $p['status_pembayaran'] = data_get($pay, 'status_pembayaran', 'Belum Dibayar');
            return $p;
        });
    
        return view('pembelian-produk.pembelian', 
            compact('pembelianProduk','users','products','promos')
        );
    }
    



    // public function create()
    // {
    //     // Fetch users
    //     $userResponse = Http::get('http://127.0.0.1:8080/api/users');
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
            'status_pengambilan_produk' => 'nullable',
            // baru:
            'metode_pembayaran'        => 'required|string|in:Tunai,Non Tunai',
            'uang'                     => 'nullable|numeric|min:0',
        ]);

        // 1) Buat penjualan
        $resp = Http::post('http://127.0.0.1:8080/api/penjualan-produk/kasir', $data);

        if (! $resp->successful()) {
            return back()->withErrors('Gagal menyimpan penjualan.');
        }

        // ambil ID penjualan yang baru
        $penjId = $resp->json('data.id_penjualan_produk');

        // 2) Buat pembayaran
        $payResp = Http::post('http://127.0.0.1:8080/api/pembayaran-produk', [
            'id_penjualan_produk' => $penjId,
            'metode_pembayaran'   => $data['metode_pembayaran'],
            'uang'                => $data['uang'],
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
        // Fetch the detail of the purchase
        $purchaseResponse = Http::get("http://127.0.0.1:8080/api/penjualan-produk/$id");
        $pembelian = $purchaseResponse->json();

        // Fetch users
        $userResponse = Http::get('http://127.0.0.1:8080/api/users');
        $users = $userResponse->json()['data'];

        // Fetch products
        $productResponse = Http::get('http://127.0.0.1:8080/api/produk');
        $products = $productResponse->json('data');

        // **Tambah: Fetch semua pembayaran, lalu cari yang id_penjualan_produk == $id**
        $paymentResponse = Http::get('http://127.0.0.1:8080/api/pembayaran-produk');
        $allPayments     = collect($paymentResponse->json());
        $payment         = $allPayments->firstWhere('id_penjualan_produk', $id);

        if ($purchaseResponse->successful() && $userResponse->successful() && $productResponse->successful()) {
            return view('pembelian-produk.detailPembelian', [
                'pembelian' => $pembelian,
                'users'     => $users,
                'products'  => $products,
                'payment'   => $payment,   // passing payment data (null jika belum)
            ]);
        }

        return redirect()->back()->with('error', 'Gagal mengambil data pembelian, pengguna, atau produk.');
    }


    public function edit($id)
    {
        $purchaseResponse = Http::get("http://127.0.0.1:8080/api/penjualan-produk/$id");
        $pembelian = $purchaseResponse->json();

        // Map detail_pembelian ke produk
        $pembelian['produk'] = collect($pembelian['detail_pembelian'])->map(function ($detail) {
            return [
                'id_produk' => $detail['id_produk'],
                'jumlah_produk' => $detail['jumlah_produk']
            ];
        })->toArray();

        $productResponse = Http::get('http://127.0.0.1:8080/api/produk');
        $products = $productResponse->json('data');

        $promoResponse = Http::get('http://127.0.0.1:8080/api/promos');
        $promos = $promoResponse->json('data');

        if ($purchaseResponse->successful() && $productResponse->successful()) {
            return view('pembelian-produk.editPembelianProduk', [
                'pembelian' => $pembelian,
                'products' => $products,
                'promos' => $promos
            ]);
        }

        return redirect()->back()->with('error', 'Gagal mengambil data pembelian, pengguna, atau produk.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_promo' => 'nullable|integer',
            'produk' => 'required|array',
            'produk.*.id_produk' => 'required|integer',
            'produk.*.jumlah_produk' => 'required|integer',
        ]);

        // Kirim data ke API
        $response = Http::put("http://127.0.0.1:8080/api/penjualan-produk/$id", $data);

        if ($response->ok()) {
            return redirect()->route('pembelianProduk.index')->with('success', 'Data berhasil diperbarui!');
        } else {
            return back()->withErrors('Gagal memperbarui data. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        // Panggil endpoint API DELETE
        $response = Http::delete("http://127.0.0.1:8080/api/penjualan-produk/{$id}");

        if ($response->successful()) {
            return redirect()
                ->route('pembelianProduk.index')  // sesuaikan nama route index-mu
                ->with('success', 'Penjualan produk berhasil dihapus.');
        }

        return back()
            ->with('error', 'Gagal menghapus penjualan produk: ' . $response->body());
    }

    public function generateInvoice($paymentId)
    {
        // 1) Ambil data pembayaran
        $respPay = Http::get("http://127.0.0.1:8080/api/pembayaran-produk/{$paymentId}");
        // kalau API single return { data: {...} }:
        $dataPay = $respPay->json('data')
            ?? $respPay->json()
            ?? abort(404, 'Pembayaran tidak ditemukan');

        // 2) Ambil data penjualan yang terkait
        $saleId   = $dataPay['id_penjualan_produk'];
        $respSale = Http::get("http://127.0.0.1:8080/api/penjualan-produk/{$saleId}");
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
}
