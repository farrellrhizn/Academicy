<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal_akademik', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->string('hari');
            $table->date('tanggal')->nullable();
            $table->time('waktu');
            $table->string('Kode_mk');
            $table->unsignedBigInteger('id_ruang');
            $table->string('id_Gol');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('Kode_mk')->references('Kode_mk')->on('matakuliah')->onDelete('cascade');
            $table->foreign('id_ruang')->references('id_ruang')->on('ruang')->onDelete('restrict');
            $table->foreign('id_Gol')->references('id_Gol')->on('golongan')->onDelete('restrict');
            
            // Index untuk performa query
            $table->index(['Kode_mk', 'id_Gol']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_akademik');
    }
};