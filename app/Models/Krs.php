<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Krs
class Krs extends Model
{
    protected $table = 'krs';
    public $incrementing = false;
    protected $fillable = ['NIM', 'Kode_mk'];
    
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'NIM');
    }
    
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'Kode_mk');
    }
}
