<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Menampilkan form register
    public function showRegisterForm()
    {
        return view('authentikasi.register');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('authentikasi.login');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'password'  => 'required|string|min:8|confirmed',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => ['nullable', Rule::in(['Laki-laki','Perempuan'])],
        ]);

        $response = Http::asForm()->post('https://klinikneshnavya.com/api/register', [
            'nama_user' => $request->nama_user,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation, 
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        if ($response->successful()) {
            return redirect()->route('login.form')->with('success', 'Registration successful. Please login.');
        }

        return back()->withErrors(['message' => $response->json('message')]);
    }

    // Proses login
    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $response = Http::post('https://klinikneshnavya.com/api/login', [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if (! $response->successful()) {
            return back()->withErrors(['message' => $response->json('message')]);
        }

        $token = $response->json('token');
        $user  = $response->json('user');

        // Simpan informasi login di session
        session([
            'user'  => $user,
            'token' => $token,
            'user_id'   => $user['id_user'],
            'user_name' => $user['nama_user'],
            'user_role' => $user['role'],
        ]);

        // Redirect berdasarkan role user (hanya staf klinik)
        $role = $user['role'];
        if (in_array($role, ['front office', 'kasir', 'dokter', 'beautician'])) {
            return redirect()->route('dashboard')->with('success', 'Login successful.');
        }

        // Role lain (misal: pelanggan) tidak diizinkan ke dashboard
        return back()->withErrors(['message' => 'Akun tidak memiliki akses login.']);
    }


    public function logout(Request $request)
    {
        // Ambil token dari session
        $token = session('token');

        // Panggil API logout, sertakan Bearer token
        Http::withToken($token)
            ->accept('application/json')
            ->post('https://klinikneshnavya.com/api/logout');

        // Hapus semua session
        Session::flush();

        // Redirect ke halaman login (ganti sesuai)
        return redirect()->route('login.form');
    }
}
