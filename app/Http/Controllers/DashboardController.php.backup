<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Krs;
use App\Models\JadwalAkademik;
use App\Models\PresensiAkademik;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * DashboardController - Mengelola dashboard mahasiswa
 * 
 * Controller ini menangani tampilan dashboard mahasiswa dengan berbagai informasi
 * seperti jadwal hari ini, statistik kehadiran, progress semester, dan pengumuman.
 */
class DashboardController extends Controller
{
    /**
     * Mapping hari dari Indonesia ke Inggris
     */
    private const DAY_MAPPING = [
        'senin' => 'Monday',
        'selasa' => 'Tuesday', 
        'rabu' => 'Wednesday',
        'kamis' => 'Thursday',
        'jumat' => 'Friday',
        'sabtu' => 'Saturday',
        'minggu' => 'Sunday'
    ];

    /**
     * Mapping hari dari Inggris ke Indonesia
     */
    private const DAY_MAPPING_REVERSE = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    ];

    /**
     * Konstanta untuk status akademik
     */
    private const ACADEMIC_STATUS = [
        'full_load' => ['min_sks' => 18, 'label' => 'Aktif - Beban Penuh', 'class' => 'text-success'],
        'normal_load' => ['min_sks' => 12, 'label' => 'Aktif - Beban Normal', 'class' => 'text-success'],
        'light_load' => ['min_sks' => 6, 'label' => 'Aktif - Beban Ringan', 'class' => 'text-warning'],
        'no_load' => ['min_sks' => 0, 'label' => 'Belum Mengambil SKS', 'class' => 'text-danger']
    ];

    /**
     * Menampilkan dashboard mahasiswa
     */
    public function showDashboard(): View|RedirectResponse
    {
        try {
            $userData = $this->getAuthenticatedUser();
            if (!$userData) {
                return $this->redirectToLogin('User data not found in dashboard');
            }

            $dashboardData = $this->prepareDashboardData($userData);
            
            return view('dashboard-mhs.index', $dashboardData);

        } catch (\Exception $e) {
            return $this->handleDashboardError($e);
        }
    }

    /**
     * Mendapatkan user yang sedang login
     */
    private function getAuthenticatedUser(): ?Mahasiswa
    {
        $userData = Auth::guard('mahasiswa')->user();
        
        if (!$userData) {
            Log::error('User data not found in dashboard');
        }
        
        return $userData;
    }

    /**
     * Redirect ke halaman login dengan pesan error
     */
    private function redirectToLogin(string $logMessage): RedirectResponse
    {
        Log::error($logMessage);
        return redirect()->route('login')
            ->with('error', 'Session expired. Please login again.');
    }

    /**
     * Menyiapkan semua data yang diperlukan untuk dashboard
     */
    private function prepareDashboardData(Mahasiswa $userData): array
    {
        $nimMahasiswa = $userData->NIM;

        // Ambil data KRS mahasiswa dengan relasi
        $krsData = $this->getKrsData($nimMahasiswa);
        
        // Hitung statistik akademik dasar
        $academicStats = $this->calculateAcademicStats($krsData, $userData);
        
        // Ambil data tambahan secara paralel untuk performa yang lebih baik
        $additionalData = $this->getAdditionalDashboardData($userData, $nimMahasiswa);

        return array_merge($academicStats, $additionalData);
    }

    /**
     * Mengambil data KRS mahasiswa dengan optimisasi query
     */
    private function getKrsData(string $nimMahasiswa): Collection
    {
        return Krs::where('NIM', $nimMahasiswa)
            ->with(['matakuliah' => function($query) {
                $query->select('id_MataKuliah', 'nama_MataKuliah', 'sks', 'semester');
            }])
            ->get();
    }

    /**
     * Menghitung statistik akademik dasar
     */
    private function calculateAcademicStats(Collection $krsData, Mahasiswa $userData): array
    {
        // Hitung total SKS dan statistik lainnya
        $totalSks = $krsData->sum(function($krs) {
            return $krs->matakuliah->sks ?? 0;
        });

        $totalMataKuliah = $krsData->count();
        $rataRataSks = $totalMataKuliah > 0 ? round($totalSks / $totalMataKuliah, 1) : 0;
        
        // Mata kuliah semester ini
        $mataKuliahSemesterIni = $krsData->filter(function($krs) use ($userData) {
            return $krs->matakuliah && $krs->matakuliah->semester == $userData->Semester;
        })->count();

        // Tentukan status akademik
        $statusAkademik = $this->determineAcademicStatus($totalSks);

        return [
            'userData' => $userData,
            'ipk' => 0, // Set IPK ke 0 karena tidak ada data nilai
            'totalSks' => $totalSks,
            'totalMataKuliah' => $totalMataKuliah,
            'rataRataSks' => $rataRataSks,
            'mataKuliahSemesterIni' => $mataKuliahSemesterIni,
            'statusAkademik' => $statusAkademik['label'],
            'statusClass' => $statusAkademik['class']
        ];
    }

    /**
     * Menentukan status akademik berdasarkan total SKS
     */
    private function determineAcademicStatus(int $totalSks): array
    {
        foreach (self::ACADEMIC_STATUS as $status) {
            if ($totalSks >= $status['min_sks']) {
                return $status;
            }
        }
        
        return self::ACADEMIC_STATUS['no_load'];
    }

    /**
     * Mengambil data tambahan untuk dashboard
     */
    private function getAdditionalDashboardData(Mahasiswa $userData, string $nimMahasiswa): array
    {
        return [
            'jadwalHariIni' => $this->getJadwalHariIni($userData),
            'attendanceStats' => $this->getAttendanceStats($nimMahasiswa),
            'recentCourses' => $this->getRecentCourses($nimMahasiswa),
            'semesterProgress' => $this->getSemesterProgress($nimMahasiswa, $userData->Semester),
            'dynamicAnnouncements' => $this->getDynamicAnnouncements($userData)
        ];
    }

    /**
     * Menormalisasi nama hari dari format Indonesia ke Inggris
     */
    private function normalizeDay(string $day): string
    {
        $normalizedDay = strtolower(trim($day));
        return self::DAY_MAPPING[$normalizedDay] ?? ucfirst($normalizedDay);
    }

    /**
     * Mendapatkan jadwal hari ini berdasarkan mata kuliah yang diambil mahasiswa
     */
    private function getJadwalHariIni(Mahasiswa $mahasiswa): Collection
    {
        try {
            $hariIni = Carbon::now()->format('l'); // Format: Monday, Tuesday, etc.
            $hariIndo = self::DAY_MAPPING_REVERSE[$hariIni] ?? $hariIni;
            
            // Optimisasi query dengan eager loading dan kondisi yang lebih efisien
            $jadwalHariIni = JadwalAkademik::select([
                'id_JadwalAkademik', 'id_MataKuliah', 'id_Ruang', 
                'id_Gol', 'hari', 'waktu'
            ])
            ->whereHas('matakuliah.krs', function($query) use ($mahasiswa) {
                $query->where('NIM', $mahasiswa->NIM);
            })
            ->where('id_Gol', $mahasiswa->id_Gol)
            ->where(function($query) use ($hariIni, $hariIndo) {
                $query->whereIn('hari', [
                    $hariIndo,
                    strtolower($hariIndo),
                    strtoupper($hariIndo),
                    $hariIni,
                    strtolower($hariIni),
                    ucfirst(strtolower($hariIni))
                ]);
            })
            ->with([
                'matakuliah:id_MataKuliah,nama_MataKuliah,sks',
                'matakuliah.pengampu.dosen:id_Dosen,nama_Dosen',
                'ruang:id_Ruang,nama_Ruang',
                'golongan:id_Gol,nama_Gol'
            ])
            ->orderBy('waktu')
            ->get();

            return $jadwalHariIni;
            
        } catch (\Exception $e) {
            Log::error('Error fetching today schedule: ' . $e->getMessage(), [
                'mahasiswa_nim' => $mahasiswa->NIM,
                'trace' => $e->getTraceAsString()
            ]);
            return collect();
        }
    }

    /**
     * Menghitung statistik kehadiran mahasiswa dengan optimisasi query
     */
    private function getAttendanceStats(string $nimMahasiswa): array
    {
        try {
            // Menggunakan query yang lebih efisien dengan grouping
            $attendanceData = PresensiAkademik::where('NIM', $nimMahasiswa)
                ->selectRaw('
                    status_kehadiran,
                    COUNT(*) as count
                ')
                ->groupBy('status_kehadiran')
                ->pluck('count', 'status_kehadiran')
                ->toArray();

            $totalPresensi = array_sum($attendanceData);
            $hadirCount = $attendanceData['Hadir'] ?? 0;
            $izinCount = $attendanceData['Izin'] ?? 0;
            $alpaCount = $attendanceData['Alpa'] ?? 0;
            
            $attendancePercentage = $totalPresensi > 0 
                ? round(($hadirCount + $izinCount) / $totalPresensi * 100, 1) 
                : 0;
            
            return [
                'total' => $totalPresensi,
                'hadir' => $hadirCount,
                'izin' => $izinCount,
                'alpa' => $alpaCount,
                'percentage' => $attendancePercentage
            ];
        } catch (\Exception $e) {
            Log::error('Error calculating attendance stats: ' . $e->getMessage(), [
                'nim' => $nimMahasiswa
            ]);
            return [
                'total' => 0, 'hadir' => 0, 'izin' => 0, 
                'alpa' => 0, 'percentage' => 0
            ];
        }
    }

    /**
     * Mendapatkan mata kuliah terbaru yang diambil mahasiswa
     */
    private function getRecentCourses(string $nimMahasiswa, int $limit = 5): Collection
    {
        try {
            return Krs::where('NIM', $nimMahasiswa)
                ->with(['matakuliah:id_MataKuliah,nama_MataKuliah,sks,semester'])
                ->orderBy('updated_at', 'desc')
                ->take($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching recent courses: ' . $e->getMessage(), [
                'nim' => $nimMahasiswa
            ]);
            return collect();
        }
    }

    /**
     * Menghitung progress semester
     */
    private function getSemesterProgress(string $nimMahasiswa, int $semester): array
    {
        try {
            // Menggunakan query yang lebih efisien
            $totalMataKuliahSemester = MataKuliah::where('semester', $semester)->count();
            
            $mataKuliahDiambil = Krs::where('NIM', $nimMahasiswa)
                ->whereHas('matakuliah', function($query) use ($semester) {
                    $query->where('semester', $semester);
                })
                ->count();
            
            $progressPercentage = $totalMataKuliahSemester > 0 
                ? round($mataKuliahDiambil / $totalMataKuliahSemester * 100, 1) 
                : 0;
            
            return [
                'total_available' => $totalMataKuliahSemester,
                'taken' => $mataKuliahDiambil,
                'completed' => $mataKuliahDiambil, // Sementara, karena tidak ada data nilai
                'progress_percentage' => $progressPercentage
            ];
        } catch (\Exception $e) {
            Log::error('Error calculating semester progress: ' . $e->getMessage(), [
                'nim' => $nimMahasiswa,
                'semester' => $semester
            ]);
            return [
                'total_available' => 0, 'taken' => 0, 
                'completed' => 0, 'progress_percentage' => 0
            ];
        }
    }

    /**
     * Mendapatkan pengumuman dinamis berdasarkan data akademik
     */
    private function getDynamicAnnouncements(Mahasiswa $userData): array
    {
        try {
            $announcements = [];
            $now = Carbon::now();
            
            // Cek deadline KRS
            $this->addKrsDeadlineAnnouncement($announcements, $now);
            
            // Cek jadwal kuliah tersedia
            $this->addScheduleAnnouncement($announcements, $userData);
            
            // Cek informasi berdasarkan total SKS
            $this->addSksInfoAnnouncement($announcements, $userData);
            
            // Pengumuman default jika tidak ada pengumuman lain
            if (empty($announcements)) {
                $announcements[] = [
                    'title' => 'Selamat Belajar',
                    'message' => 'Semoga semester ini berjalan lancar dan sukses!',
                    'time' => 'Motivasi',
                    'type' => 'info'
                ];
            }
            
            return $announcements;
        } catch (\Exception $e) {
            Log::error('Error generating dynamic announcements: ' . $e->getMessage());
            return [[
                'title' => 'Sistem Informasi Akademik',
                'message' => 'Selamat datang di dashboard mahasiswa.',
                'time' => 'Info',
                'type' => 'info'
            ]];
        }
    }

    /**
     * Menambahkan pengumuman deadline KRS
     */
    private function addKrsDeadlineAnnouncement(array &$announcements, Carbon $now): void
    {
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
    }

    /**
     * Menambahkan pengumuman jadwal kuliah
     */
    private function addScheduleAnnouncement(array &$announcements, Mahasiswa $userData): void
    {
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
    }

    /**
     * Menambahkan pengumuman informasi SKS
     */
    private function addSksInfoAnnouncement(array &$announcements, Mahasiswa $userData): void
    {
        $totalSks = Krs::where('NIM', $userData->NIM)
            ->join('matakuliah', 'krs.id_MataKuliah', '=', 'matakuliah.id_MataKuliah')
            ->sum('matakuliah.sks');
        
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
    }

    /**
     * Menangani error pada dashboard
     */
    private function handleDashboardError(\Exception $e): View
    {
        Log::error('Dashboard error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'user' => Auth::guard('mahasiswa')->id()
        ]);
        
        $userData = Auth::guard('mahasiswa')->user();
        
        return view('dashboard-mhs.index', [
            'userData' => $userData,
            'ipk' => 0,
            'totalSks' => 0,
            'totalMataKuliah' => 0,
            'rataRataSks' => 0,
            'mataKuliahSemesterIni' => 0,
            'statusAkademik' => 'Data tidak tersedia',
            'statusClass' => 'text-muted',
            'jadwalHariIni' => collect(),
            'attendanceStats' => [
                'total' => 0, 'hadir' => 0, 'izin' => 0, 
                'alpa' => 0, 'percentage' => 0
            ],
            'recentCourses' => collect(),
            'semesterProgress' => [
                'total_available' => 0, 'taken' => 0, 
                'completed' => 0, 'progress_percentage' => 0
            ],
            'dynamicAnnouncements' => [[
                'title' => 'Sistem Informasi Akademik',
                'message' => 'Dashboard sedang dalam perbaikan. Silakan refresh halaman.',
                'time' => 'Info',
                'type' => 'warning'
            ]]
        ])->with('error', 'Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
    }
}
