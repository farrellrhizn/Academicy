<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardDsnController;
use App\Http\Controllers\DashboardAdmController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\BuatJadwalController;
use App\Http\Controllers\KelolaJadwalController;
use App\Http\Controllers\JadwalDosenController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\MataKuliahDiampuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

// Route untuk Guest (yang belum login)
Route::middleware('guest:admin,dosen,mahasiswa')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']); // name dihapus karena sama
});

Route::get('/', function () {
    return redirect()->route('login');
});

// Route umum yang bisa diakses setelah login (jika ada)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:admin,dosen,mahasiswa');
Route::get('/forgot-pass', [ForgotController::class, 'showForgotForm'])->name('forgot-pass');
Route::post('/forgot-pass', [ForgotController::class, 'forgotPass']);

// Route untuk profile photo management (dapat diakses oleh dosen dan mahasiswa)
Route::post('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo')->middleware('auth:dosen,mahasiswa');
Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo')->middleware('auth:dosen,mahasiswa');
Route::get('/profile/get-photo', [ProfileController::class, 'getProfilePhoto'])->name('profile.get-photo')->middleware('auth:dosen,mahasiswa');

// Route untuk ADMIN
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdmController::class, 'showDashboardAdm'])->name('dashboard');
    Route::get('/matakuliah/filter/semester', [MataKuliahController::class, 'filterBySemester'])->name('matakuliah.filter');
    Route::resource('matakuliah', MataKuliahController::class)->parameters(['matakuliah' => 'Kode_mk']);
    Route::resource('dosen', DosenController::class);
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('ruang', RuangController::class);
    Route::resource('golongan', GolonganController::class);
    Route::resource('buat-jadwal', BuatJadwalController::class);
    Route::get('/buat-jadwal/matakuliah/semester', [BuatJadwalController::class, 'getMatakuliahBySemester'])->name('buat-jadwal.matakuliah.semester');
    Route::get('/kelola-jadwal', [KelolaJadwalController::class, 'index'])->name('kelola-jadwal.index');
    Route::get('/kelola-jadwal/matakuliah/semester', [KelolaJadwalController::class, 'getMatakuliahBySemester'])->name('kelola-jadwal.matakuliah.semester');
    Route::post('/kelola-jadwal/update/{id}', [KelolaJadwalController::class, 'update'])->name('kelola-jadwal.update');
    Route::delete('/kelola-jadwal/destroy/{id}', [KelolaJadwalController::class, 'destroy'])->name('kelola-jadwal.destroy');
});

// Route untuk DOSEN
Route::middleware('auth:dosen')->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DashboardDsnController::class, 'showDashboardDsn'])->name('dashboard');
    Route::get('/jadwal', [JadwalDosenController::class, 'index'])->name('jadwal.index');
    Route::get('/presensi-simple', [PresensiController::class, 'simple'])->name('presensi.simple');
    Route::post('/presensi-simple', [PresensiController::class, 'storeSimple'])->name('presensi.store-simple');
    Route::post('/presensi-update-status', [PresensiController::class, 'updateStatus'])->name('presensi.update-status');
    Route::get('/mata-kuliah-diampu', [MataKuliahDiampuController::class, 'index'])->name('mata-kuliah-diampu.index');
    Route::get('/profile', [ProfileController::class, 'editDosen'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'updateDosen'])->name('profile.update');
});

// Route untuk MAHASISWA
Route::middleware('auth:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::get('/krs', [App\Http\Controllers\KrsController::class, 'index'])->name('krs.index');
    Route::post('/krs', [App\Http\Controllers\KrsController::class, 'store'])->name('krs.store');
    Route::delete('/krs', [App\Http\Controllers\KrsController::class, 'destroy'])->name('krs.destroy');
    Route::get('/krs/jadwal', [App\Http\Controllers\KrsController::class, 'jadwal'])->name('krs.jadwal');
    Route::get('/krs/cetak', [App\Http\Controllers\KrsController::class, 'cetak'])->name('krs.cetak');
    Route::get('/krs/available', [App\Http\Controllers\KrsController::class, 'getAvailableCoursesAjax'])->name('krs.available');
    Route::get('/jadwal', [App\Http\Controllers\KrsController::class, 'jadwal'])->name('jadwal.index');
    Route::get('/presensi/riwayat', [App\Http\Controllers\KrsController::class, 'riwayatPresensi'])->name('presensi.riwayat');
    Route::get('/profile', [ProfileController::class, 'editMahasiswa'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'updateMahasiswa'])->name('profile.update');
});
