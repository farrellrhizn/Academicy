<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kuliah - KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .schedule-card {
            border-left: 4px solid;
            transition: transform 0.2s ease-in-out;
        }
        .schedule-card:hover {
            transform: translateY(-2px);
        }
        .time-label {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px;
            border-radius: 10px;
            font-weight: bold;
        }
        .day-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('mahasiswa.dashboard') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>SIAKAD
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mahasiswa.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mahasiswa.krs.index') }}">KRS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('mahasiswa.krs.jadwal') }}">Jadwal Kuliah</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ $mahasiswa->Nama }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card card-custom bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="card-title mb-1">
                                    <i class="bi bi-calendar-week me-2"></i>Jadwal Kuliah
                                </h3>
                                <p class="card-text mb-0">Jadwal mata kuliah berdasarkan KRS yang telah diambil</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="bg-white text-primary rounded p-3">
                                    <h5 class="mb-1">{{ $mahasiswa->NIM }}</h5>
                                    <small>{{ $mahasiswa->Nama }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($jadwalKuliah->count() > 0)
            <!-- Ringkasan Jadwal -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-book text-primary me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h5 class="mb-0">{{ $jadwalKuliah->count() }}</h5>
                                            <small class="text-muted">Mata Kuliah</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-award text-success me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h5 class="mb-0">{{ $jadwalKuliah->sum('sks') }}</h5>
                                            <small class="text-muted">Total SKS</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar-date text-info me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h5 class="mb-0">{{ $jadwalKuliah->groupBy('hari')->count() }}</h5>
                                            <small class="text-muted">Hari Kuliah</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people text-warning me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h5 class="mb-0">{{ $mahasiswa->golongan->nama_Gol ?? 'N/A' }}</h5>
                                            <small class="text-muted">Golongan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Per Hari -->
            @php
                $hariUrutan = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $jadwalPerHari = $jadwalKuliah->groupBy('hari');
                $warna = [
                    'Senin' => 'primary',
                    'Selasa' => 'success', 
                    'Rabu' => 'info',
                    'Kamis' => 'warning',
                    'Jumat' => 'danger',
                    'Sabtu' => 'secondary',
                    'Minggu' => 'dark'
                ];
            @endphp

            @foreach($hariUrutan as $hari)
                @if(isset($jadwalPerHari[$hari]))
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="day-header">
                                <h4 class="mb-0">
                                    <i class="bi bi-calendar-day me-2"></i>{{ $hari }}
                                </h4>
                            </div>
                            
                            @foreach($jadwalPerHari[$hari]->sortBy('waktu') as $jadwal)
                                <div class="card schedule-card border-{{ $warna[$hari] ?? 'primary' }} mb-3">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <div class="time-label text-center">
                                                    <i class="bi bi-clock"></i><br>
                                                    {{ $jadwal->waktu }}
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h5 class="card-title text-{{ $warna[$hari] ?? 'primary' }} mb-1">
                                                    {{ $jadwal->Nama_mk }}
                                                </h5>
                                                <p class="card-text mb-2">
                                                    <span class="badge bg-{{ $warna[$hari] ?? 'primary' }} me-2">{{ $jadwal->Kode_mk }}</span>
                                                    <span class="badge bg-secondary me-2">{{ $jadwal->sks }} SKS</span>
                                                    <span class="badge bg-info">{{ $jadwal->nama_Gol }}</span>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $jadwal->nama_ruang }}
                                                </small>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <div class="d-flex flex-column">
                                                    <span class="badge bg-light text-dark mb-1">{{ $hari }}</span>
                                                    <small class="text-muted">{{ $jadwal->waktu }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Tabel Ringkasan -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-table me-2"></i>Ringkasan Jadwal Kuliah
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode MK</th>
                                            <th>Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Hari</th>
                                            <th>Waktu</th>
                                            <th>Ruang</th>
                                            <th>Golongan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jadwalKuliah as $index => $jadwal)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><code>{{ $jadwal->Kode_mk }}</code></td>
                                                <td><strong>{{ $jadwal->Nama_mk }}</strong></td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $jadwal->sks }} SKS</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $warna[$jadwal->hari] ?? 'primary' }}">{{ $jadwal->hari }}</span>
                                                </td>
                                                <td>{{ $jadwal->waktu }}</td>
                                                <td>
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $jadwal->nama_ruang }}
                                                </td>
                                                <td>{{ $jadwal->nama_Gol }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3"><strong>Total</strong></td>
                                            <td><strong>{{ $jadwalKuliah->sum('sks') }} SKS</strong></td>
                                            <td colspan="4"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke KRS
                    </a>
                    <a href="{{ route('mahasiswa.krs.cetak') }}" class="btn btn-primary me-2">
                        <i class="bi bi-printer me-1"></i>Cetak Jadwal
                    </a>
                    <button class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-download me-1"></i>Print Jadwal
                    </button>
                </div>
            </div>

        @else
            <!-- Tidak Ada Jadwal -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">Belum Ada Jadwal Kuliah</h4>
                            <p class="text-muted mb-4">
                                Anda belum mengambil mata kuliah atau mata kuliah yang diambil belum dijadwalkan.
                            </p>
                            <a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>Ambil Mata Kuliah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Print Styles -->
    <style media="print">
        .navbar, .btn, .no-print {
            display: none !important;
        }
        .container {
            max-width: 100% !important;
        }
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
        body {
            background: white !important;
        }
    </style>
</body>
</html>