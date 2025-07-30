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
            $message = 'Mata kuliah sudah diambil sebelumnya.';
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 400);
            }
            
            return redirect()->back()->with('error', $message);
        }
        
        // Cek apakah mata kuliah sesuai semester mahasiswa
        $matakuliah = MataKuliah::where('Kode_mk', $request->Kode_mk)
                               ->where('semester', $mahasiswa->Semester)
                               ->first();
        
        if (!$matakuliah) {
            $message = 'Mata kuliah tidak sesuai dengan semester Anda.';
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 400);
            }
            
            return redirect()->back()->with('error', $message);
        }
        
        // Cek apakah ada jadwal untuk golongan mahasiswa
        $jadwal = JadwalAkademik::where('Kode_mk', $request->Kode_mk)
                               ->where('id_Gol', $mahasiswa->id_Gol)
                               ->first();
        
        if (!$jadwal) {
            $message = 'Mata kuliah belum dijadwalkan untuk golongan Anda.';
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 400);
            }
            
            return redirect()->back()->with('error', $message);
        }
        
        // Tambahkan ke KRS
        try {
            Krs::create([
                'NIM' => $mahasiswa->NIM,
                'Kode_mk' => $request->Kode_mk
            ]);
            
            $message = "Mata kuliah {$matakuliah->Nama_mk} berhasil ditambahkan ke KRS.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'kode_mk' => $matakuliah->Kode_mk,
                        'nama_mk' => $matakuliah->Nama_mk,
                        'sks' => $matakuliah->sks
                    ]
                ]);
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            $message = 'Terjadi kesalahan saat menambahkan mata kuliah ke KRS.';
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 500);
            }
            
            return redirect()->back()->with('error', $message);
        }
    }
    
    /**
     * Hapus mata kuliah dari KRS
     */
    public function destroy(Request $request)
    {
        try {
            // Debug logging
            \Log::info('KRS Delete Request', [
                'method' => $request->method(),
                'all_data' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'expects_json' => $request->expectsJson()
            ]);

            $request->validate([
                'Kode_mk' => 'required|string|exists:matakuliah,Kode_mk'
            ]);
            
            $mahasiswa = Auth::guard('mahasiswa')->user();
            
            if (!$mahasiswa) {
                $message = 'Sesi login telah berakhir. Silakan login kembali.';
                
                if ($request->expectsJson()) {
                    return response()->json(['message' => $message], 401);
                }
                
                return redirect()->route('login')->with('error', $message);
            }
            
            // Find KRS record with mata kuliah info
            $krs = Krs::where('NIM', $mahasiswa->NIM)
                      ->where('Kode_mk', $request->Kode_mk)
                      ->with('matakuliah')
                      ->first();
            
            if (!$krs) {
                $message = 'Mata kuliah tidak ditemukan dalam KRS Anda.';
                
                if ($request->expectsJson()) {
                    return response()->json(['message' => $message], 404);
                }
                
                return redirect()->back()->with('error', $message);
            }
            
            // Get mata kuliah info for response
            $matakuliah = $krs->matakuliah;
            
            if (!$matakuliah) {
                $message = 'Data mata kuliah tidak ditemukan.';
                
                if ($request->expectsJson()) {
                    return response()->json(['message' => $message], 404);
                }
                
                return redirect()->back()->with('error', $message);
            }

            // Force delete the KRS record, ignoring any foreign key constraints
            DB::beginTransaction();
            try {
                // Use raw SQL to delete and ignore foreign key constraints if needed
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                $krs->delete();
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                
                DB::commit();
                
                $message = "Mata kuliah {$matakuliah->Nama_mk} berhasil dihapus dari KRS.";
                
                if ($request->expectsJson()) {
                    // Get updated available courses after deletion
                    $matakuliahTersedia = $this->getAvailableCourses($mahasiswa);
                    
                    // Find the course that was just deleted to return it
                    $deletedCourse = $matakuliahTersedia->where('Kode_mk', $request->Kode_mk)->first();
                    
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'data' => [
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
                DB::rollback();
                throw $e;
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('KRS Delete Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('KRS Delete Error: ' . $e->getMessage(), [
                'nim' => $mahasiswa->NIM ?? 'unknown',
                'kode_mk' => $request->Kode_mk ?? 'unknown',
                'exception' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            $message = 'Terjadi kesalahan saat menghapus mata kuliah dari KRS.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Get available courses for a student
     */
    private function getAvailableCourses($mahasiswa)
    {
        // Get current KRS courses
        $krsAmbil = Krs::where('NIM', $mahasiswa->NIM)->pluck('Kode_mk');
        
        // Get available courses for student's semester and class
        return MataKuliah::where('semester', $mahasiswa->Semester)
                         ->with(['jadwalAkademik.ruang', 'jadwalAkademik.golongan'])
                         ->whereHas('jadwalAkademik', function($query) use ($mahasiswa) {
                             $query->where('id_Gol', $mahasiswa->id_Gol);
                         })
                         ->whereNotIn('Kode_mk', $krsAmbil)
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