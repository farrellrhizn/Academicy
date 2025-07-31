<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Krs
class Krs extends Model
{
    use HasFactory;
    
    protected $table = 'krs';
    protected $primaryKey = 'id_krs';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['NIM', 'Kode_mk'];
    
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'NIM', 'NIM');
    }
    
    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'Kode_mk', 'Kode_mk');
    }
    
    public function jadwalAkademik()
    {
        return $this->hasOneThrough(
            JadwalAkademik::class,
            MataKuliah::class,
            'Kode_mk',     // Foreign key pada tabel matakuliah
            'Kode_mk',     // Foreign key pada tabel jadwal_akademik
            'Kode_mk',     // Local key pada tabel krs
            'Kode_mk'      // Local key pada tabel matakuliah
        );
    }
}
