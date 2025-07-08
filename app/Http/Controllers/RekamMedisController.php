<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RekamMedisController extends Controller
{
    protected $apiUrlRekamMedis = 'https://klinikneshnavya.com/api/rekam-medis'; // API Rekam Medis

    public function rekamMedis()
    {
        // Mengambil data rekam medis dari API
        $rekamMedisResponse = Http::get($this->apiUrlRekamMedis);
        
        // Cek apakah request berhasil
        if ($rekamMedisResponse->successful()) {
            // Ambil data rekam medis
            $rekamMedisData = $rekamMedisResponse->json();
            return view('rekam-medis.index', compact('rekamMedisData'));
        } else {
            return back()->with('error', 'Gagal mengambil data rekam medis');
        }
    }

    public function rekamMedisDetail($id)
    {
        // Mengambil data detail rekam medis berdasarkan ID
        $rekamMedisDetailResponse = Http::get("{$this->apiUrlRekamMedis}/{$id}");

        // Cek apakah request berhasil
        if ($rekamMedisDetailResponse->successful()) {
            $rekamMedisDetail = $rekamMedisDetailResponse->json();
            return view('rekam-medis.detail', compact('rekamMedisDetail'));
        } else {
            return back()->with('error', 'Gagal mengambil data detail rekam medis');
        }
    }
}
