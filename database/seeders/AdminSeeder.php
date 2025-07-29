<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek dulu agar tidak membuat admin duplikat
        if (Admin::where('username', 'admin')->count() == 0) {
            Admin::create([
                'nama_lengkap' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('fxckinsane') // Password di-hash di phpsini
            ]);
        }
    }
}