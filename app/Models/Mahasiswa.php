<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'NIM';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'NIM', 'Nama', 'password', 'Alamat', 'Nohp', 'Semester', 'id_Gol', // ganti ke 'password'
    ];

    protected $hidden = [
        'password', // ganti ke 'password'
    ];

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_Gol', 'id_Gol');
    }
    
    public function krs()
    {
        return $this->hasMany(Krs::class, 'NIM', 'NIM');
    }

    // Method getAuthPassword() bisa dihapus karena nama kolom sudah standar
}