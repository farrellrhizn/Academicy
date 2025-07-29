<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class DashboardDsnController extends Controller
{
    public function showDashboardDsn(): View
    {
        // Middleware 'auth:dosen' sudah memastikan hanya dosen yang bisa masuk.
        // Jadi, pengecekan manual tidak diperlukan lagi.

        // Ambil data dosen yang sedang login dari guard 'dosen'
        $userData = Auth::guard('dosen')->user();

        // Kirim data ke view
        return view('dashboard-dosen.index', compact('userData'));
    }
}