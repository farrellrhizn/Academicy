<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAkademik extends Model
{
    use HasFactory;

    protected $table = 'jadwal_akademik';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;
    protected $fillable = [
        'hari',
        'tanggal', // <-- TAMBAHKAN INI
        'waktu',   // <-- TAMBAHKAN INI
        'Kode_mk',
        'id_ruang',
        'id_Gol'
    ];

    // Relasi ke tabel matakuliah
    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'Kode_mk', 'Kode_mk');
    }

    // Relasi ke tabel ruang
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang', 'id_ruang');
    }

    // Relasi ke tabel golongan
    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_Gol', 'id_Gol');
    }
}