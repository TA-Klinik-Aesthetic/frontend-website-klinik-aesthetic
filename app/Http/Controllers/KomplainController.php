<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KomplainController extends Controller
{
    protected $komplainApiUrl = 'http://127.0.0.1:8080/api/komplain';
    protected $bookingTreatmentApiUrl = 'http://127.0.0.1:8080/api/detailBookingTreatments'; // API untuk mendapatkan data booking treatment
    protected $kompensasiApiUrl = 'http://127.0.0.1:8080/api/kompensasi'; // API untuk mengambil daftar kompensasi
    protected $detailBookingApiUrl = 'http://127.0.0.1:8080/api/detail-booking-treatment';


    public function index()
    {
        // Ambil data komplain
        $komplainResponse = Http::get($this->komplainApiUrl);
        $komplainList = $komplainResponse->json();


        // Ambil data kompensasi
        $kompensasiResponse = Http::get($this->kompensasiApiUrl);
        $kompensasiList = $kompensasiResponse->json();

        // Ambil data booking treatment
        $bookingResponse = Http::get($this->bookingTreatmentApiUrl);
        $bookingTreatments = $bookingResponse->json();

        // Ambil data detail booking treatment
        $detailBookingResponse = Http::get($this->detailBookingApiUrl);
        $detailBookings = $detailBookingResponse->json();


        // Ambil data komplain treatment
        $komplainTreatmentResponse = Http::get('http://127.0.0.1:8080/api/komplain-treatment');
        $komplainTreatments = $komplainTreatmentResponse->json();

        // Ambil data kompensasi yang sudah diberikan
        $kompensasiDiberikanResponse = Http::get('http://127.0.0.1:8080/api/kompensasi-diberikan');
        $kompensasiDiberikan = $kompensasiDiberikanResponse->json();

        // Gabungkan data komplain dengan waktu_treatment dan treatment
        foreach ($komplainList as &$komplain) {
            $komplain['waktu_treatment'] = $this->getWaktuTreatment($komplain['id_booking_treatment'], $bookingTreatments);

            // Menambahkan informasi treatment untuk setiap komplain
            $komplain['treatment'] = $this->getTreatmentNameForKomplain($komplain['id_detail_booking_treatment'], $detailBookings);

            // Menambahkan kompensasi yang diberikan ke komplain
            $komplain['kompensasi_diberikan'] = $this->getKompensasiDiberikan($komplain['id_komplain'], $kompensasiDiberikan);
        }

        return view('komplain.listKomplain', compact('komplainList', 'kompensasiList', 'kompensasiDiberikan'));
    }


    // Fungsi untuk mendapatkan waktu treatment berdasarkan id_booking_treatment
    private function getWaktuTreatment($idBookingTreatment, $bookingTreatments)
    {
        foreach ($bookingTreatments['booking_treatments'] as $booking) {
            if ($booking['id_booking_treatment'] == $idBookingTreatment) {
                return $booking['waktu_treatment'];
            }
        }
        return null;
    }

    // Fungsi untuk mendapatkan treatment berdasarkan id_komplain
    private function getTreatmentNameForKomplain($idDetailBookingTreatment, $detailBookings)
    {
        foreach ($detailBookings['detail_booking_treatments'] as $detail) {
            if ($detail['id_detail_booking_treatment'] == $idDetailBookingTreatment) {
                return $detail['treatment']['nama_treatment'];
            }
        }
    
        return '-';
    }
    

    public function getKompensasiDiberikan($idKomplain, $kompensasiDiberikan)
    {
        // Menyaring kompensasi yang diberikan berdasarkan id_komplain
        foreach ($kompensasiDiberikan as $kompensasi) {
            if ($kompensasi['id_komplain'] == $idKomplain) {
                return $kompensasi; // Mengembalikan kompensasi yang ditemukan
            }
        }
        return null; // Jika tidak ditemukan, kembalikan null
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $data = $request->validate([
            'balasan_komplain' => 'required|string',
            'id_kompensasi' => 'nullable', // Menentukan kompensasi yang akan diberikan
            'kode_kompensasi' => 'nullable',
            'tanggal_berakhir_kompensasi' => 'nullable|date',
        ]);

        try {
            // Kirim data ke API untuk update komplain
            $response = Http::put("{$this->komplainApiUrl}/{$id}", $data);

            if ($response->successful()) {
                // Cek apakah ada kompensasi yang diberikan
                if ($data['id_kompensasi'] && $data['kode_kompensasi'] && $data['tanggal_berakhir_kompensasi']) {
                    // Simpan data kompensasi yang diberikan jika data kompensasi lengkap
                    $kompensasiData = [
                        'id_komplain' => $id,
                        'id_kompensasi' => $data['id_kompensasi'],
                        'kode_kompensasi' => $data['kode_kompensasi'],
                        'tanggal_berakhir_kompensasi' => $data['tanggal_berakhir_kompensasi'],
                    ];

                    // Kirim data ke API kompensasi diberikan
                    $kompensasiResponse = Http::post('http://127.0.0.1:8080/api/kompensasi-diberikan', $kompensasiData);

                    if ($kompensasiResponse->successful()) {
                        return redirect()->route('komplain.index')->with('success', 'Komplain berhasil diperbarui dan kompensasi berhasil dikirim');
                    } else {
                        return back()->with('error', 'Gagal mengirim kompensasi');
                    }
                } else {
                    return redirect()->route('komplain.index')->with('success', 'Komplain berhasil diperbarui');
                }
            }

            return back()->with('error', 'Gagal memperbarui komplain');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
}
