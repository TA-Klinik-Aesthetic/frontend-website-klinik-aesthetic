<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;



class PembayaranTreatmentController extends Controller
{
    // URL API untuk pembayaran treatment
    protected $apiUrlPembayaran = 'http://127.0.0.1:8080/api/pembayaran-treatment';

    // URL API untuk booking treatment
    protected $apiUrlBooking = 'http://127.0.0.1:8080/api/detailBookingTreatments';

    // Menampilkan semua pembayaran treatment
    public function index()
    {
        // Ambil data pembayaran treatment
        $response = Http::get($this->apiUrlPembayaran);
        $pembayaranTreatmentList = $response->json();

        // Ambil data booking treatment untuk mendapatkan informasi lebih lanjut
        $bookingResponse = Http::get($this->apiUrlBooking);
        $bookingTreatments = $bookingResponse->json()['booking_treatments'];

        // Menambahkan nama user dan waktu treatment pada setiap pembayaran treatment
        foreach ($pembayaranTreatmentList as &$pembayaran) {
            foreach ($bookingTreatments as $booking) {
                if ($booking['id_booking_treatment'] == $pembayaran['id_booking_treatment']) {
                    $pembayaran['user_name'] = $booking['user']['nama_user'];
                    $pembayaran['waktu_treatment'] = $booking['waktu_treatment'];
                }
            }
        }

        // Filter booking treatment yang status_pembayarannya "Belum Dibayar"
        $bookingTreatments = array_filter($bookingTreatments, function ($booking) {
            return $booking['status_pembayaran'] === 'Belum Dibayar';
        });

        return view('pembayaran.pembayaranTreatment', compact('pembayaranTreatmentList', 'bookingTreatments'));
    }

    // Menyimpan pembayaran treatment baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_booking_treatment' => 'required',
            'metode_pembayaran' => 'required',
            'pajak' => 'required',
        ]);

        try {
            // Simpan data pembayaran
            $pembayaranTreatmentResponse = Http::post($this->apiUrlPembayaran, [
                'id_booking_treatment' => $validatedData['id_booking_treatment'],
                'metode_pembayaran' => $validatedData['metode_pembayaran'],
                'pajak' => $validatedData['pajak'],
            ]);

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('pembayaran-treatment.index')->with('success', 'Pembayaran treatment berhasil disimpan');
        } catch (\Exception $e) {
            // Menampilkan pesan error jika terjadi kegagalan
            return redirect()->route('pembayaran-treatment.index')->with('error', 'Error while creating pembayaran treatment');
        }
    }

    // Memperbarui pembayaran treatment yang sudah ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'uang' => 'required|numeric|min:0',
        ]);

        try {
            // Update data pembayaran dengan total_bayar yang baru
            $pembayaranUpdateResponse = Http::put("{$this->apiUrlPembayaran}/{$id}", [
                'uang' => $request->uang,
            ]);

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('pembayaran-treatment.index')->with('success', 'Pembayaran treatment berhasil diperbarui');
        } catch (\Exception $e) {
            // Menampilkan pesan error jika terjadi kegagalan
            return redirect()->route('pembayaran-treatment.index')->with('error', 'Error while updating pembayaran treatment');
        }
    }

    public function generateInvoice($id)
    {
        // Mengambil data pembayaran treatment berdasarkan ID
        $pembayaranTreatmentResponse = Http::get("{$this->apiUrlPembayaran}/{$id}");
        $pembayaranTreatment = $pembayaranTreatmentResponse->json()['data'];

        // Mengambil data booking treatment berdasarkan id_booking_treatment
        $bookingResponse = Http::get("{$this->apiUrlBooking}/{$pembayaranTreatment['id_booking_treatment']}");
        $bookingData = $bookingResponse->json()['booking_treatment'];

        // Mengambil data user
        $userName = isset($bookingData['user']['nama_user']) ? $bookingData['user']['nama_user'] : 'Nama Tidak Tersedia';
        $userTelp = isset($bookingData['user']['no_telp']) ? $bookingData['user']['no_telp'] : 'No. Telp Tidak Tersedia';
        $userEmail = isset($bookingData['user']['email']) ? $bookingData['user']['email'] : 'Email Tidak Tersedia';
        $potonganHarga = isset($bookingData['potongan_harga']) ? $bookingData['potongan_harga'] : 0;

        // Membuat data untuk invoice
        $invoiceData = [
            'user_name' => $userName,
            'no_telp' => $userTelp,
            'email' => $userEmail,
            'waktu_treatment' => $bookingData['waktu_treatment'],
            'metode_pembayaran' => $pembayaranTreatment['metode_pembayaran'],
            'subtotal' => $pembayaranTreatment['harga_akhir_treatment'],
            'potongan_harga' => $potonganHarga,
            'pajak' => $pembayaranTreatment['pajak'],
            'total' => $pembayaranTreatment['total'],
            'uang' => $pembayaranTreatment['uang'],
            'kembalian' => $pembayaranTreatment['kembalian'],
            'detail_booking' => $bookingData['detail_booking'],
        ];

        // Membuat PDF menggunakan DomPDF
        $pdf = PDF::loadView('invoice.pembayaranTreatment', $invoiceData);

        // Menghasilkan file PDF dan mengunduhnya
        return $pdf->download('invoice_pembayaran_' . $pembayaranTreatment['id_pembayaran_treatment'] . '.pdf');
    }
}
