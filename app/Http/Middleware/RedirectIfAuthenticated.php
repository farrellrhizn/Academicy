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
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Check which guard is authenticated and redirect accordingly
                if (Auth::guard('admin')->check()) {
                    return redirect()->route('admin.dashboard');
                } elseif (Auth::guard('dosen')->check()) {
                    return redirect()->route('dosen.dashboard');
                } elseif (Auth::guard('mahasiswa')->check()) {
                    return redirect()->route('mahasiswa.dashboard');
                }
                
                // Fallback redirect if guard check fails
                return redirect('/');
            }
        }

        return $next($request);
    }
}