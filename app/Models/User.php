<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke tabel dosen
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'user_id');
    }

    // Relasi ke tabel mahasiswa
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    // Helper methods - PASTIKAN METHOD INI ADA
    public function isDosen()
    {
        return $this->user_type === 'dosen';
    }

    public function isMahasiswa()
    {
        return $this->user_type === 'mahasiswa';
    }

    // Method untuk mendapatkan profil lengkap
    public function getProfile()
    {
        if ($this->isDosen()) {
            return $this->dosen;
        }
        return $this->mahasiswa;
    }

    // Method untuk mendapatkan nama lengkap
    public function getFullName()
    {
        $profile = $this->getProfile();
        return $profile ? $profile->Nama : $this->name;
    }
}
