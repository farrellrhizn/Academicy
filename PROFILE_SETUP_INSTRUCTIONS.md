# Profile Edit Setup Instructions

## Fitur yang Telah Dibuat

Saya telah membuat sistem edit profile lengkap untuk dosen dan mahasiswa dengan fitur upload foto profil. Berikut adalah komponen yang telah dibuat:

### 1. Database Migration
- File: `database/migrations/2025_01_27_000001_add_profile_photo_to_dosen_and_mahasiswa.php`
- Menambah kolom `profile_photo` ke tabel `dosen` dan `mahasiswa`

### 2. Model Updates
- **Dosen Model**: Ditambahkan `profile_photo` ke `$fillable`
- **Mahasiswa Model**: Ditambahkan `profile_photo` ke `$fillable`

### 3. Controller
- **ProfileController**: `app/Http/Controllers/ProfileController.php`
  - `editDosen()`: Menampilkan form edit profile dosen
  - `updateDosen()`: Update profile dosen dengan validasi
  - `editMahasiswa()`: Menampilkan form edit profile mahasiswa  
  - `updateMahasiswa()`: Update profile mahasiswa dengan validasi
  - `deletePhoto()`: Hapus foto profil (Ajax)

### 4. Views
- **Dosen**: `resources/views/profile/edit-dosen.blade.php`
- **Mahasiswa**: `resources/views/profile/edit-mahasiswa.blade.php`

### 5. Routes
- **Dosen Routes**:
  - GET `/dosen/profile` - Form edit profile
  - POST `/dosen/profile` - Update profile
- **Mahasiswa Routes**:
  - GET `/mahasiswa/profile` - Form edit profile
  - POST `/mahasiswa/profile` - Update profile
- **Common Route**:
  - POST `/profile/delete-photo` - Hapus foto profil (Ajax)

## Langkah Setup

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Setup Storage Link (jika belum ada)
```bash
php artisan storage:link
```

### 3. Buat Directory untuk Profile Photos
```bash
mkdir -p storage/app/public/profile_photos
```

### 4. Set Permissions (jika diperlukan)
```bash
chmod 755 storage/app/public/profile_photos
```

## Fitur yang Tersedia

### Untuk Dosen:
- Edit nama, alamat, no HP
- Upload/ganti foto profil
- Ubah password (opsional)
- Hapus foto profil

### Untuk Mahasiswa:
- Edit nama, alamat, no HP
- Upload/ganti foto profil  
- Ubah password (opsional)
- Hapus foto profil
- View semester dan golongan (read-only)

## Akses URL

### Dosen:
- Profile Edit: `/dosen/profile`

### Mahasiswa:
- Profile Edit: `/mahasiswa/profile`

## Validasi

### Upload Foto:
- Format: jpeg, png, jpg, gif
- Maksimal ukuran: 2MB
- Otomatis resize dan crop menjadi lingkaran

### Password:
- Minimal 6 karakter
- Konfirmasi password wajib jika mengubah password
- Password lama tidak perlu diinput (opsional update)

## Security Features

- CSRF Protection
- File upload validation
- Authentication guard untuk masing-masing user type
- Storage foto di direktori yang aman
- Auto delete foto lama saat upload baru

## User Interface

- Desain mengikuti template Bootstrap yang sudah ada
- Avatar upload dengan preview real-time
- Form validation dengan error display
- Success/error notifications
- Responsive design
- Edit icon pada avatar untuk upload
- Delete button untuk menghapus foto

## Database Schema

### Tabel dosen:
```sql
ALTER TABLE dosen ADD COLUMN profile_photo VARCHAR(255) NULL AFTER Nohp;
```

### Tabel mahasiswa:
```sql
ALTER TABLE mahasiswa ADD COLUMN profile_photo VARCHAR(255) NULL AFTER Nohp;
```

## File Storage Structure
```
storage/
  app/
    public/
      profile_photos/
        dosen_NIP_timestamp.jpg
        mahasiswa_NIM_timestamp.jpg
```

## Testing

1. Login sebagai dosen atau mahasiswa
2. Akses menu Profile di sidebar
3. Test upload foto profil
4. Test edit data profile
5. Test ubah password
6. Test hapus foto profil

Semua fitur telah siap digunakan dan terintegrasi dengan sistem authentication yang sudah ada.