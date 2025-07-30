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
        Schema::create('presensi_akademik', function (Blueprint $table) {
            $table->string('NIM', 20);
            $table->string('Kode_mk', 20);
            $table->date('tanggal');
            $table->string('hari', 20); // Changed from date to string to store day names
            $table->enum('status_kehadiran', ['Hadir', 'Izin', 'Alpa']);
            
            // Set primary key as composite
            $table->primary(['NIM', 'Kode_mk', 'tanggal']);
            
            // Foreign key constraints
            $table->foreign('NIM')->references('NIM')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('Kode_mk')->references('Kode_mk')->on('matakuliah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_akademik');
    }
};
