<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * @var string
     */
    protected $table = 'golongan';

    /**
     * Primary key untuk tabel.
     * @var string
     */
    protected $primaryKey = 'id_Gol';

    /**
     * Menandakan jika model harus memiliki timestamps (created_at, updated_at).
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal.
     * @var array
     */
    protected $fillable = [
        'nama_Gol'
    ];
}