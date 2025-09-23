<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1) Ambil tahun dari query string atau session, default = tahun sekarang
        $year = $request->query('year', session('dashboard_year', date('Y')));
        session(['dashboard_year' => $year]);

        // Panggil API untuk hitung konsultasi Verifikasi
        $respKonsultasi = Http::get('https://klinikneshnavya.com/api/konsultasi/total-verifikasi');
        $consultCount   = $respKonsultasi->successful()
            ? $respKonsultasi->json('total_verifikasi', 0)
            : 0;

        // Panggil API untuk hitung booking treatment Verifikasi
        $respTreatment = Http::get('https://klinikneshnavya.com/api/bookingTreatments/total-verifikasi');
        $treatCount    = $respTreatment->successful()
            ? $respTreatment->json('total_verifikasi', 0)
            : 0;

        // Panggil API untuk hitung booking treatment PAKET (Verifikasi)
        $respTreatmentPaket = Http::get('https://klinikneshnavya.com/api/booking-treatment-paket/total-verifikasi');
        $treatPaketCount    = $respTreatmentPaket->successful()
            ? $respTreatmentPaket->json('total_verifikasi', 0)
            : 0;

        $respPending = Http::get('https://klinikneshnavya.com/api/komplain/total-pending');
        $pendingCount = $respPending->successful()
            ? $respPending->json('total_pending', 0)
            : 0;

        // 1) Panggil API pembayaran treatment
        $respT       = Http::get("https://klinikneshnavya.com/api/pembayaran-treatment/total-bayar?year={$year}");
        $treatMonthly = $respT->successful() ? $respT->json('bayar_perbulan', []) : [];

        // 2) Buat array semua bulan dari Jan–Des
        $allMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $allMonths[] = sprintf('%04d-%02d', $year, $m);
        }

        // 3) Map total per bulan dari API
        $mapT = [];
        foreach ($treatMonthly as $row) {
            $mapT[$row['bulan']] = $row['total'];
        }

        // 4) Siapkan labels & data lengkap (0 jika tidak ada)
        $treatmentLabels = $allMonths;
        $treatmentData   = array_map(fn($mo) => $mapT[$mo] ?? 0, $allMonths);

        // 5) Panggil API pembayaran produk
        $respP        = Http::get("https://klinikneshnavya.com/api/pembayaran-produk/total-bayar?year={$year}");
        $prodMonthly  = $respP->successful() ? $respP->json('bayar_per_bulan', []) : [];

        // 6) Map total per bulan produk
        $mapP = [];
        foreach ($prodMonthly as $row) {
            $mapP[$row['bulan']] = $row['total'];
        }

        // 7) Siapkan labels & data produk lengkap
        $productLabels = $allMonths;
        $productData   = array_map(fn($mo) => $mapP[$mo] ?? 0, $allMonths);

        // ==== Chart: Top Treatments & Top Products ====
        $base = 'https://klinikneshnavya.com/api'; // atau pakai config/services kalau mau

        // kalau API support limit, sekalian minta 3
        $respTopTreat    = Http::get("$base/top-treatment",          ['limit' => 3]);
        $respTopProduct  = Http::get("$base/top-produk",             ['limit' => 3]);
        $respTopPaket    = Http::get("$base/top-paket-treatment",    ['limit' => 3]);

        $topTreatments = $respTopTreat->successful()   ? ($respTopTreat->json('data') ?? []) : [];
        $topProducts   = $respTopProduct->successful() ? ($respTopProduct->json('data') ?? []) : [];
        $topPakets     = $respTopPaket->successful()   ? ($respTopPaket->json('data') ?? []) : [];

        // fallback: sort desc & ambil 3 teratas jika API balikin >3
        $topTreatments = collect($topTreatments)->sortByDesc('total_dibeli')->take(3)->values()->all();
        $topProducts   = collect($topProducts)->sortByDesc('total_dibeli')->take(3)->values()->all();
        $topPakets     = collect($topPakets)->sortByDesc('total_dibeli')->take(3)->values()->all();

        // siapkan label+value untuk chart bar
        $topTreatLabels = array_map(fn($i) => $i['nama_treatment'],         $topTreatments);
        $topTreatValues = array_map(fn($i) => (int) $i['total_dibeli'],     $topTreatments);
        $topProdLabels  = array_map(fn($i) => $i['nama_produk'],            $topProducts);
        $topProdValues  = array_map(fn($i) => (int) $i['total_dibeli'],     $topProducts);

        // NEW: Top Paket Treatment
        $topPaketLabels = array_map(fn($i) => $i['nama_paket_treatment'],   $topPakets);
        $topPaketValues = array_map(fn($i) => (int) $i['total_dibeli'],     $topPakets);

        // Kirim ke view
        return view('dashboard.dashboard', compact(
            'year',
            'consultCount',
            'treatCount',
            'pendingCount',
            'treatmentLabels',
            'treatmentData',
            'productLabels',
            'productData',
            'topTreatLabels',
            'topTreatValues',
            'topProdLabels',
            'topProdValues',
            // NEW
            'topPaketLabels',
            'topPaketValues',
            // NEW
            'treatPaketCount'
        ));
    }

    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if (! session()->has('user')) {
    //             return redirect()->route('login.form');
    //         }
    //         $role = session('user.role');
    //         if (! in_array($role, ['dokter', 'beautician', 'front office'])) {
    //             return redirect()->route('login.form');
    //         }
    //         return $next($request);
    //     });
    // }
}
