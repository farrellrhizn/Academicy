<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login melalui Laravel Auth Guards
        $isAuthenticated = Auth::guard('admin')->check() || 
                          Auth::guard('dosen')->check() || 
                          Auth::guard('mahasiswa')->check();

        // Jika request adalah AJAX/API
        if ($request->expectsJson() || $request->is('api/*')) {
            if (!$isAuthenticated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
        } else {
            // Untuk web routes
            if (!$isAuthenticated && !$request->is('login', 'register', 'forgot-password', 'forgot-pass', '/')) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}