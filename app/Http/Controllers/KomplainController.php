<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KomplainController extends Controller
{
    protected $komplainApiUrl = 'https://klinikneshnavya.com/api/komplain';
    protected $kompensasiApiUrl = 'https://klinikneshnavya.com/api/kompensasi'; // API untuk mengambil daftar kompensasi


    public function index()
    {
        // Ambil data komplain
        $komplainResponse = Http::get($this->komplainApiUrl);
        $komplainList = $komplainResponse->json();


        // Ambil data kompensasi
        $kompensasiResponse = Http::get($this->kompensasiApiUrl);
        $kompensasiList = $kompensasiResponse->json();

        // Ambil data kompensasi yang sudah diberikan
        $kompensasiDiberikanResponse = Http::get('https://klinikneshnavya.com/api/kompensasi-diberikan');
        $kompensasiDiberikan = $kompensasiDiberikanResponse->json();

        // Gabungkan data komplain dengan waktu_treatment dan treatment
        foreach ($komplainList as &$komplain) {

            // Menambahkan kompensasi yang diberikan ke komplain
            $komplain['kompensasi_diberikan'] = $this->getKompensasiDiberikan($komplain['id_komplain'], $kompensasiDiberikan);
        }

        return view('komplain.listKomplain', compact('komplainList', 'kompensasiList', 'kompensasiDiberikan'));
    }
    

    public function getKompensasiDiberikan($idKomplain, $kompensasiDiberikan)
    {
        // Menyaring kompensasi yang diberikan berdasarkan id_komplain
        foreach ($kompensasiDiberikan as $kompensasi) {
            if ($kompensasi['id_komplain'] == $idKomplain) {
                return $kompensasi; // Mengembalikan kompensasi yang ditemukan
            }
        }
        return null; // Jika tidak ditemukan, kembalikan null
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $data = $request->validate([
            'balasan_komplain' => 'required|string',
            'id_kompensasi' => 'nullable', // Menentukan kompensasi yang akan diberikan
            'kode_kompensasi' => 'nullable',
            'tanggal_berakhir_kompensasi' => 'nullable|date',
        ]);

        try {
            // Kirim data ke API untuk update komplain
            $response = Http::put("{$this->komplainApiUrl}/{$id}", $data);

            if ($response->successful()) {
                // Cek apakah ada kompensasi yang diberikan
                if ($data['id_kompensasi'] && $data['tanggal_berakhir_kompensasi']) {
                    // Simpan data kompensasi yang diberikan jika data kompensasi lengkap
                    $kompensasiData = [
                        'id_komplain' => $id,
                        'id_kompensasi' => $data['id_kompensasi'],
                        'tanggal_berakhir_kompensasi' => $data['tanggal_berakhir_kompensasi'],
                    ];

                    // Kirim data ke API kompensasi diberikan
                    $kompensasiResponse = Http::post('https://klinikneshnavya.com/api/kompensasi-diberikan', $kompensasiData);

                    if ($kompensasiResponse->successful()) {
                        return redirect()->route('komplain.index')->with('success', 'Komplain berhasil diperbarui dan kompensasi berhasil dikirim');
                    } else {
                        return back()->with('error', 'Gagal mengirim kompensasi');
                    }
                } else {
                    return redirect()->route('komplain.index')->with('success', 'Komplain berhasil diperbarui');
                }
            }

            return back()->with('error', 'Gagal memperbarui komplain');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
}
