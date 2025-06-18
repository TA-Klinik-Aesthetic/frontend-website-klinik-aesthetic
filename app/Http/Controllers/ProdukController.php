<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProdukController extends Controller
{
    // Menampilkan daftar produk
    public function index()
    {
        // Ambil daftar produk
        $response       = Http::get('http://127.0.0.1:8080/api/produk');
        $produkList     = $response->successful() ? $response->json('data', []) : [];

        // Ambil daftar kategori untuk modal tambah
        $katResponse    = Http::get('http://127.0.0.1:8080/api/kategori');
        $kategoriList   = $katResponse->successful() ? $katResponse->json() : [];

        return view('produk.listProduk', compact('produkList', 'kategoriList'));
    }

    // Menampilkan form untuk membuat produk baru
    public function create()
    {
        $kategoriList = Http::get('http://127.0.0.1:8080/api/kategori')->json();
        return view('produk.createProduk', compact('kategoriList'));
    }


    // Menyimpan data produk baru
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required',
            'nama_produk' => 'required',
            'deskripsi_produk' => 'required',
            'harga_produk' => 'required',
            'stok_produk' => 'required',
            'status_produk' => 'required',
            'gambar_produk' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $response = Http::attach(
            'gambar_produk',
            file_get_contents($request->file('gambar_produk')->getRealPath()),
            $request->file('gambar_produk')->getClientOriginalName()
        )->post('http://127.0.0.1:8080/api/produk', [
            'id_kategori' => $request->id_kategori,
            'nama_produk' => $request->nama_produk,
            'deskripsi_produk' => $request->deskripsi_produk,
            'harga_produk' => $request->harga_produk,
            'stok_produk' => $request->stok_produk,
            'status_produk' => $request->status_produk,
        ]);

        if ($response->successful()) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
        }

        return back()->with('error', 'Gagal menambahkan produk');
    }

    // Method untuk menampilkan detail produk
    public function show($id)
    {
        $response = Http::get("http://127.0.0.1:8080/api/produk/{$id}");
        $produk = $response->json()['data'] ?? null;

        if ($produk) {
            // Jika gambar tersimpan di storage server backend, pastikan URL gambar sesuai
            if (!empty($produk['gambar_produk'])) {
                $produk['gambar_produk'] = "http://127.0.0.1:8080/storage/" . ltrim($produk['gambar_produk'], '/');
            }

            return view('produk.detailProduk', compact('produk'));
        }

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
        // validasi semua field termasuk gambar
        $validated = $request->validate([
            'nama_produk'      => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'harga_produk'     => 'required|numeric',
            'stok_produk'      => 'required|integer',
            'status_produk'    => 'required|string',
            'id_kategori'      => 'required',
            'gambar_produk'    => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
    
        // siapkan request ke API
        $http = Http::withHeaders([
            'Accept' => 'application/json'
        ]);
    
        // kalau ada gambar baru, attach file
        if ($request->hasFile('gambar_produk')) {
            $file = $request->file('gambar_produk');
            $http = $http->attach(
                'gambar_produk',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            );
        }
    
        // sisipkan method spoofing untuk PUT
        $http = $http->asMultipart()->post("http://127.0.0.1:8080/api/produk/{$id}", [
            '_method'         => 'PUT',
            'nama_produk'     => $validated['nama_produk'],
            'deskripsi_produk'=> $validated['deskripsi_produk'],
            'harga_produk'    => $validated['harga_produk'],
            'stok_produk'     => $validated['stok_produk'],
            'status_produk'   => $validated['status_produk'],
            'id_kategori'     => $validated['id_kategori'],
            // jika tidak ada gambar, server akan mempertahankan gambar lama
        ]);
    
        if ($http->successful()) {
            return redirect()->route('produk.index')
                             ->with('success', 'Produk berhasil diperbarui.');
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
