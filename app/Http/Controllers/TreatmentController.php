<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TreatmentController extends Controller
{
    protected $baseApiUrl = 'https://klinikneshnavya.com/api/treatment';
    protected $apiJenisTreatment ='https://klinikneshnavya.com/api/jenisTreatment';



    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_treatment' => 'required',
            'nama_treatment' => 'required',
            'deskripsi_treatment' => 'required',
            'biaya_treatment' => 'required|numeric',
            'estimasi_treatment' => 'required',
            'gambar_treatment' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
    
        // Kirim file langsung ke backend
        $response = Http::attach(
            'gambar_treatment',
            file_get_contents($request->file('gambar_treatment')->getRealPath()),
            $request->file('gambar_treatment')->getClientOriginalName()
        )->post($this->baseApiUrl, [
            'id_jenis_treatment' => $request->id_jenis_treatment,
            'nama_treatment' => $request->nama_treatment,
            'deskripsi_treatment' => $request->deskripsi_treatment,
            'biaya_treatment' => $request->biaya_treatment,
            'estimasi_treatment' => $request->estimasi_treatment,
        ]);
    
        if ($response->successful()) {
            return redirect()->route('treatment.index')->with('success', 'Treatment berhasil ditambahkan');
        }
    
        return back()->with('error', 'Gagal menambahkan treatment');
    }

    public function index()
    {
        $response = Http::get($this->baseApiUrl);
        $treatments = $response->json()['data'] ?? [];

        // Ambil data jenis treatment
        $jenisTreatmentResponse = Http::get($this->apiJenisTreatment);
        $jenisTreatments = $jenisTreatmentResponse->json()['data'] ?? [];

        return view('treatment.listTreatment', [
            'treatments' => $treatments,
            'jenisTreatments' => $jenisTreatments, // Kirim data jenis treatment ke view
        ]);
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseApiUrl}/{$id}");
        $treatment = $response->json()['data'] ?? null;
    
        if ($treatment) {
            if (!empty($treatment['gambar_treatment'])) {
                // Gunakan URL backend langsung agar bisa diakses dari frontend
                $treatment['gambar_treatment'] = "https://klinikneshnavya.com/" . ltrim($treatment['gambar_treatment'], '/');
            }
    
            return view('treatment.detailTreatment', compact('treatment'));
        }
    
        return back()->with('error', 'Data treatment tidak ditemukan');
    }
    

    
    public function update(Request $request, $id)
    {
        // 1) Validasi input
        $validated = $request->validate([
            'id_jenis_treatment'  => 'required',
            'nama_treatment'      => 'required|string|max:255',
            'deskripsi_treatment' => 'nullable|string',
            'biaya_treatment'     => 'required|numeric',
            'estimasi_treatment'  => 'required',
            'gambar_treatment'    => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
    
        // 2) Siapkan payload (termasuk _method untuk override PUT)
        $payload = [
            'id_jenis_treatment'  => $validated['id_jenis_treatment'],
            'nama_treatment'      => $validated['nama_treatment'],
            'deskripsi_treatment' => $validated['deskripsi_treatment'] ?? '',
            'biaya_treatment'     => $validated['biaya_treatment'],
            'estimasi_treatment'  => $validated['estimasi_treatment'],
            '_method'             => 'PUT',
        ];
    
        $url = "{$this->baseApiUrl}/{$id}";
    
        // 3) Kalau ada file, attach; kalau tidak, langsung post
        if ($request->hasFile('gambar_treatment')) {
            $file     = $request->file('gambar_treatment');
            $response = Http::attach(
                'gambar_treatment',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post($url, $payload);
        } else {
            $response = Http::post($url, $payload);
        }
    
        // 4) Cek hasil
        if ($response->successful()) {
            return redirect()->route('treatment.index')
                             ->with('success', 'Treatment berhasil diperbarui');
        }
    
        return back()->with('error', 'Gagal memperbarui treatment');
    }
    
    

    public function destroy($id)
    {
        $response = Http::delete("{$this->baseApiUrl}/{$id}");

        if ($response->successful()) {
            return redirect()->route('treatment.index')->with('success', 'Treatment berhasil dihapus');
        }

        return back()->with('error', 'Gagal menghapus treatment');
    }
}
