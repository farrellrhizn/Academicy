# KEMASAN / PAKET KOMPETENSI - PERTANYAAN DAN JAWABAN
## Jenis Kemasan: KKNI / OKUPASI NASIONAL / KLASTER

### 5.2. Rincian Unit Kompetensi atau Uraian Tugas

---

## 1. J.620100.001.01 - Menganalisis Tools

**Pertanyaan:** Jelaskan tools apa saja yang digunakan dalam pengembangan aplikasi Laravel ini dan fungsi masing-masing!

**Jawaban:**
Dalam project Laravel ini, menggunakan berbagai tools yang terintegrasi:

- **Composer** (composer.json): Package manager PHP untuk dependencies
  ```json
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1"
  }
  ```

- **Laravel Artisan**: Command-line tools untuk development
  ```bash
  php artisan serve
  php artisan migrate
  php artisan queue:listen
  ```

- **Vite** (vite.config.js): Build tool untuk asset compilation
  ```javascript
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    tailwindcss(),
  ]
  ```

- **PHPUnit** (phpunit.xml): Testing framework
  ```xml
  <testsuites>
    <testsuite name="Unit">
      <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
      <directory>tests/Feature</directory>
    </testsuite>
  </testsuites>
  ```

- **Laravel Pint**: Code formatting tool
- **TailwindCSS**: CSS framework untuk styling
- **Concurrently**: Tool untuk menjalankan multiple commands bersamaan

---

## 2. J.620100.002.01 - Menganalisis Skalabilitas Perangkat Lunak

**Pertanyaan:** Bagaimana cara menganalisis dan meningkatkan skalabilitas aplikasi Laravel?

**Jawaban:**
Dalam project ini, skalabilitas diterapkan melalui:

- **Database Optimization**: Foreign key relationships dan indexing
  ```php
  // Database migration dengan foreign keys (create_krs_table.php)
  $table->foreign('NIM')->references('NIM')->on('mahasiswa')->onDelete('cascade');
  $table->foreign('Kode_mk')->references('Kode_mk')->on('matakuliah')->onDelete('cascade');
  $table->unique(['NIM', 'Kode_mk']); // Unique constraint untuk performance
  ```

- **Query Optimization**: Eager loading untuk mengurangi N+1 queries
  ```php
  // KrsController.php - Optimized query dengan with()
  $krsAmbil = Krs::where('NIM', $mahasiswa->NIM)
                ->with([
                    'matakuliah', 
                    'matakuliah.jadwalAkademik.ruang',
                    'matakuliah.jadwalAkademik.golongan'
                ])
                ->get();
  ```

- **Queue System**: Configured untuk background processing
  ```bash
  # composer.json scripts
  "php artisan queue:listen --tries=1"
  ```

- **Asset Optimization**: Vite untuk bundling dan compression
  ```javascript
  // vite.config.js - Asset optimization
  laravel({
    input: ['resources/css/app.css', 'resources/js/app.js'],
    refresh: true,
  })
  ```

- **Session Management**: Database sessions untuk scalability
  ```php
  // Migration: create_sessions_table.php
  Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->text('payload');
      $table->integer('last_activity')->index();
  });
  ```

---

## 3. J.620100.003.01 - Melakukan Identifikasi Library, Komponen atau Framework yang Diperlukan

**Pertanyaan:** Identifikasi library dan komponen yang diperlukan dalam project Laravel ini!

**Jawaban:**
Library dan komponen yang diidentifikasi dalam project:

**Core Dependencies (composer.json):**
```json
"require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1"
}
```

**Development Dependencies:**
```json
"require-dev": {
    "fakerphp/faker": "^1.24",
    "laravel/pail": "^1.2.2", 
    "laravel/pint": "^1.13",
    "phpunit/phpunit": "^11.5.3"
}
```

**Frontend Dependencies (package.json):**
```json
"devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "axios": "^1.8.2",
    "laravel-vite-plugin": "^2.0.0",
    "tailwindcss": "^4.0.0",
    "vite": "^7.0.4"
}
```

**PSR-4 Autoloading Structure:**
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
}
```

**Komponen Aplikasi:**
- **Models**: User, Mahasiswa, Dosen, KRS, JadwalAkademik
- **Controllers**: 15+ controllers untuk different functionalities
- **Middleware**: Authentication guards (admin, dosen, mahasiswa)
- **Migrations**: 14 migration files untuk database schema

---

## 4. J.620100.006.01 - Merancang User Experience

**Pertanyaan:** Bagaimana merancang user experience yang baik dalam aplikasi web Laravel?

**Jawaban:**
UX Design implementation dalam project ini:

**1. Multi-Role Navigation Structure:**
```php
// web.php - Role-based routing untuk UX yang tersegmentasi
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdmController::class, 'showDashboardAdm']);
    Route::resource('matakuliah', MataKuliahController::class);
});

