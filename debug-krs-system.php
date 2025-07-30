<?php

/**
 * KRS System Debug Script
 * Script untuk debugging masalah KRS system
 */

// Include Laravel's autoloader if available
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    $laravelAvailable = true;
} else {
    $laravelAvailable = false;
    echo "Laravel autoloader tidak ditemukan. Running standalone debug...\n";
}

echo "=== KRS SYSTEM DEBUG SCRIPT ===\n";
echo "Waktu: " . date('Y-m-d H:i:s') . "\n\n";

if ($laravelAvailable) {
    try {
        // Initialize Laravel
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        echo "✅ Laravel berhasil di-load\n";
        
        // Test database connection
        try {
            $pdo = DB::connection()->getPdo();
            echo "✅ Database connection berhasil\n";
            echo "Database driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
        } catch (Exception $e) {
            echo "❌ Database connection gagal: " . $e->getMessage() . "\n";
            exit(1);
        }

        // Check if tables exist
        $tables = ['mahasiswa', 'matakuliah', 'jadwal_akademik', 'krs', 'golongan', 'ruang'];
        echo "\n=== CHECKING TABLES ===\n";
        
        foreach ($tables as $table) {
            try {
                $exists = DB::getSchemaBuilder()->hasTable($table);
                if ($exists) {
                    $count = DB::table($table)->count();
                    echo "✅ Tabel {$table}: {$count} records\n";
                } else {
                    echo "❌ Tabel {$table}: tidak ditemukan\n";
                }
            } catch (Exception $e) {
                echo "❌ Error checking table {$table}: " . $e->getMessage() . "\n";
            }
        }

        // Check KRS table structure
        echo "\n=== KRS TABLE STRUCTURE ===\n";
        try {
            $columns = DB::select("PRAGMA table_info(krs)");
            if (empty($columns)) {
                // Try MySQL syntax
                $columns = DB::select("DESCRIBE krs");
            }
            
            foreach ($columns as $column) {
                if (isset($column->name)) {
                    echo "- {$column->name} ({$column->type})\n";
                } elseif (isset($column->Field)) {
                    echo "- {$column->Field} ({$column->Type})\n";
                }
            }
        } catch (Exception $e) {
            echo "❌ Error getting KRS structure: " . $e->getMessage() . "\n";
        }

        // Check foreign key constraints
        echo "\n=== FOREIGN KEY CONSTRAINTS ===\n";
        try {
            $constraints = DB::select("PRAGMA foreign_key_list(krs)");
            if (empty($constraints)) {
                // Try MySQL syntax
                $constraints = DB::select("
                    SELECT 
                        CONSTRAINT_NAME,
                        COLUMN_NAME,
                        REFERENCED_TABLE_NAME,
                        REFERENCED_COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_NAME = 'krs' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
            }
            
            if (empty($constraints)) {
                echo "ℹ️  Tidak ada foreign key constraints ditemukan atau tidak bisa diakses\n";
            } else {
                foreach ($constraints as $constraint) {
                    echo "- " . json_encode($constraint) . "\n";
                }
            }
        } catch (Exception $e) {
            echo "❌ Error checking constraints: " . $e->getMessage() . "\n";
        }

        // Test sample data
        echo "\n=== SAMPLE DATA TEST ===\n";
        try {
            $mahasiswa = DB::table('mahasiswa')->first();
            if ($mahasiswa) {
                echo "✅ Sample mahasiswa: NIM {$mahasiswa->NIM}\n";
                
                $matakuliah = DB::table('matakuliah')
                    ->where('semester', $mahasiswa->Semester ?? 1)
                    ->first();
                
                if ($matakuliah) {
                    echo "✅ Sample matakuliah: {$matakuliah->Kode_mk} ({$matakuliah->Nama_mk})\n";
                    
                    // Check if jadwal exists for this combination
                    $jadwal = DB::table('jadwal_akademik')
                        ->where('Kode_mk', $matakuliah->Kode_mk)
                        ->where('id_Gol', $mahasiswa->id_Gol ?? 1)
                        ->first();
                    
                    if ($jadwal) {
                        echo "✅ Jadwal ditemukan untuk kombinasi mahasiswa-matakuliah\n";
                        
                        // Test KRS creation
                        echo "\n=== TEST KRS CREATION ===\n";
                        try {
                            $existingKrs = DB::table('krs')
                                ->where('NIM', $mahasiswa->NIM)
                                ->where('Kode_mk', $matakuliah->Kode_mk)
                                ->first();
                            
                            if ($existingKrs) {
                                echo "ℹ️  KRS sudah ada untuk kombinasi ini\n";
                            } else {
                                $krsData = [
                                    'NIM' => $mahasiswa->NIM,
                                    'Kode_mk' => $matakuliah->Kode_mk,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                
                                echo "Mencoba insert KRS: " . json_encode($krsData) . "\n";
                                
                                $id = DB::table('krs')->insertGetId($krsData);
                                echo "✅ KRS berhasil dibuat dengan ID: {$id}\n";
                                
                                // Cleanup test data
                                DB::table('krs')->where('id_krs', $id)->delete();
                                echo "✅ Test data dibersihkan\n";
                            }
                        } catch (Exception $e) {
                            echo "❌ Error creating KRS: " . $e->getMessage() . "\n";
                            echo "Error details: " . $e->getTraceAsString() . "\n";
                        }
                    } else {
                        echo "❌ Tidak ada jadwal untuk kombinasi mahasiswa-matakuliah\n";
                    }
                } else {
                    echo "❌ Tidak ada matakuliah untuk semester mahasiswa\n";
                }
            } else {
                echo "❌ Tidak ada data mahasiswa\n";
            }
        } catch (Exception $e) {
            echo "❌ Error testing sample data: " . $e->getMessage() . "\n";
        }

        // Check Model relationships
        echo "\n=== MODEL RELATIONSHIPS TEST ===\n";
        try {
            $krs = new App\Models\Krs();
            $mahasiswa = new App\Models\Mahasiswa();
            $matakuliah = new App\Models\MataKuliah();
            
            echo "✅ Models loaded successfully\n";
            
            // Test if we can get related data
            $testKrs = App\Models\Krs::with(['mahasiswa', 'matakuliah'])->first();
            if ($testKrs) {
                echo "✅ KRS relationships working\n";
                echo "  - Mahasiswa: " . ($testKrs->mahasiswa->Nama ?? 'null') . "\n";
                echo "  - Matakuliah: " . ($testKrs->matakuliah->Nama_mk ?? 'null') . "\n";
            } else {
                echo "ℹ️  Tidak ada data KRS untuk testing relationships\n";
            }
        } catch (Exception $e) {
            echo "❌ Error testing model relationships: " . $e->getMessage() . "\n";
        }

    } catch (Exception $e) {
        echo "❌ Fatal error: " . $e->getMessage() . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
    }
} else {
    echo "❌ Laravel tidak tersedia, tidak bisa melakukan testing lengkap\n";
}

echo "\n=== REKOMENDASI PERBAIKAN ===\n";
echo "1. Pastikan database sudah di-migrate: php artisan migrate\n";
echo "2. Jalankan seeder untuk data test: php artisan db:seed --class=KrsTestDataSeeder\n";
echo "3. Periksa foreign key constraints di database\n";
echo "4. Pastikan data mahasiswa dan matakuliah valid sebelum insert KRS\n";
echo "5. Check log file di storage/logs/laravel.log untuk error details\n";

echo "\n=== DEBUG COMPLETED ===\n";