<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\JadwalAkademik;
use App\Models\Matakuliah;
use App\Models\Ruang;
use App\Models\Golongan;
use App\Models\Dosen; // 1. Tambahkan model Dosen
use App\Models\Pengampu; // 2. Tambahkan model Pengampu
use Illuminate\Support\Facades\Validator;

class KelolaJadwalController extends Controller
{
    /**
     * Menampilkan halaman kelola jadwal dengan semua data yang diperlukan.
     */
    public function index()
    {
        $jadwal = JadwalAkademik::with(['matakuliah.pengampu.dosen', 'ruang', 'golongan'])
                        ->orderBy('hari', 'asc')
                        ->get();

        $matakuliah = Matakuliah::all();
        $ruang = Ruang::all();
        $golongan = Golongan::all();
        $dosen = Dosen::all(); // 3. Ambil semua data dosen

        // 4. Kirim data dosen ke view
        return view('kelola-jadwal.index', compact('jadwal', 'matakuliah', 'ruang', 'golongan', 'dosen'));
    }

    /**
     * Memperbarui data jadwal yang ada di database.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|string',
            'Kode_mk' => 'required|string|exists:matakuliah,Kode_mk',
            'NIP' => 'required|string|exists:dosen,NIP',
            'id_ruang' => 'required|integer|exists:ruang,id_ruang',
            'id_Gol' => 'required|integer|exists:golongan,id_Gol',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwal = JadwalAkademik::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan.'], 404);
        }

        // Update data pada tabel jadwal_akademik
        $jadwal->update($request->only(['hari', 'Kode_mk', 'id_ruang', 'id_Gol']));

        // 2. GUNAKAN QUERY BUILDER UNTUK MENG-UPDATE TABEL PENGAMPU
        // Ini adalah pengganti dari Pengampu::updateOrCreate()
        DB::table('pengampu')->updateOrInsert(
            ['Kode_mk' => $request->Kode_mk], // Kondisi untuk mencari baris
            ['NIP' => $request->NIP]         // Data yang akan di-update atau di-insert
        );

        return response()->json(['message' => 'Jadwal dan Dosen Pengampu berhasil diperbarui!']);
    }


    public function destroy($id)
    {
        $jadwal = JadwalAkademik::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan.'], 404);
        }
        
        $jadwal->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }
}