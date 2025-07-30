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
     * Mendapatkan jadwal mengajar hari ini berdasarkan mata kuliah yang diampu dosen
     */
    private function getJadwalHariIni($nipDosen)
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
        
        // Ambil jadwal berdasarkan mata kuliah yang diampu dosen
        $jadwalHariIni = JadwalAkademik::whereHas('matakuliah.pengampu', function($query) use ($nipDosen) {
                $query->where('NIP', $nipDosen);
            })
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

        // Ambil jadwal hari ini berdasarkan mata kuliah yang diampu
        $jadwalHariIni = $this->getJadwalHariIni($nipDosen);

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