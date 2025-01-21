<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JadwalBeauticianController extends Controller
{
    // Tampilkan jadwal beautician
    public function index()
    {
        try {
            // Ambil data dari API
            $response = Http::withoutVerifying()->get('http://127.0.0.1:8080/api/jadwal-beautician');
            $data = $response->json();

            // Kelompokkan data berdasarkan hari
            $grouped = collect($data)->groupBy('hari');

            return view('jadwal.jadwalbeautician', compact('grouped'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data jadwal.');
        }
    }

    // Tambah atau update jadwal beautician
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_beautician' => 'required|integer',
            'hari' => 'required|string|max:255',
            'tgl_kerja' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        try {
            if ($request->id) {
                // Update data jadwal menggunakan API
                Http::withoutVerifying()->put("https://backend-klinik-aesthetic-production.up.railway.app/api/jadwal-beautician/{$request->id}", $data);
            } else {
                // Tambah data jadwal menggunakan API
                Http::withoutVerifying()->post('https://backend-klinik-aesthetic-production.up.railway.app/api/jadwal-beautician', $data);
            }

            return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan jadwal.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_beautician' => 'required|integer',
            'hari' => 'required|string',
            'tgl_kerja' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        Http::withoutVerifying()->put("https://backend-klinik-aesthetic-production.up.railway.app/api/jadwal-beautician/{$id}", $data);

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    // Hapus jadwal beautician
    public function destroy($id)
    {
        try {
            // Hapus data jadwal menggunakan API
            Http::withoutVerifying()->delete("https://backend-klinik-aesthetic-production.up.railway.app/api/jadwal-beautician/{$id}");

            return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal.');
        }
    }
}
