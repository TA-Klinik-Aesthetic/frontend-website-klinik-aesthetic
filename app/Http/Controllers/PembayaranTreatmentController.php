<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;



class PembayaranTreatmentController extends Controller
{
    // URL API untuk pembayaran treatment
    protected $apiUrlPembayaran = 'https://klinikneshnavya.com/api/pembayaran-treatment';

    // URL API untuk booking treatment
    protected $apiUrlBooking = 'https://klinikneshnavya.com/api/detailBookingTreatments';

    // Menampilkan semua pembayaran treatment
    public function index()
    {
        $response = Http::get($this->apiUrlPembayaran);
        $list = $response->json();
    
        // Ambil booking treatments
        $bookingResponse = Http::get($this->apiUrlBooking);
        $bookings = collect($bookingResponse->json()['booking_treatments']);
    
        // Flatten: tambahkan user_name & harga_akhir langsung ke setiap pembayaran
        $flattened = collect($list)->map(function($p) use ($bookings) {
            if (isset($p['booking_treatment'])) {
                $bt = $p['booking_treatment'];
                $p['user_name'] = data_get($bt, 'user.nama_user', '-');
                $p['harga_akhir'] = data_get($bt, 'harga_akhir_treatment', 0);
            } else {
                $p['user_name'] = '-';
                $p['harga_akhir'] = 0;
            }
            return $p;
        });
    
        return view('pembayaran.pembayaranTreatment', [
            'pembayaranTreatmentList' => $flattened,
        ]);
    }

    // Menyimpan pembayaran treatment baru
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'id_booking_treatment' => 'required',
    //         'metode_pembayaran' => 'required',
    //         'pajak' => 'required',
    //     ]);

    //     try {
    //         // Simpan data pembayaran
    //         $pembayaranTreatmentResponse = Http::post($this->apiUrlPembayaran, [
    //             'id_booking_treatment' => $validatedData['id_booking_treatment'],
    //             'metode_pembayaran' => $validatedData['metode_pembayaran'],
    //             'pajak' => $validatedData['pajak'],
    //         ]);

    //         // Redirect kembali ke halaman index dengan pesan sukses
    //         return redirect()->route('pembayaran-treatment.index')->with('success', 'Pembayaran treatment berhasil disimpan');
    //     } catch (\Exception $e) {
    //         // Menampilkan pesan error jika terjadi kegagalan
    //         return redirect()->route('pembayaran-treatment.index')->with('error', 'Error while creating pembayaran treatment');
    //     }
    // }

    // Memperbarui pembayaran treatment yang sudah ada
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:Tunai,Non Tunai',
            'uang'              => 'nullable|numeric|min:0',
        ]);
    
        try {
            // Kirim data lengkap ke API
            $response = Http::put("{$this->apiUrlPembayaran}/{$id}", [
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'uang'              => $validated['uang'],
            ]);
    
            if ($response->successful()) {
                return redirect()
                    ->route('pembayaran-treatment.index')
                    ->with('success', 'Pembayaran treatment berhasil diperbarui');
            }
    
            // jika API merespon error
            return redirect()
                ->route('pembayaran-treatment.index')
                ->with('error', 'Gagal memperbarui pembayaran treatment');
        } catch (\Exception $e) {
            return redirect()
                ->route('pembayaran-treatment.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function generateInvoice($id)
    {
        // Ambil data pembayaran
        $respPay = Http::get("{$this->apiUrlPembayaran}/{$id}");
        $dataPay = $respPay->json()['data'] ?? abort(404, 'Pembayaran tidak ditemukan');
    
        // Ambil data booking treatment
        $respBook = Http::get("{$this->apiUrlBooking}/{$dataPay['id_booking_treatment']}");
        $dataBook = $respBook->json()['booking_treatment'] ?? abort(404, 'Booking tidak ditemukan');
    
        // Siapkan data untuk view
        $invoiceData = [
            'user_name'           => $dataBook['user']['nama_user'] ?? '-',
            'no_telp'             => $dataBook['user']['no_telp'] ?? '-',
            'email'               => $dataBook['user']['email'] ?? '-',
            'waktu_treatment'     => $dataBook['waktu_treatment'],
            'treatment_mulai'   => $dataBook['treatment_mulai']   ?? '-',
            'treatment_selesai' => $dataBook['treatment_selesai'] ?? '-',
            'metode_pembayaran'   => $dataPay['metode_pembayaran'],
            // Subtotal sebelum potongan = harga_total
            'subtotal'            => $dataBook['harga_total'],
            'potongan_harga'      => $dataBook['potongan_harga'],
            'tipe_potongan'       => data_get($dataBook, 'promo.tipe_potongan'),
            // besaran_pajak adalah nominal pajak (misal 67500)
            'pajak'               => $dataBook['besaran_pajak'],
            // total setelah diskon + pajak
            'total'               => $dataBook['harga_akhir_treatment'],
            'uang'                => $dataPay['uang'],
            'kembalian'           => $dataPay['kembalian'],
            'detail_booking'      => $dataBook['detail_booking'],
            'waktu_pembayaran'    => $dataPay['waktu_pembayaran'],
        ];
    
        $pdf = PDF::loadView('invoice.pembayaranTreatment', $invoiceData);
        return $pdf->download("invoice_pembayaran_{$dataPay['id_pembayaran']}.pdf");
    }

    public function confirmPaymentTreatment($id)
    {
        // Panggil endpoint API eksternal
        $response = Http::put("https://klinikneshnavya.com/api/pembayaran-treatment/{$id}/konfirmasi");

        if ($response->successful()) {
            return redirect()->back()
                ->with('success', 'Pembayaran treatment berhasil dikonfirmasi.');
        }

        // Jika gagal, ambil pesan error dari body API
        $body = $response->json();
        $msg  = $body['message'] ?? $response->body();

        return redirect()->back()
            ->with('error', 'Gagal konfirmasi: '.$msg);
    }
}
