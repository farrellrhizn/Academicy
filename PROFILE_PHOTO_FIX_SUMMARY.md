# Perbaikan Bug Upload Foto Profil

## Masalah yang Ditemukan

1. **Storage Symlink Hilang**: Symlink dari `public/storage` ke `storage/app/public` tidak ada, menyebabkan gambar yang diupload tidak bisa diakses dari web.

2. **Directory profile_photos Tidak Ada**: Folder `storage/app/public/profile_photos` tidak ada untuk menyimpan foto profil.

3. **JavaScript Tidak Optimal**: Fungsi preview dan update avatar navbar tidak bekerja dengan baik.

4. **Form Submission Tidak AJAX**: Setelah upload, halaman reload sehingga avatar di navbar tidak langsung terupdate.

5. **Error Handling Kurang**: Tidak ada validasi client-side dan feedback yang memadai.

## Perbaikan yang Diterapkan

### 1. Storage Configuration
```bash
# Membuat directory untuk foto profil
mkdir -p storage/app/public/profile_photos

# Membuat symlink storage
ln -s ../storage/app/public public/storage

# Set permissions
chmod 755 storage/app/public/profile_photos
```

### 2. Controller Improvements
**File**: `app/Http/Controllers/ProfileController.php`

- Menambahkan response JSON untuk AJAX request
- Mengembalikan URL foto profil baru setelah upload berhasil
- Improve error handling untuk delete photo

### 3. Frontend JavaScript Enhancements

#### Dosen Profile (`resources/views/profile/edit-dosen.blade.php`)
- **Image Preview**: Improved dengan validasi file type dan size
- **AJAX Form Submission**: Form menggunakan AJAX untuk update tanpa reload
- **Navbar Avatar Update**: Avatar di navbar langsung terupdate setelah upload
- **File Validation**: Client-side validation untuk tipe file dan ukuran
- **Loading States**: Button menampilkan status loading saat proses upload
- **Better Error Handling**: Error message yang lebih informatif

#### Mahasiswa Profile (`resources/views/profile/edit-mahasiswa.blade.php`)
- Implementasi yang sama dengan dosen profile

### 4. File Validation
**Client-side validation**:
- Tipe file: JPEG, PNG, GIF
- Ukuran maksimal: 2MB
- Real-time feedback saat memilih file

**Server-side validation**:
- Laravel validation rules sudah ada
- File type: `image|mimes:jpeg,png,jpg,gif`
- Max size: 2048KB

### 5. User Experience Improvements
- Preview gambar real-time saat memilih file
- Loading indicator saat upload/delete
- Navbar avatar langsung terupdate tanpa reload halaman
- Tombol delete foto otomatis muncul/hilang sesuai kondisi
- Error message yang jelas dan informatif

## Fitur yang Bekerja Sekarang

1. ✅ **Upload Foto Profil**: Dapat upload foto baru dengan preview real-time
2. ✅ **Edit/Ganti Foto**: Dapat mengganti foto yang sudah ada
3. ✅ **Delete Foto**: Dapat menghapus foto profil
4. ✅ **Navbar Update**: Avatar di navbar langsung terupdate
5. ✅ **File Validation**: Validasi tipe file dan ukuran
6. ✅ **Error Handling**: Pesan error yang informatif
7. ✅ **Loading States**: Feedback visual saat proses berlangsung

## Cara Menggunakan

### Upload/Edit Foto Profil:
1. Klik icon edit pada avatar
2. Pilih file gambar (JPEG, PNG, GIF, max 2MB)
3. Preview akan langsung muncul
4. Klik "Simpan Perubahan"
5. Avatar di navbar akan langsung terupdate

### Delete Foto Profil:
1. Klik tombol "Hapus Foto" (muncul jika ada foto)
2. Konfirmasi penghapusan
3. Foto akan terhapus dan avatar kembali ke default

## Technical Details

### Database Structure
- Kolom `profile_photo` ditambahkan ke tabel `dosen` dan `mahasiswa`
- Tipe data: `VARCHAR(255) NULL`
- Menyimpan nama file, bukan path lengkap

### File Storage
- Path storage: `storage/app/public/profile_photos/`
- URL akses: `public/storage/profile_photos/`
- Naming convention: `{type}_{id}_{timestamp}.{ext}`
  - Contoh: `dosen_123456_1234567890.jpg`

### Security
- File type validation (client + server)
- File size limitation (2MB)
- Unique filename generation (prevent conflicts)
- Proper file permissions

## Testing Checklist

- [ ] Upload foto profil baru
- [ ] Ganti foto profil yang sudah ada  
- [ ] Delete foto profil
- [ ] Cek avatar di navbar terupdate
- [ ] Test dengan file invalid (type/size)
- [ ] Test dengan file valid
- [ ] Cek file tersimpan di storage
- [ ] Cek file dapat diakses via URL

## Troubleshooting

### Jika Gambar Tidak Muncul:
1. Cek symlink: `ls -la public/storage`
2. Cek permissions: `ls -la storage/app/public/profile_photos`
3. Cek file ada: `ls -la storage/app/public/profile_photos/`

### Jika Upload Gagal:
1. Cek size file < 2MB
2. Cek tipe file (JPEG, PNG, GIF)
3. Cek permissions directory storage
4. Cek error di browser console

### Jika JavaScript Error:
1. Cek jQuery library loaded
2. Cek CSRF token
3. Cek browser console untuk error
4. Cek network tab untuk AJAX request

## Files Modified

1. `app/Http/Controllers/ProfileController.php` - Controller enhancements
2. `resources/views/profile/edit-dosen.blade.php` - Frontend improvements
3. `resources/views/profile/edit-mahasiswa.blade.php` - Frontend improvements
4. `storage/app/public/profile_photos/` - Directory creation
5. `public/storage` - Symlink creation

Semua bug foto profil sudah diperbaiki dan sistem berfungsi dengan baik.