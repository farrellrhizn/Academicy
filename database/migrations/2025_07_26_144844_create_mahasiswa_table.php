<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('NIM')->primary();
            $table->string('Nama');
            $table->string('password'); // Added password field
            $table->text('Alamat');
            $table->string('Nohp')->nullable(); // Added Nohp field
            $table->integer('Semester');
            $table->string('id_Gol');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('id_Gol')->references('id_Gol')->on('golongan')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mahasiswa');
    }
};
