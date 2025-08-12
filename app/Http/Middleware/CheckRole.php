<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Ambil role dari session yang kamu set saat login
        $rawRole = session('user_role') ?? data_get(session('user'), 'role');

        // Kalau belum login / role kosong → lempar ke login (atau abort 403 terserah kamu)
        if (!$rawRole) {
            return redirect()->route('login.form');
        }

        // Normalizer: case-insensitive, spasi dirapikan, terima '-' atau '_' sebagai spasi
        $normalize = function ($v) {
            $v = str_replace(['-', '_'], ' ', (string) $v);
            $v = preg_replace('/\s+/', ' ', $v);
            return mb_strtolower(trim($v));
        };

        $userRole  = $normalize($rawRole);
        $allowed   = array_map($normalize, $roles);  // dari parameter middleware: check.role:front office,dokter,...

        if (!in_array($userRole, $allowed, true)) {
            $msg = "Halaman tidak bisa diakses oleh role \"{$rawRole}\".";
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 403);
            }
            abort(403, $msg);
        }

        return $next($request);
    }
}