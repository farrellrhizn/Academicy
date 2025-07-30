<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Krs;
use App\Models\MataKuliah;
use App\Models\JadwalAkademik;
use App\Models\Mahasiswa;
use App\Models\Ruang;
use App\Models\Golongan;
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
                      ->with([
                          'matakuliah', 
                          'matakuliah.jadwalAkademik' => function($query) use ($mahasiswa) {
                              $query->where('id_Gol', $mahasiswa->id_Gol);
                          },
                          'matakuliah.jadwalAkademik.ruang',
                          'matakuliah.jadwalAkademik.golongan'
                      ])
                      ->get();
        
        // Ambil kode mata kuliah yang sudah diambil
        $krsKodeMk = $krsAmbil->pluck('Kode_mk')->toArray();
        
        // Ambil mata kuliah yang tersedia untuk semester mahasiswa
        $matakuliahTersedia = MataKuliah::where('semester', $mahasiswa->Semester)
                                      ->with([
                                          'jadwalAkademik' => function($query) use ($mahasiswa) {
                                              $query->where('id_Gol', $mahasiswa->id_Gol)
                                                    ->with(['ruang', 'golongan']);
                                          }
                                      ])
                                      ->whereHas('jadwalAkademik', function($query) use ($mahasiswa) {
                                          $query->where('id_Gol', $mahasiswa->id_Gol);
                                      })
                                      ->whereNotIn('Kode_mk', $krsKodeMk)
                                      ->orderBy('Nama_mk', 'asc')
                                      ->get();
        
        return view('mahasiswa.krs.index', compact('krsAmbil', 'matakuliahTersedia', 'mahasiswa'));
    }
    
    /**
     * Ambil mata kuliah ke dalam KRS
     */
    public function store(Request $request)
    {
        $request->validate([
            'Kode_mk' => 'required|string|exists:matakuliah,Kode_mk'
        ]);
        
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa) {
            return $this->errorResponse('Sesi login telah berakhir. Silakan login kembali.', 401, $request);
        }
        
        // Cek apakah mata kuliah sudah diambil
        if (Krs::where('NIM', $mahasiswa->NIM)->where('Kode_mk', $request->Kode_mk)->exists()) {
            return $this->errorResponse('Mata kuliah sudah diambil sebelumnya.', 400, $request);
        }
        
        // Cek apakah mata kuliah sesuai semester mahasiswa
        $matakuliah = MataKuliah::where('Kode_mk', $request->Kode_mk)
                               ->where('semester', $mahasiswa->Semester)
                               ->first();
        
        if (!$matakuliah) {
            return $this->errorResponse('Mata kuliah tidak sesuai dengan semester Anda.', 400, $request);
        }
        
        // Cek apakah ada jadwal untuk golongan mahasiswa
        $jadwal = JadwalAkademik::where('Kode_mk', $request->Kode_mk)
                               ->where('id_Gol', $mahasiswa->id_Gol)
                               ->first();
        
        if (!$jadwal) {
            return $this->errorResponse('Mata kuliah belum dijadwalkan untuk golongan Anda.', 400, $request);
        }
        
        try {
            // Tambahkan ke KRS
            $krs = Krs::create([
                'NIM' => $mahasiswa->NIM,
                'Kode_mk' => $request->Kode_mk
            ]);
            
            $message = "Mata kuliah {$matakuliah->Nama_mk} berhasil ditambahkan ke KRS.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'id_krs' => $krs->id_krs,
                        'kode_mk' => $matakuliah->Kode_mk,
                        'nama_mk' => $matakuliah->Nama_mk,
                        'sks' => $matakuliah->sks
                    ]
                ]);
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return $this->errorResponse('Terjadi kesalahan saat menambahkan mata kuliah ke KRS.', 500, $request);
        }
    }
    
    /**
     * Hapus mata kuliah dari KRS
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id_krs' => 'sometimes|integer|exists:krs,id_krs',
            'Kode_mk' => 'sometimes|string|exists:matakuliah,Kode_mk'
        ]);
        
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa) {
            return $this->errorResponse('Sesi login telah berakhir. Silakan login kembali.', 401, $request);
        }
        
        // Find KRS record - prioritas id_krs jika ada, fallback ke Kode_mk
        if ($request->has('id_krs')) {
            $krs = Krs::where('id_krs', $request->id_krs)
                      ->where('NIM', $mahasiswa->NIM)
                      ->with('matakuliah')
                      ->first();
        } else {
            $krs = Krs::where('NIM', $mahasiswa->NIM)
                      ->where('Kode_mk', $request->Kode_mk)
                      ->with('matakuliah')
                      ->first();
        }
        
        if (!$krs) {
            return $this->errorResponse('Mata kuliah tidak ditemukan dalam KRS Anda.', 404, $request);
        }
        
        $matakuliah = $krs->matakuliah;
        
        if (!$matakuliah) {
            return $this->errorResponse('Data mata kuliah tidak ditemukan.', 404, $request);
        }
        
        try {
            $krs->delete();
            
            $message = "Mata kuliah {$matakuliah->Nama_mk} berhasil dihapus dari KRS.";
            
            if ($request->expectsJson()) {
                // Get updated available courses after deletion
                $matakuliahTersedia = $this->getAvailableCourses($mahasiswa);
                
                // Find the course that was just deleted to return it
                $deletedCourse = $matakuliahTersedia->where('Kode_mk', $matakuliah->Kode_mk)->first();
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'id_krs' => $krs->id_krs,
                        'kode_mk' => $matakuliah->Kode_mk,
                        'nama_mk' => $matakuliah->Nama_mk,
                        'sks' => $matakuliah->sks
                    ],
                    'available_course' => $deletedCourse ? [
                        'kode_mk' => $deletedCourse->Kode_mk,
                        'nama_mk' => $deletedCourse->Nama_mk,
                        'sks' => $deletedCourse->sks,
                        'jadwal' => $deletedCourse->jadwalAkademik->where('id_Gol', $mahasiswa->id_Gol)->first()
                    ] : null
                ]);
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return $this->errorResponse('Terjadi kesalahan saat menghapus mata kuliah dari KRS.', 500, $request);
        }
    }
    
    /**
     * Get available courses for a student
     */
    private function getAvailableCourses($mahasiswa)
    {
        // Get current KRS courses
        $krsAmbil = Krs::where('NIM', $mahasiswa->NIM)->pluck('Kode_mk')->toArray();
        
        // Get available courses for student's semester and class
        return MataKuliah::where('semester', $mahasiswa->Semester)
                         ->with([
                             'jadwalAkademik' => function($query) use ($mahasiswa) {
                                 $query->where('id_Gol', $mahasiswa->id_Gol)
                                       ->with(['ruang', 'golongan']);
                             }
                         ])
                         ->whereHas('jadwalAkademik', function($query) use ($mahasiswa) {
                             $query->where('id_Gol', $mahasiswa->id_Gol);
                         })
                         ->whereNotIn('Kode_mk', $krsAmbil)
                         ->orderBy('Nama_mk', 'asc')
                         ->get();
    }
    
    /**
     * Get available courses via AJAX
     */
    public function getAvailableCoursesAjax(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $matakuliahTersedia = $this->getAvailableCourses($mahasiswa);
        
        $courses = $matakuliahTersedia->map(function($matkul) use ($mahasiswa) {
            $jadwal = $matkul->jadwalAkademik->where('id_Gol', $mahasiswa->id_Gol)->first();
            
            return [
                'kode_mk' => $matkul->Kode_mk,
                'nama_mk' => $matkul->Nama_mk,
                'sks' => $matkul->sks,
                'jadwal' => $jadwal ? [
                    'hari' => $jadwal->hari,
                    'waktu' => $jadwal->waktu,
                    'ruang' => $jadwal->ruang->nama_ruang ?? 'TBA'
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }
    
    /**
     * Tampilkan jadwal kuliah berdasarkan KRS
     */
    public function jadwal()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Sesi login telah berakhir. Silakan login kembali.');
        }
        
        $jadwalKuliah = Krs::where('NIM', $mahasiswa->NIM)
                          ->with([
                              'matakuliah',
                              'matakuliah.jadwalAkademik' => function($query) use ($mahasiswa) {
                                  $query->where('id_Gol', $mahasiswa->id_Gol)
                                        ->with(['ruang', 'golongan'])
                                        ->orderBy('hari')
                                        ->orderBy('waktu');
                              }
                          ])
                          ->get()
                          ->map(function($krs) use ($mahasiswa) {
                              $jadwal = $krs->matakuliah->jadwalAkademik->where('id_Gol', $mahasiswa->id_Gol)->first();
                              
                              return [
                                  'Kode_mk' => $krs->matakuliah->Kode_mk,
                                  'Nama_mk' => $krs->matakuliah->Nama_mk,
                                  'sks' => $krs->matakuliah->sks,
                                  'hari' => $jadwal->hari ?? '',
                                  'waktu' => $jadwal->waktu ?? '',
                                  'nama_ruang' => $jadwal->ruang->nama_ruang ?? 'TBA',
                                  'nama_Gol' => $jadwal->golongan->nama_Gol ?? ''
                              ];
                          })
                          ->sortBy('hari')
                          ->sortBy('waktu');
        
        return view('mahasiswa.krs.jadwal', compact('jadwalKuliah', 'mahasiswa'));
    }
    
    /**
     * Cetak KRS (untuk keperluan administrasi)
     */
    public function cetak()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Sesi login telah berakhir. Silakan login kembali.');
        }
        
        $krsData = Krs::where('NIM', $mahasiswa->NIM)
                     ->with([
                         'matakuliah', 
                         'matakuliah.jadwalAkademik' => function($query) use ($mahasiswa) {
                             $query->where('id_Gol', $mahasiswa->id_Gol)
                                   ->with(['ruang', 'golongan']);
                         }
                     ])
                     ->orderBy('Kode_mk', 'asc')
                     ->get();
        
        $totalSks = $krsData->sum(function($krs) {
            return $krs->matakuliah->sks ?? 0;
        });
        
        return view('mahasiswa.krs.cetak', compact('krsData', 'mahasiswa', 'totalSks'));
    }
    
    /**
     * Helper method untuk response error
     */
    private function errorResponse($message, $code, $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], $code);
        }
        
        return redirect()->back()->with('error', $message);
    }
}