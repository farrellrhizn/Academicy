<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class RuangController extends Controller
{
    /**
     * Menampilkan halaman utama dengan daftar semua ruang.
     */
    public function index()
    {
        $ruang = Ruang::orderBy('nama_ruang')->get();
        return view('ruang.index', compact('ruang'));
    }

    /**
     * Menyimpan data ruang baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_ruang' => 'required|string|max:100|unique:ruang,nama_ruang',
        ]);

        $ruang = Ruang::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Data ruang berhasil ditambahkan!',
            'data'    => $ruang
        ]);
    }

    /**
     * Mengambil data ruang spesifik untuk form edit.
     */
    public function edit(Ruang $ruang)
    {
        return response()->json($ruang);
    }

    /**
     * Memperbarui data ruang di database.
     */
    public function update(Request $request, Ruang $ruang)
    {
        $validatedData = $request->validate([
            'nama_ruang' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ruang')->ignore($ruang->id_ruang, 'id_ruang'),
            ],
        ]);

        $ruang->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Data ruang berhasil diupdate!',
            'data'    => $ruang
        ]);
    }

    /**
     * Menghapus data ruang dari database.
     */
    public function destroy(Ruang $ruang)
    {
        try {
            $ruang->delete();
            return response()->json(['success' => true, 'message' => 'Data ruang berhasil dihapus!']);
        } catch (\Exception $e) {
            // Menangani error jika ruang masih terhubung dengan data lain (misal: jadwal)
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus! Data ini mungkin terkait dengan data lain.'
            ], 422);
        }
    }
}