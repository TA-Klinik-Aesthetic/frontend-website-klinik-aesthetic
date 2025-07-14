<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FeedbackTreatmentController extends Controller
{
    protected $baseApiUrl = 'https://klinikneshnavya.com/api/feedbackTreatments';

    public function index()
    {
        // 1) Ambil seluruh feedback treatment
        $response  = Http::get($this->baseApiUrl);
        $feedbacks = $response->json()['data'] ?? [];

        // 2) Untuk setiap feedback, tambahkan langsung nama treatment dari nested detail_booking → treatment
        foreach ($feedbacks as &$f) {
            $f['nama_treatment'] = data_get($f, 'detail_booking.treatment.nama_treatment', '-');
        }
        unset($f);

        // 3) Kirim ke view
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