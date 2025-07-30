<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Krs;
use App\Models\JadwalAkademik;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function showDashboard()
{

        // Middleware 'auth:mahasiswa' sudah memastikan hanya mahasiswa yang bisa masuk.
        // Jadi, pengecekan manual tidak diperlukan lagi.

        // Ambil data mahasiswa yang sedang login dari guard 'mahasiswa'
        $userData = Auth::guard('mahasiswa')->user();
        $nimMahasiswa = $userData->NIM;

        // Ambil data KRS mahasiswa
        $krsData = Krs::where('NIM', $nimMahasiswa)
            ->with('matakuliah')
            ->get();

        // Hitung statistik akademik
        $totalSks = $krsData->sum(function($krs) {
            return $krs->matakuliah->sks ?? 0;
        });

        // Hitung IPK (berdasarkan nilai yang ada)
        $totalNilai = 0;
        $totalSksBerNilai = 0;
        foreach($krsData as $krs) {
            if($krs->Nilai !== null && $krs->matakuliah) {
                $totalNilai += $krs->Nilai * $krs->matakuliah->sks;
                $totalSksBerNilai += $krs->matakuliah->sks;
            }
        }
        $ipk = $totalSksBerNilai > 0 ? round($totalNilai / $totalSksBerNilai, 2) : 0;

        // Mata kuliah semester ini (semester aktif mahasiswa)
        $mataKuliahSemesterIni = Krs::where('NIM', $nimMahasiswa)
            ->whereHas('matakuliah', function($query) use ($userData) {
                $query->where('semester', $userData->Semester);
            })
            ->with('matakuliah')
            ->count();

        // Status akademik (berdasarkan IPK)
        if($ipk >= 3.0) {
            $statusAkademik = 'Aktif';
            $statusClass = 'text-success';
        } elseif($ipk >= 2.0) {
            $statusAkademik = 'Peringatan';
            $statusClass = 'text-warning';
        } else {
            $statusAkademik = 'Probasi';
            $statusClass = 'text-danger';
        }

        // Ambil jadwal hari ini berdasarkan golongan mahasiswa
        $hariIni = Carbon::now()->format('l'); // Mendapatkan nama hari dalam bahasa Inggris
        
        $jadwalHariIni = JadwalAkademik::where('id_Gol', $userData->id_Gol)
            ->where('hari', $hariIni)
            ->with(['matakuliah', 'ruang'])
            ->orderBy('waktu')
            ->get();

        // Kirim data ke view
        return view('dashboard-mhs.index', compact(
            'userData', 
            'ipk', 
            'totalSks', 
            'mataKuliahSemesterIni',
            'statusAkademik',
            'statusClass',
            'jadwalHariIni'
        ));
    
}
}