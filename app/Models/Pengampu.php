<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengampu extends Model
{
    protected $table = 'pengampu';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['Kode_mk', 'NIP'];

    // Relasi ke matakuliah (sudah ada)
    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'Kode_mk', 'Kode_mk');
    }

    // Relasi ke dosen (sudah ada)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'NIP', 'NIP');
    }
}