<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'dosen';
    protected $primaryKey = 'NIP';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'NIP', 'Nama', 'password', 'Alamat', 'Nohp', 'profile_photo'
    ];

    protected $hidden = [
        'password',
    ];

    // TAMBAHAN RELASI SAJA
    /**
     * Relasi ke tabel pengampu
     */
    public function pengampu()
    {
        return $this->hasMany(Pengampu::class, 'NIP', 'NIP');
    }

    /**
     * Relasi ke mata kuliah yang diampu
     */
    public function matakuliah()
    {
        return $this->hasManyThrough(
            // 1. Model tujuan akhir yang ingin diakses
            \App\Models\MataKuliah::class,

            // 2. Model perantara
            \App\Models\Pengampu::class,

            // 3. Foreign key di tabel perantara (pengampu) yang merujuk ke tabel awal (dosen)
            'NIP',

            // 4. Foreign key di tabel perantara (pengampu) yang merujuk ke tabel tujuan (matakuliah)
            'Kode_mk',

            // 5. Primary key di tabel awal (dosen)
            'NIP',

            // 6. Primary key di tabel tujuan (matakuliah)
            'Kode_mk'
        );
    }
}