Route::middleware('auth:dosen')->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DashboardDsnController::class, 'showDashboardDsn']);
    Route::get('/jadwal', [JadwalDosenController::class, 'index']);
});

Route::middleware('auth:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard']);
    Route::get('/krs', [KrsController::class, 'index']);
});
```

**2. User-Friendly Error Messages:**
```php
// ProfileController.php - Comprehensive validation messages
$validatedData = $request->validate([
    'Nama' => 'required|string|max:100',
    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
], [
    'Nama.required' => 'Nama harus diisi',
    'Nama.max' => 'Nama maksimal 100 karakter',
    'profile_photo.image' => 'File harus berupa gambar',
    'profile_photo.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
    'profile_photo.max' => 'Ukuran gambar maksimal 2MB'
]);
```

**3. Responsive Design dengan TailwindCSS:**
```json
// package.json - Modern UI framework
"devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "tailwindcss": "^4.0.0"
}
```

**4. AJAX-Friendly Error Handling:**
```php
// KrsController.php - Dynamic response berdasarkan request type
private function errorResponse($message, $status, $request)
{
    if ($request->ajax()) {
        return response()->json(['error' => $message], $status);
    }
    return redirect()->back()->with('error', $message);
}
```

**5. Organized View Structure:**
```
resources/views/
├── auth/
├── dashboard-admin/
├── dashboard-dosen/
├── dashboard-mhs/
├── profile/
├── mahasiswa/
└── dosen/
```

**6. Intuitive Data Organization:**
```php
// DashboardController.php - User-centric data mapping
private const DAY_MAPPING_REVERSE = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu'
];
```

---

## 5. J.620100.017.02 - Mengimplementasikan Pemrograman Terstruktur

**Pertanyaan:** Jelaskan implementasi pemrograman terstruktur dalam Laravel!

**Jawaban:**
Structured programming dalam project ini:

**1. MVC Architecture - Clear Separation:**
```php
// app/Models/User.php - Model Layer
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'username', 'password', 'user_type'];
    
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'user_id');
    }
}

// app/Http/Controllers/DashboardController.php - Controller Layer
class DashboardController extends Controller
{
    public function showDashboard(): View
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        return view('dashboard-mhs.index', compact('mahasiswa'));
    }
}

// resources/views/ - View Layer (Blade templates)
```

**2. Namespace Organization (PSR-4):**
```json
// composer.json - Structured autoloading
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
}
```

**3. Controller Organization - Single Responsibility:**
```php
// Dedicated controllers untuk specific functionality
├── Controllers/
    ├── DashboardController.php      // Student dashboard
    ├── DashboardDsnController.php   // Lecturer dashboard  
    ├── DashboardAdmController.php   // Admin dashboard
    ├── KrsController.php            // Course registration
    ├── ProfileController.php        // Profile management
    ├── PresensiController.php       // Attendance system
    └── LoginController.php          // Authentication
```

**4. Helper Methods & Code Reusability:**
```php
// KrsController.php - Reusable helper methods
private function normalizeDay($day)
{
    $dayMapping = [
        'senin' => 'Monday',
        'selasa' => 'Tuesday', 
        // ...
    ];
    
    $normalizedDay = strtolower(trim($day));
    return $dayMapping[$normalizedDay] ?? ucfirst($normalizedDay);
}

private function errorResponse($message, $status, $request)
{
    if ($request->ajax()) {
        return response()->json(['error' => $message], $status);
    }
    return redirect()->back()->with('error', $message);
}
```

**5. Service Injection Pattern:**
```php
// ProfileController.php - Service Layer separation
use App\Services\ImageService;

protected $imageService;

public function __construct(ImageService $imageService)
{
    $this->imageService = $imageService;
}
```

**6. Constants Organization:**
```php
// DashboardController.php - Structured constants
private const DAY_MAPPING = [
    'senin' => 'Monday',
    'selasa' => 'Tuesday', 
    'rabu' => 'Wednesday',
    // ...
];

private const DAY_MAPPING_REVERSE = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    // ...
];
```

**7. Database Structure - Normalized Schema:**
```php
// Migration files - Structured database design
2025_07_26_144842_create_ruang_table.php
2025_07_26_144843_create_dosen_table.php  
2025_07_26_144844_create_krs_table.php
2025_07_26_144845_create_jadwal_akademik_table.php
```

---

## 6. J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek

**Pertanyaan:** Bagaimana penerapan OOP principles dalam Laravel application?

**Jawaban:**
Implementasi OOP dalam project ini:

**1. Encapsulation - Properties Protection:**
```php
// User.php - Protected properties
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'username', 'password', 'user_type'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];
}
```

**2. Inheritance - Extending Base Classes:**
```php
// User.php extends Authenticatable
class User extends Authenticatable
{
    use HasFactory, Notifiable;
}

