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
        try {
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
        } catch (\Exception $e) {
            \Log::error('Error fetching today schedule: ' . $e->getMessage());
            return collect(); // Return empty collection on error
        }
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
     * Mendapatkan mata kuliah terbaru yang diambil mahasiswa
     */
    private function getRecentCourses($nimMahasiswa, $limit = 5)
    {
        return Krs::where('NIM', $nimMahasiswa)
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
        
        // Untuk sementara, anggap semua mata kuliah yang diambil sebagai "sedang berlangsung"
        // karena tidak ada kolom nilai untuk menentukan mata kuliah yang selesai
        $mataKuliahSelesai = $mataKuliahDiambil; // Menggunakan jumlah yang diambil sebagai yang sedang berlangsung
        
        $progressPercentage = $totalMataKuliahSemester > 0 ? round($mataKuliahDiambil / $totalMataKuliahSemester * 100, 1) : 0;
        
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
        
        // Cek total SKS untuk informasi akademik
        $krsData = Krs::where('NIM', $userData->NIM)->with('matakuliah')->get();
        $totalSks = $krsData->sum(function($krs) {
            return $krs->matakuliah->sks ?? 0;
        });
        
        // Berikan informasi berdasarkan total SKS yang diambil
        if ($totalSks >= 20) {
            $announcements[] = [
                'title' => 'Beban Akademik Optimal',
                'message' => 'Anda telah mengambil SKS yang cukup untuk semester ini. Pertahankan konsistensi belajar!',
                'time' => 'Info',
                'type' => 'success'
            ];
        } elseif ($totalSks >= 12) {
            $announcements[] = [
                'title' => 'Beban Akademik Normal',
                'message' => 'Beban SKS Anda normal. Manfaatkan waktu untuk fokus pada setiap mata kuliah.',
                'time' => 'Info',
                'type' => 'info'
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
        try {
            // Middleware 'auth:mahasiswa' sudah memastikan hanya mahasiswa yang bisa masuk.
            // Jadi, pengecekan manual tidak diperlukan lagi.

            // Ambil data mahasiswa yang sedang login dari guard 'mahasiswa'
            $userData = Auth::guard('mahasiswa')->user();
            
            if (!$userData) {
                \Log::error('User data not found in dashboard');
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }

            $nimMahasiswa = $userData->NIM;

        // Ambil data KRS mahasiswa
        $krsData = Krs::where('NIM', $nimMahasiswa)
            ->with('matakuliah')
            ->get();

        // Hitung statistik akademik
        $totalSks = $krsData->sum(function($krs) {
            return $krs->matakuliah->sks ?? 0;
        });

        // Karena tidak ada kolom nilai, tampilkan informasi akademik lain
        // Misalnya rata-rata SKS per mata kuliah atau total mata kuliah diambil
        $totalMataKuliah = $krsData->count();
        $rataRataSks = $totalMataKuliah > 0 ? round($totalSks / $totalMataKuliah, 1) : 0;
        $ipk = 0; // Set IPK ke 0 karena tidak ada data nilai

        // Mata kuliah semester ini (semester aktif mahasiswa)
        $mataKuliahSemesterIni = Krs::where('NIM', $nimMahasiswa)
            ->whereHas('matakuliah', function($query) use ($userData) {
                $query->where('semester', $userData->Semester);
            })
            ->with('matakuliah')
            ->count();

        // Status akademik (berdasarkan jumlah SKS yang diambil)
        if($totalSks >= 18) {
            $statusAkademik = 'Aktif - Beban Penuh';
            $statusClass = 'text-success';
        } elseif($totalSks >= 12) {
            $statusAkademik = 'Aktif - Beban Normal';
            $statusClass = 'text-success';
        } elseif($totalSks >= 6) {
            $statusAkademik = 'Aktif - Beban Ringan';
            $statusClass = 'text-warning';
        } else {
            $statusAkademik = 'Belum Mengambil SKS';
            $statusClass = 'text-danger';
        }

        // Ambil jadwal hari ini berdasarkan mata kuliah yang diambil mahasiswa
        $jadwalHariIni = $this->getJadwalHariIni($userData);

        // Ambil data tambahan untuk dashboard yang lebih informatif
        $attendanceStats = $this->getAttendanceStats($nimMahasiswa);
        $recentCourses = $this->getRecentCourses($nimMahasiswa);
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
                'recentCourses', 
                'semesterProgress',
                'dynamicAnnouncements',
                'totalMataKuliah',
                'rataRataSks'
            ));
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return dashboard with minimal data in case of error
            $userData = Auth::guard('mahasiswa')->user();
            
            return view('dashboard-mhs.index', [
                'userData' => $userData,
                'ipk' => 0,
                'totalSks' => 0,
                'mataKuliahSemesterIni' => 0,
                'statusAkademik' => 'Data tidak tersedia',
                'statusClass' => 'text-muted',
                'jadwalHariIni' => collect(),
                'attendanceStats' => ['total' => 0, 'hadir' => 0, 'izin' => 0, 'alpa' => 0, 'percentage' => 0],
                'recentCourses' => collect(),
                'semesterProgress' => ['total_available' => 0, 'taken' => 0, 'completed' => 0, 'progress_percentage' => 0],
                'dynamicAnnouncements' => [[
                    'title' => 'Sistem Informasi Akademik',
                    'message' => 'Dashboard sedang dalam perbaikan. Silakan refresh halaman.',
                    'time' => 'Info',
                    'type' => 'warning'
                ]],
                'totalMataKuliah' => 0,
                'rataRataSks' => 0
            ])->with('error', 'Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
        }
    }
}
}