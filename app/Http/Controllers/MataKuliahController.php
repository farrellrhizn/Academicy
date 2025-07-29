<?php


namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    // Tampilkan semua data mata kuliah (READ)
    public function index()
    {
        $matakuliah = MataKuliah::orderBy('semester')->orderBy('Kode_mk')->get();
        return view('matakuliah.index', compact('matakuliah'));
    }

    // Simpan data baru (CREATE)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Kode_mk' => 'required|max:20|unique:matakuliah,Kode_mk',
            'Nama_mk' => 'required|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $matakuliah = MataKuliah::create($validatedData);

        // Mengembalikan data yang baru dibuat sebagai JSON
        return response()->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil ditambahkan!',
            'data'    => $matakuliah
        ]);
    }

    // Ambil data untuk form edit
    public function edit($Kode_mk)
    {
        $matakuliah = MataKuliah::where('Kode_mk', $Kode_mk)->firstOrFail();
        // Fungsi ini sudah benar, mengembalikan JSON
        return response()->json($matakuliah);
    }

    // Update data mata kuliah (UPDATE)
    public function update(Request $request, $Kode_mk)
    {
        $matakuliah = MataKuliah::where('Kode_mk', $Kode_mk)->firstOrFail();
        
        $validatedData = $request->validate([
            'Nama_mk'  => 'required|max:100',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            // Kode_mk tidak diupdate, karena itu Primary Key
        ]);

        $matakuliah->update($validatedData);

        // Mengembalikan data yang baru diupdate sebagai JSON
        return response()->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil diupdate!',
            'data'    => $matakuliah
        ]);
    }

    // Hapus data mata kuliah (DELETE)
    public function destroy($Kode_mk)
    {
        try {
            $matakuliah = MataKuliah::where('Kode_mk', $Kode_mk)->firstOrFail();
            $matakuliah->delete();
            
            return response()->json(['success' => true, 'message' => 'Data mata kuliah berhasil dihapus!']);
        } catch (\Exception $e) {
            // Menangkap error jika data terikat oleh foreign key
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus! Data ini mungkin digunakan di tabel lain.'
            ], 422); // 422 Unprocessable Entity
        }
    }


    // Filter berdasarkan semester
    public function filterBySemester(Request $request)
    {
        $semester = $request->get('semester');
        
        if ($semester && $semester != 'all') {
            $matakuliah = MataKuliah::where('semester', $semester)
                                   ->orderBy('Kode_mk')
                                   ->get();
        } else {
            $matakuliah = MataKuliah::orderBy('semester')->orderBy('Kode_mk')->get();
        }

        return view('matakuliah.index', compact('matakuliah', 'semester'));
    }
}