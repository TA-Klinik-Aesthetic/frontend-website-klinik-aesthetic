<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsPresent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jangan cek untuk rute login atau register
        if (in_array($request->route()->getName(), [
            'login.form',
            'login'
        ])) {
            return $next($request);
        }

        // Jika token kosong, redirect ke login
        if (! session('token')) {
            return redirect()->route('login.form');
        }

        return $next($request);
    }
}
