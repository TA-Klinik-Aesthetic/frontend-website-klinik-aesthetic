<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

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
    public function exportPdf($userId)
    {
        // 1) Hit API
        $resp = Http::get("https://klinikneshnavya.com/api/rekam-medis/{$userId}");
        if (! $resp->successful()) {
            abort(404, 'Data rekam medis tidak ditemukan.');
        }
        // ambil seluruh payload
        $data = $resp->json();
    
        // 2) Render view PDF, kirim payload apa adanya
        $pdf = PDF::loadView('rekam-medis.pdf', compact('data'));
    
        // 3) Buat filename tanpa Str::slug
        $name = $data['user']['nama_user'] ?? 'user';
        // ubah ke lowercase, ganti non-alfanumerik jadi '-', lalu trim '-'
        $slug = trim(
            preg_replace('/[^a-z0-9]+/i', '-', mb_strtolower($name, 'UTF-8')),
            '-'
        );
        $filename = "rekam_medis_{$slug}.pdf";
    
        return $pdf->download($filename);
    }
}
