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
                $promo['gambar_promo'] = "http://127.0.0.1:8080/" . ltrim($promo['gambar_promo'], '/');
            }
            return view('promo.detailPromo', compact('promo'));
        }

        return back()->with('error', 'Data promo tidak ditemukan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_promo' => 'required',
            'jenis_promo' => 'required',
            'deskripsi_promo' => 'required',
            'tipe_potongan' => 'required|string',
            'potongan_harga' => 'required|numeric',
            'minimal_belanja' => 'nullable|numeric',
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
            'jenis_promo' => $request->jenis_promo,
            'deskripsi_promo' => $request->deskripsi_promo,
            'tipe_potongan' => $request->tipe_potongan,
            'potongan_harga' => $request->potongan_harga,
            'minimal_belanja' => $request->minimal_belanja,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'status_promo' => $request->status_promo,
        ]);

        if ($response->successful()) {
            return redirect()->route('promo.index')->with('success', 'Promo berhasil ditambahkan');
        }

        return back()->with('error', 'Gagal menambahkan promo');
    }

    public function update(Request $request, $id)
    {
        // 1) Validasi input
        $validated = $request->validate([
            'nama_promo'       => 'required|string|max:255',
            'jenis_promo'      => 'required|string',
            'deskripsi_promo'  => 'required|string',
            'tipe_potongan'    => 'required|in:Diskon,Rupiah',
            'potongan_harga'   => 'required|numeric|min:0',
            'minimal_belanja'  => 'nullable|numeric|min:0',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'status_promo'     => 'required|string',
            'gambar_promo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2) Siapkan payload + override method
        $payload = [
            'nama_promo'       => $validated['nama_promo'],
            'jenis_promo'      => $validated['jenis_promo'],
            'deskripsi_promo'  => $validated['deskripsi_promo'],
            'tipe_potongan'    => $validated['tipe_potongan'],
            'potongan_harga'   => $validated['potongan_harga'],
            'minimal_belanja'  => $validated['minimal_belanja'] ?? null,
            'tanggal_mulai'    => $validated['tanggal_mulai'],
            'tanggal_berakhir' => $validated['tanggal_berakhir'],
            'status_promo'     => $validated['status_promo'],
            '_method'          => 'PUT',
        ];

        $url = "{$this->baseApiUrl}/{$id}";

        // 3) Jika ada file, attach; jika tidak, langsung POST
        if ($request->hasFile('gambar_promo')) {
            $file = $request->file('gambar_promo');
            $response = Http::attach(
                'gambar_promo',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post($url, $payload);
        } else {
            $response = Http::post($url, $payload);
        }

        // 4) Redirect sesuai hasil
        if ($response->successful()) {
            return redirect()->route('promo.index')
                ->with('success', 'Promo berhasil diperbarui');
        }

        return back()->with('error', 'Gagal memperbarui promo');
    }
}
