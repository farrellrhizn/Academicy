# Sistem KRS (Kartu Rencana Studi) - Dokumentasi

## Overview
Sistem KRS yang telah dibuat memungkinkan mahasiswa untuk mengambil mata kuliah yang telah dijadwalkan oleh admin dan terintegrasi dengan sistem presensi dosen.

## Fitur Utama

### 1. Halaman KRS Mahasiswa (`/mahasiswa/krs`)
- **Daftar Mata Kuliah Diambil**: Menampilkan mata kuliah yang sudah diambil mahasiswa
- **Daftar Mata Kuliah Tersedia**: Menampilkan mata kuliah yang bisa diambil sesuai semester dan golongan
- **Validasi Otomatis**: 
  - Mata kuliah sesuai semester mahasiswa
  - Mata kuliah yang sudah dijadwalkan untuk golongan mahasiswa
  - Tidak dapat mengambil mata kuliah yang sudah diambil
- **Total SKS**: Perhitungan otomatis total SKS yang diambil

### 2. Halaman Jadwal Kuliah (`/mahasiswa/krs/jadwal`)
- **Jadwal per Hari**: Tampilan jadwal yang terorganisir per hari
- **Informasi Lengkap**: Waktu, ruang, SKS, dan golongan
- **Ringkasan**: Total mata kuliah, SKS, dan hari kuliah
- **Print-friendly**: Dapat dicetak langsung

### 3. Halaman Cetak KRS (`/mahasiswa/krs/cetak`)
- **Format Resmi**: Kop surat universitas dan format akademik
- **Informasi Mahasiswa**: NIM, nama, semester, golongan, total SKS
- **Tanda Tangan**: Area untuk tanda tangan Kaprodi, Dosen PA, dan Mahasiswa
- **Print-ready**: Optimized untuk pencetakan

## Struktur Database

### Tabel yang Terlibat:
1. **krs**: NIM, Kode_mk
2. **mahasiswa**: NIM, Nama, Semester, id_Gol, dll
3. **matakuliah**: Kode_mk, Nama_mk, sks, semester
4. **jadwal_akademik**: id_jadwal, hari, waktu, Kode_mk, id_ruang, id_Gol
5. **ruang**: id_ruang, nama_ruang
6. **golongan**: id_Gol, nama_Gol

### Relasi:
- KRS → Mahasiswa (belongsTo)
- KRS → MataKuliah (belongsTo)
- MataKuliah → JadwalAkademik (hasMany)
- Mahasiswa → Golongan (belongsTo)

## File yang Dibuat/Dimodifikasi

### Controllers:
- `app/Http/Controllers/KrsController.php` - Controller utama untuk KRS

### Models:
- `app/Models/Krs.php` - Ditambahkan relasi dan konfigurasi
- `app/Models/Mahasiswa.php` - Ditambahkan relasi ke KRS

### Views:
- `resources/views/mahasiswa/krs/index.blade.php` - Halaman utama KRS
- `resources/views/mahasiswa/krs/jadwal.blade.php` - Halaman jadwal kuliah  
- `resources/views/mahasiswa/krs/cetak.blade.php` - Halaman cetak KRS

### Routes:
- `routes/web.php` - Ditambahkan route group untuk KRS mahasiswa

### Dashboard:
- `resources/views/dashboard-mhs/index.blade.php` - Diperbarui link menu

## Alur Kerja Sistem

### 1. Admin:
1. Mengelola data mata kuliah di semester tertentu
2. Membuat jadwal akademik untuk mata kuliah per golongan
3. Menentukan ruang dan waktu kuliah

### 2. Mahasiswa:
1. Login ke sistem
2. Mengakses halaman KRS
3. Melihat mata kuliah tersedia (sesuai semester dan golongan)
4. Mengambil mata kuliah yang diinginkan
5. Melihat jadwal kuliah berdasarkan KRS
6. Mencetak KRS untuk keperluan administrasi

### 3. Dosen:
1. Sistem presensi otomatis menampilkan mahasiswa yang mengambil mata kuliah (berdasarkan KRS)
2. Dosen dapat melakukan presensi hanya untuk mahasiswa yang terdaftar di KRS

## Integrasi dengan Sistem Presensi

### PresensiController (`app/Http/Controllers/PresensiController.php`):
- Line 38: `$nimMahasiswa = Krs::where('Kode_mk', $selectedMk)->pluck('NIM');`
- Line 39-42: Mengambil data mahasiswa berdasarkan KRS untuk mata kuliah tertentu
- Dosen hanya bisa mengabsen mahasiswa yang terdaftar di KRS

