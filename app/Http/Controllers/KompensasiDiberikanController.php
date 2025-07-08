<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KompensasiDiberikanController extends Controller
{
    protected $kompensasiDiberikanApi = 'https://klinikneshnavya.com/api/kompensasi-diberikan';
    protected $komplainApi = 'https://klinikneshnavya.com/api/komplain';
    protected $kompensasiApi = 'https://klinikneshnavya.com/api/kompensasi';

    public function index()
    {
        $kompensasiDiberikan = Http::get($this->kompensasiDiberikanApi)->json() ?? [];
        $komplainList = Http::get($this->komplainApi)->json() ?? [];
        $kompensasiList = Http::get($this->kompensasiApi)->json() ?? [];

        // Filter komplain yang pemberian_kompensasinya masih "Menunggu pengiriman"
        $komplainList = array_filter($komplainList, function ($komplain) {
            return isset($komplain['pemberian_kompensasi']) && $komplain['pemberian_kompensasi'] === 'Menunggu pengiriman';
        });


        return view('komplain.kompensasiDiberikan', compact('kompensasiDiberikan', 'komplainList', 'kompensasiList'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'id_komplain' => 'required',
    //         'id_kompensasi' => 'required|array',
    //         'kode_kompensasi' => 'required|array',
    //         'tanggal_berakhir_kompensasi' => 'required|array',
    //     ]);

    //     $data = [];

    //     foreach ($request->id_kompensasi as $index => $id_kompensasi) {
    //         $data[] = [
    //             'id_komplain' => $request->id_komplain,
    //             'id_kompensasi' => $id_kompensasi,
    //             'kode_kompensasi' => $request->kode_kompensasi[$index],
    //             'tanggal_berakhir_kompensasi' => $request->tanggal_berakhir_kompensasi[$index],
    //         ];
    //     }

    //     $response = Http::post($this->kompensasiDiberikanApi, $data);

    //     if ($response->successful()) {
    //         return redirect()->route('kompensasi-diberikan.index')->with('success', 'Kompensasi berhasil diberikan');
    //     }

    //     return back()->with('error', 'Gagal memberikan kompensasi');
    // }
}
