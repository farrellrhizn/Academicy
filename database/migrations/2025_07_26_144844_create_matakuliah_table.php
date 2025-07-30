<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('matakuliah', function (Blueprint $table) {
            $table->string('Kode_mk')->primary();
            $table->string('Nama_mk');
            $table->integer('sks'); // Fixed from 'SKS' to 'sks' to match model
            $table->integer('semester'); // Added semester field
            $table->string('NIP');
            $table->timestamps();

            $table->foreign('NIP')->references('NIP')->on('dosen')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('matakuliah');
    }
};