// DashboardController extends Controller
class DashboardController extends Controller
{
    // Controller logic
}
```

**3. Polymorphism - Method Implementation:**
```php
// User.php - Polymorphic methods
public function getProfile()
{
    if ($this->isDosen()) {
        return $this->dosen;
    }
    return $this->mahasiswa;
}

public function getFullName()
{
    $profile = $this->getProfile();
    return $profile ? $profile->Nama : $this->name;
}
```

**4. Abstraction - Interface dan Relationships:**
```php
// User.php - Relationship abstractions
public function dosen()
{
    return $this->hasOne(Dosen::class, 'user_id');
}

public function mahasiswa()
{
    return $this->hasOne(Mahasiswa::class, 'user_id');
}
```

**5. Composition - Model Relationships:**
```php
// Mahasiswa.php
public function golongan()
{
    return $this->belongsTo(Golongan::class, 'id_Gol', 'id_Gol');
}

public function krs()
{
    return $this->hasMany(Krs::class, 'NIM', 'NIM');
}
```

---

## 7. J.620100.020.02 - Menggunakan SQL

**Pertanyaan:** Demonstrasi penggunaan SQL dalam aplikasi Laravel!

**Jawaban:**
Penggunaan SQL dalam project ini:

**1. Database Migrations - DDL Operations:**
```php
// create_krs_table.php - CREATE TABLE
Schema::create('krs', function (Blueprint $table) {
    $table->id('id_krs');
    $table->string('NIM');
    $table->string('Kode_mk');
    $table->decimal('Nilai', 3, 2)->nullable();
    $table->char('Grade', 2)->nullable();
    $table->timestamps();
    
    // Foreign Key Constraints
    $table->foreign('NIM')->references('NIM')->on('mahasiswa')->onDelete('cascade');
    $table->foreign('Kode_mk')->references('Kode_mk')->on('matakuliah')->onDelete('cascade');
    $table->unique(['NIM', 'Kode_mk']);
});
```

**2. Eloquent ORM - Complex Queries:**
```php
// KrsController.php - JOIN operations dengan relationships
$krsAmbil = Krs::where('NIM', $mahasiswa->NIM)
              ->with([
                  'matakuliah', 
                  'matakuliah.jadwalAkademik' => function($query) use ($mahasiswa) {
                      $query->where('id_Gol', $mahasiswa->id_Gol);
                  },
                  'matakuliah.jadwalAkademik.ruang',
                  'matakuliah.jadwalAkademik.golongan'
              ])
              ->get();
```

**3. Query Builder - Conditional Filtering:**
```php
// Complex WHERE dengan subqueries
$matakuliahTersedia = MataKuliah::where('semester', $mahasiswa->Semester)
                                ->whereHas('jadwalAkademik', function($query) use ($mahasiswa) {
                                    $query->where('id_Gol', $mahasiswa->id_Gol);
                                })
                                ->whereNotIn('Kode_mk', $krsKodeMk)
                                ->orderBy('Nama_mk', 'asc')
                                ->get();
```

**4. Raw SQL - Direct Database Operations:**
```php
// DashboardController.php - menggunakan DB facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Logging untuk SQL debugging
Log::info('Query executed', ['sql' => $query->toSql()]);
```

**5. Database Relationships - Foreign Keys:**
```php
// Model relationships yang menghasilkan JOIN queries
public function golongan()
{
    return $this->belongsTo(Golongan::class, 'id_Gol', 'id_Gol');
}
```

---

## 8. J.620100.021.02 - Menerapkan Akses Basis Data

**Pertanyaan:** Bagaimana implementasi akses basis data dalam Laravel?

**Jawaban:**
Database access implementation dalam project:

**1. Eloquent Model Configuration:**
```php
// app/Models/Mahasiswa.php - Custom model configuration
class Mahasiswa extends Authenticatable
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'NIM';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'NIM', 'Nama', 'password', 'Alamat', 'Nohp', 'Semester', 'id_Gol', 'profile_photo'
    ];
}
```

**2. Complex Query Operations:**
```php
// KrsController.php - Advanced database queries
$krsAmbil = Krs::where('NIM', $mahasiswa->NIM)
              ->with([
                  'matakuliah', 
                  'matakuliah.jadwalAkademik' => function($query) use ($mahasiswa) {
                      $query->where('id_Gol', $mahasiswa->id_Gol);
                  },
                  'matakuliah.jadwalAkademik.ruang',
                  'matakuliah.jadwalAkademik.golongan'
              ])
              ->get();
