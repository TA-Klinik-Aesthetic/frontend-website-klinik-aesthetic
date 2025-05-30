<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class DetailBookingTreatmentController extends Controller
{

    public function index()
    {
        $bookingResponse = Http::get('http://127.0.0.1:8080/api/detailBookingTreatments');
        $bookingTreatments = $bookingResponse->json()['booking_treatments'] ?? [];

        $users = Http::get('http://127.0.0.1:8080/api/users')->json()['data'];
        $promos = Http::get('http://127.0.0.1:8080/api/promos')->json()['data'];
        $treatments = Http::get('http://127.0.0.1:8080/api/treatments')->json()['data'];
        $dokters = Http::get('http://127.0.0.1:8080/api/dokters')->json()['data'];
        $beauticians = Http::get('http://127.0.0.1:8080/api/beauticians')->json()['data'];
        $kompensasis = Http::get('http://127.0.0.1:8080/api/kompensasi-diberikan')->json() ?? [];

        // Gabungkan nama user
        $usersMap = [];
        foreach ($users as $user) {
            $usersMap[$user['id_user']] = $user['nama_user'];
        }
        foreach ($bookingTreatments as &$booking) {
            $booking['user_name'] = $usersMap[$booking['id_user']] ?? 'Unknown';
        }

        return view('treatment.bookingTreatment', compact(
            'bookingTreatments',
            'users',
            'promos',
            'treatments',
            'dokters',
            'beauticians',
            'kompensasis'
        ));
    }


    // Menyimpan data booking treatment
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'waktu_treatment' => 'required|date',
            'id_dokter' => 'nullable|integer',     // Tambahkan ini
            'id_beautician' => 'required|integer', // Tambahkan ini
            'status_booking_treatment' => 'required|string',
            'details' => 'required|array',
        ]);

        // Data booking treatment yang akan dikirim ke API
        $bookingData = [
            'id_user' => $request->id_user,
            'waktu_treatment' => $request->waktu_treatment,
            'id_dokter' => $request->id_dokter,         // Tambahkan ini
            'id_beautician' => $request->id_beautician, // Tambahkan ini
            'status_booking_treatment' => $request->status_booking_treatment,
            'id_promo' => $request->id_promo,
            'details' => $request->details
        ];

        // Kirim data ke API untuk menyimpan booking treatment
        $response = Http::post('http://127.0.0.1:8080/api/detailBookingTreatments', $bookingData);

        if ($response->successful()) {
            return redirect()->route('detailBooking.index')->with('success', 'Booking Treatment berhasil ditambahkan!');
        } else {
            return back()->with('error', 'Terjadi kesalahan, silakan coba lagi.');
        }
    }


    public function show($id)
    {
        // Mengambil detail booking treatment dari API
        $bookingDetail = Http::get("http://127.0.0.1:8080/api/detailBookingTreatments/{$id}")->json();

        // Mengambil data dokter dan beautician dari API
        $dokters = Http::get('http://127.0.0.1:8080/api/dokters')->json('data');
        $beauticians = Http::get('http://127.0.0.1:8080/api/beauticians')->json('data');
        $treatments = Http::get('http://127.0.0.1:8080/api/treatments')->json('data');

        // Mengirim data ke view
        return view('treatment.detailBooking', [
            'bookingDetail' => $bookingDetail,
            'dokters' => $dokters,
            'beauticians' => $beauticians,
            'treatments' => $treatments
        ]);
    }


    public function update(Request $request, $id)
    {
        $response = Http::put("http://127.0.0.1:8080/api/detailBookingTreatments/{$id}", [
            'id_dokter' => $request->input('id_dokter'),
            'id_beautician' => $request->input('id_beautician'),
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Detail booking berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui detail booking.');
    }

    // public function autocompleteKompensasi(Request $request)
    // {
    //     $term = $request->input('term');

    //     $results = KompensasiDiberikan::where('kode_kompensasi', 'like', '%' . $term . '%')
    //         ->limit(10)
    //         ->get();

    //     return response()->json(
    //         $results->map(function ($item) {
    //             return [
    //                 'id' => $item->id_kompensasi_diberikan,
    //                 'label' => $item->kode_kompensasi,
    //                 'value' => $item->kode_kompensasi, // ini yang ditampilkan di input
    //             ];
    //         })
    //     );
    // }
}
