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
        $response = Http::get('https://klinikneshnavya.com/api/laporan-penjualan-treatment');

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
        $response = Http::get("https://klinikneshnavya.com/api/laporan-treatment-hari?tanggal={$tanggal}");

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
        $response = Http::get("https://klinikneshnavya.com/api/laporan-treatment-bulan?tahun={$tahun}&bulan={$bulan}");

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
        $response = Http::get("https://klinikneshnavya.com/api/laporan-treatment-hari?tanggal={$tanggal}");

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
        $response = Http::get("https://klinikneshnavya.com/api/laporan-treatment-bulan?tahun={$tahun}&bulan={$bulan}");

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
        $response = Http::get('https://klinikneshnavya.com/api/laporan-penjualan-produk');

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
        $response = Http::get("https://klinikneshnavya.com/api/laporan-produk-hari?tanggal={$tanggal}");

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
        $response = Http::get("https://klinikneshnavya.com/api/laporan-produk-bulan?bulan={$bulan}&tahun={$tahun}");

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
        $response = Http::get("https://klinikneshnavya.com/api/laporan-produk-hari?tanggal={$tanggal}");

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
        $response = Http::get("https://klinikneshnavya.com/api/laporan-produk-bulan?bulan={$bulan}&tahun={$tahun}");

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = [];
        }

        // Generate PDF using the data retrieved
        $pdf = PDF::loadView('laporan.exportBulananProduk', compact('data', 'bulan', 'tahun'));
        return $pdf->download('laporan_bulanan_' . $bulan . '-' . $tahun . '.pdf');
    }

     // ❗️SESUIKAN kalau endpoint backend-mu beda nama/path
     protected string $BASE = 'https://klinikneshnavya.com/api';
     protected string $EP_ALL    = '/laporan-penjualan-paket-treatment';
     protected string $EP_DAILY  = '/laporan-paket-treatment-hari';    // ?tanggal=YYYY-MM-DD
     protected string $EP_MONTH  = '/laporan-paket-treatment-bulan';   // ?bulan=MM&tahun=YYYY
 
     // Default: semua data
     public function indexPaketTreatment()
     {
         $response = Http::get($this->BASE . $this->EP_ALL);
         $data = $response->successful() ? $response->json() : [];
 
         return view('laporan.laporanPaketTreatment', compact('data'));
     }
 
     // Harian
     public function laporanHarianPaketTreatment(Request $request)
     {
         $tanggal = $request->input('tanggal'); // YYYY-MM-DD
         $response = Http::get($this->BASE . $this->EP_DAILY . "?tanggal={$tanggal}");
         $data = $response->successful() ? $response->json() : [];
 
         return view('laporan.laporanPaketTreatment', compact('data'));
     }
 
     // Bulanan
     public function laporanBulananPaketTreatment(Request $request)
     {
         $bulanInput = $request->input('bulan'); // "YYYY-MM"
         $tahun = substr($bulanInput, 0, 4);
         $bulan = substr($bulanInput, 5, 2);
 
         $response = Http::get($this->BASE . $this->EP_MONTH . "?bulan={$bulan}&tahun={$tahun}");
         $data = $response->successful() ? $response->json() : [];
 
         return view('laporan.laporanPaketTreatment', compact('data'));
     }
 
     // Export Harian → PDF
     public function exportHarianPaketTreatment(Request $request)
     {
         $tanggal = $request->query('tanggal');
         $response = Http::get($this->BASE . $this->EP_DAILY . "?tanggal={$tanggal}");
         $data = $response->successful() ? $response->json() : [];
 
         $pdf = PDF::loadView('laporan.exportHarianPaketTreatment', compact('data', 'tanggal'));
         return $pdf->download('laporan_paket_treatment_harian_' . $tanggal . '.pdf');
     }
 
     // Export Bulanan → PDF
     public function exportBulananPaketTreatment(Request $request)
     {
         $bulanInput = $request->input('bulan'); // "YYYY-MM"
         $tahun = substr($bulanInput, 0, 4);
         $bulan = substr($bulanInput, 5, 2);
 
         $response = Http::get($this->BASE . $this->EP_MONTH . "?bulan={$bulan}&tahun={$tahun}");
         $data = $response->successful() ? $response->json() : [];
 
         $pdf = PDF::loadView('laporan.exportBulananPaketTreatment', compact('data', 'bulan', 'tahun'));
         return $pdf->download("laporan_paket_treatment_bulanan_{$bulan}-{$tahun}.pdf");
     }
}