```

**3. Database Relationships:**
```php
// Models dengan relationships
// User.php
public function dosen()
{
    return $this->hasOne(Dosen::class, 'user_id');
}

// Mahasiswa.php  
public function golongan()
{
    return $this->belongsTo(Golongan::class, 'id_Gol', 'id_Gol');
}

public function krs()
{
    return $this->hasMany(Krs::class, 'NIM', 'NIM');
}
```

**4. Transaction Management:**
```php
// ProfileController.php - Database transactions
use Illuminate\Support\Facades\DB;

DB::beginTransaction();

try {
    $dosen->update($updateData);
    
    if ($request->hasFile('profile_photo')) {
        // File operations
    }
    
    DB::commit();
    Log::info('Profile update successful for: ' . $dosen->NIP);
} catch (Exception $e) {
    DB::rollback();
    Log::error('Profile update failed: ' . $e->getMessage());
    throw $e;
}
```

**5. Query Validation & Security:**
```php
// KrsController.php - Safe database operations
if (Krs::where('NIM', $mahasiswa->NIM)->where('Kode_mk', $request->Kode_mk)->exists()) {
    return $this->errorResponse('Mata kuliah sudah diambil sebelumnya.', 400, $request);
}

$matakuliah = MataKuliah::where('Kode_mk', $request->Kode_mk)
                       ->where('semester', $mahasiswa->Semester)
                       ->first();
```

**6. Foreign Key Constraints:**
```php
// create_krs_table.php - Database integrity
Schema::create('krs', function (Blueprint $table) {
    $table->foreign('NIM')->references('NIM')->on('mahasiswa')->onDelete('cascade');
    $table->foreign('Kode_mk')->references('Kode_mk')->on('matakuliah')->onDelete('cascade');
    $table->unique(['NIM', 'Kode_mk']);
});
```

**7. Optimized Queries:**
```php
// Filtering dengan subqueries
$matakuliahTersedia = MataKuliah::where('semester', $mahasiswa->Semester)
                                ->whereHas('jadwalAkademik', function($query) use ($mahasiswa) {
                                    $query->where('id_Gol', $mahasiswa->id_Gol);
                                })
                                ->whereNotIn('Kode_mk', $krsKodeMk)
                                ->orderBy('Nama_mk', 'asc')
                                ->get();
```

---

## 9. J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman

**Pertanyaan:** Contoh implementasi algoritma dalam aplikasi Laravel!

**Jawaban:**
Implementasi algoritma dalam project ini:

**1. Search & Filter Algorithm:**
```php
// KrsController.php - Algorithm untuk filtering data
private function normalizeDay($day)
{
    $dayMapping = [
        'senin' => 'Monday',
        'selasa' => 'Tuesday', 
        'rabu' => 'Wednesday',
        'kamis' => 'Thursday',
        'jumat' => 'Friday',
        'sabtu' => 'Saturday',
        'minggu' => 'Sunday'
    ];
    
    $normalizedDay = strtolower(trim($day));
    return $dayMapping[$normalizedDay] ?? ucfirst($normalizedDay);
}
```

**2. Data Validation Algorithm:**
```php
// KrsController.php - Validation logic
public function store(Request $request)
{
    $request->validate([
        'Kode_mk' => 'required|string|exists:matakuliah,Kode_mk'
    ]);
    
    // Custom validation algorithm
    if (Krs::where('NIM', $mahasiswa->NIM)->where('Kode_mk', $request->Kode_mk)->exists()) {
        return $this->errorResponse('Mata kuliah sudah diambil sebelumnya.', 400, $request);
    }
}
```

**3. Data Processing Algorithm:**
```php
// DashboardController.php - Constants mapping algorithm
private const DAY_MAPPING = [
    'senin' => 'Monday',
    'selasa' => 'Tuesday', 
    'rabu' => 'Wednesday',
    'kamis' => 'Thursday',
    'jumat' => 'Friday',
    'sabtu' => 'Saturday',
    'minggu' => 'Sunday'
];

private const DAY_MAPPING_REVERSE = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    // ...
];
```

**4. Collection Processing Algorithm:**
```php
// Array manipulation dan filtering
$krsKodeMk = $krsAmbil->pluck('Kode_mk')->toArray();

// Complex query building algorithm
$matakuliahTersedia = MataKuliah::where('semester', $mahasiswa->Semester)
                                ->whereNotIn('Kode_mk', $krsKodeMk)
                                ->orderBy('Nama_mk', 'asc')
                                ->get();
```

**5. Authentication Algorithm:**
```php
// User.php - Role-based logic algorithm
public function isDosen()
{
    return $this->user_type === 'dosen';
}

