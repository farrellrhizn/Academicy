<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::orderBy('Nama')->get();
        return view('dosen.index', compact('dosen'));
    }

    public function store(Request $request)
    {
        // UBAH INI: validasi menggunakan 'password' (lowercase)
        $validatedData = $request->validate([
            'NIP'      => 'required|string|max:20|unique:dosen,NIP',
            'Nama'     => 'required|string|max:100',
            'password' => 'required|string|min:8', // 'password' bukan 'Password'
            'Alamat'   => 'required|string|max:255',
            'Nohp'     => 'required|string|max:15',
        ]);

        // UBAH INI: hash field 'password'
        $validatedData['password'] = Hash::make($validatedData['password']);

        $dosen = Dosen::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil ditambahkan!',
            'data'    => $dosen
        ]);
    }

    public function edit(Dosen $dosen)
    {
        return response()->json($dosen);
    }

    public function update(Request $request, Dosen $dosen)
    {
        // UBAH INI: validasi menggunakan 'password' (lowercase)
        $validatedData = $request->validate([
            'Nama'     => 'required|string|max:100',
            'Alamat'   => 'required|string|max:255',
            'Nohp'     => 'required|string|max:15',
            'password' => 'nullable|string|min:8', // 'password' bukan 'Password'
        ]);

        // Logika update yang lebih ringkas
        $updateData = $request->only('Nama', 'Alamat', 'Nohp');

        // Jika ada password baru yang diinput, hash dan tambahkan ke data update
        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $dosen->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil diupdate!',
            'data'    => $dosen->fresh() // Mengambil data terbaru dari db
        ]);
    }
    
    public function destroy(Dosen $dosen)
    {
        try {
            $dosen->delete();
            return response()->json(['success' => true, 'message' => 'Data dosen berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus! Data ini mungkin terkait dengan data lain.'
            ], 422);
        }
    }
}