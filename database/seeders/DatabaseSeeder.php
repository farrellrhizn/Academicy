<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call specific seeders for our application
        $this->call([
            DosenMahasiswaSeeder::class,
            TestDataSeeder::class,
            KrsTestDataSeeder::class
        ]);
    }
}
