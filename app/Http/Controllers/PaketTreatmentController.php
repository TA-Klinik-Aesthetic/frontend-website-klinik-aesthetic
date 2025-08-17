<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaketTreatmentController extends Controller
{
    // Sesuaikan ke endpoint backend-mu
    protected $baseApiUrl   = 'http://127.0.0.1:8080/api/paket-treatment';
    protected $apiTreatment = 'http://127.0.0.1:8080/api/treatment';

    public function index()
    {
        // Ambil list paket
        $resp = Http::get($this->baseApiUrl);
        $pakets = $resp->json()['data'] ?? [];

        // Ambil semua treatment untuk dropdown (modal tambah/edit)
        $treatmentsResp = Http::get($this->apiTreatment);
        $treatments = $treatmentsResp->json()['data'] ?? [];

        return view('paketTreatment.listPaketTreatment', [
            'pakets'     => $pakets,
            'treatments' => $treatments,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket_treatment'                  => 'required|string|max:255',
            'deskripsi_paket_treatment'             => 'nullable|string',
            'harga_paket_treatment'                 => 'required|numeric|min:0',
            'details'                     => 'required|array|min:1',
            'details.*.id_treatment'      => 'required|integer',
            'details.*.jumlah_penggunaan' => 'required|integer|min:1',
        ]);
    
        $payload = [
            'nama_paket_treatment'       => $request->nama_paket_treatment,
            'deskripsi_paket_treatment'  => $request->deskripsi_paket_treatment,
            'harga_paket_treatment'      => $request->harga_paket_treatment,
            'details' => array_map(fn($d) => [
                'id_treatment'       => (int)$d['id_treatment'],
                'jumlah_penggunaan'  => (int)$d['jumlah_penggunaan'],
            ], $request->details),
        ];
    
        $response = Http::post($this->baseApiUrl, $payload);
    
        if ($response->successful()) {
            return redirect()->route('paketTreatment.index')->with('success', 'Paket treatment berhasil ditambahkan');
        }
        return back()->with('error', 'Gagal menambahkan paket treatment');
    }
    

    public function show($id)
    {
        $response = Http::get("{$this->baseApiUrl}/{$id}");
        $paket = $response->json()['data'] ?? null;

        if ($paket) {
            return view('paketTreatment.detailPaketTreatment', compact('paket'));
        }
        return back()->with('error', 'Data paket treatment tidak ditemukan');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_paket_treatment'                  => 'required|string|max:255',
            'deskripsi_paket_treatment'             => 'nullable|string',
            'harga_paket_treatment'                 => 'required|numeric|min:0',
            'details'                     => 'required|array|min:1',
            'details.*.id_treatment'      => 'required|integer',
            'details.*.jumlah_penggunaan' => 'required|integer|min:1',
        ]);
    
        $payload = [
            'nama_paket_treatment'       => $validated['nama_paket_treatment'],
            'deskripsi_paket_treatment'  => $validated['deskripsi_paket_treatment'] ?? '',
            'harga_paket_treatment'      => $validated['harga_paket_treatment'],
            'details' => array_map(fn($d) => [
                'id_treatment'       => (int)$d['id_treatment'],
                'jumlah_penggunaan'  => (int)$d['jumlah_penggunaan'],
            ], $validated['details']),
        ];
    
        $url = "{$this->baseApiUrl}/{$id}";
        $response = Http::put($url, $payload);
    
        if ($response->successful()) {
            return redirect()->route('paketTreatment.index')
                             ->with('success', 'Paket treatment berhasil diperbarui');
        }
        return back()->with('error', 'Gagal memperbarui paket treatment');
    }    
}