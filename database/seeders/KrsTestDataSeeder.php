<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KrsTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Golongan
        $golongan = [
            ['id_Gol' => 1, 'nama_Gol' => 'SI-A'],
            ['id_Gol' => 2, 'nama_Gol' => 'SI-B'],
            ['id_Gol' => 3, 'nama_Gol' => 'TI-A'],
        ];

        foreach ($golongan as $gol) {
            DB::table('golongan')->updateOrInsert(
                ['id_Gol' => $gol['id_Gol']], 
                $gol
            );
        }

        // Data Ruang
        $ruang = [
            ['id_ruang' => 1, 'nama_ruang' => 'Lab Komputer 1'],
            ['id_ruang' => 2, 'nama_ruang' => 'Lab Komputer 2'],
            ['id_ruang' => 3, 'nama_ruang' => 'Ruang Kelas A'],
            ['id_ruang' => 4, 'nama_ruang' => 'Ruang Kelas B'],
        ];

        foreach ($ruang as $r) {
            DB::table('ruang')->updateOrInsert(
                ['id_ruang' => $r['id_ruang']], 
                $r
            );
        }

                // Data Dosen
        $dosen = [
            [
                'NIP' => 'DSN001',
                'Nama' => 'Dr. John Doe, M.Kom',
                'Alamat' => 'Jl. Dosen No. 1',
                'Nohp' => '081111111111',
            ],
            [
                'NIP' => 'DSN002',
                'Nama' => 'Prof. Jane Smith, Ph.D',
                'Alamat' => 'Jl. Dosen No. 2',
                'Nohp' => '081222222222',
            ],
        ];

        foreach ($dosen as $d) {
            DB::table('dosen')->updateOrInsert(
                ['NIP' => $d['NIP']], 
                $d
            );
        }

        // Data Mahasiswa
        $mahasiswa = [
            [
                'NIM' => '2021001',
                'Nama' => 'Ahmad Budi Santoso',
                'password' => Hash::make('password'),
                'Alamat' => 'Jl. Merdeka No. 123',
                'Nohp' => '081234567890',
                'Semester' => 3,
                'id_Gol' => 1,
            ],
            [
                'NIM' => '2021002',
                'Nama' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'Alamat' => 'Jl. Sudirman No. 456',
                'Nohp' => '081234567891',
                'Semester' => 3,
                'id_Gol' => 1,
            ],
            [
                'NIM' => '2021003',
                'Nama' => 'Rudi Hermawan',
                'password' => Hash::make('password'),
                'Alamat' => 'Jl. Thamrin No. 789',
                'Nohp' => '081234567892',
                'Semester' => 3,
                'id_Gol' => 2,
            ],
        ];

        foreach ($mahasiswa as $m) {
            DB::table('mahasiswa')->updateOrInsert(
                ['NIM' => $m['NIM']], 
                $m
            );
        }

        // Data Mata Kuliah
        $matakuliah = [
            [
                'Kode_mk' => 'SI301',
                'Nama_mk' => 'Basis Data',
                'sks' => 3,
                'semester' => 3,
                'NIP' => 'DSN001',
            ],
            [
                'Kode_mk' => 'SI302',
                'Nama_mk' => 'Pemrograman Web',
                'sks' => 3,
                'semester' => 3,
                'NIP' => 'DSN001',
            ],
            [
                'Kode_mk' => 'SI303',
                'Nama_mk' => 'Analisis dan Perancangan Sistem',
                'sks' => 3,
                'semester' => 3,
                'NIP' => 'DSN002',
            ],
            [
                'Kode_mk' => 'SI304',
                'Nama_mk' => 'Jaringan Komputer',
                'sks' => 2,
                'semester' => 3,
                'NIP' => 'DSN002',
            ],
            [
                'Kode_mk' => 'SI305',
                'Nama_mk' => 'Struktur Data',
                'sks' => 3,
                'semester' => 3,
                'NIP' => 'DSN001',
            ],
        ];

        foreach ($matakuliah as $mk) {
            DB::table('matakuliah')->updateOrInsert(
                ['Kode_mk' => $mk['Kode_mk']], 
                $mk
            );
        }

        // Data pengampu sudah terintegrasi di tabel matakuliah melalui kolom NIP

        // Data Jadwal Akademik
        $jadwal = [
            [
                'id_jadwal' => 1,
                'hari' => 'Senin',
                'waktu' => '08:00-10:30',
                'Kode_mk' => 'SI301',
                'id_ruang' => 1,
                'id_Gol' => 1,
            ],
            [
                'id_jadwal' => 2,
                'hari' => 'Senin',
                'waktu' => '08:00-10:30',
                'Kode_mk' => 'SI301',
                'id_ruang' => 2,
                'id_Gol' => 2,
            ],
            [
                'id_jadwal' => 3,
                'hari' => 'Selasa',
                'waktu' => '10:30-13:00',
                'Kode_mk' => 'SI302',
                'id_ruang' => 1,
                'id_Gol' => 1,
            ],
            [
                'id_jadwal' => 4,
                'hari' => 'Selasa',
                'waktu' => '13:00-15:30',
                'Kode_mk' => 'SI302',
                'id_ruang' => 1,
                'id_Gol' => 2,
            ],
            [
                'id_jadwal' => 5,
                'hari' => 'Rabu',
                'waktu' => '08:00-10:30',
                'Kode_mk' => 'SI303',
                'id_ruang' => 3,
                'id_Gol' => 1,
            ],
            [
                'id_jadwal' => 6,
                'hari' => 'Rabu',
                'waktu' => '10:30-13:00',
                'Kode_mk' => 'SI303',
                'id_ruang' => 3,
                'id_Gol' => 2,
            ],
            [
                'id_jadwal' => 7,
                'hari' => 'Kamis',
                'waktu' => '08:00-09:40',
                'Kode_mk' => 'SI304',
                'id_ruang' => 2,
                'id_Gol' => 1,
            ],
            [
                'id_jadwal' => 8,
                'hari' => 'Kamis',
                'waktu' => '09:40-11:20',
                'Kode_mk' => 'SI304',
                'id_ruang' => 2,
                'id_Gol' => 2,
            ],
            [
                'id_jadwal' => 9,
                'hari' => 'Jumat',
                'waktu' => '08:00-10:30',
                'Kode_mk' => 'SI305',
                'id_ruang' => 1,
                'id_Gol' => 1,
            ],
            [
                'id_jadwal' => 10,
                'hari' => 'Jumat',
                'waktu' => '10:30-13:00',
                'Kode_mk' => 'SI305',
                'id_ruang' => 1,
                'id_Gol' => 2,
            ],
        ];

        foreach ($jadwal as $j) {
            DB::table('jadwal_akademik')->updateOrInsert(
                ['id_jadwal' => $j['id_jadwal']], 
                $j
            );
        }

        // Data KRS Sample (beberapa mahasiswa sudah ambil mata kuliah)
        $krs_sample = [
            ['NIM' => '2021001', 'Kode_mk' => 'SI301'],
            ['NIM' => '2021001', 'Kode_mk' => 'SI302'],
            ['NIM' => '2021002', 'Kode_mk' => 'SI301'],
            ['NIM' => '2021002', 'Kode_mk' => 'SI303'],
            ['NIM' => '2021003', 'Kode_mk' => 'SI301'],
        ];

        foreach ($krs_sample as $krs) {
            DB::table('krs')->updateOrInsert(
                ['NIM' => $krs['NIM'], 'Kode_mk' => $krs['Kode_mk']], 
                $krs
            );
        }

        echo "✅ Data testing KRS berhasil dibuat!\n";
        echo "📚 Mata Kuliah: " . count($matakuliah) . " items\n";
        echo "👨‍🎓 Mahasiswa: " . count($mahasiswa) . " items\n";
        echo "📅 Jadwal: " . count($jadwal) . " items\n";
        echo "📝 KRS Sample: " . count($krs_sample) . " items\n\n";
        
        echo "🔑 Login Credentials:\n";
        echo "Mahasiswa:\n";
        foreach ($mahasiswa as $m) {
            echo "  - NIM: {$m['NIM']}, Password: password\n";
        }
        echo "\nDosen:\n";
        foreach ($dosen as $d) {
            echo "  - NIP: {$d['NIP']}, Nama: {$d['Nama']}\n";
        }
    }
}