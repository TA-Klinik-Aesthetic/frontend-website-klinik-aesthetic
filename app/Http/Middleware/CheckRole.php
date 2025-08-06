<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $role = session('user_role');
        if (! $role || ! in_array($role, $roles)) {
            abort(403, "Halaman tidak bisa diakses oleh role “{$role}”.");
        }
        return $next($request);
    }
}