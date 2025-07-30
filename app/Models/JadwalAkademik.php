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
        'tanggal', // Field opsional untuk referensi tanggal spesifik (tidak digunakan untuk filtering)
        'waktu',
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

    // Scope untuk filter berdasarkan hari tanpa mempertimbangkan tanggal
    public function scopeByDay($query, $day)
    {
        return $query->where('hari', $day);
    }

    // Scope untuk filter berdasarkan golongan
    public function scopeForGolongan($query, $idGol)
    {
        return $query->where('id_Gol', $idGol);
    }

    // Scope untuk mendapatkan jadwal mingguan (berdasarkan hari saja)
    public function scopeWeeklySchedule($query)
    {
        return $query->orderByRaw("
            CASE hari 
                WHEN 'Monday' THEN 1
                WHEN 'Tuesday' THEN 2  
                WHEN 'Wednesday' THEN 3
                WHEN 'Thursday' THEN 4
                WHEN 'Friday' THEN 5
                WHEN 'Saturday' THEN 6
                WHEN 'Sunday' THEN 7
                ELSE 8
            END
        ")->orderBy('waktu');
    }
}