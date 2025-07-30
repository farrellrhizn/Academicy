<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id('id_krs');
            $table->string('NIM');
            $table->string('Kode_mk');
            $table->decimal('Nilai', 3, 2)->nullable();
            $table->char('Grade', 2)->nullable();
            $table->timestamps();

            $table->foreign('NIM')->references('NIM')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('Kode_mk')->references('Kode_mk')->on('matakuliah')->onDelete('cascade');
            
            // Unique constraint untuk mencegah mahasiswa mengambil mata kuliah yang sama dua kali
            $table->unique(['NIM', 'Kode_mk']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('krs');
    }
};
