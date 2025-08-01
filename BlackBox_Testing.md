# Black Box Testing - Sistem Manajemen Akademik

## Tabel Test Case

| ID Test | Fitur/Skenario | Pengujian | Aktor | Hasil yang Diharapkan | Status |
|---------|---------------|-----------|-------|----------------------|--------|
| TC001 | Login Admin | Input username dan password admin yang valid | Admin | Berhasil login dan redirect ke dashboard admin | ✅ |
| TC002 | Login Dosen | Input username dan password dosen yang valid | Dosen | Berhasil login dan redirect ke dashboard dosen | ✅ |
| TC003 | Login Mahasiswa | Input username dan password mahasiswa yang valid | Mahasiswa | Berhasil login dan redirect ke dashboard mahasiswa | ✅ |
| TC004 | Login Invalid | Input username/password yang salah | User | Menampilkan pesan error "Invalid credentials" | ❌ |
| TC005 | Tambah Mata Kuliah | Admin menambah mata kuliah baru dengan data lengkap | Admin | Data mata kuliah tersimpan dan muncul di daftar | ✅ |
| TC006 | Tambah Dosen | Admin menambah data dosen dengan informasi lengkap | Admin | Data dosen tersimpan dan dapat diakses | ✅ |
| TC007 | Tambah Mahasiswa | Admin menambah data mahasiswa baru | Admin | Data mahasiswa tersimpan dalam sistem | ✅ |
| TC008 | Buat Jadwal Kuliah | Admin membuat jadwal kuliah untuk semester aktif | Admin | Jadwal kuliah terbuat dan dapat dilihat mahasiswa | ✅ |
| TC009 | KRS Mahasiswa | Mahasiswa mengambil mata kuliah dalam periode KRS | Mahasiswa | Mata kuliah terdaftar dalam KRS mahasiswa | ✅ |
| TC010 | Lihat Jadwal Dosen | Dosen melihat jadwal mengajar hari ini | Dosen | Menampilkan jadwal mengajar sesuai hari | ✅ |
| TC011 | Input Presensi | Dosen melakukan input presensi mahasiswa | Dosen | Data presensi tersimpan dan terupdate | ✅ |
| TC012 | Lihat Riwayat Presensi | Mahasiswa melihat riwayat presensi mata kuliah | Mahasiswa | Menampilkan data presensi per mata kuliah | ✅ |
| TC013 | Update Profile | User mengubah data profile dan foto | User | Data profile terupdate dalam sistem | ✅ |
| TC014 | Forgot Password | User reset password melalui email | User | Link reset password dikirim ke email | ✅ |
| TC015 | Logout | User melakukan logout dari sistem | User | Session berakhir dan redirect ke halaman login | ✅ |

## Keterangan Status:
- ✅ : Test case berhasil (Pass)
- ❌ : Test case gagal (Fail)
- ⏳ : Test case belum dijalankan (Pending)

## Ringkasan Testing:
- **Total Test Cases**: 15
- **Passed**: 14
- **Failed**: 1
- **Success Rate**: 93.3%