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


// == KELOMPOK ROUTE BERDASARKAN PERAN ==

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

    // Route::get('/buat-jadwal', [BuatJadwalController::class, 'index'])->name('buat-jadwal.index');
    // Route::post('/buat-jadwal/store', [BuatJadwalController::class, 'store'])->name('buat-jadwal.store');
    // Route::post('/buat-jadwal/update', [BuatJadwalController::class, 'update'])->name('buat-jadwal.update');
    // Route::post('/buat-jadwal/destroy', [BuatJadwalController::class, 'destroy'])->name('buat-jadwal.destroy');

    Route::get('/kelola-jadwal', [KelolaJadwalController::class, 'index'])->name('kelola-jadwal.index');
    Route::post('/kelola-jadwal/update/{id}', [KelolaJadwalController::class, 'update'])->name('kelola-jadwal.update');
    Route::delete('/kelola-jadwal/destroy/{id}', [KelolaJadwalController::class, 'destroy'])->name('kelola-jadwal.destroy');
});


// Route untuk DOSEN
Route::middleware('auth:dosen')->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DashboardDsnController::class, 'showDashboardDsn'])->name('dashboard');

    Route::get('/jadwal', [JadwalDosenController::class, 'index'])->name('jadwal.index');

    // Route presensi cepat (hanya pilih mata kuliah, tanggal otomatis)
    Route::get('/presensi-simple', [PresensiController::class, 'simple'])->name('presensi.simple');
    Route::post('/presensi-simple', [PresensiController::class, 'storeSimple'])->name('presensi.store-simple');
    
    // Route presensi detail (pilih mata kuliah dan tanggal)
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    
    // Route mata kuliah diampu
    Route::get('/mata-kuliah-diampu', [MataKuliahDiampuController::class, 'index'])->name('mata-kuliah-diampu.index');
});


// Route untuk MAHASISWA
Route::middleware('auth:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    
    // Route KRS
    Route::get('/krs', [App\Http\Controllers\KrsController::class, 'index'])->name('krs.index');
    Route::post('/krs', [App\Http\Controllers\KrsController::class, 'store'])->name('krs.store');
    Route::delete('/krs', [App\Http\Controllers\KrsController::class, 'destroy'])->name('krs.destroy');
    Route::get('/krs/jadwal', [App\Http\Controllers\KrsController::class, 'jadwal'])->name('krs.jadwal');
    Route::get('/krs/cetak', [App\Http\Controllers\KrsController::class, 'cetak'])->name('krs.cetak');
});

// Route::get('/', function () {
//     return redirect()->route('login');
// });

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login'])->name('login.post');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:mahasiswa,dosen,admin');

// Route::get('/forgot-pass', [ForgotController::class, 'showForgotForm'])->name('forgot-pass');
// Route::post('/forgot-pass', [ForgotController::class, 'forgotPass']);

// Route::get('/dashboard-mhs', [DashboardController::class, 'showDashboard'])->name('dashboard-mhs');
// Route::post('/dashboard-mhs', [DashboardController::class, 'dashboard-mhs']);

// //ADMIN
// Route::get('/dashboard-admin', [DashboardAdmController::class, 'showDashboardAdm'])->name('dashboard-admin');
// Route::post('/dashboard-admin', [DashboardAdmController::class, 'dashboard-admin']);

// Route::get('/matakuliah/filter/semester', [MataKuliahController::class, 'filterBySemester'])->name('matakuliah.filter');
// Route::get('/matakuliah', [MataKuliahController::class, 'index'])->name('matakuliah.index');
// Route::post('/matakuliah', [MataKuliahController::class, 'store'])->name('matakuliah.store');
// Route::get('/matakuliah/{Kode_mk}/edit', [MataKuliahController::class, 'edit'])->name('matakuliah.edit');
// Route::put('/matakuliah/{Kode_mk}', [MataKuliahController::class, 'update'])->name('matakuliah.update');
// Route::delete('/matakuliah/{Kode_mk}', [MataKuliahController::class, 'destroy'])->name('matakuliah.destroy');

// Route::resource('dosen', DosenController::class);
// Route::resource('mahasiswa', MahasiswaController::class);
// Route::resource('ruang', RuangController::class);
// Route::resource('golongan', GolonganController::class);

// Route::get('/buat-jadwal', [BuatJadwalController::class, 'index'])->name('buat-jadwal.index');
// Route::post('/buat-jadwal/store', [BuatJadwalController::class, 'store'])->name('buat-jadwal.store');
// Route::post('/buat-jadwal/update', [BuatJadwalController::class, 'update'])->name('buat-jadwal.update');
// Route::post('/buat-jadwal/destroy', [BuatJadwalController::class, 'destroy'])->name('buat-jadwal.destroy');

// Route::get('/kelola-jadwal', [KelolaJadwalController::class, 'index'])->name('kelola-jadwal.index');
// Route::post('/kelola-jadwal/update/{id}', [KelolaJadwalController::class, 'update'])->name('kelola-jadwal.update');
// Route::delete('/kelola-jadwal/destroy/{id}', [KelolaJadwalController::class, 'destroy'])->name('kelola-jadwal.destroy');

// Route::get('/dashboard-dosen', [DashboardDsnController::class, 'showDashboardDsn'])->name('dashboard-dosen');
// Route::post('/dashboard-dosen', [DashboardDsnController::class, 'dashboard-dosen']);

// Route::get('/jadwal-dosen', [JadwalDosenController::class, 'index'])->name('jadwal-dosen.index');
// Route::post('/dashboard-dosen', [DashboardDsnController::class, 'dashboard-dosen']);

// Route::middleware(['auth:dosen'])->group(function () {
//     Route::get('/dashboard-dosen', [DashboardDsnController::class, 'showDashboardDsn'])->name('dashboard-dosen');
// });
