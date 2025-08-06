<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (! session()->has('user')) {
            return redirect()->route('login.form')
                             ->withErrors(['message'=>'Silakan login terlebih dulu.']);
        }
        return $next($request);
    }
}