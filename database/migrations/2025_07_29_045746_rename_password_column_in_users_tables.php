<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah nama kolom di tabel dosen
        Schema::table('dosen', function (Blueprint $table) {
            $table->renameColumn('Password', 'password');
        });

        // Mengubah nama kolom di tabel mahasiswa
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->renameColumn('Password', 'password');
        });
    }

    public function down(): void
    {
        // Kode untuk mengembalikan jika migrasi di-rollback
        Schema::table('dosen', function (Blueprint $table) {
            $table->renameColumn('password', 'Password');
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->renameColumn('password', 'Password');
        });
    }
};