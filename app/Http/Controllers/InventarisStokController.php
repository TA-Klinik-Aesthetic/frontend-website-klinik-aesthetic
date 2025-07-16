<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InventarisStokController extends Controller
{
    // public function index()
    // {
    //     // Ambil data dari API
    //     $response = Http::get('http://127.0.0.1:8080/api/inventaris-stok');

    //     // Ambil data dari response JSON
    //     $inventaris = $response->json()['data'] ?? [];

    //     // Kirim ke view
    //     return view('laporan.inventarisStok', compact('inventaris'));
    // }
}
