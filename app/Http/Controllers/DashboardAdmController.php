<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class DashboardAdmController extends Controller
{
    public function showDashboardAdm(): View
    {
        // Middleware 'auth:admin' sudah memastikan hanya admin yang bisa masuk.
        // Jadi, pengecekan manual tidak diperlukan lagi.

        // Ambil data admin yang sedang login dari guard 'admin'
        $userData = Auth::guard('admin')->user();

        // Kirim data ke view
        return view('dashboard-admin.index', compact('userData'));
    }
}