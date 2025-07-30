# Sistem KRS (Kartu Rencana Studi) - Dokumentasi

## Overview
Sistem KRS yang telah dibuat memungkinkan mahasiswa untuk mengambil mata kuliah yang telah dijadwalkan oleh admin dan terintegrasi dengan sistem presensi dosen.

## Perubahan Terbaru (Update Database)

### Perubahan Database
1. **Primary Key KRS**: Telah diperbaiki dari `id_KRS` menjadi `id_krs` untuk konsistensi penamaan
2. **Penghapusan KRS**: Sistem sekarang menggunakan `id_krs` sebagai identifier utama untuk operasi penghapusan
3. **Backward Compatibility**: Sistem masih mendukung penghapusan berdasarkan `Kode_mk` sebagai fallback

### Perubahan File
1. **Model**: `app/Models/Krs.php`
   - Primary key diubah ke `id_krs`
   
2. **Controller**: `app/Http/Controllers/KrsController.php`
   - Method `destroy()` diperbarui untuk menerima `id_krs` atau `Kode_mk`
   - Prioritas diberikan pada `id_krs` jika tersedia
   
3. **View**: `resources/views/mahasiswa/krs/index.blade.php`
   - Tombol hapus sekarang mengirim `id_krs` dan `Kode_mk`
   - JavaScript diperbarui untuk menggunakan `id_krs` sebagai identifier
   
4. **Migration**: `database/migrations/create_krs_table.php`
   - Primary key diubah dari `id_KRS` ke `id_krs`

## Fitur Utama

### 1. Halaman KRS Mahasiswa (`/mahasiswa/krs`)
- **Mata Kuliah yang Sudah Diambil**: Daftar mata kuliah yang sudah dipilih mahasiswa
- **Mata Kuliah Tersedia**: Daftar mata kuliah yang bisa diambil sesuai semester dan golongan
- **Aksi**: Tambah dan hapus mata kuliah dari KRS
- **Validasi**: Pengecekan batas maksimal 24 SKS
- **Real-time Update**: Interface diperbarui tanpa reload halaman

### 2. Halaman Jadwal Kuliah (`/mahasiswa/krs/jadwal`)
- Menampilkan jadwal kuliah berdasarkan KRS yang sudah diambil
- Filter berdasarkan golongan mahasiswa
- Informasi ruang dan waktu kuliah

### 3. Halaman Cetak KRS (`/mahasiswa/krs/cetak`)
- Format cetak untuk administrasi
- Total SKS dan mata kuliah
- Layout yang rapi untuk pencetakan

## Struktur Database

### Tabel Utama
1. **krs**: id_krs (PK), NIM, Kode_mk, Nilai, Grade
2. **mahasiswa**: Data mahasiswa dengan semester dan golongan
3. **matakuliah**: Data mata kuliah dengan SKS dan semester
4. **jadwal_akademik**: Jadwal kuliah per golongan

### Relasi
- KRS → Mahasiswa (belongsTo)
- KRS → MataKuliah (belongsTo)
- Mahasiswa → KRS (hasMany)
- MataKuliah → KRS (hasMany)

## File yang Dimodifikasi

### Backend (Laravel)
- `app/Http/Controllers/KrsController.php` - Controller utama untuk KRS
- `app/Models/Mahasiswa.php` - Ditambahkan relasi ke KRS
- `app/Models/MataKuliah.php` - Ditambahkan relasi ke KRS
- `app/Models/Krs.php` - Ditambahkan relasi dan konfigurasi
- `database/migrations/create_krs_table.php` - Struktur tabel KRS

### Frontend (Blade Templates)
- `resources/views/mahasiswa/krs/index.blade.php` - Halaman utama KRS
- `resources/views/mahasiswa/krs/jadwal.blade.php` - Halaman jadwal kuliah
- `resources/views/mahasiswa/krs/cetak.blade.php` - Halaman cetak KRS

### Route
- `routes/web.php` - Route untuk KRS mahasiswa

## API Endpoints

### KRS Management
- `GET /mahasiswa/krs` - Halaman utama KRS
- `POST /mahasiswa/krs` - Tambah mata kuliah ke KRS
- `DELETE /mahasiswa/krs` - Hapus mata kuliah dari KRS (menggunakan id_krs atau Kode_mk)
- `GET /mahasiswa/krs/available` - Get available courses via AJAX

### Additional Features
- `GET /mahasiswa/krs/jadwal` - Lihat jadwal kuliah
- `GET /mahasiswa/krs/cetak` - Cetak KRS

## Validasi dan Error Handling

### Validasi Input
1. **Tambah KRS**:
   - Mata kuliah harus exist
   - Sesuai semester mahasiswa
   - Belum diambil sebelumnya
   - Ada jadwal untuk golongan mahasiswa
   - Total SKS tidak melebihi 24

2. **Hapus KRS**:
   - `id_krs` atau `Kode_mk` harus valid
   - Mata kuliah harus milik mahasiswa yang login

### Error Handling
- Validasi input dengan pesan error yang jelas
- Logging untuk debugging
- Rollback database transaction jika terjadi error
- Response JSON untuk AJAX request

## Fitur AJAX

### Real-time Updates
- Tambah/hapus mata kuliah tanpa reload halaman
- Update counter SKS secara otomatis
- Animasi smooth untuk perubahan UI
- SweetAlert untuk konfirmasi dan notifikasi

### Progressive Enhancement
- Form tetap berfungsi tanpa JavaScript
- Fallback ke reload halaman jika AJAX gagal
- Graceful degradation untuk browser lama

## Security Features

### Authentication & Authorization
- Guard terpisah untuk mahasiswa (`mahasiswa`)
- Setiap mahasiswa hanya bisa akses KRS sendiri
- CSRF protection untuk semua form

### Data Validation
- Server-side validation untuk semua input
- Sanitasi data sebelum disimpan
- Foreign key constraints di database

## Performance Considerations

### Database
- Index pada foreign key (NIM, Kode_mk)
- Unique constraint untuk mencegah duplikasi
- Eager loading untuk mengurangi query N+1

### Frontend
- AJAX untuk mengurangi full page reload
- Caching static assets
- Optimized JavaScript untuk manipulasi DOM

## Troubleshooting

### Common Issues
1. **Mata kuliah tidak muncul**: Cek jadwal untuk golongan mahasiswa
2. **Error hapus KRS**: Pastikan `id_krs` atau `Kode_mk` valid
3. **SKS limit**: Maksimal 24 SKS per semester

### Debug Mode
- Set `APP_DEBUG=true` di `.env` untuk melihat error detail
- Log file di `storage/logs/laravel.log`
- Browser developer tools untuk AJAX errors

## Future Enhancements

1. **Batch Operations**: Tambah/hapus multiple mata kuliah sekaligus
2. **KRS History**: Track perubahan KRS mahasiswa
3. **Email Notifications**: Notifikasi perubahan KRS
4. **Mobile Responsive**: Optimasi untuk device mobile
5. **Export Features**: Export KRS ke PDF/Excel