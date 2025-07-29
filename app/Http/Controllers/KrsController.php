<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Krs;
use App\Models\MataKuliah;
use App\Models\JadwalAkademik;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
{
    /**
     * Tampilkan halaman KRS utama dengan mata kuliah tersedia
     */
    public function index()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        // Ambil mata kuliah yang sudah diambil mahasiswa
        $krsAmbil = Krs::where('NIM', $mahasiswa->NIM)
                      ->with(['matakuliah', 'matakuliah.jadwalAkademik.ruang', 'matakuliah.jadwalAkademik.golongan'])
                      ->get();
        
        // Ambil mata kuliah yang tersedia untuk semester mahasiswa
        $matakuliahTersedia = MataKuliah::where('semester', $mahasiswa->Semester)
                                      ->with(['jadwalAkademik.ruang', 'jadwalAkademik.golongan'])
                                      ->whereHas('jadwalAkademik', function($query) use ($mahasiswa) {
                                          $query->where('id_Gol', $mahasiswa->id_Gol);
                                      })
                                      ->whereNotIn('Kode_mk', $krsAmbil->pluck('Kode_mk'))
                                      ->get();
        
        return view('mahasiswa.krs.index', compact('krsAmbil', 'matakuliahTersedia', 'mahasiswa'));
    }
    
    /**
     * Ambil mata kuliah ke dalam KRS
     */
    public function store(Request $request)
    {
        $request->validate([
            'Kode_mk' => 'required|exists:matakuliah,Kode_mk'
        ]);
        
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        // Cek apakah mata kuliah sudah diambil
        $existingKrs = Krs::where('NIM', $mahasiswa->NIM)
                         ->where('Kode_mk', $request->Kode_mk)
                         ->first();
        
        if ($existingKrs) {
            return redirect()->back()->with('error', 'Mata kuliah sudah diambil sebelumnya.');
        }
        
        // Cek apakah mata kuliah sesuai semester mahasiswa
        $matakuliah = MataKuliah::where('Kode_mk', $request->Kode_mk)
                               ->where('semester', $mahasiswa->Semester)
                               ->first();
        
        if (!$matakuliah) {
            return redirect()->back()->with('error', 'Mata kuliah tidak sesuai dengan semester Anda.');
        }
        
        // Cek apakah ada jadwal untuk golongan mahasiswa
        $jadwal = JadwalAkademik::where('Kode_mk', $request->Kode_mk)
                               ->where('id_Gol', $mahasiswa->id_Gol)
                               ->first();
        
        if (!$jadwal) {
            return redirect()->back()->with('error', 'Mata kuliah belum dijadwalkan untuk golongan Anda.');
        }
        
        // Tambahkan ke KRS
        Krs::create([
            'NIM' => $mahasiswa->NIM,
            'Kode_mk' => $request->Kode_mk
        ]);
        
        return redirect()->back()->with('success', 'Mata kuliah berhasil ditambahkan ke KRS.');
    }
    
    /**
     * Hapus mata kuliah dari KRS
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'Kode_mk' => 'required|exists:matakuliah,Kode_mk'
        ]);
        
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        $krs = Krs::where('NIM', $mahasiswa->NIM)
                  ->where('Kode_mk', $request->Kode_mk)
                  ->first();
        
        if (!$krs) {
            return redirect()->back()->with('error', 'Mata kuliah tidak ditemukan dalam KRS Anda.');
        }
        
        $krs->delete();
        
        return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus dari KRS.');
    }
    
    /**
     * Tampilkan jadwal kuliah berdasarkan KRS
     */
    public function jadwal()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        $jadwalKuliah = DB::table('krs')
                         ->join('matakuliah', 'krs.Kode_mk', '=', 'matakuliah.Kode_mk')
                         ->join('jadwal_akademik', 'matakuliah.Kode_mk', '=', 'jadwal_akademik.Kode_mk')
                         ->join('ruang', 'jadwal_akademik.id_ruang', '=', 'ruang.id_ruang')
                         ->join('golongan', 'jadwal_akademik.id_Gol', '=', 'golongan.id_Gol')
                         ->where('krs.NIM', $mahasiswa->NIM)
                         ->where('jadwal_akademik.id_Gol', $mahasiswa->id_Gol)
                         ->select(
                             'matakuliah.Kode_mk',
                             'matakuliah.Nama_mk',
                             'matakuliah.sks',
                             'jadwal_akademik.hari',
                             'jadwal_akademik.waktu',
                             'ruang.nama_ruang',
                             'golongan.nama_Gol'
                         )
                         ->orderBy('jadwal_akademik.hari')
                         ->orderBy('jadwal_akademik.waktu')
                         ->get();
        
        return view('mahasiswa.krs.jadwal', compact('jadwalKuliah', 'mahasiswa'));
    }
    
    /**
     * Cetak KRS (untuk keperluan administrasi)
     */
    public function cetak()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        $krsData = Krs::where('NIM', $mahasiswa->NIM)
                     ->with(['matakuliah', 'matakuliah.jadwalAkademik' => function($query) use ($mahasiswa) {
                         $query->where('id_Gol', $mahasiswa->id_Gol);
                     }, 'matakuliah.jadwalAkademik.ruang'])
                     ->get();
        
        $totalSks = $krsData->sum(function($krs) {
            return $krs->matakuliah->sks;
        });
        
        return view('mahasiswa.krs.cetak', compact('krsData', 'mahasiswa', 'totalSks'));
    }
}