<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Gunakan Auth facade

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:mahasiswa,dosen,admin',
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $userType = $request->input('user_type');
        $loginId = $request->input('login_id');
        $password = $request->input('password');

        $credentials = ['password' => $password];
        $guard = '';

        // Tentukan guard dan field untuk login berdasarkan user_type
        switch ($userType) {
            case 'mahasiswa':
                $credentials['NIM'] = $loginId;
                $guard = 'mahasiswa';
                break;
            case 'dosen':
                $credentials['NIP'] = $loginId;
                $guard = 'dosen';
                break;
            case 'admin':
                $credentials['username'] = $loginId;
                $guard = 'admin';
                break;
        }

        // Lakukan percobaan login menggunakan guard yang sesuai
        if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Simpan tipe guard di session untuk proses logout
            session(['auth_guard' => $guard]);

            // Redirect berdasarkan tipe user
            switch ($userType) {
                case 'mahasiswa':
                    return redirect()->intended(route('mahasiswa.dashboard'));
                case 'dosen':
                    return redirect()->intended(route('dosen.dashboard'));
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
            }
        }

        // Login gagal
        return back()
            ->withInput($request->only('login_id', 'user_type'))
            ->withErrors(['login_id' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.']);
    }

    public function logout(Request $request)
    {
        // Logout dari guard yang aktif
        $guard = session('auth_guard', 'web');
        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}