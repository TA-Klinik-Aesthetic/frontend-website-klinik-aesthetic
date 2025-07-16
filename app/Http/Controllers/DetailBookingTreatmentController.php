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
        $bookingResponse = Http::get('https://klinikneshnavya.com/api/bookingTreatment');
        $bookingTreatments = $bookingResponse->json()['booking_treatments'] ?? [];

        $users = Http::get('https://klinikneshnavya.com/api/user')->json()['data'];

        // Hanya ambil promo yang jenis_promo-nya Treatment
        $promosResponse = Http::get('https://klinikneshnavya.com/api/promo');
        $promos = collect($promosResponse->json()['data'])
            ->where('jenis_promo', 'Treatment')
            ->where('status_promo', 'Aktif')
            ->values();

        $jenisResp           = Http::get('https://klinikneshnavya.com/api/jenisTreatment');
        $jenisTreatments     = $jenisResp->json()['data'] ?? [];

        $treatResp           = Http::get('https://klinikneshnavya.com/api/treatment');
        $treatments          = $treatResp->json()['data'] ?? [];

        $dokters = Http::get('https://klinikneshnavya.com/api/dokter')->json()['data'];
        $beauticians = Http::get('https://klinikneshnavya.com/api/beautician')->json()['data'];
        $kompensasis = Http::get('https://klinikneshnavya.com/api/kompensasi-diberikan')->json() ?? [];

        $groupedByJenis = collect($treatments)->groupBy('id_jenis_treatment')->toArray();
        foreach ($jenisTreatments as &$jenis) {
            $jenis['treatment'] = $groupedByJenis[$jenis['id_jenis_treatment']] ?? [];
        }
        unset($jenis);

        // Gabungkan nama user
        $usersMap = [];
        foreach ($users as $user) {
            $usersMap[$user['id_user']] = $user['nama_user'];
        }
        foreach ($bookingTreatments as &$booking) {
            $booking['user_name'] = $usersMap[$booking['id_user']] ?? 'Unknown';
        }

        // ⬅️ Tambahkan sort di sini
        $bookingTreatments = collect($bookingTreatments)
            ->sortByDesc('id_booking_treatment')   // Urut dari id terbesar
            ->values()                             // Reset index ke 0,1,2...
            ->all();

        return view('treatment.bookingTreatment', compact(
            'bookingTreatments',
            'users',
            'promos',
            'treatments',
            'dokters',
            'beauticians',
            'kompensasis',
            'jenisTreatments'
        ));
    }

    // public function getSlots($tanggal)
    // {
    //     $response = Http::get("https://klinikneshnavya.com/api/jadwal-treatment/{$tanggal}");

    //     if ($response->successful()) {
    //         return response()->json($response->json());
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Gagal mengambil slot jadwal.'
    //     ], 500);
    // }

    // Menyimpan data booking treatment
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'waktu_treatment' => 'required|date',
            // 'id_detail_jadwal_treatment' => 'required',
            'id_dokter' => 'nullable|integer',     // Tambahkan ini
            'id_beautician' => 'required|integer', // Tambahkan ini
            'details' => 'required|array',
        ]);

        // Data booking treatment yang akan dikirim ke API
        $bookingData = [
            'id_user' => $request->id_user,
            'waktu_treatment' => $request->waktu_treatment,
            // 'id_detail_jadwal_treatment' => $request->id_detail_jadwal_treatment,
            'id_dokter' => $request->id_dokter,         // Tambahkan ini
            'id_beautician' => $request->id_beautician, // Tambahkan ini
            'id_promo' => $request->id_promo,
            'details' => $request->details
        ];

        // Kirim data ke API untuk menyimpan booking treatment
        $response = Http::post('https://klinikneshnavya.com/api/bookingTreatment', $bookingData);

        if ($response->successful()) {
            return redirect()->route('bookingTreatment.index')->with('success', 'Booking Treatment berhasil ditambahkan!');
        } else {
            return back()->with('error', 'Terjadi kesalahan, silakan coba lagi.');
        }
    }


    public function show($id)
    {
        $response = Http::get("https://klinikneshnavya.com/api/bookingTreatment/{$id}");

        if (! $response->successful()) {
            return redirect()->back()->with('error', 'Gagal mengambil detail booking.');
        }

        // langsung ambil objek booking_treatment
        $booking = $response->json('booking_treatment');

        return view('treatment.detailBooking', compact('booking'));
    }



    public function update(Request $request, $id)
    {
        $response = Http::put("https://klinikneshnavya.com/api/bookingTreatment/{$id}", [
            'id_dokter' => $request->input('id_dokter'),
            'id_beautician' => $request->input('id_beautician'),
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Detail booking berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui detail booking.');
    }

    public function updateStatus(Request $request, $id)
    {
        // 1) Validasi input hanya boleh Selesai atau Dibatalkan
        $validated = $request->validate([
            'status_booking_treatment' => 'required|string|in:Treatment dimulai,Selesai,Dibatalkan',
        ]);

        // 2) Panggil endpoint internal untuk update status
        $response = Http::put(
            "https://klinikneshnavya.com/api/statusBookingTreatment/{$id}",
            ['status_booking_treatment' => $validated['status_booking_treatment']]
        );

        // 3) Jika sukses, redirect dengan pesan sukses
        if ($response->successful()) {
            return redirect()
                ->route('bookingTreatment.index')  // sesuaikan dengan nama route index-mu
                ->with('success', 'Status booking treatment berhasil diperbarui.');
        }

        // 4) Jika gagal, kembalikan error
        return back()->with('error', 'Gagal mengubah status booking treatment. ' . $response->body());
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
