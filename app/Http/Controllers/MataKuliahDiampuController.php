<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Pengampu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MataKuliahDiampuController extends Controller
{
    public function index(): View
    {
        // Ambil data dosen yang sedang login
        $dosen = Auth::guard('dosen')->user();
        
        if (!$dosen) {
            abort(403, 'Unauthorized');
        }

        // Ambil mata kuliah yang diampu oleh dosen ini
        // Menggunakan relasi yang sudah didefinisikan di model Dosen
        $matakuliahDiampu = $dosen->matakuliah()
            ->select('matakuliah.Kode_mk', 'matakuliah.Nama_mk', 'matakuliah.sks', 'matakuliah.semester')
            ->orderBy('matakuliah.semester')
            ->orderBy('matakuliah.Nama_mk')
            ->get();

        // Hitung statistik
        $totalMataKuliah = $matakuliahDiampu->count();
        $totalSKS = $matakuliahDiampu->sum('sks');
        
        // Kelompokkan berdasarkan semester untuk tampilan yang lebih baik
        $matakuliahPerSemester = $matakuliahDiampu->groupBy('semester');

        return view('dashboard-dosen.mata-kuliah-diampu', compact(
            'dosen',
            'matakuliahDiampu', 
            'totalMataKuliah', 
            'totalSKS',
            'matakuliahPerSemester'
        ));
    }
}