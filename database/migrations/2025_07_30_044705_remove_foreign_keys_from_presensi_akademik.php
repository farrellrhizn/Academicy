<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('presensi_akademik', function (Blueprint $table) {
            $table->dropForeign(['NIM']);
            $table->dropForeign(['Kode_mk']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_akademik', function (Blueprint $table) {
            $table->foreign('NIM')->references('NIM')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('Kode_mk')->references('Kode_mk')->on('matakuliah')->onDelete('cascade');
        });
    }
};
