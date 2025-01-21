<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PembelianProdukController extends Controller
{
    public function index()
    {
        // Fetch product purchases
        $pembelianResponse = Http::get('http://127.0.0.1:8080/api/penjualan-produk');
        $pembelianProduk = $pembelianResponse->json();

        // Fetch user data
        $userResponse = Http::get('http://127.0.0.1:8080/api/users');
        $users = collect($userResponse->json()['data']); // Adjust to access the 'data' key

        // Map id_user to user name
        foreach ($pembelianProduk as &$pembelian) {
            $user = $users->firstWhere('id_user', $pembelian['id_user']); // Match id_user
            $pembelian['nama_user'] = $user ? $user['nama_user'] : 'Tidak Diketahui';
        }

        return view('pembelian-produk.pembelian', compact('pembelianProduk'));
    }

    public function create()
    {
        // Fetch users
        $userResponse = Http::get('http://127.0.0.1:8080/api/users');
        $users = $userResponse->json()['data'];
    
        // Fetch products
        $productResponse = Http::get('http://127.0.0.1:8080/api/produk');
        $products = $productResponse->json('data');
    
        // Fetch promos
        $promoResponse = Http::get('http://127.0.0.1:8080/api/promos');
        $promos = $promoResponse->json('data');
    
        return view('pembelian-produk.createPembelian', compact('users', 'products', 'promos'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer',
            'produk' => 'required|array',
            'produk.*.id_produk' => 'required|integer',
            'produk.*.jumlah_produk' => 'required|integer',
            'id_promo' => 'nullable|integer',
        ]);
    
        // Kirim data ke API
        $response = Http::post('http://127.0.0.1:8080/api/penjualan-produk', $data);
    
        if ($response->ok()) {
            return redirect()->route('pembelianProduk.index')->with('success', 'Data berhasil ditambahkan!');
        } else {
            return back()->withErrors('Gagal menambahkan data. Silakan coba lagi.');
        }
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
    
        // Check if the API responses are successful
        if ($purchaseResponse->successful() && $userResponse->successful() && $productResponse->successful()) {
            return view('pembelian-produk.detailPembelian', [
                'pembelian' => $pembelian,
                'users' => $users,
                'products' => $products
            ]);
        }
    
        // Redirect back with error if any API fails
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
    
}
