<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KompensasiController extends Controller
{

    protected $baseApiUrl = 'https://klinikneshnavya.com/api/kompensasi';
    protected $ApiTreatment = 'https://klinikneshnavya.com/api/treatment';

    public function index()
    {
        $kompensasiResponse = Http::get($this->baseApiUrl);
        $kompensasiList = $kompensasiResponse->json() ?? [];

        $treatmentResponse = Http::get($this->ApiTreatment);
        $treatmentData = $treatmentResponse->json()['data'] ?? [];

        // Mapping treatment ID ke nama treatment
        $treatmentMap = collect($treatmentData)->keyBy('id_treatment');

        // Gabungkan nama treatment ke tiap kompensasi
        foreach ($kompensasiList as &$kompensasi) {
            $idTreatment = $kompensasi['id_treatment'] ?? null;

            // Tambahkan id_treatment ke kompensasi (agar bisa diakses oleh Blade & JavaScript)
            $kompensasi['id_treatment'] = $idTreatment;

            // Tambahkan nama_treatment dari hasil mapping
            $kompensasi['nama_treatment'] = $treatmentMap[$idTreatment]['nama_treatment'] ?? '-';
        }


        return view('komplain.listKompensasi', ['kompensasiList' => $kompensasiList, 'treatments' => $treatmentData]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_kompensasi' => 'required',
            'id_treatment' => 'required',
            'deskripsi_kompensasi' => 'required',
        ]);

        $response = Http::post($this->baseApiUrl, [
            'nama_kompensasi' => $request->nama_kompensasi,
            'id_treatment' => $request->id_treatment,
            'deskripsi_kompensasi' => $request->deskripsi_kompensasi,
        ]);

        if ($response->successful()) {
            return redirect()->route('kompensasi.index')->with('success', 'Kompensasi berhasil ditambahkan');
        }

        return back()->with('error', 'Gagal menambahkan kompensasi');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kompensasi' => 'required',
            'id_treatment' => 'required',
            'deskripsi_kompensasi' => 'required',
        ]);

        $data = [
            'nama_kompensasi' => $request->nama_kompensasi,
            'id_treatment' => $request->id_treatment,
            'deskripsi_kompensasi' => $request->deskripsi_kompensasi,
        ];

        $response = Http::put("{$this->baseApiUrl}/{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('kompensasi.index')->with('success', 'Kompensasi berhasil diperbarui');
        }

        return back()->with('error', 'Gagal memperbarui kompensasi');
    }
}
