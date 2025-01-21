<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JadwalDokterController extends Controller
{
    public function index()
    {
        // Ambil data dari API
        $response = Http::withoutVerifying()->get('http://127.0.0.1:8080/api/jadwal-dokter');
        // $jadwalDokter = $response->json();
        $data = $response->json();
        // dd($data);

        // Kelompokkan data berdasarkan hari
        $jadwals = collect($data)->groupBy('hari');

        return view('jadwal.jadwaldokter', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_dokter' => 'required|integer',
            'hari' => 'required|string',
            'tgl_kerja' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        Http::withoutVerifying()->post('http://127.0.0.1:8080/api/jadwal-dokter', $data);

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_dokter' => 'required|integer',
            'hari' => 'required|string',
            'tgl_kerja' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        Http::withoutVerifying()->put("https://backend-klinik-aesthetic-production.up.railway.app/api/jadwal-dokter/{$id}", $data);

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy($id)
    {
        Http::withoutVerifying()->delete("https://backend-klinik-aesthetic-production.up.railway.app/api/jadwal-dokter/{$id}");

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
