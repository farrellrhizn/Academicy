<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'matakuliah';
    protected $primaryKey = 'Kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'Kode_mk',
        'Nama_mk',
        'sks',
        'semester',
    ];

    // Relasi dengan Pengampu (Dosen)
    public function pengampu()
    {
        return $this->hasMany(Pengampu::class, 'Kode_mk', 'Kode_mk');
    }

    // Relasi dengan Jadwal Akademik
    public function jadwalAkademik()
    {
        return $this->hasMany(JadwalAkademik::class, 'Kode_mk', 'Kode_mk');
    }

    // Relasi dengan KRS
    public function krs()
    {
        return $this->hasMany(Krs::class, 'Kode_mk', 'Kode_mk');
    }

    // Relasi dengan Presensi
    public function presensi()
    {
        return $this->hasMany(PresensiAkademik::class, 'Kode_mk', 'Kode_mk');
    }
}