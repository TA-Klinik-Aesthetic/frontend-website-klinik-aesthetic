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
        // 1) Panggil API pembayaran‐treatment saja
        $response = Http::get($this->apiUrlPembayaran);
    
        if (! $response->successful()) {
            abort(500, 'Gagal mengambil data pembayaran treatment.');
        }
    
        $list = $response->json(); // ini array of pembayaran
    
        // 2) Flatten: untuk tiap pembayaran ambil nama_user & harga_akhir_treatment
        $flattened = collect($list)->map(function ($p) {
            $p['user_name']  = data_get($p, 'booking_treatment.user.nama_user', '-');
            $p['harga_akhir'] = data_get($p, 'booking_treatment.harga_akhir_treatment', 0);
            return $p;
        });
    
        // 3) Tampilkan ke view
        return view('pembayaran.pembayaranTreatment', [
            'pembayaranTreatmentList' => $flattened,
        ]);
    }

    public function show($id)
    {
        // Memanggil API
        $response = Http::get("https://klinikneshnavya.com/api/pembayaran-treatment/{$id}");

        if (! $response->successful()) {
            return redirect()->route('pembayaran-treatment.index')
                ->with('error', 'Gagal mengambil detail pembayaran treatment.');
        }

        $data = $response->json()['data'];

        // Jika ada gambar, tambahkan domain prefix
        if (! empty($data['gambar_bukti_pembayaran'])) {
            $data['gambar_bukti_pembayaran'] =
                'https://klinikneshnavya.com/' . ltrim($data['gambar_bukti_pembayaran'], '/');
        }

        return view('pembayaran.detailTreatment', [
            'payment' => $data,
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
            'uang'              => 'required_if:metode_pembayaran,Tunai|nullable|numeric|min:0',
        ]);

        try {

            $uang = $validated['metode_pembayaran'] === 'Tunai'
                ? $validated['uang']
                : null;

            // Kirim data lengkap ke API
            $response = Http::put("{$this->apiUrlPembayaran}/{$id}", [
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'uang'              => $uang,
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

    public function confirmPaymentTreatment(Request $request, $id)
    {
        $request->validate([
            'gambar_bukti_pembayaran' => 'required|image',
        ]);

        // bangun client multipart
        $http = Http::withHeaders(['Accept' => 'application/json'])
            ->asMultipart()
            ->attach(
                'gambar_bukti_pembayaran',
                file_get_contents($request->file('gambar_bukti_pembayaran')->getRealPath()),
                $request->file('gambar_bukti_pembayaran')->getClientOriginalName()
            );

        // spoof PUT via _method
        $response = $http->post(
            "https://klinikneshnavya.com/api/pembayaran-treatment/{$id}/konfirmasi",
            ['_method' => 'PUT']
        );

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Pembayaran treatment berhasil dikonfirmasi.');
        }

        $body = $response->json();
        $msg  = $body['message'] ?? $response->body();
        return redirect()->back()->with('error', 'Gagal konfirmasi: ' . $msg);
    }
}