public function getProfile()
{
    if ($this->isDosen()) {
        return $this->dosen;
    }
    return $this->mahasiswa;
}
```

---

## 10. J.620100.024.02 - Melakukan Migrasi ke Teknologi Baru

**Pertanyaan:** Bagaimana strategi migrasi teknologi dalam project Laravel?

**Jawaban:**
Technology migration dalam project ini:

**1. Framework Version Migration:**
```json
// composer.json - Laravel 12 migration
"require": {
    "php": "^8.2",           // PHP 8.2+ requirement
    "laravel/framework": "^12.0"  // Latest Laravel version
}
```

**2. Database Schema Migrations:**
```php
// Progressive database updates
2025_07_30_044705_remove_foreign_keys_from_presensi_akademik.php
2025_07_30_add_timestamps_to_krs_table.php
2025_01_27_000001_add_profile_photo_to_dosen_and_mahasiswa.php

// Migration dengan rollback capability
public function up()
{
    Schema::table('krs', function (Blueprint $table) {
        $table->timestamps();
    });
}

public function down()
{
    Schema::table('krs', function (Blueprint $table) {
        $table->dropTimestamps();
    });
}
```

**3. Build Tool Migration (Webpack → Vite):**
```javascript
// vite.config.js - Modern build system
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

**4. CSS Framework Migration:**
```json
// package.json - TailwindCSS 4.0 adoption
"devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "tailwindcss": "^4.0.0"
}
```

**5. Testing Framework Updates:**
```json
// composer.json - PHPUnit 11.x
"require-dev": {
    "phpunit/phpunit": "^11.5.3"
}
```

**6. Authentication System Migration:**
```php
// Multiple guard authentication
// web.php - Multi-guard system implementation
Route::middleware('auth:admin,dosen,mahasiswa')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('guest:admin,dosen,mahasiswa')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
});
```

**7. Legacy Code Preservation:**
```
// Backup strategy untuk rollback
DashboardController.php.backup
DashboardController_backup.php
```

**8. Progressive Enhancement:**
```php
// ProfileController.php - Backward compatible updates
protected $fillable = [
    'NIM', 'Nama', 'password', 'Alamat', 'Nohp', 'Semester', 'id_Gol', 
    'profile_photo'  // New field added progressively
];
```

---

## 11. J.620100.025.02 - Melakukan Debugging

**Pertanyaan:** Teknik debugging yang digunakan dalam Laravel development!

**Jawaban:**
Teknik debugging yang diterapkan dalam project:

**1. Laravel Tinker - Interactive Debugging:**
```json
// composer.json - Tinker untuk debugging
"require": {
    "laravel/tinker": "^2.10.1"
}
```

**2. Logging System:**
```php
// DashboardController.php - Log debugging
use Illuminate\Support\Facades\Log;

Log::info('Query executed', ['data' => $someData]);
Log::error('Database error', ['error' => $exception->getMessage()]);
```

**3. Laravel Pail - Real-time Log Monitoring:**
```json
// composer.json
"require-dev": {
    "laravel/pail": "^1.2.2"
}
```

**4. PHPUnit Testing Configuration:**
```xml
<!-- phpunit.xml - Testing environment -->
<phpunit bootstrap="vendor/autoload.php" colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
    </php>
</phpunit>
```

**5. Development Scripts - Integrated Debugging:**
```json
// composer.json - Development workflow
"scripts": {
    "dev": [
        "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" 
         \"php artisan serve\" 
         \"php artisan queue:listen --tries=1\" 
         \"php artisan pail --timeout=0\" 
         \"npm run dev\" 
         --names=server,queue,logs,vite"
    ],
    "test": [
        "@php artisan config:clear --ansi",
        "@php artisan test"
    ]
}
```

**6. Error Handling dalam Controllers:**
```php
// KrsController.php - Custom error responses
private function errorResponse($message, $status, $request)
{
    if ($request->ajax()) {
        return response()->json(['error' => $message], $status);
    }
    return redirect()->back()->with('error', $message);
}
```

---

## 12. J.620100.030.02 - Menerapkan Pemrograman Multimedia

**Pertanyaan:** Implementasi multimedia handling dalam Laravel!

**Jawaban:**
Implementasi multimedia dalam project ini:

**1. Image Upload & Validation:**
```php
// ProfileController.php - File upload validation
$validatedData = $request->validate([
    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
], [
    'profile_photo.image' => 'File harus berupa gambar',
    'profile_photo.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
    'profile_photo.max' => 'Ukuran gambar maksimal 2MB'
]);
```

