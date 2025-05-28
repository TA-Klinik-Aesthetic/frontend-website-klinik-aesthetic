<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PromoController extends Controller
{
    protected $baseApiUrl = 'http://127.0.0.1:8080/api/promo';

    public function index()
    {
        $response = Http::get($this->baseApiUrl);
        $promos = $response->json()['data'] ?? [];

        return view('promo.listPromo', compact('promos'));
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseApiUrl}/{$id}");
        $promo = $response->json()['data'] ?? null;

        if ($promo) {
            if (!empty($promo['gambar_promo'])) {
                $promo['gambar_promo'] = "http://127.0.0.1:8080/storage/" . ltrim($promo['gambar_promo'], '/');
            }
            return view('promo.detailPromo', compact('promo'));
        }

        return back()->with('error', 'Data promo tidak ditemukan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_promo' => 'required',
            'deskripsi_promo' => 'required',
            'potongan_harga' => 'required|numeric',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date',
            'gambar_promo' => 'required|image|mimes:jpeg,png,jpg,gif',
            'status_promo' => 'required',
        ]);

        $response = Http::attach(
            'gambar_promo',
            file_get_contents($request->file('gambar_promo')->getRealPath()),
            $request->file('gambar_promo')->getClientOriginalName()
        )->post($this->baseApiUrl, [
            'nama_promo' => $request->nama_promo,
            'deskripsi_promo' => $request->deskripsi_promo,
            'potongan_harga' => $request->potongan_harga,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'status_promo' => $request->status_promo,
        ]);

        if ($response->successful()) {
            return redirect()->route('promo.index')->with('success', 'Promo berhasil ditambahkan');
        }

        return back()->with('error', 'Gagal menambahkan promo');
    }
}
