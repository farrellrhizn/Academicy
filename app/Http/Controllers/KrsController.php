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
use Carbon\Carbon;

class KrsController extends Controller
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
        
        // Cek batas SKS maksimal
        $currentSks = Krs::where('NIM', $mahasiswa->NIM)
                         ->join('matakuliah', 'krs.Kode_mk', '=', 'matakuliah.Kode_mk')
                         ->sum('matakuliah.sks');
        
        $maxSks = 24; // Batas maksimal SKS per semester
        
        if (($currentSks + $matakuliah->sks) > $maxSks) {
            return $this->errorResponse("Penambahan mata kuliah ini akan melebihi batas maksimal {$maxSks} SKS. SKS saat ini: {$currentSks}, SKS mata kuliah: {$matakuliah->sks}.", 400, $request);
        }
        
        try {
            // Log the attempt for debugging
            // \Log::info('KRS Creation Attempt', [
            //     'NIM' => $mahasiswa->NIM,
            //     'Kode_mk' => $request->Kode_mk,
            //     'matakuliah_name' => $matakuliah->Nama_mk,
            //     'mahasiswa_semester' => $mahasiswa->Semester,
            //     'mahasiswa_golongan' => $mahasiswa->id_Gol
            // ]);
            
            // Validate data before creation
            if (empty($mahasiswa->NIM)) {
                throw new \Exception('NIM mahasiswa tidak valid');
            }
            
            if (empty($request->Kode_mk)) {
                throw new \Exception('Kode mata kuliah tidak valid');
            }
            
            // Use DB transaction for data integrity
            $krs = DB::transaction(function () use ($mahasiswa, $request) {
                return Krs::create([
                    'NIM' => $mahasiswa->NIM,
                    'Kode_mk' => $request->Kode_mk
                ]);
            });
            
            // \Log::info('KRS Created Successfully', [
            //     'id_krs' => $krs->id_krs,
            //     'NIM' => $krs->NIM,
            //     'Kode_mk' => $krs->Kode_mk
            // ]);
            
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
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Database specific errors
            // \Log::error('KRS Database Error', [
            //     'error_code' => $e->getCode(),
            //     'error_message' => $e->getMessage(),
            //     'sql' => $e->getSql() ?? 'No SQL',
            //     'bindings' => $e->getBindings() ?? [],
            //     'NIM' => $mahasiswa->NIM,
            //     'Kode_mk' => $request->Kode_mk
            // ]);
            
            // Check for specific database errors
            if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                return $this->errorResponse('Mata kuliah sudah diambil sebelumnya.', 400, $request);
            } elseif (strpos($e->getMessage(), 'FOREIGN KEY constraint failed') !== false) {
                return $this->errorResponse('Data mata kuliah atau mahasiswa tidak valid.', 400, $request);
            } else {
                return $this->errorResponse('Error database: ' . $e->getMessage(), 500, $request);
            }
            
        } catch (\Exception $e) {
            // General errors
            // \Log::error('KRS General Error', [
            //     'error_message' => $e->getMessage(),
            //     'error_trace' => $e->getTraceAsString(),
            //     'NIM' => $mahasiswa->NIM,
            //     'Kode_mk' => $request->Kode_mk,
            //     'file' => $e->getFile(),
            //     'line' => $e->getLine()
            // ]);
            
            return $this->errorResponse('Terjadi kesalahan: ' . $e->getMessage(), 500, $request);
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
            // Log the deletion attempt
            // \Log::info('KRS Deletion Attempt', [
            //     'id_krs' => $krs->id_krs,
            //     'NIM' => $krs->NIM,
            //     'Kode_mk' => $krs->Kode_mk,
            //     'matakuliah_name' => $matakuliah->Nama_mk
            // ]);
            
            // Use DB transaction for data integrity
            DB::transaction(function () use ($krs) {
                $krs->delete();
            });
            
            // \Log::info('KRS Deleted Successfully', [
            //     'id_krs' => $krs->id_krs,
            //     'NIM' => $krs->NIM,
            //     'Kode_mk' => $krs->Kode_mk
            // ]);
            
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
            
        } catch (\Illuminate\Database\QueryException $e) {
            // \Log::error('KRS Delete Database Error', [
            //     'error_code' => $e->getCode(),
            //     'error_message' => $e->getMessage(),
            //     'id_krs' => $krs->id_krs ?? 'unknown',
            //     'NIM' => $mahasiswa->NIM,
            //     'Kode_mk' => $krs->Kode_mk ?? 'unknown'
            // ]);
            
            return $this->errorResponse('Error database saat menghapus: ' . $e->getMessage(), 500, $request);
            
        } catch (\Exception $e) {
            // \Log::error('KRS Delete General Error', [
            //     'error_message' => $e->getMessage(),
            //     'error_trace' => $e->getTraceAsString(),
            //     'id_krs' => $krs->id_krs ?? 'unknown',
            //     'NIM' => $mahasiswa->NIM,
            //     'file' => $e->getFile(),
            //     'line' => $e->getLine()
            // ]);
            
            return $this->errorResponse('Terjadi kesalahan saat menghapus: ' . $e->getMessage(), 500, $request);
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
     * Tampilkan jadwal kuliah berdasarkan KRS (filter berdasarkan hari saja, bukan tanggal)
     * 
     * Sistem ini akan menampilkan jadwal kuliah berdasarkan hari dalam seminggu,
     * contohnya jika ada mata kuliah yang dijadwalkan pada hari Rabu,
     * maka akan muncul di tabel jadwal kuliah setiap hari Rabu
     * tanpa mempertimbangkan tanggal spesifik.
     */
    public function jadwal()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Sesi login telah berakhir. Silakan login kembali.');
        }
        
        // Mapping urutan hari untuk sorting yang benar
        $urutanHari = [
            'Monday' => 1,
            'Tuesday' => 2, 
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 7
        ];
        
        $jadwalKuliah = Krs::where('NIM', $mahasiswa->NIM)
                          ->with([
                              'matakuliah',
                              'matakuliah.jadwalAkademik' => function($query) use ($mahasiswa) {
                                  $query->forGolongan($mahasiswa->id_Gol)
                                        ->weeklySchedule()
                                        ->with(['ruang', 'golongan']);
                              }
                          ])
                          ->get()
                          ->map(function($krs) use ($mahasiswa) {
                              $jadwal = $krs->matakuliah->jadwalAkademik->where('id_Gol', $mahasiswa->id_Gol)->first();
                              
                              // Pastikan jadwal ada dan memiliki hari yang valid
                              if (!$jadwal || empty($jadwal->hari)) {
                                  return null;
                              }
                              
                              return [
                                  'id_jadwal' => $jadwal->id_jadwal,
                                  'Kode_mk' => $krs->matakuliah->Kode_mk,
                                  'Nama_mk' => $krs->matakuliah->Nama_mk,
                                  'sks' => $krs->matakuliah->sks,
                                  'hari' => $this->normalizeDay($jadwal->hari),
                                  'waktu' => $jadwal->waktu ?? '',
                                  'nama_ruang' => $jadwal->ruang->nama_ruang ?? 'TBA',
                                  'nama_Gol' => $jadwal->golongan->nama_Gol ?? 'TBA'
                              ];
                          })
                          ->filter() // Hapus item null
                          ->sortBy(function($jadwal) use ($urutanHari) {
                              // Sort berdasarkan urutan hari kemudian waktu
                              return ($urutanHari[$jadwal['hari']] ?? 8) . $jadwal['waktu'];
                          });
        
        return view('mahasiswa.jadwal.index', compact('jadwalKuliah', 'mahasiswa'));
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
     * Tampilkan riwayat presensi mahasiswa
     */
    public function riwayatPresensi(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Sesi login telah berakhir. Silakan login kembali.');
        }
        
        // Ambil mata kuliah yang diambil mahasiswa untuk filter
        $matakuliahDiambil = Krs::where('NIM', $mahasiswa->NIM)
                               ->with('matakuliah')
                               ->get()
                               ->pluck('matakuliah')
                               ->filter()
                               ->unique('Kode_mk')
                               ->sortBy('Nama_mk');
        
        // Filter berdasarkan mata kuliah yang dipilih
        $selectedKodeMk = $request->get('kode_mk', '');
        
        $riwayatPresensi = collect();
        
        if ($selectedKodeMk) {
            // Ambil riwayat presensi untuk mata kuliah tertentu
            $riwayatPresensi = \App\Models\PresensiAkademik::where('NIM', $mahasiswa->NIM)
                                                         ->where('Kode_mk', $selectedKodeMk)
                                                         ->with('matakuliah')
                                                         ->orderBy('tanggal', 'desc')
                                                         ->get();
            
            // Hitung statistik presensi
            $statistik = [
                'total_pertemuan' => $riwayatPresensi->count(),
                'hadir' => $riwayatPresensi->where('status_kehadiran', 'Hadir')->count(),
                'izin' => $riwayatPresensi->where('status_kehadiran', 'Izin')->count(),
                'alpa' => $riwayatPresensi->where('status_kehadiran', 'Alpa')->count(),
            ];
            
            $statistik['persentase_kehadiran'] = $statistik['total_pertemuan'] > 0 
                ? round(($statistik['hadir'] / $statistik['total_pertemuan']) * 100, 1) 
                : 0;
        } else {
            $statistik = null;
        }
        
        return view('mahasiswa.presensi.riwayat', compact(
            'riwayatPresensi', 
            'matakuliahDiambil', 
            'selectedKodeMk', 
            'mahasiswa',
            'statistik'
        ));
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