**2. File Storage Management:**
```php
// ProfileController.php - Storage handling
use Illuminate\Support\Facades\Storage;

// Handle profile photo upload
if ($request->hasFile('profile_photo')) {
    try {
        $this->ensureDirectoryExists();
        
        // Delete old photo if exists
        if ($dosen->profile_photo) {
            $this->deleteOldPhoto($dosen->profile_photo);
        }
        
        // Store new photo
        $file = $request->file('profile_photo');
        $fileName = $this->generateFileName($file, $dosen->NIP);
        $file->storeAs('profile_photos', $fileName, 'public');
        
        $updateData['profile_photo'] = $fileName;
        $newPhotoUrl = Storage::url('profile_photos/' . $fileName);
    } catch (Exception $e) {
        Log::error('Profile photo upload failed: ' . $e->getMessage());
    }
}
```

**3. Image Service Class:**
```php
// ProfileController.php - Service dependency injection
use App\Services\ImageService;

protected $imageService;

public function __construct(ImageService $imageService)
{
    $this->imageService = $imageService;
}
```

**4. Routes untuk Multimedia Operations:**
```php
// web.php - Profile photo management routes
Route::post('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])
     ->name('profile.delete-photo')
     ->middleware('auth:dosen,mahasiswa');
     
Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])
     ->name('profile.upload-photo')
     ->middleware('auth:dosen,mahasiswa');
     
Route::get('/profile/get-photo', [ProfileController::class, 'getProfilePhoto'])
     ->name('profile.get-photo')
     ->middleware('auth:dosen,mahasiswa');
```

**5. Database Support untuk Multimedia:**
```php
// Migration - add_profile_photo_to_dosen_and_mahasiswa.php
$table->string('profile_photo')->nullable();
```

**6. Frontend Integration:**
```javascript
// Vite configuration untuk asset handling
input: ['resources/css/app.css', 'resources/js/app.js']
```

---

## 13. J.620100.032.01 - Menerapkan Code Review

**Pertanyaan:** Bagaimana proses code review dalam development Laravel?

**Jawaban:**
Code review practices dalam project ini:

**1. Code Standards & Formatting:**
```json
// composer.json - Laravel Pint untuk code formatting
"require-dev": {
    "laravel/pint": "^1.13"
}
```

**2. Documentation Standards:**
```php
// DashboardController.php - Class documentation
/**
 * DashboardController - Mengelola dashboard mahasiswa
 * 
 * Controller ini menangani tampilan dashboard mahasiswa dengan berbagai informasi
 * seperti jadwal hari ini, statistik kehadiran, progress semester, dan pengumuman.
 */
class DashboardController extends Controller
{
    /**
     * Mapping hari dari Indonesia ke Inggris
     */
    private const DAY_MAPPING = [
        'senin' => 'Monday',
        'selasa' => 'Tuesday', 
        // ...
    ];
}
```

**3. Error Handling & Logging:**
```php
// ProfileController.php - Comprehensive error handling
try {
    $dosen = Auth::guard('dosen')->user();
    
    if (!$dosen) {
        Log::warning('Unauthenticated user trying to access dosen profile edit');
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    return view('profile.edit-dosen', compact('dosen'));
} catch (Exception $e) {
    Log::error('Error accessing dosen profile edit: ' . $e->getMessage());
    return redirect()->back()->with('error', 'Terjadi kesalahan saat mengakses halaman profile.');
}
```

**4. Code Organization & Structure:**
```php
// Namespace organization sesuai PSR-4
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Krs;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
```

**5. Validation & Security Review:**
```php
// Enhanced validation dengan custom messages
$validatedData = $request->validate([
    'Nama' => 'required|string|max:100',
    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
], [
    'Nama.required' => 'Nama harus diisi',
    'profile_photo.max' => 'Ukuran gambar maksimal 2MB'
]);
```

**6. Performance Review - Database Optimization:**
```php
// Query optimization dengan eager loading
$krsAmbil = Krs::where('NIM', $mahasiswa->NIM)
              ->with([
                  'matakuliah', 
                  'matakuliah.jadwalAkademik.ruang',
                  'matakuliah.jadwalAkademik.golongan'
              ])
              ->get();
```

**7. Version Control - Backup Files:**
```
DashboardController.php.backup  // Backup untuk version comparison
DashboardController_backup.php  // Previous version tracking
```

---

## 14. J.620100.036.02 - Melaksanakan Pengujian Kode Program secara Statis

**Pertanyaan:** Implementasi static code analysis dalam Laravel!

**Jawaban:**
Static testing tools yang diimplementasikan:

**1. Laravel Pint - Code Style Analysis:**
```json
// composer.json - Laravel Pint configuration
"require-dev": {
    "laravel/pint": "^1.13"
}

// Usage
"scripts": {
    "pint": "./vendor/bin/pint"
}
```

