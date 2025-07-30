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
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Menampilkan halaman input presensi dengan datepicker.
     */
    public function simple()
    {
        // Ambil model Dosen yang sedang login
        $dosen = Auth::user(); 
        if (!$dosen) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid, silakan login kembali.');
        }
        
        // Ambil mata kuliah yang diampu dosen
        $matakuliahDiampu = $dosen->matakuliah()->orderBy('Nama_mk')->get();

        // Ambil input mata kuliah yang dipilih dan tanggal
        $selectedMk = request()->input('kode_mk');
        $selectedDate = request()->input('tanggal', date('Y-m-d')); // Default ke hari ini
        
        $mahasiswa = null;
        $selectedMkName = '';
        $existingPresensi = [];

        if ($selectedMk) {
            // Ambil daftar mahasiswa dari KRS
            $nimMahasiswa = Krs::where('Kode_mk', $selectedMk)->pluck('NIM');
            $mahasiswa = Mahasiswa::with('golongan')
                                  ->whereIn('NIM', $nimMahasiswa)
                                  ->orderBy('Nama')
                                  ->get();

            $mkInfo = MataKuliah::find($selectedMk);
            $selectedMkName = $mkInfo ? $mkInfo->Nama_mk : '';

            // Ambil data presensi yang sudah ada untuk tanggal tersebut
            $existingPresensi = PresensiAkademik::getPresensiByMkAndDate($selectedMk, $selectedDate);
        }
        
        return view('presensi-dosen.simple', [
            'matakuliah' => $matakuliahDiampu,
            'selectedMk' => $selectedMk,
            'selectedDate' => $selectedDate,
            'mahasiswa' => $mahasiswa,
            'selectedMkName' => $selectedMkName,
            'existingPresensi' => $existingPresensi,
        ]);
    }

    /**
     * Menyimpan data presensi dengan tanggal yang dipilih.
     */
    public function storeSimple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_mk' => 'required|string|exists:matakuliah,Kode_mk',
            'tanggal' => 'required|date',
            'mahasiswa' => 'required|array',
            'mahasiswa.*.nim' => 'required|string|exists:mahasiswa,NIM',
            'mahasiswa.*.status' => 'required|string|in:Hadir,Izin,Alpa',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        try {
            // Gunakan tanggal yang dipilih atau hari ini
            $tanggal = $request->tanggal;
            $hari = Carbon::parse($tanggal)->format('l');
            
            // Konversi hari ke bahasa Indonesia
            $hariIndonesia = [
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa', 
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu'
            ];
            
            $hari = $hariIndonesia[$hari] ?? $hari;
            
            foreach ($request->mahasiswa as $mhs) {
                PresensiAkademik::updateOrCreateAttendance(
                    $mhs['nim'],
                    $request->kode_mk,
                    $tanggal,
                    $hari,
                    $mhs['status']
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Data presensi berhasil disimpan untuk tanggal ' . Carbon::parse($tanggal)->format('d F Y') . '!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status kehadiran individual
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|exists:mahasiswa,NIM',
            'kode_mk' => 'required|string|exists:matakuliah,Kode_mk',
            'tanggal' => 'required|date',
            'status_kehadiran' => 'required|string|in:Hadir,Izin,Alpa',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $updated = PresensiAkademik::updateAttendanceStatus(
                $request->nim,
                $request->kode_mk,
                $request->tanggal,
                $request->status_kehadiran
            );

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status kehadiran berhasil diperbarui!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data presensi tidak ditemukan!'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}