<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $response = Http::post('http://127.0.0.1:8080/api/register', [
            'nama_user' => $request->name,
            'no_telp' => $request->phone,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            return redirect()->route('login.form')->with('success', 'Registration successful. Please login.');
        }

        return back()->withErrors(['message' => $response->json('message')]);
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $response = Http::post('http://127.0.0.1:8080/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $token = $response->json('token');
            $user = $response->json('user');

            // Simpan informasi login di session
            session([
                'user' => $user,
                'token' => $token,
            ]);

            // Redirect berdasarkan role user
            $role = $user['role'];
            if ($role === 'pelanggan') {
                return redirect()->route('user.home')->with('success', 'Login successful.');
            } elseif (in_array($role, ['dokter', 'beautician', 'front office'])) {
                return redirect()->route('dashboard')->with('success', 'Login successful.');
            } else {
                return back()->withErrors(['message' => 'Role not recognized.']);
            }
        }

        return back()->withErrors(['message' => $response->json('message')]);
    }

    public function logout(Request $request)
    {
        // Pastikan pengguna terautentikasi menggunakan Sanctum
        if ($request->user()) {
            // Hapus token autentikasi pengguna
            $request->user()->currentAccessToken()->delete();

            // Logout dari sesi
            Auth::logout();

            // Redirect ke landing page
            return redirect('/'); // Ganti dengan route atau halaman landing
        }

        return response()->json(['error' => 'No authenticated user.'], 401);
    }
}
