<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FeedbackTreatmentController extends Controller
{
    protected $baseApiUrl = 'http://127.0.0.1:8080/api/feedbackTreatments';

    public function index()
    {
        // Ambil data feedback treatment
        $feedbackResponse = Http::get($this->baseApiUrl);
        $feedbacks = $feedbackResponse->json()['data'] ?? [];
        
        // Ambil data detail booking treatment
        $detailBookingResponse = Http::get('http://127.0.0.1:8080/api/detailBookingTreatments');
        $detailBookingData = $detailBookingResponse->json()['booking_treatments'][0]['detail_booking'] ?? [];  // Sesuaikan dengan struktur JSON
    
        // Buat mapping id_detail_booking_treatment ke detail booking
        $detailBookingMap = [];
        foreach ($detailBookingData as $detail) {
            $detailBookingMap[$detail['id_detail_booking_treatment']] = $detail;
        }
        
        // Gabungkan data feedback dengan detail booking treatment
        foreach ($feedbacks as &$feedback) {
            $idDetail = $feedback['id_detail_booking_treatment'];
            
            if (isset($detailBookingMap[$idDetail])) {
                $detail = $detailBookingMap[$idDetail];
    
                // Ambil nama dokter atau beautician
                $dokter = $detail['dokter'] ? $detail['dokter']['nama_dokter'] : '-';
                $beautician = $detail['beautician'] ? $detail['beautician']['nama_beautician'] : '-';
        
                // Ambil nama treatment
                $treatment = $detail['treatment'] ? $detail['treatment']['nama_treatment'] : '-';
        
                $feedback['nama_dokter'] = $dokter;
                $feedback['nama_beautician'] = $beautician;
                $feedback['nama_treatment'] = $treatment;  // Menambahkan nama treatment
            } else {
                // Jika tidak ditemukan, set default '-'
                $feedback['nama_dokter'] = '-';
                $feedback['nama_beautician'] = '-';
                $feedback['nama_treatment'] = '-';
            }
        }
        
        return view('feedback.feedbackTreatment', compact('feedbacks'));
    }
    

    

    // public function show($id)
    // {
    //     // Panggil API untuk mendapatkan detail feedback berdasarkan ID
    //     $response = Http::get("{$this->baseApiUrl}/{$id}");
    
    //     // Cek jika respons dari API berhasil
    //     if ($response->successful()) {
    //         // Ambil seluruh respons sebagai array
    //         $feedbackData = $response->json();
    
    //         // Cek apakah data tersedia dalam key 'data'
    //         $feedback = $feedbackData['data'] ?? $feedbackData; // Gunakan key 'data' jika ada
    
    //         if ($feedback) {
    //             return view('feedback.detailFeedbackTreatment', compact('feedback'));
    //         } else {
    //             return back()->with('error', 'Feedback tidak ditemukan.');
    //         }
    //     } else {
    //         return back()->with('error', 'Tidak dapat mengambil detail feedback treatment.');
    //     }
    // }
    
    

    // // Store Feedback Treatments
    // public function store(Request $request)
    // {
    //     $response = Http::post($this->baseApiUrl, $request->all());

    //     if ($response->successful()) {
    //         return redirect()->route('feedback.feedbackTreatment.index')->with('success', 'Feedback berhasil ditambahkan');
    //     } else {
    //         return redirect()->route('feedback.feedbackTreatment.index')->with('error', 'Gagal menambahkan feedback');
    //     }
    // }

    // // Update Feedback Treatments
    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'rating' => 'required|integer|min:1|max:5',
    //         'teks_feedback' => 'required|string',
    //         'balasan_feedback' => 'nullable|string',
    //     ]);

    //     $response = Http::put("{$this->baseApiUrl}/{$id}", $validated);

    //     if ($response->successful()) {
    //         return redirect()->route('feedback.feedbackTreatment.index')
    //             ->with('success', 'Feedback berhasil diupdate');
    //     } else {
    //         return back()->with('error', 'Gagal mengupdate feedback');
    //     }
    // }

    // Delete Feedback Treatments
    public function destroy($id)
    {
        $response = Http::delete("{$this->baseApiUrl}/{$id}");
    
        if ($response->successful()) {
            return redirect()->route('feedback.feedbackTreatment.index')
                ->with('success', 'Feedback berhasil dihapus');
        } else {
            return back()->with('error', 'Gagal menghapus feedback');
        }
    }
    
}