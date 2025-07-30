<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('Nohp');
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('Nohp');
        });
    }

    public function down()
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
        });
    }
};