**2. PHPUnit - Unit Testing Framework:**
```xml
<!-- phpunit.xml - Static test configuration -->
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

**3. Composer Scripts untuk Testing:**
```json
// composer.json - Automated testing scripts
"scripts": {
    "test": [
        "@php artisan config:clear --ansi",
        "@php artisan test"
    ],
    "post-autoload-dump": [
        "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
        "@php artisan package:discover --ansi"
    ]
}
```

**4. Code Quality Tools:**
```json
// package.json - Frontend code analysis
"devDependencies": {
    "axios": "^1.8.2",
    "@tailwindcss/vite": "^4.0.0",
    "vite": "^7.0.4"
}
```

**5. Static Analysis dalam Development:**
```php
// ProfileController.php - Type hints dan return types
public function updateDosen(Request $request): JsonResponse
{
    $validatedData = $request->validate([
        'Nama' => 'required|string|max:100'
    ]);
    
    return response()->json(['status' => 'success']);
}
```

**6. Namespace & PSR-4 Compliance:**
```json
// composer.json - PSR-4 autoloading standards
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
}
```

**7. Environment-based Testing:**
```xml
<!-- phpunit.xml - Test environment isolation -->
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="BCRYPT_ROUNDS" value="4"/>
    <env name="CACHE_STORE" value="array"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

**8. Development Tools Integration:**
```json
// composer.json - Mockery untuk testing
"require-dev": {
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.6"
}
```

---

## 15. J.620100.044.01 - Menerapkan Alert Notification jika Aplikasi Bermasalah

**Pertanyaan:** Sistem notifikasi untuk monitoring aplikasi Laravel!

**Jawaban:**
Sistem monitoring dan alerting dalam project:

**1. Laravel Pail - Real-time Log Monitoring:**
```json
// composer.json - Real-time log monitoring
"require-dev": {
    "laravel/pail": "^1.2.2"
}

// Development script dengan log monitoring
"scripts": {
    "dev": [
        "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" 
         \"php artisan serve\" 
         \"php artisan queue:listen --tries=1\" 
         \"php artisan pail --timeout=0\" 
         \"npm run dev\""
    ]
}
```

**2. Comprehensive Error Logging:**
```php
// ProfileController.php - Multi-level logging
use Illuminate\Support\Facades\Log;

try {
    // Application logic
} catch (Exception $e) {
    Log::error('Error accessing dosen profile edit: ' . $e->getMessage());
    Log::warning('Unauthenticated user trying to access dosen profile edit');
    Log::info('Password updated for dosen: ' . $dosen->NIP);
}
```

**3. Session & Authentication Monitoring:**
```php
// Migrations - Session tracking untuk monitoring
Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});
```

**4. Error Response System:**
```php
// KrsController.php - Structured error responses
private function errorResponse($message, $status, $request)
{
    if ($request->ajax()) {
        return response()->json(['error' => $message], $status);
    }
    return redirect()->back()->with('error', $message);
}
```

**5. Database Transaction Monitoring:**
```php
// ProfileController.php - Transaction rollback alerts
DB::beginTransaction();

try {
    // Database operations
    DB::commit();
    Log::info('Profile update successful for: ' . $dosen->NIP);
} catch (Exception $e) {
    DB::rollback();
    Log::error('Profile update failed for: ' . $dosen->NIP . ' - ' . $e->getMessage());
    throw $e;
}
```

**6. Queue & Background Job Monitoring:**
```bash
# Queue listener dengan retry mechanism
php artisan queue:listen --tries=1
```

**7. Frontend Error Handling:**
```javascript
// axios untuk API error handling
"dependencies": {
    "axios": "^1.8.2"
}
```

**8. Application Health Checks:**
```php
// ProfileController.php - Health validation
if (!$dosen) {
    Log::warning('Unauthenticated user trying to access dosen profile edit');
    return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
}
```

---

## 16. J.620100.045.01 - Melakukan Pemantauan Resource yang Digunakan Aplikasi

**Pertanyaan:** Bagaimana monitoring resource usage aplikasi Laravel?

**Jawaban:**
Resource monitoring implementation dalam project:

**1. Real-time Application Monitoring:**
```json
// composer.json - Integrated monitoring tools
"scripts": {
    "dev": [
        "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" 
         \"php artisan serve\" 
         \"php artisan queue:listen --tries=1\" 
         \"php artisan pail --timeout=0\" 
         \"npm run dev\" 
         --names=server,queue,logs,vite"
    ]
}
```

**2. Database Performance Monitoring:**
```php
// ProfileController.php - Query performance tracking
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();

try {
    $startTime = microtime(true);
    
    // Database operations
    $dosen->update($updateData);
    
    $executionTime = microtime(true) - $startTime;
    Log::info('Database update completed', [
        'execution_time' => $executionTime,
        'user_id' => $dosen->NIP
    ]);
    
    DB::commit();
} catch (Exception $e) {
    DB::rollback();
    Log::error('Database operation failed', [
        'error' => $e->getMessage(),
        'memory_usage' => memory_get_usage(true)
    ]);
}
```

