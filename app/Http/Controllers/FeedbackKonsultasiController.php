<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FeedbackKonsultasiController extends Controller
{
    protected $baseApiUrl = 'https://klinikneshnavya.com/api/feedbackKonsultasi';
    protected $konsultasiApiUrl = 'https://klinikneshnavya.com/api/konsultasi/';
    
    public function index()
    {
        $response = Http::get($this->baseApiUrl);
    
        if ($response->successful()) {
            $feedbacks = $response->json()['data'];
    
            // Ambil nama dokter untuk setiap konsultasi
            foreach ($feedbacks as &$feedback) {
                $konsultasiResponse = Http::get($this->konsultasiApiUrl . $feedback['id_konsultasi']);
                if ($konsultasiResponse->successful()) {
                    $feedback['nama_dokter'] = $konsultasiResponse->json()['data']['dokter']['nama_dokter'] ?? 'Tidak Diketahui';
                } else {
                    $feedback['nama_dokter'] = 'Tidak Diketahui';
                }
            }
    
            return view('feedback.feedbackKonsultasi', compact('feedbacks'));
        } else {
            return view('feedback.feedbackKonsultasi', ['error' => 'Failed to fetch feedbacks']);
        }
    }
    
    // public function show($id)
    // {
    //     $response = Http::get("{$this->baseApiUrl}/{$id}");

    //     if ($response->successful()) {
    //         $feedback = $response->json();

    //         return view('feedback.detailFeedbackKonsultasi', compact('feedback'));
    //     } else {
    //         return back()->with('error', 'Tidak dapat mengambil detail feedback konsultasi.');
    //     }
    // }

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
    //             return view('feedback.detailFeedbackKonsultasi', compact('feedback'));
    //         } else {
    //             return back()->with('error', 'Feedback tidak ditemukan.');
    //         }
    //     } else {
    //         return back()->with('error', 'Tidak dapat mengambil detail feedback konsultasi.');
    //     }
    // }
    


    // // Store Feedback Konsultasi
    // public function store(Request $request)
    // {
    //     $response = Http::post($this->baseApiUrl, $request->all());

    //     if ($response->successful()) {
    //         return redirect()->route('feedback.feedbackKonsultasi.index')->with('success', 'Feedback berhasil ditambahkan');
    //     } else {
    //         return redirect()->route('feedback.feedbackKonsultasi.index')->with('error', 'Gagal menambahkan feedback');
    //     }
    // }

    // // Update Feedback Konsultasi
    // public function update(Request $request, $id)
    // {
    //     // Validasi input
    //     $validated = $request->validate([
    //         'rating' => 'required|integer|min:1|max:5',
    //         'teks_feedback' => 'required|string',
    //         'balasan_feedback' => 'nullable|string',
    //     ]);
    
    //     // Kirim request PUT ke API
    //     $response = Http::put("{$this->baseApiUrl}/{$id}", $validated);
    
    //     // Cek respons
    //     if ($response->successful()) {
    //         return redirect()->route('feedback.feedbackKonsultasi.index')
    //             ->with('success', 'Feedback berhasil diupdate');
    //     } else {
    //         return back()->with('error', 'Gagal mengupdate feedback');
    //     }
    // }
    
    // Delete Feedback Konsultasi
    public function destroy($id)
    {
        $response = Http::delete("{$this->baseApiUrl}/{$id}");
    
        if ($response->successful()) {
            return redirect()->route('feedback.feedbackKonsultasi.index')
                ->with('success', 'Feedback berhasil dihapus');
        } else {
            return back()->with('error', 'Gagal menghapus feedback');
        }
    }
    
}