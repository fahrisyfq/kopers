<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next)
    {
        // Jika belum login → arahkan ke login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = $request->user();

if (empty($user->nis) || empty($user->kelas) || empty($user->jurusan)) {
    return response()->json([
        'success' => false,
        'message' => 'Lengkapi profil dulu sebelum belanja.',
    ]);
}

        return $next($request);
    }
}