**3. Session Monitoring:**
```php
// Migration - Session resource tracking
Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();  // Activity tracking
});
```

**4. Memory Usage Monitoring:**
```php
// ProfileController.php - Memory tracking
if ($request->hasFile('profile_photo')) {
    $memoryBefore = memory_get_usage(true);
    
    try {
        $this->ensureDirectoryExists();
        // File processing
        
        $memoryAfter = memory_get_usage(true);
        Log::info('File upload memory usage', [
            'memory_used' => $memoryAfter - $memoryBefore,
            'peak_memory' => memory_get_peak_usage(true)
        ]);
    } catch (Exception $e) {
        Log::error('File upload failed', [
            'error' => $e->getMessage(),
            'memory_usage' => memory_get_usage(true)
        ]);
    }
}
```

**5. Query Performance Monitoring:**
```php
// KrsController.php - Complex query monitoring
$startTime = microtime(true);

$krsAmbil = Krs::where('NIM', $mahasiswa->NIM)
              ->with([
                  'matakuliah', 
                  'matakuliah.jadwalAkademik.ruang',
                  'matakuliah.jadwalAkademik.golongan'
              ])
              ->get();

$queryTime = microtime(true) - $startTime;
if ($queryTime > 0.1) { // Log slow queries
    Log::warning('Slow query detected', [
        'execution_time' => $queryTime,
        'query_type' => 'KRS_fetch_with_relations'
    ]);
}
```

**6. Asset Performance Monitoring:**
```javascript
// vite.config.js - Build performance tracking
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,  // Hot reload monitoring
        }),
        tailwindcss(),
    ],
});
```

**7. Error Rate Monitoring:**
```php
// ProfileController.php - Error tracking
try {
    // Application logic
} catch (Exception $e) {
    Log::error('Application error', [
        'error_type' => get_class($e),
        'error_message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'memory_usage' => memory_get_usage(true),
        'timestamp' => now()
    ]);
}
```

---

## 17. J.620100.047.01 - Melakukan Pembaharuan Perangkat Lunak

**Pertanyaan:** Proses update dan maintenance aplikasi Laravel!

**Jawaban:**
Pembaharuan software dalam project ini:

**1. Composer Dependency Management:**
```json
// composer.json - Version constraints untuk updates
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "laravel/tinker": "^2.10.1"
    },
    "require-dev": {
        "laravel/pint": "^1.13",
        "phpunit/phpunit": "^11.5.3"
    }
}
```

**2. Database Migration System:**
```php
// Migration files untuk schema updates
2025_07_30_044705_remove_foreign_keys_from_presensi_akademik.php
2025_07_30_add_timestamps_to_krs_table.php
2025_01_27_000001_add_profile_photo_to_dosen_and_mahasiswa.php

// Migration rollback capability
public function down()
{
    Schema::dropIfExists('krs');
}
```

**3. Automated Scripts untuk Updates:**
```json
// composer.json - Post-update automation
"scripts": {
    "post-update-cmd": [
        "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
    ],
    "post-autoload-dump": [
        "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
        "@php artisan package:discover --ansi"
    ]
}
```

**4. Frontend Package Updates:**
```json
// package.json - Frontend dependencies
"devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "vite": "^7.0.4",
    "tailwindcss": "^4.0.0"
}
```

**5. Version Control & Backup Strategy:**
```
// Backup files untuk rollback capability
DashboardController.php.backup
DashboardController_backup.php
```

**6. Environment Configuration Updates:**
```php
// phpunit.xml - Test environment updates
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
```

**7. Asset Build Updates:**
```javascript
// vite.config.js - Build system updates
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

**8. Security Updates & Patches:**
```php
// ProfileController.php - Security validation updates
$validatedData = $request->validate([
    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
]);
```

---

## Kesimpulan

Semua 17 unit kompetensi telah diimplementasikan dalam project Laravel ini dengan contoh konkret dari kode yang ada. Project ini mencakup:

- **Sistem Akademik Lengkap**: KRS, Jadwal, Presensi, Profile Management
- **Multi-Role Authentication**: Admin, Dosen, Mahasiswa
- **Modern Development Stack**: Laravel 12, Vite, TailwindCSS, PHPUnit
- **Best Practices**: OOP, MVC, Database Relations, Error Handling
- **Quality Assurance**: Testing, Code Review, Static Analysis
- **Production Ready**: Monitoring, Logging, Security, Scalability

Setiap kompetensi saling terintegrasi untuk menghasilkan aplikasi web yang robust, scalable, dan maintainable dengan standar industri modern.