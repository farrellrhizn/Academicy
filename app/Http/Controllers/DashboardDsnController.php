<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class DashboardDsnController extends Controller
{
    public function showDashboardDsn(): View
    {
        // Middleware 'auth:admin' sudah memastikan hanya admin yang bisa masuk.
        // Jadi, pengecekan manual tidak diperlukan lagi.

        // Ambil data admin yang sedang login dari guard 'admin'
        $userData = Auth::guard('admin')->user();

        // Kirim data ke view
        return view('dashboard-dosen.index', compact('userData'));
    }
}