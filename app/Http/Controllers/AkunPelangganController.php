<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class AkunPelangganController extends Controller
{
    // Tampilkan form pendaftaran + daftar user
    public function index()
    {
        $resp = Http::get('https://klinikneshnavya.com/api/user');
        $users = $resp->successful()
            ? $resp->json('data', [])
            : [];

        return view('auth.register', compact('users'));
    }

    // Proses pendaftaran via API
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_user'         => 'required|string|max:255',
            'no_telp'           => 'required|string|max:15',
            'email'             => 'required|email|max:255',
            'password'          => 'required|string|min:6|confirmed',
            'tanggal_lahir'     => 'nullable|date',
            'jenis_kelamin'     => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
        ]);

        // 2. Kirim ke API sebagai form data
        $api = Http::asForm()->post('https://klinikneshnavya.com/api/register', [
            'nama_user'             => $data['nama_user'],
            'no_telp'               => $data['no_telp'],
            'email'                 => $data['email'],
            'password'              => $data['password'],
            'password_confirmation' => $request->input('password_confirmation'),
            'tanggal_lahir'         => $data['tanggal_lahir'] ?? null,
            'jenis_kelamin'         => $data['jenis_kelamin'] ?? null,
        ]);

        if ($api->status() === 201) {
            return redirect()->route('register.form')
                ->with('success', $api->json('message', 'Pendaftaran berhasil'));
        }

        if ($api->status() === 422) {
            return back()
                ->withErrors($api->json('errors', []))
                ->withInput();
        }

        return back()
            ->with('error', $api->json('message', 'Gagal mendaftar, silakan coba lagi'))
            ->withInput();
    }
    public function updatePassword(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'password'              => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Panggil API backend
        $resp = Http::put("https://klinikneshnavya.com/api/user/{$id}/password", [
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($resp->successful()) {
            return response()->json(['message' => 'Password berhasil diubah'], 200);
        }

        // kalau gagal di API
        return response()->json([
            'message' => 'Gagal mengubah password',
            'error'   => $resp->body()
        ], $resp->status());
    }
}
