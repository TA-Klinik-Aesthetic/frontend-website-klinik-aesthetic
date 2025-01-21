<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KonsultasiController extends Controller
{
    public function indexWithDoctor()
    {
        // $token = session('token'); // Mendapatkan token dari session

        // $response = Http::withToken($token)->get('http://127.0.0.1:8080/api/konsultasi');
        // $data = $response->json();

        $response = Http::get('http://127.0.0.1:8080/api/konsultasi');
        $data = $response->json();

        // Filter data untuk konsultasi dengan dokter
        $dataWithDoctor = collect($data['data'])->filter(function ($item) {
            return isset($item['dokter']); // Data memiliki dokter
        });

        // Mengirim data ke tampilan
        return view('konsultasi.tambahBooking', ['data' => $dataWithDoctor]);
    }

    public function indexWithoutDoctor()
    {
        // $token = session('token'); // Mendapatkan token dari session

        // $response = Http::withToken($token)->get('http://127.0.0.1:8080/api/konsultasi');
        // $data = $response->json();

        $response = Http::get('http://127.0.0.1:8080/api/konsultasi');
        $data = $response->json();

        // Filter data untuk konsultasi tanpa dokter
        $dataWithoutDoctor = collect($data['data'])->filter(function ($item) {
            return !isset($item['dokter']); // Data tidak memiliki dokter
        });

        // Mengirim data ke tampilan
        return view('konsultasi.lihatBooking', ['data' => $dataWithoutDoctor]);
    }

    public function create()
    {
        // $token = session('token'); // Mendapatkan token dari session

        // Ambil data dari API untuk dropdown
        
        // $usersResponse = Http::withToken($token)->get('http://127.0.0.1:8080/api/users');
        // $doktersResponse = Http::withToken($token)->get('http://127.0.0.1:8080/api/dokters');

        $usersResponse = Http::get('http://127.0.0.1:8080/api/users');
        $doktersResponse = Http::get('http://127.0.0.1:8080/api/dokters');

        $users = $usersResponse->json()['data'];
        $dokters = $doktersResponse->json()['data'];

        // Debugging jika data kosong atau bermasalah
        if (empty($users) || empty($dokters)) {
            dd('Users:', $users, 'Dokters:', $dokters);
        }

        return view('konsultasi.create', compact('users', 'dokters'));
    }

    public function store(Request $request)
    {
        // $token = session('token'); // Mendapatkan token dari session

        // $response = Http::withToken($token)->post('http://127.0.0.1:8080/api/konsultasi', [

        $response = Http::post('http://127.0.0.1:8080/api/konsultasi', [
            'id_user' => $request->id_user,
            'id_dokter' => $request->id_dokter,
            'waktu_konsultasi' => $request->waktu_konsultasi,
        ]);

        if ($response->successful()) {
            session()->flash('success', 'Data konsultasi berhasil ditambahkan!');
            return redirect()->route('konsultasi.create');
        }

        session()->flash('error', 'Terjadi kesalahan saat menambahkan data!');
        return redirect()->back();
    }

    public function edit($id)
    {
        // $token = session('token'); // Mendapatkan token dari session

        // // Ambil data konsultasi berdasarkan ID
        // $response = Http::withToken($token)->get("http://127.0.0.1:8080/api/konsultasi/{$id}");
        
        // $konsultasi = $response->json()['data'];

        // // Ambil semua dokter
        // $dokters = Http::withToken($token)->get('http://127.0.0.1:8080/api/dokters')->json()['data'];

        $response =Http::get("http://127.0.0.1:8080/api/konsultasi/{$id}");

        $konsultasi = $response->json()['data'];

        // Ambil semua dokter
        $dokters = Http::get('http://127.0.0.1:8080/api/dokters')->json()['data'];

        return view('konsultasi.edit', compact('konsultasi', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        // $token = session('token'); // Mendapatkan token dari session

        // $response = Http::withToken($token)->put("http://127.0.0.1:8080/api/konsultasi/{$id}", [
        //     'id_dokter' => $request->id_dokter,
        // ]);

        $response = Http::put("http://127.0.0.1:8080/api/konsultasi/{$id}", [
            'id_dokter' => $request->id_dokter,
        ]);

        if ($response->successful()) {
            session()->flash('success', 'Data dokter berhasil diperbarui!');
            return redirect()->route('konsultasi.without-doctor');
        }

        session()->flash('error', 'Terjadi kesalahan saat memperbarui data dokter!');
        return redirect()->back();
    }

    public function destroy($id)
    {
        // $token = session('token'); // Mendapatkan token dari session

        // $response = Http::withToken($token)->delete("http://127.0.0.1:8080/api/konsultasi/{$id}");

        $response = Http::delete("http://127.0.0.1:8080/api/konsultasi/{$id}");

        if ($response->successful()) {
            return redirect()->route('konsultasi.with-doctor')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->route('konsultasi.with-doctor')->with('error', 'Gagal menghapus data');
        }
    }

    public function show($id)
    {
        // $token = session('token'); // Mendapatkan token dari session

        // $konsultasiResponse = Http::withToken($token)->get("http://127.0.0.1:8080/api/konsultasi/{$id}");
        // $usersResponse = Http::withToken($token)->get('http://127.0.0.1:8080/api/users');
        // $doktersResponse = Http::withToken($token)->get('http://127.0.0.1:8080/api/dokters');
        // $detailKonsultasiResponse = Http::withToken($token)->get("http://127.0.0.1:8080/api/detail-konsultasi/{$id}");

         // Ambil data konsultasi berdasarkan ID
         $konsultasiResponse = Http::get("http://127.0.0.1:8080/api/konsultasi/{$id}");
        
         // Ambil data semua pengguna dan dokter
         $usersResponse = Http::get('http://127.0.0.1:8080/api/users');
         $doktersResponse = Http::get('http://127.0.0.1:8080/api/dokters');
         
         // Ambil data detail konsultasi dari API
         $detailKonsultasiResponse = Http::get("http://127.0.0.1:8080/api/detail-konsultasi/{$id}");

        if ($konsultasiResponse->successful() && $usersResponse->successful() && $doktersResponse->successful() && $detailKonsultasiResponse->successful()) {
            $konsultasi = $konsultasiResponse->json('data');
            $users = $usersResponse->json('data');
            $dokters = $doktersResponse->json('data');
            $detailKonsultasi = $detailKonsultasiResponse->json('data');

            $user = collect($users)->firstWhere('id_user', $konsultasi['id_user']);
            $dokter = collect($dokters)->firstWhere('id_dokter', $konsultasi['id_dokter']);

            $konsultasi['nama_user'] = $user['nama_user'] ?? 'Tidak diketahui';
            $konsultasi['nama_dokter'] = $dokter['nama_dokter'] ?? 'Tidak diketahui';
            $konsultasi['detail_konsultasi'] = $detailKonsultasi;

            return view('konsultasi.detail', compact('konsultasi'));
        }

        return redirect()->route('konsultasi.with-doctor')->with('error', 'Gagal mengambil data konsultasi.');
    }

    public function editKeluhan($id)
    {
        // $token = session('token'); // Mendapatkan token dari session

        // $response = Http::withToken($token)->get("http://127.0.0.1:8080/api/detail-konsultasi/{$id}");

        $response = Http::get("http://127.0.0.1:8080/api/detail-konsultasi/{$id}");

        if ($response->successful()) {
            $data = $response->json()['data'];
            return view('konsultasi.editKeluhan', ['data' => $data]);
        } else {
            return redirect()->route('konsultasi.with-doctor')->with('error', 'Data tidak ditemukan.');
        }
    }

    public function updateKeluhan(Request $request, $id)
    {
        // $token = session('token'); // Mendapatkan token dari session

        // $response = Http::withToken($token)->put("http://127.0.0.1:8080/api/detail-konsultasi/{$id}", [

        $response = Http::put("http://127.0.0.1:8080/api/detail-konsultasi/{$id}", [
            'keluhan_pelanggan' => $request->input('keluhan_pelanggan'),
            'saran_tindakan' => $request->input('saran_tindakan'),
        ]);

        if ($response->successful()) {
            return redirect()->route('konsultasi.with-doctor')->with('success', 'Data berhasil diperbarui.');
        } else {
            return back()->with('error', 'Gagal memperbarui data.');
        }
    }
}
