<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\Golongan;
use App\Models\User;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a golongan first or find existing
        $golongan = Golongan::firstOrCreate([
            'id_Gol' => 'S1'
        ], [
            'Nama_gol' => 'Sarjana'
        ]);

        // Create a user for dosen
        $dosenUser = User::firstOrCreate([
            'username' => 'testdosen'
        ], [
            'password' => Hash::make('password123'),
            'user_type' => 'dosen'
        ]);

        // Create a dosen
        $dosen = Dosen::firstOrCreate([
            'NIP' => '1234567890'
        ], [
            'Nama' => 'Test Dosen',
            'Alamat' => 'Jl. Dosen No. 123',
            'Nohp' => '081234567890',
            'user_id' => $dosenUser->id
        ]);

        // Create a user for mahasiswa or find existing
        $user = User::firstOrCreate([
            'username' => 'testmahasiswa'
        ], [
            'password' => Hash::make('password123'),
            'user_type' => 'mahasiswa'
        ]);

        // Create a mahasiswa or find existing
        $mahasiswa = Mahasiswa::firstOrCreate([
            'NIM' => '12345678'
        ], [
            'Nama' => 'Test Mahasiswa',
            'Alamat' => 'Jl. Test No. 123',
            'Semester' => 5,
            'id_Gol' => 'S1',
            'user_id' => $user->id
        ]);

        // Create sample mata kuliah or find existing
        $matakuliah1 = MataKuliah::firstOrCreate([
            'Kode_mk' => 'CS101'
        ], [
            'Nama_mk' => 'Pemrograman Dasar',
            'SKS' => 3,
            'NIP' => $dosen->NIP
        ]);

        $matakuliah2 = MataKuliah::firstOrCreate([
            'Kode_mk' => 'CS102'
        ], [
            'Nama_mk' => 'Struktur Data',
            'SKS' => 3,
            'NIP' => $dosen->NIP
        ]);

        $matakuliah3 = MataKuliah::firstOrCreate([
            'Kode_mk' => 'CS103'
        ], [
            'Nama_mk' => 'Basis Data',
            'SKS' => 4,
            'NIP' => $dosen->NIP
        ]);

        // Create KRS records or find existing
        Krs::firstOrCreate([
            'NIM' => '12345678',
            'Kode_mk' => 'CS101'
        ], [
            'Nilai' => null,
            'Grade' => null
        ]);

        Krs::firstOrCreate([
            'NIM' => '12345678',
            'Kode_mk' => 'CS102'
        ], [
            'Nilai' => null,
            'Grade' => null
        ]);

        Krs::firstOrCreate([
            'NIM' => '12345678',
            'Kode_mk' => 'CS103'
        ], [
            'Nilai' => null,
            'Grade' => null
        ]);

        echo "Test data seeded successfully!\n";
        echo "- Created dosen: {$dosen->NIP} - {$dosen->Nama}\n";
        echo "- Created mahasiswa: {$mahasiswa->NIM} - {$mahasiswa->Nama}\n";
        echo "- Created 3 mata kuliah\n";
        echo "- Created 3 KRS records\n";
    }
}
