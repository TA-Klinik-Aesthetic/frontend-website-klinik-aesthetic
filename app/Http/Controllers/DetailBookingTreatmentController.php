<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DetailBookingTreatmentController extends Controller
{

    public function index()
    {
        // Ambil data booking treatment
        $bookingResponse = Http::get('http://127.0.0.1:8080/api/detailBookingTreatments');
        $bookingTreatments = $bookingResponse->json()['booking_treatments'];

        // Ambil data pengguna
        $usersResponse = Http::get('http://127.0.0.1:8080/api/users');
        $users = $usersResponse->json()['data'];

        // Membuat array untuk memetakan id_user ke nama_user
        $usersMap = [];
        foreach ($users as $user) {
            $usersMap[$user['id_user']] = $user['nama_user'];
        }

        // Gabungkan data booking dan nama pengguna
        foreach ($bookingTreatments as &$booking) {
            $booking['user_name'] = $usersMap[$booking['id_user']] ?? 'Unknown';
        }

        return view('treatment.bookingTreatment', compact('bookingTreatments'));
    }

    public function create()
    {
        // Mengambil data dari API untuk pengisian form
        $users = Http::get('http://127.0.0.1:8080/api/users')->json('data');
        $promos = Http::get('http://127.0.0.1:8080/api/promos')->json('data');
        $treatments = Http::get('http://127.0.0.1:8080/api/treatments')->json('data');
        $dokters = Http::get('http://127.0.0.1:8080/api/dokters')->json('data');
        $beauticians = Http::get('http://127.0.0.1:8080/api/beauticians')->json('data');

        return view('treatment.addBooking', compact('users', 'promos', 'treatments', 'dokters', 'beauticians'));
    }

    // Menyimpan data booking treatment
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'waktu_treatment' => 'required|date',
            'status_booking_treatment' => 'required|string',
            'details' => 'required|array',
        ]);

        // Data booking treatment yang akan dikirim ke API
        $bookingData = [
            'id_user' => $request->id_user,
            'waktu_treatment' => $request->waktu_treatment,
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
    
//     public function store(Request $request)
//     {

//         $details = [];

//         foreach ($request->details as $detail) {
//             $details[] = [
//                 'id_treatment' => $detail['id_treatment'],
//                 'id_dokter' => $detail['id_dokter'],
//                 'id_beautician' => $detail['id_beautician'],
//             ];
//         }

//         $response = Http::post($this->baseApiUrl, [
//             'id_user' => $request->id_user,
//             'waktu_treatment' => $request->waktu_treatment,
//             'status_booking_treatment' => $request->status_booking_treatment,
//             'potongan_harga' => $request->potongan_harga,
//             'details' => $details,
//         ]);

//         if ($response->successful()) {
//             return back()->with('success', 'Data berhasil dikirim');
//         } else {
//             return back()->with('error', 'Gagal mengirim data');
//         }
//     }

//     public function show($id)
//     {
//         // Ambil detail booking treatment berdasarkan id_detail_booking_treatment
//         $responseDetailBooking = Http::get("{$this->baseApiUrl}/{$id}");

//         if ($responseDetailBooking->successful()) {
//             $detail = $responseDetailBooking->json();
//         } else {
//             return redirect()->back()->with('error', 'Detail Booking Treatment tidak ditemukan.');
//         }

//         // Ambil data dokter dari API
//         $responseDokter = Http::get($this->apiDokters);
//         $dokters = $responseDokter->successful() ? $responseDokter->json()['data'] : [];

//         // Ambil data beautician dari API
//         $responseBeautician = Http::get($this->apiBeauticians);
//         $beauticians = $responseBeautician->successful() ? $responseBeautician->json()['data'] : [];

//         // Kirim data ke view untuk ditampilkan dalam form modal
//         return view('treatment.detailBookingTreatment', [
//             'detail' => $detail,
//             'dokters' => $dokters,
//             'beauticians' => $beauticians,
//         ]);
//     }

//     public function update(Request $request, $id)
//     {
//         // Kirim data yang akan diupdate ke API backend tanpa validasi
//         $response = Http::put("{$this->baseApiUrl}/{$id}", [
//             'id_dokter' => $request->id_dokter,
//             'id_beautician' => $request->id_beautician,
//             'status_booking_treatment' => $request->status_booking_treatment,
//         ]);

//         // Periksa apakah respons API berhasil
//         if ($response->successful()) {
//             return back()->with('success', 'Data berhasil diperbarui');
//         } else {
//             // Ambil pesan error dari API jika ada
//             $errorMessage = $response->json('message') ?? 'Gagal memperbarui data';
//             return back()->with('error', $errorMessage);
//         }
//     }
// }
}