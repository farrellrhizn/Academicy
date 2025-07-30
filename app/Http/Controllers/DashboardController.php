<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Krs;
use App\Models\JadwalAkademik;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Helper method untuk menormalisasi nama hari
     */
    private function normalizeDay($day)
    {
        $dayMapping = [
            'senin' => 'Monday',
            'selasa' => 'Tuesday', 
            'rabu' => 'Wednesday',
            'kamis' => 'Thursday',
            'jumat' => 'Friday',
            'sabtu' => 'Saturday',
            'minggu' => 'Sunday'
        ];
        
        $normalizedDay = strtolower(trim($day));
        return $dayMapping[$normalizedDay] ?? ucfirst($normalizedDay);
    }

    /**
     * Mendapatkan jadwal hari ini berdasarkan mata kuliah yang diambil mahasiswa
     */
    private function getJadwalHariIni($mahasiswa)
    {
        $hariIni = Carbon::now()->format('l'); // Format: Monday, Tuesday, etc.
        
        // Konversi ke format Indonesia yang digunakan di database
        $hariIndonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        $hariIndo = $hariIndonesia[$hariIni] ?? $hariIni;
        
        // Ambil jadwal berdasarkan mata kuliah yang diambil mahasiswa (KRS)
        $jadwalHariIni = JadwalAkademik::whereHas('matakuliah.krs', function($query) use ($mahasiswa) {
                $query->where('NIM', $mahasiswa->NIM);
            })
            ->where('id_Gol', $mahasiswa->id_Gol)
            ->where(function($query) use ($hariIni, $hariIndo) {
                // Cari berdasarkan format Indonesia (yang digunakan di database)
                $query->where('hari', $hariIndo)
                      ->orWhere('hari', strtolower($hariIndo))
                      ->orWhere('hari', strtoupper($hariIndo))
                      // Fallback ke format Inggris
                      ->orWhere('hari', $hariIni)
                      ->orWhere('hari', strtolower($hariIni))
                      ->orWhere('hari', ucfirst(strtolower($hariIni)));
            })
            ->with(['matakuliah.pengampu.dosen', 'ruang', 'golongan'])
            ->orderBy('waktu')
            ->get();

        return $jadwalHariIni;
    }

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

        // Ambil jadwal hari ini berdasarkan mata kuliah yang diambil mahasiswa
        $jadwalHariIni = $this->getJadwalHariIni($userData);

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