## Validasi dan Keamanan

### 1. Validasi KRS:
- Mata kuliah harus sesuai semester mahasiswa
- Mata kuliah harus sudah dijadwalkan untuk golongan mahasiswa  
- Tidak boleh duplikasi mata kuliah
- Mata kuliah harus exist di database

### 2. Authorization:
- Hanya mahasiswa yang login dapat mengakses halaman KRS
- Mahasiswa hanya bisa mengelola KRS mereka sendiri
- Menggunakan middleware `auth:mahasiswa`

## UI/UX Features

### 1. Responsive Design:
- Bootstrap 5.3.2 untuk layout responsif
- Mobile-friendly interface

### 2. Interactive Elements:
- Hover effects pada card
- Alert messages untuk feedback
- Confirmation dialog untuk hapus mata kuliah

### 3. Visual Indicators:
- Badge untuk SKS dan golongan
- Color coding untuk hari kuliah
- Icons untuk setiap section

### 4. Print Optimization:
- CSS khusus untuk pencetakan
- Hide navigasi saat print
- Format dokumen resmi

## Routes yang Tersedia

```php
// Mahasiswa Routes (middleware: auth:mahasiswa)
GET  /mahasiswa/krs              // Halaman utama KRS
POST /mahasiswa/krs              // Tambah mata kuliah ke KRS  
DELETE /mahasiswa/krs            // Hapus mata kuliah dari KRS
GET  /mahasiswa/krs/jadwal       // Halaman jadwal kuliah
GET  /mahasiswa/krs/cetak        // Halaman cetak KRS
```

## Cara Penggunaan

### 1. Setup Database:
Pastikan tabel-tabel berikut sudah ada dan terisi:
- mahasiswa (dengan data semester dan id_Gol)
- matakuliah (dengan data semester)
- jadwal_akademik (dengan relasi ke matakuliah dan golongan)
- ruang
- golongan

### 2. Login sebagai Mahasiswa:
- Akses `/login`
- Login dengan kredensial mahasiswa
- Redirect ke dashboard mahasiswa

### 3. Menggunakan KRS:
- Klik menu "KRS" di sidebar
- Pilih mata kuliah dari daftar "Mata Kuliah Tersedia"
- Klik tombol "+" untuk menambahkan
- Lihat mata kuliah yang sudah diambil di "Mata Kuliah Diambil"
- Klik tombol trash untuk menghapus jika perlu

### 4. Melihat Jadwal:
- Klik menu "Jadwal Kuliah" di sidebar
- Lihat jadwal per hari dan ringkasan
- Print jadwal jika diperlukan

### 5. Cetak KRS:
- Dari halaman KRS, klik "Cetak KRS"
- Dokumen siap untuk dicetak dengan format resmi

## Troubleshooting

### 1. Mata Kuliah Tidak Muncul:
- Pastikan mata kuliah sesuai semester mahasiswa
- Pastikan ada jadwal untuk golongan mahasiswa
- Pastikan mata kuliah belum diambil sebelumnya

### 2. Error saat Tambah KRS:
- Check validasi server-side
- Pastikan mahasiswa login
- Pastikan mata kuliah valid

### 3. Jadwal Tidak Tampil:
- Pastikan sudah mengambil mata kuliah di KRS
- Pastikan jadwal_akademik sudah diset admin
- Check relasi database

## Pengembangan Selanjutnya

### 1. Enhancement yang Disarankan:
- Batas maksimal SKS per semester
- Periode KRS (buka/tutup)
- Approval workflow (Dosen PA)
- Conflict detection untuk jadwal bentrok
- History KRS per semester

### 2. Integrasi Lanjutan:
- Sistem pembayaran
- Prasyarat mata kuliah
- Notifikasi real-time
- Export ke PDF/Excel
- Mobile app

## Kesimpulan

Sistem KRS yang telah dibuat menyediakan:
✅ Interface yang user-friendly untuk mahasiswa
✅ Validasi otomatis sesuai business rules
✅ Integrasi dengan sistem presensi dosen
✅ Format cetak yang sesuai standar akademik
✅ Responsive design untuk berbagai device
✅ Security dan authorization yang tepat

Sistem ini siap digunakan dan dapat dikembangkan lebih lanjut sesuai kebutuhan institusi.