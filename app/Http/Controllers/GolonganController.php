<?php

namespace App\Http\Controllers;

use App\Models\Golongan; // Ganti model ke Golongan
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class GolonganController extends Controller
{
    /**
     * Menampilkan halaman utama dengan daftar semua golongan.
     */
    public function index()
    {
        // Ambil data golongan, bukan ruang
        $golongan = Golongan::orderBy('nama_Gol')->get();
        // Arahkan ke view golongan.index dengan data golongan
        return view('golongan.index', compact('golongan'));
    }

    /**
     * Menyimpan data golongan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi untuk nama_Gol
        $validatedData = $request->validate([
            'nama_Gol' => 'required|string|max:100|unique:golongan,nama_Gol',
        ]);

        $golongan = Golongan::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Data golongan berhasil ditambahkan!',
            'data'    => $golongan
        ]);
    }

    /**
     * Mengambil data golongan spesifik untuk form edit.
     */
    public function edit(Golongan $golongan) // Gunakan model Golongan
    {
        return response()->json($golongan);
    }

    /**
     * Memperbarui data golongan di database.
     */
    public function update(Request $request, Golongan $golongan) // Gunakan model Golongan
    {
        // Validasi dengan Rule::unique yang disesuaikan
        $validatedData = $request->validate([
            'nama_Gol' => [
                'required',
                'string',
                'max:100',
                Rule::unique('golongan')->ignore($golongan->id_Gol, 'id_Gol'),
            ],
        ]);

        $golongan->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Data golongan berhasil diupdate!',
            'data'    => $golongan
        ]);
    }

    /**
     * Menghapus data golongan dari database.
     */
    public function destroy(Golongan $golongan) // Gunakan model Golongan
    {
        try {
            $golongan->delete();
            return response()->json(['success' => true, 'message' => 'Data golongan berhasil dihapus!']);
        } catch (\Exception $e) {
            // Menangani error jika golongan masih terhubung dengan data lain
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus! Data ini mungkin terkait dengan data lain.'
            ], 422);
        }
    }
}