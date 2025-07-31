<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Krs;
use App\Models\JadwalAkademik;
use App\Models\PresensiAkademik;
use App\Models\MataKuliah;
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

    /**
     * Menghitung statistik kehadiran mahasiswa
     */
    private function getAttendanceStats($nimMahasiswa)
    {
        $totalPresensi = PresensiAkademik::where('NIM', $nimMahasiswa)->count();
        $hadirCount = PresensiAkademik::where('NIM', $nimMahasiswa)
            ->where('status_kehadiran', 'Hadir')->count();
        $izinCount = PresensiAkademik::where('NIM', $nimMahasiswa)
            ->where('status_kehadiran', 'Izin')->count();
        $alpaCount = PresensiAkademik::where('NIM', $nimMahasiswa)
            ->where('status_kehadiran', 'Alpa')->count();
        
        $attendancePercentage = $totalPresensi > 0 ? round(($hadirCount + $izinCount) / $totalPresensi * 100, 1) : 0;
        
        return [
            'total' => $totalPresensi,
            'hadir' => $hadirCount,
            'izin' => $izinCount,
            'alpa' => $alpaCount,
            'percentage' => $attendancePercentage
        ];
    }

    /**
     * Mendapatkan nilai terbaru mahasiswa
     */
    private function getRecentGrades($nimMahasiswa, $limit = 5)
    {
        return Krs::where('NIM', $nimMahasiswa)
            ->whereNotNull('Nilai')
            ->with(['matakuliah'])
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Menghitung progress semester
     */
    private function getSemesterProgress($nimMahasiswa, $semester)
    {
        // Total mata kuliah yang tersedia untuk semester ini
        $totalMataKuliahSemester = MataKuliah::where('semester', $semester)->count();
        
        // Mata kuliah yang sudah diambil mahasiswa untuk semester ini
        $mataKuliahDiambil = Krs::where('NIM', $nimMahasiswa)
            ->whereHas('matakuliah', function($query) use ($semester) {
                $query->where('semester', $semester);
            })
            ->count();
        
        // Mata kuliah yang sudah diselesaikan (ada nilai)
        $mataKuliahSelesai = Krs::where('NIM', $nimMahasiswa)
            ->whereNotNull('Nilai')
            ->whereHas('matakuliah', function($query) use ($semester) {
                $query->where('semester', $semester);
            })
            ->count();
        
        $progressPercentage = $mataKuliahDiambil > 0 ? round($mataKuliahSelesai / $mataKuliahDiambil * 100, 1) : 0;
        
        return [
            'total_available' => $totalMataKuliahSemester,
            'taken' => $mataKuliahDiambil,
            'completed' => $mataKuliahSelesai,
            'progress_percentage' => $progressPercentage
        ];
    }

    /**
     * Mendapatkan pengumuman dinamis berdasarkan data akademik
     */
    private function getDynamicAnnouncements($userData)
    {
        $announcements = [];
        
        // Cek deadline KRS (contoh: jika semester ganjil dan bulan Juli-Agustus)
        $now = Carbon::now();
        if ($now->month >= 7 && $now->month <= 8) {
            $krsDeadline = Carbon::create($now->year, 8, 30);
            $daysLeft = $now->diffInDays($krsDeadline, false);
            
            if ($daysLeft > 0 && $daysLeft <= 10) {
                $announcements[] = [
                    'title' => 'Batas Akhir KRS',
                    'message' => "Pengisian KRS semester ganjil akan ditutup pada {$krsDeadline->format('d F Y')}.",
                    'time' => "{$daysLeft} hari lagi",
                    'type' => 'warning'
                ];
            }
        }
        
        // Cek jadwal ujian berdasarkan mata kuliah yang diambil
        $hasScheduledExams = JadwalAkademik::whereHas('matakuliah.krs', function($query) use ($userData) {
                $query->where('NIM', $userData->NIM);
            })
            ->where('id_Gol', $userData->id_Gol)
            ->exists();
            
        if ($hasScheduledExams) {
            $announcements[] = [
                'title' => 'Jadwal Kuliah Tersedia',
                'message' => 'Jadwal kuliah Anda telah tersedia. Silakan cek di menu Jadwal.',
                'time' => 'Terbaru',
                'type' => 'info'
            ];
        }
        
        // Cek IPK untuk rekomendasi beasiswa
        $krsData = Krs::where('NIM', $userData->NIM)->with('matakuliah')->get();
        $totalNilai = 0;
        $totalSksBerNilai = 0;
        
        foreach($krsData as $krs) {
            if($krs->Nilai !== null && $krs->matakuliah) {
                $totalNilai += $krs->Nilai * $krs->matakuliah->sks;
                $totalSksBerNilai += $krs->matakuliah->sks;
            }
        }
        
        $ipk = $totalSksBerNilai > 0 ? $totalNilai / $totalSksBerNilai : 0;
        
        if ($ipk >= 3.5) {
            $announcements[] = [
                'title' => 'Rekomendasi Beasiswa',
                'message' => 'IPK Anda memenuhi syarat untuk beasiswa prestasi. Silakan hubungi bagian akademik.',
                'time' => 'Info',
                'type' => 'success'
            ];
        }
        
        // Jika tidak ada pengumuman, berikan pengumuman default
        if (empty($announcements)) {
            $announcements[] = [
                'title' => 'Selamat Belajar',
                'message' => 'Semoga semester ini berjalan lancar dan sukses!',
                'time' => 'Motivasi',
                'type' => 'info'
            ];
        }
        
        return $announcements;
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

        // Ambil data tambahan untuk dashboard yang lebih informatif
        $attendanceStats = $this->getAttendanceStats($nimMahasiswa);
        $recentGrades = $this->getRecentGrades($nimMahasiswa);
        $semesterProgress = $this->getSemesterProgress($nimMahasiswa, $userData->Semester);
        $dynamicAnnouncements = $this->getDynamicAnnouncements($userData);

        // Kirim data ke view
        return view('dashboard-mhs.index', compact(
            'userData', 
            'ipk', 
            'totalSks', 
            'mataKuliahSemesterIni',
            'statusAkademik',
            'statusClass',
            'jadwalHariIni',
            'attendanceStats',
            'recentGrades', 
            'semesterProgress',
            'dynamicAnnouncements'
        ));
    
}
}