<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalAkademik;
use App\Models\Pengampu;
use App\Models\MataKuliah;
use Carbon\Carbon;

class DashboardDsnController extends Controller
{
    public function showDashboardDsn(): View
    {
        // Middleware 'auth:dosen' sudah memastikan hanya dosen yang bisa masuk.
        // Jadi, pengecekan manual tidak diperlukan lagi.

        // Ambil data dosen yang sedang login dari guard 'dosen'
        $userData = Auth::guard('dosen')->user();
        $nipDosen = $userData->NIP;

        // Ambil mata kuliah yang diampu oleh dosen ini
        $mataKuliahDiampu = Pengampu::where('NIP', $nipDosen)
            ->with('matakuliah')
            ->get();

        $totalMataKuliahDiampu = $mataKuliahDiampu->count();
        $totalSks = $mataKuliahDiampu->sum(function($pengampu) {
            return $pengampu->matakuliah->sks ?? 0;
        });

        // Ambil jadwal hari ini
        $hariIni = Carbon::now()->format('l'); // Mendapatkan nama hari dalam bahasa Inggris
        
        $jadwalHariIni = JadwalAkademik::whereHas('matakuliah.pengampu', function($query) use ($nipDosen) {
                $query->where('NIP', $nipDosen);
            })
            ->where('hari', $hariIni)
            ->with(['matakuliah', 'ruang', 'golongan'])
            ->orderBy('waktu')
            ->get();

        // Hitung total mahasiswa dari semua kelas yang diajar
        $totalMahasiswa = 0;
        foreach($mataKuliahDiampu as $pengampu) {
            // Asumsi: setiap mata kuliah memiliki jadwal dan setiap jadwal memiliki golongan
            $jadwalMataKuliah = JadwalAkademik::where('Kode_mk', $pengampu->Kode_mk)->get();
            foreach($jadwalMataKuliah as $jadwal) {
                if($jadwal->golongan) {
                    $totalMahasiswa += $jadwal->golongan->mahasiswa()->count();
                }
            }
        }

        // Kirim data ke view
        return view('dashboard-dosen.index', compact(
            'userData', 
            'totalMataKuliahDiampu', 
            'totalSks', 
            'totalMahasiswa',
            'jadwalHariIni'
        ));
    }
}