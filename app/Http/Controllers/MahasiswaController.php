<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Golongan; // Tambahkan model Golongan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan halaman utama dengan daftar semua mahasiswa.
     */
    public function index()
    {
        // Ambil data mahasiswa beserta relasi golongannya (eager loading)
        $mahasiswa = Mahasiswa::with('golongan')->orderBy('Nama')->get();
        // Ambil semua data golongan untuk dropdown di form
        $golongan = Golongan::all();
        $userData = Auth::guard('admin')->user();
        return view('mahasiswa.index', compact('mahasiswa', 'golongan', 'userData'));
    }

    /**
     * Menyimpan data mahasiswa baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NIM'      => 'required|string|max:20|unique:mahasiswa,NIM',
            'Nama'     => 'required|string|max:100',
            'password' => 'required|string|min:8',
            'Alamat'   => 'nullable|string|max:255',
            'Nohp'     => 'nullable|string|max:20',
            'Semester' => 'required|integer',
            'id_Gol'   => 'required|integer|exists:golongan,id_Gol', // Pastikan id_Gol ada di tabel golongan
        ]);

        // Hash password sebelum disimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        $mahasiswa = Mahasiswa::create($validatedData);
        // Load relasi golongan agar bisa dikirim balik ke view
        $mahasiswa->load('golongan');

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan!',
            'data'    => $mahasiswa
        ]);
    }

    /**
     * Mengambil data mahasiswa spesifik untuk form edit.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        return response()->json($mahasiswa);
    }

    /**
     * Memperbarui data mahasiswa di database.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validatedData = $request->validate([
            'Nama'     => 'required|string|max:100',
            'Alamat'   => 'nullable|string|max:255',
            'Nohp'     => 'nullable|string|max:20',
            'Semester' => 'required|integer',
            'id_Gol'   => 'required|integer|exists:golongan,id_Gol',
            'password' => 'nullable|string|min:8', // Password tidak wajib diisi saat update
        ]);

        // Update data mahasiswa
        $mahasiswa->Nama = $validatedData['Nama'];
        $mahasiswa->Alamat = $validatedData['Alamat'];
        $mahasiswa->Nohp = $validatedData['Nohp'];
        $mahasiswa->Semester = $validatedData['Semester'];
        $mahasiswa->id_Gol = $validatedData['id_Gol'];
        
        // Jika ada password baru yang diinput, hash dan perbarui
        if (!empty($validatedData['password'])) {
            $mahasiswa->password = Hash::make($validatedData['password']);
        }

        $mahasiswa->save();
        // Load relasi golongan untuk data yang diupdate
        $mahasiswa->load('golongan');

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diupdate!',
            'data'    => $mahasiswa
        ]);
    }

    /**
     * Menghapus data mahasiswa dari database.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            $mahasiswa->delete();
            return response()->json(['success' => true, 'message' => 'Data mahasiswa berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus! Data ini mungkin terkait dengan data lain.'
            ], 422);
        }
    }
}