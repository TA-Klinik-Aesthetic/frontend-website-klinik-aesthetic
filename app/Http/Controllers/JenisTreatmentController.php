<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JenisTreatmentController extends Controller
{
    protected $baseApiUrl = 'http://127.0.0.1:8080/api/jenisTreatments';


    public function index()
    {
        $response = Http::get($this->baseApiUrl);
        $jenisTreatments = $response->json()['data'] ?? [];

        return view('treatment.listJenisTreatment', ['jenisTreatments' => $jenisTreatments]);
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseApiUrl}/{$id}");
        $jenisTreatment = $response->json()['data'] ?? null;

        if ($jenisTreatment) {
            return view('treatment.detailJenisTreatment', ['jenisTreatment' => $jenisTreatment]);
        } else {
            return back()->with('error', 'Data jenis treatment tidak ditemukan');
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $response = Http::post($this->baseApiUrl, $data);

        if ($response->successful()) {
            return redirect()->route('jenisTreatment.index')->with('success', 'Jenis treatment berhasil ditambahkan');
        }

        return back()->with('error', 'Gagal menambahkan jenis treatment');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $response = Http::put("{$this->baseApiUrl}/{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('jenisTreatment.index')->with('success', 'Jenis treatment berhasil diperbarui');
        }

        return back()->with('error', 'Gagal memperbarui jenis treatment');
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->baseApiUrl}/{$id}");

        if ($response->successful()) {
            return redirect()->route('jenisTreatment.index')->with('success', 'Jenis treatment berhasil dihapus');
        }

        return back()->with('error', 'Gagal menghapus jenis treatment');
    }
}
