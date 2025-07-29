<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // GANTI INI
use Illuminate\Database\Eloquent\Factories\HasFactory;  // TAMBAHKAN INI

class Admin extends Authenticatable // GANTI INI
{
    use HasFactory; // TAMBAHKAN INI

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    protected $fillable = [
        'username', 'password', 'nama_lengkap'
    ];

    protected $hidden = [
        'password'
    ];

    // Method getAuthPassword(), getKey(), dan getName() bisa dihapus karena sudah ditangani oleh Authenticatable secara otomatis
}