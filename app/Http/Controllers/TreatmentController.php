<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TreatmentController extends Controller
{
    protected $baseApiUrl = 'http://127.0.0.1:8080/api/treatments';
    protected $apiJenisTreatment ='http://127.0.0.1:8080/api/jenisTreatments';



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
                $treatment['gambar_treatment'] = "http://127.0.0.1:8080/storage/" . ltrim($treatment['gambar_treatment'], '/');
            }
    
            return view('treatment.detailTreatment', compact('treatment'));
        }
    
        return back()->with('error', 'Data treatment tidak ditemukan');
    }
    

    
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $response = Http::put("{$this->baseApiUrl}/{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('treatment.index')->with('success', 'Treatment berhasil diperbarui');
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
