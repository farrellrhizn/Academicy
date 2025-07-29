<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Gunakan Auth untuk mengambil data dosen
use Illuminate\Support\Facades\Validator;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\PresensiAkademik;
use App\Models\MataKuliah; // Diperlukan untuk info nama MK

class PresensiController extends Controller
{
    /**
     * Menampilkan halaman input presensi.
     */
    public function index(Request $request)
    {
        // Ambil model Dosen yang sedang login
        $dosen = Auth::user(); 
        if (!$dosen) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }
        // 1. (OPTIMASI) Ambil mata kuliah diampu langsung dari relasi `matakuliah()` di model Dosen.
        $matakuliahDiampu = $dosen->matakuliah()->orderBy('Nama_mk')->get();

        // 2. Ambil input dari filter form
        $selectedMk = $request->input('kode_mk');
        $selectedDate = $request->input('tanggal', date('Y-m-d'));

        $mahasiswa = null;
        $selectedMkName = '';
        $presensiData = [];

        if ($selectedMk) {
            // 3. Ambil daftar mahasiswa dari KRS (logika tetap sama)
            $nimMahasiswa = Krs::where('Kode_mk', $selectedMk)->pluck('NIM');
            $mahasiswa = Mahasiswa::with('golongan')
                                  ->whereIn('NIM', $nimMahasiswa)
                                  ->orderBy('Nama')
                                  ->get();

            $mkInfo = MataKuliah::find($selectedMk);
            $selectedMkName = $mkInfo ? $mkInfo->Nama_mk : '';
            
            // 4. (OPTIMASI) Ambil data presensi yang sudah ada menggunakan static method dari model Anda.
            $presensiData = PresensiAkademik::getPresensiByMkAndDate($selectedMk, $selectedDate);
        }
        
        // 5. Kirim data ke view
        return view('presensi-dosen.index', [
            'matakuliah' => $matakuliahDiampu,
            'selectedMk' => $selectedMk,
            'selectedDate' => $selectedDate,
            'mahasiswa' => $mahasiswa,
            'selectedMkName' => $selectedMkName,
            'presensiData' => $presensiData,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data presensi.
     * (Metode ini tidak perlu diubah karena sudah menggunakan cara yang paling efisien)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_mk' => 'required|string|exists:matakuliah,Kode_mk',
            'tanggal' => 'required|date',
            'hari' => 'required|string',
            'mahasiswa' => 'required|array',
            'mahasiswa.*.nim' => 'required|string|exists:mahasiswa,NIM',
            'mahasiswa.*.status' => 'required|string|in:Hadir,Izin,Alpa',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        try {
            foreach ($request->mahasiswa as $mhs) {
                PresensiAkademik::updateOrCreate(
                    [
                        'NIM' => $mhs['nim'],
                        'Kode_mk' => $request->kode_mk,
                        'tanggal' => $request->tanggal,
                    ],
                    [
                        'hari' => $request->hari,
                        'status_kehadiran' => $mhs['status'],
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Data presensi berhasil disimpan!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}