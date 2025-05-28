<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $penjualanProduk = $penjualanResponse->json();

        // Menambahkan nama user pada setiap pembayaran produk
        foreach ($pembayaranProdukList as &$pembayaran) {
            foreach ($penjualanProduk as $penjualan) {
                if ($penjualan['id_penjualan_produk'] == $pembayaran['id_penjualan_produk']) {
                    $pembayaran['user_name'] = $penjualan['user']['nama_user'];
                }
            }
        }

        // Filter penjualan produk yang status_pembayarannya "Belum Dibayar"
        $penjualanProduk = array_filter($penjualanProduk, function ($penjualan) {
            return $penjualan['status_pembayaran'] === 'Belum Dibayar';
        });

        return view('pembayaran.pembayaranProduk', compact('pembayaranProdukList', 'penjualanProduk'));
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
        $request->validate([
            'uang' => 'required|numeric|min:0',
        ]);

        try {
            // Update data pembayaran dengan total_bayar yang baru
            $pembayaranUpdateResponse = Http::put("{$this->apiUrlPembayaran}/{$id}", [
                'uang' => $request->uang,
            ]);

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('pembayaran-produk.index')->with('success', 'Pembayaran produk berhasil diperbarui');
        } catch (\Exception $e) {
            // Menampilkan pesan error jika terjadi kegagalan
            return redirect()->route('pembayaran-produk.index')->with('error', 'Error while updating pembayaran produk');
        }
    }
}
