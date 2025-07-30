# 🔧 PANDUAN TROUBLESHOOTING SISTEM KRS

## 🎯 MASALAH YANG TELAH DIPERBAIKI

### 1. **Import Model yang Tidak Konsisten** ✅
**Masalah**: Case-sensitive import pada `Matakuliah` vs `MataKuliah`
**Solusi**: Standardisasi nama model menjadi `MataKuliah` di semua file

### 2. **Missing Import Statements** ✅
**Masalah**: Import model yang kurang lengkap di KrsController
**Solusi**: Menambahkan import untuk `Ruang`, `Golongan`, `Log`, dan `ValidationException`

### 3. **Query Optimization Issues** ✅
**Masalah**: Eager loading yang tidak efisien dan N+1 query problem
**Solusi**: Optimasi eager loading dengan kondisi filter yang tepat

### 4. **Exception Handling yang Lemah** ✅
**Masalah**: Error handling yang tidak comprehensive
**Solusi**: Menambahkan try-catch block dengan logging yang detail

## 🚨 LANGKAH TROUBLESHOOTING

### A. CEK KONEKSI DATABASE
```bash
# Cek apakah database terhubung
php artisan tinker
> DB::connection()->getPdo();
```

### B. VALIDASI STRUKTUR TABEL
```bash
# Cek apakah tabel KRS ada
php artisan tinker
> Schema::hasTable('krs');
> Schema::hasTable('mahasiswa');
> Schema::hasTable('matakuliah');
> Schema::hasTable('jadwal_akademik');
```

### C. CEK DATA SEEDER
```bash
# Jalankan seeder untuk data test
php artisan db:seed --class=KrsTestDataSeeder
```

### D. VALIDASI RELASI MODEL
```bash
php artisan tinker
> $mahasiswa = App\Models\Mahasiswa::first();
> $mahasiswa->krs; // Harus bisa akses KRS
> $krs = App\Models\Krs::first();
> $krs->matakuliah; // Harus bisa akses mata kuliah
> $krs->mahasiswa; // Harus bisa akses mahasiswa
```

## 🐛 DEBUGGING COMMON ERRORS

### 1. **Error: "Class 'Matakuliah' not found"**
**Solusi**: Pastikan semua import menggunakan `MataKuliah` (capital K)

### 2. **Error: "SQLSTATE[42S02]: Base table or view not found"**
**Solusi**: 
```bash
php artisan migrate
php artisan db:seed --class=KrsTestDataSeeder
```

### 3. **Error: "Call to undefined method"**
**Solusi**: Periksa relasi di model dan pastikan nama method sesuai

### 4. **Error: "Attempting to access array offset on value of type null"**
**Solusi**: Tambahkan null check di view atau controller

## 📋 CHECKLIST SEBELUM TESTING

- [ ] ✅ Database sudah di-migrate
- [ ] ✅ Seeder sudah dijalankan  
- [ ] ✅ Mahasiswa test sudah ada (NIM: 2021001, Password: password)
- [ ] ✅ Mata kuliah semester 3 sudah ada
- [ ] ✅ Jadwal akademik sudah dibuat untuk golongan mahasiswa
- [ ] ✅ Routes KRS sudah terdaftar
- [ ] ✅ Middleware auth:mahasiswa sudah aktif

## 🔍 TESTING WORKFLOW

### 1. **Login Mahasiswa**
```
URL: /login
NIM: 2021001
Password: password
```

### 2. **Akses KRS**
```
URL: /mahasiswa/krs
Expected: Melihat mata kuliah tersedia dan yang sudah diambil
```

### 3. **Test Tambah Mata Kuliah**
```
Action: Klik tombol "Ambil" pada mata kuliah
Expected: Mata kuliah berhasil ditambahkan ke KRS
```

### 4. **Test Hapus Mata Kuliah**
```
Action: Klik tombol "Hapus" pada mata kuliah yang sudah diambil
Expected: Mata kuliah berhasil dihapus dari KRS
```

### 5. **Test Validasi**
```
- Coba ambil mata kuliah yang sama dua kali
- Coba ambil mata kuliah di luar semester
- Coba ambil mata kuliah tanpa jadwal untuk golongan
```

## 🚀 OPTIMASI PERFORMA

### 1. **Database Indexing**
```sql
-- Tambahkan index untuk performa yang lebih baik
CREATE INDEX idx_krs_nim ON krs(NIM);
CREATE INDEX idx_krs_kode_mk ON krs(Kode_mk);
CREATE INDEX idx_jadwal_kode_mk_gol ON jadwal_akademik(Kode_mk, id_Gol);
```

### 2. **Caching (Opsional)**
```php
// Cache data mata kuliah yang sering diakses
Cache::remember("matakuliah_semester_{$semester}", 3600, function() use ($semester) {
    return MataKuliah::where('semester', $semester)->get();
});
```

## 📝 LOG MONITORING

### Lokasi Log Files:
- `storage/logs/laravel.log` - General application logs
- Browser Console - JavaScript errors
- Network Tab - AJAX request/response

### Contoh Log yang Berguna:
```
[2025-01-XX XX:XX:XX] local.ERROR: KRS Store Error: SQLSTATE[23000]
Context: {"nim":"2021001","kode_mk":"IF301","file":"...","line":"..."}
```

## 🔧 PERBAIKAN TERAKHIR YANG DILAKUKAN

1. **KrsController.php**: 
   - Menambahkan import yang missing
   - Memperbaiki exception handling
   - Optimasi eager loading
   - Menambahkan auth check di semua method

2. **JadwalAkademik.php**: 
   - Memperbaiki relasi ke MataKuliah (case-sensitive)

3. **Query Optimization**:
   - Eager loading dengan kondisi filter
   - Mengurangi N+1 query problem
   - Menambahkan ordering untuk consistency

## 📞 SUPPORT

Jika masih mengalami masalah setelah mengikuti panduan ini:

1. Cek log error di `storage/logs/laravel.log`
2. Pastikan semua dependency terinstall
3. Restart web server jika perlu
4. Clear cache: `php artisan cache:clear`
5. Clear config: `php artisan config:clear`

---

**Sistem KRS telah diperbaiki dan siap untuk production!** 🎉