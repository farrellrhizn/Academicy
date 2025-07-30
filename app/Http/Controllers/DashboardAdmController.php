<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Ruang;
use App\Models\JadwalAkademik;

class DashboardAdmController extends Controller
{
    public function showDashboardAdm(): View
    {
        // Middleware 'auth:admin' sudah memastikan hanya admin yang bisa masuk.
        // Jadi, pengecekan manual tidak diperlukan lagi.

        // Ambil data admin yang sedang login dari guard 'admin'
        $userData = Auth::guard('admin')->user();

        // Ambil statistik data dari database
        $totalMataKuliah = MataKuliah::count();
        $totalDosen = Dosen::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalRuang = Ruang::count();

        // Ambil aktivitas terbaru dari jadwal akademik (contoh)
        $aktivitasTerbaru = JadwalAkademik::with(['matakuliah', 'ruang', 'golongan'])
            ->latest('id_jadwal')
            ->take(5)
            ->get();

        // Kirim data ke view
        return view('dashboard-admin.index', compact(
            'userData', 
            'totalMataKuliah', 
            'totalDosen', 
            'totalMahasiswa', 
            'totalRuang',
            'aktivitasTerbaru'
        ));
    }
}