<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login melalui session
        $isAuthenticated = Session::has('user_id') && Session::has('user_type');

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
            if (!$isAuthenticated && !$request->is('login', 'register', 'forgot-password', '/')) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}