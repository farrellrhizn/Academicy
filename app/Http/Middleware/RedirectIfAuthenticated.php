<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Jika tidak ada guard yang dispesifikasi, gunakan default guards
        $guards = empty($guards) ? ['admin', 'dosen', 'mahasiswa'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect berdasarkan guard yang aktif
                switch ($guard) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'dosen':
                        return redirect()->route('dosen.dashboard');
                    case 'mahasiswa':
                        return redirect()->route('mahasiswa.dashboard');
                    default:
                        // Fallback redirect if guard tidak dikenali
                        return redirect('/');
                }
            }
        }

        return $next($request);
    }
}