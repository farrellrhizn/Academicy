<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
public function showDashboard()
{

        // Middleware 'auth:admin' sudah memastikan hanya admin yang bisa masuk.
        // Jadi, pengecekan manual tidak diperlukan lagi.

        // Ambil data admin yang sedang login dari guard 'admin'
        $userData = Auth::guard('mahasiswa')->user();

        // Kirim data ke view
        return view('dashboard-mhs.index', compact('userData'));
    
}
}