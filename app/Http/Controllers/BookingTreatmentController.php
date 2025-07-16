<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingTreatmentController extends Controller
{
    protected $baseApiUrl = 'http://127.0.0.1:8080/api/bookingTreatments';

    // public function index()
    // {
    //     try {
    //         $response = Http::get($this->baseApiUrl);
    
    //         if ($response->successful()) {
    //             $bookings = is_array($response->json()) ? $response->json() : [];
    //         } else {
    //             $bookings = [];
    //             return back()->with('error', 'Gagal mengambil data booking treatment dari API.');
    //         }
    //     } catch (\Exception $e) {
    //         $bookings = [];
    //         return back()->with('error', 'Terjadi kesalahan saat mengakses API: ' . $e->getMessage());
    //     }
    
    //     // Kirim data ke view
    //     return view('treatment.bookingTreatment', ['bookings' => $bookings]);
    // }
    

    // public function show($id)
    // {
    //     $response = Http::get("{$this->baseApiUrl}/{$id}");
    //     $booking = $response->json()['data'] ?? null;

    //     if ($booking) {
    //         return view('treatment.detailBookingTreatment', ['booking' => $booking]);
    //     } else {
    //         return back()->with('error', 'Data booking treatment tidak ditemukan');
    //     }
    // }
}
