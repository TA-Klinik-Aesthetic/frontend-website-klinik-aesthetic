<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TreatmentController extends Controller
{
    protected $baseApiUrl = 'http://127.0.0.1:8080/api/treatments';
    protected $apiJenisTreatment ='http://127.0.0.1:8080/api/jenisTreatments';


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
            return view('treatment.detailTreatment', ['treatment' => $treatment]);
        } else {
            return back()->with('error', 'Data treatment tidak ditemukan');
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $response = Http::post($this->baseApiUrl, $data);

        if ($response->successful()) {
            return redirect()->route('treatment.index')->with('success', 'Treatment berhasil ditambahkan');
        }

        return back()->with('error', 'Gagal menambahkan treatment');
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
