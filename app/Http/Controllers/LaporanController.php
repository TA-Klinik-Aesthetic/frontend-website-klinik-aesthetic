<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function indexTreatment()
    {
        // Get data from the backend API for laporan treatment
        $response = Http::get('http://127.0.0.1:8080/api/laporan-penjualan-treatment');

        // Check if the response is successful
        if ($response->successful()) {
            $data = $response->json();  // Get the data from the API response
        } else {
            $data = [];
        }

        return view('laporan.laporanTreatment', compact('data'));
    }

    public function laporanHarianTreatment(Request $request)
    {
        // Get the date from the request
        $tanggal = $request->input('tanggal');

        // Fetch the data for daily report from the backend API
        $response = Http::get("http://127.0.0.1:8080/api/laporan-treatment-hari?tanggal={$tanggal}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        return view('laporan.laporanTreatment', compact('data'));
    }

    public function laporanBulananTreatment(Request $request)
    {
        // Get the month and year from the request
        $bulan = $request->input('bulan');  // This will get the "YYYY-MM" value from the input field
        $tahun = substr($bulan, 0, 4);  // Extract year from the "YYYY-MM" format
        $bulan = substr($bulan, 5, 2);  // Extract month from the "YYYY-MM" format

        // Fetch the data for monthly report from the backend API
        $response = Http::get("http://127.0.0.1:8080/api/laporan-treatment-bulan?tahun={$tahun}&bulan={$bulan}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        return view('laporan.laporanTreatment', compact('data'));
    }

    public function exportHarianTreatment(Request $request)
    {
        $tanggal = $request->query('tanggal');

        // Fetch data from the 'laporan-treatment-hari' API endpoint
        $response = Http::get("http://127.0.0.1:8080/api/laporan-treatment-hari?tanggal={$tanggal}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        // Generate PDF using the data retrieved
        $pdf = PDF::loadView('laporan.exportHarianTreatment', compact('data', 'tanggal'));
        return $pdf->download('laporan_harian_' . $tanggal . '.pdf');
    }

    public function exportBulananTreatment(Request $request)
    {
        $bulan = $request->input('bulan');  // This will get the "YYYY-MM" value from the input field
        $tahun = substr($bulan, 0, 4);  // Extract year from the "YYYY-MM" format
        $bulan = substr($bulan, 5, 2);  // Extract month from the "YYYY-MM" format

        // Fetch data from the 'laporan-treatment-bulan' API endpoint
        $response = Http::get("http://127.0.0.1:8080/api/laporan-treatment-bulan?tahun={$tahun}&bulan={$bulan}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        // Generate PDF using the data retrieved
        $pdf = PDF::loadView('laporan.exportBulananTreatment', compact('data', 'bulan', 'tahun'));
        return $pdf->download('laporan_bulanan_' . $bulan . '-' . $tahun . '.pdf');
    }

    // Show product report data (Default)
    public function indexProduk()
    {
        // Get data from the backend API for laporan produk
        $response = Http::get('http://127.0.0.1:8080/api/laporan-penjualan-produk');

        // Check if the response is successful
        if ($response->successful()) {
            $data = $response->json();  // Get the data from the API response
        } else {
            $data = [];
        }

        return view('laporan.laporanProduk', compact('data'));
    }

    // Laporan Harian Produk (Daily Report)
    public function laporanHarianProduk(Request $request)
    {
        // Get the date from the request
        $tanggal = $request->input('tanggal');

        // Fetch the data for daily report from the backend API
        $response = Http::get("http://127.0.0.1:8080/api/laporan-produk-hari?tanggal={$tanggal}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        return view('laporan.laporanProduk', compact('data'));
    }

    // Laporan Bulanan Produk (Monthly Report)
    public function laporanBulananProduk(Request $request)
    {
        // Get the month and year from the request
        $bulan = $request->input('bulan');  // This will get the "YYYY-MM" value from the input field
        $tahun = substr($bulan, 0, 4);  // Extract year from the "YYYY-MM" format
        $bulan = substr($bulan, 5, 2);  // Extract month from the "YYYY-MM" format

        // Fetch the data for monthly report from the backend API
        $response = Http::get("http://127.0.0.1:8080/api/laporan-produk-bulan?bulan={$bulan}&tahun={$tahun}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        return view('laporan.laporanProduk', compact('data'));
    }

    // Export Harian Produk (Daily Report) to PDF
    public function exportHarianProduk(Request $request)
    {
        $tanggal = $request->query('tanggal');

        // Fetch data from the 'laporan-produk-hari' API endpoint
        $response = Http::get("http://127.0.0.1:8080/api/laporan-produk-hari?tanggal={$tanggal}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        // Generate PDF using the data retrieved
        $pdf = PDF::loadView('laporan.exportHarianProduk', compact('data', 'tanggal'));
        return $pdf->download('laporan_harian_' . $tanggal . '.pdf');
    }

    // Export Bulanan Produk (Monthly Report) to PDF
    public function exportBulananProduk(Request $request)
    {
        $bulan = $request->input('bulan');  // This will get the "YYYY-MM" value from the input field
        $tahun = substr($bulan, 0, 4);  // Extract year from the "YYYY-MM" format
        $bulan = substr($bulan, 5, 2);  // Extract month from the "YYYY-MM" format

        // Fetch data from the 'laporan-produk-bulan' API endpoint
        $response = Http::get("http://127.0.0.1:8080/api/laporan-produk-bulan?bulan={$bulan}&tahun={$tahun}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        // Generate PDF using the data retrieved
        $pdf = PDF::loadView('laporan.exportBulananProduk', compact('data', 'bulan', 'tahun'));
        return $pdf->download('laporan_bulanan_' . $bulan . '-' . $tahun . '.pdf');
    }
}
