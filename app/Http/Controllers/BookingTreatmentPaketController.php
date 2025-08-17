<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingTreatmentPaketController extends Controller
{
    // Endpoint — silakan sesuaikan bila beda di backend-mu
    protected $apiBookingPaket = 'https://klinikneshnavya.com/api/booking-treatment-paket';
    protected $apiStatus       = 'https://klinikneshnavya.com/api/status-booking-treatment-paket';
    protected $apiPelangganPkg = 'https://klinikneshnavya.com/api/paket-treatment-pelanggan';

    // (pakai sumber yang sama seperti halaman booking biasa)
    protected $apiUsers      = 'https://klinikneshnavya.com/api/user';
    protected $apiDokter     = 'https://klinikneshnavya.com/api/dokter';
    protected $apiBeautician = 'https://klinikneshnavya.com/api/beautician';

    public function index()
    {
        // List booking paket
        $resp = Http::get($this->apiBookingPaket);
        $bookings = $resp->json()['data'] ?? $resp->json()['booking_treatment_paket'] ?? [];

        // Users → filter pelanggan
        $allUsers  = Http::get($this->apiUsers)->json()['data'] ?? [];
        $pelanggan = collect($allUsers)->where('role', 'pelanggan')->values()->all();

        // Dokter & beautician
        $dokters     = Http::get($this->apiDokter)->json()['data'] ?? [];
        $beauticians = Http::get($this->apiBeautician)->json()['data'] ?? [];

        // Map nama user ke booking (kalau backend belum embed user_name)
        $usersMap = [];
        foreach ($pelanggan as $u) {
            $usersMap[$u['id_user']] = $u['nama_user'];
        }
        foreach ($bookings as &$b) {
            $b['user_name'] = $b['user']['nama_user'] ?? $usersMap[$b['id_user']] ?? ($b['nama_pelanggan'] ?? 'Unknown');
        }

        // Urutkan terbaru
        $bookings = collect($bookings)->sortByDesc(function ($x) {
            return $x['id_booking_treatment_paket'] ?? $x['id'] ?? 0;
        })->values()->all();

        // ⬅️ tidak ada $promos
        return view('treatment.bookingTreatmentPaket', compact('bookings', 'pelanggan', 'dokters', 'beauticians'));
    }

    public function paketByUser($id)
    {
        // Ambil semua paket-treatment-pelanggan, filter by id_user
        $resp  = Http::get($this->apiPelangganPkg);
        $rows  = $resp->json('data') ?? [];
        $owned = array_values(array_filter($rows, fn($r) => (string)($r['id_user'] ?? '') === (string)$id));

        $out = array_map(function ($p) {
            $pid   = $p['id_paket_treatment_pelanggan'] ?? $p['id'];
            $label = $p['paket']['nama_paket_treatment']
                ?? $p['nama_paket_treatment']
                ?? ('Paket #' . $pid);
            return [
                'id'    => $pid,
                'label' => $label,
            ];
        }, $owned);

        return response()->json(['success' => true, 'data' => $out]);
    }

    public function paketDetail($id)
    {
        // Ambil detail paket-treatment-pelanggan/{id}
        $resp  = Http::get("{$this->apiPelangganPkg}/{$id}");
        $paket = $resp->json('data') ?? null;
        if (!$paket) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $details = $paket['details'] ?? $paket['detail_paket'] ?? [];
        $rows = [];
        foreach ($details as $d) {
            $max   = $d['jumlah_penggunaan']     ?? $d['jumlah_penggunaan_max'] ?? 0;
            $pakai = $d['jumlah_dipakai']        ?? 0;
            $sisa  = isset($d['sisa_kuota']) ? $d['sisa_kuota'] : max(0, $max - $pakai);
            if ($sisa > 0) {
                $rows[] = [
                    'id_detail'    => $d['id_detail_paket_treatment_pelanggan'] ?? $d['id_detail'] ?? null,
                    'id_treatment' => $d['id_treatment'] ?? ($d['treatment']['id_treatment'] ?? null),
                    'nama'         => $d['treatment']['nama_treatment'] ?? $d['nama_treatment'] ?? 'Treatment',
                    'sisa'         => $sisa,
                ];
            }
        }

        return response()->json(['success' => true, 'data' => $rows]);
    }

    /**
     * Ambil paket milik pelanggan (AJAX) + detail & sisa kuota.
     * Mengembalikan { data: { id_paket_treatment_pelanggan, details: [...] }[] } atau { data: [] }
     */
    public function show($id)
    {
        // Ambil detail booking paket dari backend
        $resp = Http::get("{$this->apiBookingPaket}/{$id}");

        // Backend kamu bisa pakai kunci 'data' atau 'booking_treatment_paket'
        $booking = $resp->json('data')
            ?? $resp->json('booking_treatment_paket')
            ?? null;

        if ($resp->successful() && $booking) {
            return view('treatment.detailBookingTreatmentPaket', compact('booking'));
        }

        return back()->with('error', 'Data booking treatment paket tidak ditemukan');
    }

    /**
     * Simpan Booking Treatment Paket
     * Input (contoh):
     * - id_user
     * - waktu_treatment (datetime-local string)
     * - details[0][id_paket_treatment_pelanggan]
     * - details[0][id_detail_paket_treatment_pelanggan]
     * - details[0][id_treatment]
     * - id_dokter (nullable), id_beautician (nullable)
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id_user'         => 'required|integer',
            'waktu_treatment' => 'required', // pastikan format sesuai backend
            'id_dokter'       => 'nullable|integer',
            'id_beautician'   => 'nullable|integer',

            'details'                                => 'required|array|min:1',
            'details.*.id_paket_treatment_pelanggan' => 'required|integer',
            'details.*.id_treatment'                 => 'required|integer',
            'details.*.jumlah_dipakai'               => 'required|integer|min:1',
        ]);

        // Normalisasi tipe data details
        $details = collect($validated['details'])->map(function ($d) {
            return [
                'id_paket_treatment_pelanggan' => (int) $d['id_paket_treatment_pelanggan'],
                'id_treatment'                 => (int) $d['id_treatment'],
                'jumlah_dipakai'               => (int) $d['jumlah_dipakai'],
            ];
        })->all();

        // Payload final ke API
        $payload = [
            'id_user'         => (int) $validated['id_user'],
            'waktu_treatment' => $validated['waktu_treatment'],
            'id_dokter'       => $request->id_dokter ?: null,
            'id_beautician'   => $request->id_beautician ?: null,
            'details'         => $details,
        ];

        $response = Http::post($this->apiBookingPaket, $payload);

        if ($response->successful()) {
            return redirect()
                ->route('bookingTreatmentPaket.index')
                ->with('success', 'Booking paket berhasil ditambahkan');
        }

        return back()->with('error', 'Gagal menambahkan booking paket');
    }

    /**
     * Update dokter & beautician
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_dokter'     => 'nullable|integer',
            'id_beautician' => 'nullable|integer',
        ]);

        $resp = Http::put("{$this->apiBookingPaket}/{$id}", $validated);
        if ($resp->successful()) {
            return back()->with('success', 'Petugas berhasil diperbarui');
        }
        return back()->with('error', 'Gagal memperbarui petugas');
    }

    /**
     * Update status booking: Treatment dimulai | Selesai | Dibatalkan
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_booking_treatment' => 'required|string|in:Treatment dimulai,Selesai,Dibatalkan',
        ]);

        // Endpoint baru: .../booking-treatment-paket/{id}/status
        $url  = rtrim($this->apiBookingPaket, '/') . "/{$id}/status";
        $resp = Http::put($url, [
            'status_booking_treatment' => $validated['status_booking_treatment'],
        ]);

        if ($resp->successful()) {
            return redirect()
                ->route('bookingTreatmentPaket.index')
                ->with('success', 'Status booking diperbarui');
        }

        // (opsional) tampilkan pesan dari backend jika ada
        $msg = $resp->json('message') ?? 'Gagal mengubah status booking paket';
        return back()->with('error', $msg);
    }
}
