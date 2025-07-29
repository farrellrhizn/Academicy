<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalAkademik;
use App\Models\Pengampu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JadwalDosenController extends Controller
{
    /**
     * Menampilkan halaman daftar jadwal mengajar.
     */
    public function index()
    {
        try {
            // Ambil data dosen yang sedang login
            $dosen = Auth::guard('dosen')->user();
            
            if (!$dosen) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            // Ambil jadwal berdasarkan mata kuliah yang diampu dosen
            $jadwal = JadwalAkademik::whereHas('matakuliah', function ($query) use ($dosen) {
                $query->whereHas('pengampu', function ($subQuery) use ($dosen) {
                    $subQuery->where('NIP', $dosen->NIP);
                });
            })
            ->with(['matakuliah', 'ruang', 'golongan'])
            ->orderByRaw("
                CASE 
                    WHEN hari = 'Senin' THEN 1
                    WHEN hari = 'Selasa' THEN 2
                    WHEN hari = 'Rabu' THEN 3
                    WHEN hari = 'Kamis' THEN 4
                    WHEN hari = 'Jumat' THEN 5
                    WHEN hari = 'Sabtu' THEN 6
                    WHEN hari = 'Minggu' THEN 7
                    ELSE 8
                END
            ")
            ->get();

            return view('jadwal-dosen.index', compact('jadwal', 'dosen'));

        } catch (\Exception $e) {
            Log::error('Error di JadwalDosenController: ' . $e->getMessage());
            
            return view('jadwal-dosen.index', [
                'jadwal' => collect([]),
                'dosen' => Auth::guard('dosen')->user(),
                'error' => 'Terjadi kesalahan saat mengambil data jadwal: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Method untuk debug - DIPERBAIKI
     */
    public function debug()
    {
        $dosen = Auth::guard('dosen')->user();
        
        if (!$dosen) {
            echo "<h3>Error: Dosen tidak login</h3>";
            return;
        }

        $nipDosen = $dosen->NIP;

        echo "<h3>Debug Information:</h3>";
        echo "<p><strong>NIP Dosen:</strong> " . $nipDosen . "</p>";
        echo "<p><strong>Nama Dosen:</strong> " . $dosen->Nama . "</p>";
        echo "<hr>";

        // PERBAIKAN: Gunakan query langsung ke model Pengampu
        $matakuliahDiampu = Pengampu::where('NIP', $nipDosen)
            ->with('matakuliah')
            ->get();

        echo "<h4>Mata Kuliah yang Diampu (" . $matakuliahDiampu->count() . "):</h4>";
        if ($matakuliahDiampu->count() > 0) {
            foreach ($matakuliahDiampu as $pengampu) {
                echo "<p>- <strong>" . $pengampu->Kode_mk . "</strong>: " . 
                     ($pengampu->matakuliah->Nama_mk ?? 'N/A') . 
                     " (SKS: " . ($pengampu->matakuliah->sks ?? 'N/A') . ")</p>";
            }
        } else {
            echo "<p style='color: red;'>Tidak ada mata kuliah yang diampu oleh dosen ini.</p>";
        }
        echo "<hr>";

        // Cek jadwal akademik yang ada untuk mata kuliah yang diampu
        $kodeMK = $matakuliahDiampu->pluck('Kode_mk')->toArray();
        
        if (!empty($kodeMK)) {
            $jadwalTerkait = JadwalAkademik::whereIn('Kode_mk', $kodeMK)
                ->with(['matakuliah', 'ruang', 'golongan'])
                ->get();

            echo "<h4>Jadwal Akademik untuk MK yang Diampu (" . $jadwalTerkait->count() . "):</h4>";
            if ($jadwalTerkait->count() > 0) {
                foreach ($jadwalTerkait as $jadwal) {
                    echo "<p>- <strong>" . $jadwal->hari . "</strong>, " . 
                         $jadwal->Kode_mk . " (" . 
                         ($jadwal->matakuliah->Nama_mk ?? 'N/A') . "), " .
                         "Ruang: " . ($jadwal->ruang->nama_ruang ?? 'N/A') . ", " .
                         "Golongan: " . ($jadwal->golongan->nama_Gol ?? 'N/A') . "</p>";
                }
            } else {
                echo "<p style='color: red;'>Tidak ada jadwal akademik untuk mata kuliah yang diampu.</p>";
            }
        } else {
            echo "<h4>Jadwal Akademik untuk MK yang Diampu (0):</h4>";
            echo "<p style='color: red;'>Tidak ada mata kuliah yang diampu, sehingga tidak ada jadwal.</p>";
        }
        
        echo "<hr>";

        // Cek semua jadwal akademik yang ada (untuk perbandingan)
        $semuaJadwal = JadwalAkademik::with(['matakuliah', 'ruang', 'golongan'])->get();
        
        echo "<h4>Semua Jadwal Akademik di Database (" . $semuaJadwal->count() . "):</h4>";
        if ($semuaJadwal->count() > 0) {
            foreach ($semuaJadwal->take(10) as $jadwal) { // Tampilkan hanya 10 pertama
                echo "<p>- " . $jadwal->hari . ", " . 
                     $jadwal->Kode_mk . " (" . 
                     ($jadwal->matakuliah->Nama_mk ?? 'N/A') . ")</p>";
            }
            if ($semuaJadwal->count() > 10) {
                echo "<p><em>... dan " . ($semuaJadwal->count() - 10) . " jadwal lainnya</em></p>";
            }
        } else {
            echo "<p style='color: red;'>Tidak ada jadwal akademik di database.</p>";
        }

        echo "<hr>";
        echo "<h4>Kesimpulan:</h4>";
        if ($matakuliahDiampu->count() == 0) {
            echo "<p style='color: red;'><strong>MASALAH:</strong> Dosen ini tidak mengampu mata kuliah apapun. Pastikan data di tabel 'pengampu' sudah benar dengan NIP: " . $nipDosen . "</p>";
        } elseif (empty($kodeMK) || $jadwalTerkait->count() == 0) {
            echo "<p style='color: red;'><strong>MASALAH:</strong> Tidak ada jadwal untuk mata kuliah yang diampu dosen ini. Pastikan admin sudah membuat jadwal untuk mata kuliah: " . implode(', ', $kodeMK) . "</p>";
        } else {
            echo "<p style='color: green;'><strong>OK:</strong> Data sudah lengkap, jadwal seharusnya tampil di halaman utama.</p>";
        }

        echo "<br><p><a href='" . route('dosen.jadwal.index') . "'>← Kembali ke Halaman Jadwal</a></p>";
    }
}