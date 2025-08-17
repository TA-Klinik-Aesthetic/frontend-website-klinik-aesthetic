<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaketTreatmentPelangganController extends Controller
{
    protected $baseApiUrl = 'https://klinikneshnavya.com/api/paket-treatment-pelanggan';

    public function index()
    {
        $resp = Http::get($this->baseApiUrl);
        $items = $resp->json()['data'] ?? [];

        return view('paketTreatmentPelanggan.listPaketTreatmentPelanggan', [
            'items' => $items,
        ]);
    }

    public function show($id)
    {
        $resp = Http::get("{$this->baseApiUrl}/{$id}");
        $paket = $resp->json()['data'] ?? null;

        if ($paket) {
            return view('paketTreatmentPelanggan.detailPaketTreatmentPelanggan', compact('paket'));
        }
        return back()->with('error', 'Data paket treatment pelanggan tidak ditemukan');
    }
}
