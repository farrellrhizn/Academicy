<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class DosenMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 data Dosen
        Dosen::create([
            'NIP' => 'DSN003',
            'Nama' => 'Budi Santoso, M.Kom',
            'password' => Hash::make('12345678'), // Password di-hash
            'Alamat' => 'Jl. Merdeka No. 10',
            'Nohp' => '081234567890',
        ]);

        // Buat 1 data Mahasiswa
        Mahasiswa::create([
            'NIM' => 'MHS003',
            'Nama' => 'Ani Wijaya',
            'password' => Hash::make('12345678'), // Password di-hash
            'Alamat' => 'Jl. Pelajar No. 5',
            'Nohp' => '081209876543',
            'Semester' => 1,
            'id_Gol' => 1,
        ]);
    }
}