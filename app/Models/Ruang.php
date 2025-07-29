<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $table = 'ruang';
    protected $primaryKey = 'id_ruang';
    
    // Hanya nama_ruang yang perlu diisi oleh pengguna
    protected $fillable = ['nama_ruang'];

    // Tambahkan ini jika tabel Anda tidak memiliki kolom created_at dan updated_at
    public $timestamps = false;

    /**
     * Relasi ke model Jadwal (jika ada).
     * Anda bisa menghapus fungsi ini jika tidak diperlukan.
     */
    public function jadwal()
    {
        return $this->hasMany(JadwalAkademik::class, 'id_ruang');
    }
}