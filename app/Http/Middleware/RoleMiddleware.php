<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Pakai: ->middleware('role:admin') atau ->middleware('role:admin,pengajar')
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Belum login? tendang ke login
        if (!$user) {
            return redirect()->route('login');
        }

        // Tidak ada daftar role -> lolos
        if (empty($roles)) {
            return $next($request);
        }

        // Cek role user
        if (!in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
