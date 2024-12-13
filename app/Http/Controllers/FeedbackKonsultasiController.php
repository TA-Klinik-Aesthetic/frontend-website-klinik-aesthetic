<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FeedbackKonsultasiController extends Controller
{
    protected $baseApiUrl = 'https://backend-klinik-aesthetic-production.up.railway.app/api/feedbacks';

    // Display Feedback Konsultasi
    public function index()
    {
        $response = Http::get($this->baseApiUrl);

        if ($response->successful()) {
            $feedbacks = $response->json();
            return view('feedback.feedbackKonsultasi', compact('feedbacks'));
        } else {
            return view('feedback.feedbackKonsultasi', ['error' => 'Failed to fetch feedbacks']);
        }
    }

    public function show($id)
    {
        $response = Http::get("{$this->baseApiUrl}/{$id}");

        if ($response->successful()) {
            $feedback = $response->json();

            return view('feedback.detailFeedbackKonsultasi', compact('feedback'));
        } else {
            return back()->with('error', 'Tidak dapat mengambil detail feedback konsultasi.');
        }
    }

    // Store Feedback Konsultasi
    public function store(Request $request)
    {
        $response = Http::post($this->baseApiUrl, $request->all());

        if ($response->successful()) {
            return redirect()->route('feedback.feedbackKonsultasi.index')->with('success', 'Feedback berhasil ditambahkan');
        } else {
            return redirect()->route('feedback.feedbackKonsultasi.index')->with('error', 'Gagal menambahkan feedback');
        }
    }

    // Update Feedback Konsultasi
    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->baseApiUrl}/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('feedback.feedbackKonsultasi.index')->with('success', 'Feedback berhasil diupdate');
        } else {
            return redirect()->route('feedback.feedbackKonsultasi.index')->with('error', 'Gagal mengupdate feedback');
        }
    }

    // Delete Feedback Konsultasi
    public function destroy($id)
    {
        $response = Http::delete("{$this->baseApiUrl}/{$id}");

        if ($response->successful()) {
            return redirect()->route('feedback.feedbackKonsultasi.index')->with('success', 'Feedback berhasil dihapus');
        } else {
            return redirect()->route('feedback.feedbackKonsultasi.index')->with('error', 'Gagal menghapus feedback');
        }
    }
}