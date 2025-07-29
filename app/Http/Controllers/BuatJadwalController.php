<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalAkademik;
use App\Models\Matakuliah;
use App\Models\Ruang;
use App\Models\Golongan;
use Illuminate\Support\Facades\Validator;

class BuatJadwalController extends Controller
{
    /**
     * Menampilkan halaman dan daftar jadwal.
     */
    public function index()
    {
        $jadwal = JadwalAkademik::with(['matakuliah.pengampu.dosen', 'ruang', 'golongan'])
            ->orderBy('hari')
            ->orderBy('waktu')
            ->get();

        $matakuliah = Matakuliah::all();
        $ruang = Ruang::all();
        $golongan = Golongan::all();

        return view('buat-jadwal.index', compact('jadwal', 'matakuliah', 'ruang', 'golongan'));
    }

    /**
     * Menyimpan jadwal baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|string|max:20',
            'waktu' => 'required|date_format:H:i',
            'Kode_mk' => 'required|string|exists:matakuliah,Kode_mk',
            'id_ruang' => 'required|integer|exists:ruang,id_ruang',
            'id_Gol' => 'required|integer|exists:golongan,id_Gol',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $jadwal = JadwalAkademik::create($request->all());
        $jadwal->load(['matakuliah.pengampu.dosen', 'ruang', 'golongan']);

        return response()->json([
            'message' => 'Jadwal berhasil ditambahkan!',
            'data'    => $jadwal
        ], 201);
    }

    /**
     * Mengupdate jadwal yang ada.
     */
    public function update(Request $request, JadwalAkademik $buat_jadwal)
    {
        $validator = Validator::make($request->all(), [
            'hari'      => 'required|string',
            'waktu'     => 'required|date_format:H:i',
            'Kode_mk'   => 'required|string|exists:matakuliah,Kode_mk',
            'id_ruang'  => 'required|integer|exists:ruang,id_ruang',
            'id_Gol'    => 'required|integer|exists:golongan,id_Gol',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $buat_jadwal->update($request->all());
        $buat_jadwal->load(['matakuliah.pengampu.dosen', 'ruang', 'golongan']);

        return response()->json([
            'message' => 'Jadwal berhasil diperbarui!',
            'data'    => $buat_jadwal
        ]);
    }

    /**
     * Menghapus jadwal.
     */
    public function destroy(JadwalAkademik $buat_jadwal)
    {
        $buat_jadwal->delete();
        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }
}