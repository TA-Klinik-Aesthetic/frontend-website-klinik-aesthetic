<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranProdukController extends Controller
{
    // URL API untuk pembayaran produk
    protected $apiUrlPembayaran = 'http://127.0.0.1:8080/api/pembayaran-produk';

    // URL API untuk penjualan produk
    protected $apiUrlPenjualan = 'http://127.0.0.1:8080/api/penjualan-produk';

    // Menampilkan semua pembayaran produk
    public function index()
    {
        // Ambil data pembayaran produk
        $response = Http::get($this->apiUrlPembayaran);
        $pembayaranProdukList = $response->json();

        // Ambil data penjualan produk untuk mendapatkan informasi lebih lanjut
        $penjualanResponse = Http::get($this->apiUrlPenjualan);
        $penjualanProduk = collect($penjualanResponse->json());

        // Flatten: tambahkan user_name & harga_akhir langsung ke setiap pembayaran
        $flattened = collect($pembayaranProdukList)->map(function($p) use ($penjualanProduk) {
            if (isset($p['penjualan_produk'])) {
                $bt = $p['penjualan_produk'];
                $p['user_name'] = data_get($bt, 'user.nama_user', '-');
                $p['harga_akhir'] = data_get($bt, 'harga_akhir', 0);
            } else {
                $p['user_name'] = '-';
                $p['harga_akhir'] = 0;
            }
            return $p;
        });

        // Filter penjualan produk yang status_pembayarannya "Belum Dibayar"
        // $penjualanProduk = array_filter($penjualanProduk, function ($penjualan) {
        //     return $penjualan['status_pembayaran'] === 'Belum Dibayar';
        // });

        // return view('pembayaran.pembayaranProduk', compact('pembayaranProdukList', 'penjualanProduk'));

        return view('pembayaran.pembayaranProduk', [
            'pembayaranProdukList' => $flattened,
        ]);
    }

    // Menyimpan pembayaran produk baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_penjualan_produk' => 'required',
            'metode_pembayaran' => 'required',
        ]);

        try {
            // Simpan data pembayaran
            $pembayaranProdukResponse = Http::post($this->apiUrlPembayaran, [
                'id_penjualan_produk' => $validatedData['id_penjualan_produk'],
                'metode_pembayaran' => $validatedData['metode_pembayaran'],
            ]);

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('pembayaran-produk.index')->with('success', 'Pembayaran produk berhasil disimpan');
        } catch (\Exception $e) {
            // Menampilkan pesan error jika terjadi kegagalan
            return redirect()->route('pembayaran-produk.index')->with('error', 'Error while creating pembayaran produk');
        }
    }

    // Memperbarui pembayaran produk yang sudah ada
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:Tunai,Non Tunai',
            'uang' => 'required|numeric|min:0',
        ]);

        try {
            // Kirim data lengkap ke API
            $response = Http::put("{$this->apiUrlPembayaran}/{$id}", [
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'uang'              => $validated['uang'],
            ]);
    
            if ($response->successful()) {
                return redirect()
                    ->route('pembayaran-produk.index')
                    ->with('success', 'Pembayaran treatment berhasil diperbarui');
            }

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('pembayaran-produk.index')->with('success', 'Pembayaran produk berhasil diperbarui');
        } catch (\Exception $e) {
            // Menampilkan pesan error jika terjadi kegagalan
            return redirect()->route('pembayaran-produk.index')->with('error', 'Error while updating pembayaran produk');
        }
    }

    public function generateInvoice($id)
    {
        // 1. Ambil data pembayaran produk
        $respPay = Http::get("{$this->apiUrlPembayaran}/{$id}");
        $dataPay = $respPay->json()['data'] ?? abort(404, 'Pembayaran tidak ditemukan');

        // 2. Ambil data penjualan produk terkait
        $respSale = Http::get("{$this->apiUrlPenjualan}/{$dataPay['id_penjualan_produk']}");
        $dataSale = $respSale->json() ?? abort(404, 'Penjualan produk tidak ditemukan');

        // 3. Siapkan data untuk view
        $invoiceData = [
            'user_name'         => $dataSale['user']['nama_user'] ?? '-',
            'no_telp'           => $dataSale['user']['no_telp'] ?? '-',
            'email'             => $dataSale['user']['email'] ?? '-',
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

        // 4. Render PDF
        $pdf = PDF::loadView('invoice.pembayaranProduk', $invoiceData);

        // 5. Download
        return $pdf->download("invoice_pembayaran_{$dataPay['id_pembayaran']}.pdf");
        }

}
