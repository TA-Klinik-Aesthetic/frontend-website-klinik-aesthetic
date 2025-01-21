<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProdukController extends Controller
{
    // Menampilkan daftar produk
    public function index()
    {
        $response = Http::get('http://127.0.0.1:8080/api/produk'); // Ganti URL ini sesuai API Anda

        if ($response->successful()) {
            $produkList = $response->json('data'); // Hanya ambil bagian 'data'
        } else {
            $produkList = []; // Jika gagal, gunakan array kosong
        }

        return view('produk.listProduk', compact('produkList'));
    }

    // Menampilkan form untuk membuat produk baru
    public function create()
    {
        $kategoriList = Http::get('hhttp://127.0.0.1:8080/api/kategori')->json();
        return view('produk.createProduk', compact('kategoriList'));
    }

    // Menyimpan data produk baru
    public function store(Request $request)
    {
        $response = Http::post('http://127.0.0.1:8080/api/produk', $request->all());

        if ($response->successful()) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
        }

        return back()->with('error', 'Gagal menambahkan produk.');
    }

    // Method untuk menampilkan detail produk
    public function show($id)
    {
        // Mengambil data produk dari API
        $response = Http::get("http://127.0.0.1:8080/api/produk/{$id}");

        if ($response->successful()) {
            $produk = $response->json('data'); // Mendapatkan data produk
            return view('produk.detailProduk', compact('produk'));
        }

        // Jika produk tidak ditemukan
        return redirect()->route('produk.index')->with('error', 'Produk tidak ditemukan.');
    }

    // Menampilkan form edit produk
    public function edit($id)
    {
        // Ambil data produk berdasarkan ID
        $produkResponse = Http::get("http://127.0.0.1:8080/api/produk/$id");

        // Ambil data kategori
        $kategoriResponse = Http::get("http://127.0.0.1:8080/api/kategori");

        // Pastikan kedua API berhasil diakses
        if ($produkResponse->successful() && $kategoriResponse->successful()) {
            $produk = $produkResponse->json('data');
            $kategoriList = $kategoriResponse->json(); // Tidak perlu mengambil 'data', karena data kategori langsung berupa array

            return view('produk.editProduk', [
                'produk' => $produk,
                'kategoriList' => $kategoriList
            ]);
        }

        // Jika salah satu API gagal, kembali dengan pesan error
        return back()->with('error', 'Gagal mengambil data produk atau kategori.');
    }


    // Memperbarui data produk
    public function update(Request $request, $id)
    {
        $response = Http::put("http://127.0.0.1:8080/api/produk/$id", $request->all());

        if ($response->successful()) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal memperbarui produk.');
    }

    // Menghapus produk
    public function destroy($id)
    {
        $response = Http::delete("http://127.0.0.1:8080/api/produk/$id");

        if ($response->successful()) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
        }

        return back()->with('error', 'Gagal menghapus produk.');
    }
}
