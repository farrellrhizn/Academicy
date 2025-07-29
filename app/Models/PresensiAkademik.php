<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PresensiAkademik extends Model
{
    use HasFactory;

    protected $table = 'presensi_akademik';
    public $timestamps = false; // Karena tidak ada created_at, updated_at di tabel
    
    protected $fillable = [
        'hari',
        'tanggal',
        'status_kehadiran',
        'NIM',
        'Kode_mk'
    ];

    protected $dates = [
        'hari',
        'tanggal'
    ];

    /**
     * Relasi ke model Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'NIM', 'NIM');
    }

    /**
     * Relasi ke model MataKuliah
     */
    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'Kode_mk', 'Kode_mk');
    }

    /**
     * Scope untuk filter berdasarkan mata kuliah
     */
    public function scopeByMataKuliah($query, $kodeMk)
    {
        return $query->where('Kode_mk', $kodeMk);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    /**
     * Get presensi by mata kuliah and date
     */
    public static function getPresensiByMkAndDate($kodeMk, $tanggal)
    {
        return self::where('Kode_mk', $kodeMk)
                   ->where('tanggal', $tanggal)
                   ->pluck('status_kehadiran', 'NIM')
                   ->toArray();
    }

    /**
     * Format tanggal untuk display
     */
    public function getFormattedTanggalAttribute()
    {
        return Carbon::parse($this->tanggal)->format('d F Y');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        switch($this->status_kehadiran) {
            case 'Hadir':
                return 'badge-success';
            case 'Ijin':
                return 'badge-warning';
            case 'Alpa':
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Get presensi summary for mata kuliah
     */
    public static function getPresensiSummary($kodeMk)
    {
        return self::where('Kode_mk', $kodeMk)
                   ->selectRaw('status_kehadiran, COUNT(*) as total')
                   ->groupBy('status_kehadiran')
                   ->pluck('total', 'status_kehadiran')
                   ->toArray();
    }

    /**
     * Get unique dates for mata kuliah
     */
    public static function getUniqueDates($kodeMk)
    {
        return self::where('Kode_mk', $kodeMk)
                   ->distinct()
                   ->orderBy('tanggal')
                   ->pluck('tanggal');
    }
}