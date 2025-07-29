<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRS - Kartu Rencana Studi</title>
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
            transition: transform 0.2s ease-in-out;
        }
        .card-custom:hover {
            transform: translateY(-2px);
        }
        .btn-custom {
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 500;
        }
        .table-custom {
            border-radius: 10px;
            overflow: hidden;
        }
        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
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
                        <a class="nav-link active" href="{{ route('mahasiswa.krs.index') }}">KRS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mahasiswa.krs.jadwal') }}">Jadwal Kuliah</a>
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
                                    <i class="bi bi-journal-text me-2"></i>Kartu Rencana Studi (KRS)
                                </h3>
                                <p class="card-text mb-0">Kelola mata kuliah yang akan Anda ambil pada semester ini</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="bg-white text-primary rounded p-3">
                                    <h5 class="mb-1">{{ $mahasiswa->NIM }}</h5>
                                    <small>Semester {{ $mahasiswa->Semester }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Mata Kuliah yang Sudah Diambil -->
            <div class="col-lg-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-check-square me-2"></i>Mata Kuliah Diambil
                            <span class="badge bg-light text-success ms-2">{{ $krsAmbil->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($krsAmbil->count() > 0)
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Total SKS: <strong>{{ $krsAmbil->sum(fn($krs) => $krs->matakuliah->sks) }}</strong>
                                </small>
                                <a href="{{ route('mahasiswa.krs.cetak') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-printer me-1"></i>Cetak KRS
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-custom table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Jadwal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($krsAmbil as $krs)
                                            <tr>
                                                <td><code>{{ $krs->Kode_mk }}</code></td>
                                                <td>
                                                    <strong>{{ $krs->matakuliah->Nama_mk }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-custom bg-primary">{{ $krs->matakuliah->sks }} SKS</span>
                                                </td>
                                                <td>
                                                    @if($krs->matakuliah->jadwalAkademik->isNotEmpty())
                                                        @foreach($krs->matakuliah->jadwalAkademik as $jadwal)
                                                            @if($jadwal->id_Gol == $mahasiswa->id_Gol)
                                                                <small class="text-muted">
                                                                    {{ $jadwal->hari }}, {{ $jadwal->waktu }}<br>
                                                                    <i class="bi bi-geo-alt"></i> {{ $jadwal->ruang->nama_ruang ?? 'TBA' }}
                                                                </small>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <small class="text-warning">Belum dijadwalkan</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('mahasiswa.krs.destroy') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="Kode_mk" value="{{ $krs->Kode_mk }}">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                onclick="return confirm('Yakin ingin menghapus mata kuliah ini dari KRS?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Belum ada mata kuliah yang diambil</p>
                                <small class="text-muted">Pilih mata kuliah dari daftar sebelah kanan</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mata Kuliah Tersedia -->
            <div class="col-lg-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-plus-square me-2"></i>Mata Kuliah Tersedia
                            <span class="badge bg-light text-info ms-2">{{ $matakuliahTersedia->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($matakuliahTersedia->count() > 0)
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Mata kuliah untuk semester {{ $mahasiswa->Semester }} dan golongan {{ $mahasiswa->golongan->nama_Gol ?? 'Anda' }}
                                </small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-custom table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Jadwal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($matakuliahTersedia as $mk)
                                            <tr>
                                                <td><code>{{ $mk->Kode_mk }}</code></td>
                                                <td>
                                                    <strong>{{ $mk->Nama_mk }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-custom bg-secondary">{{ $mk->sks }} SKS</span>
                                                </td>
                                                <td>
                                                    @if($mk->jadwalAkademik->isNotEmpty())
                                                        @foreach($mk->jadwalAkademik as $jadwal)
                                                            @if($jadwal->id_Gol == $mahasiswa->id_Gol)
                                                                <small class="text-muted">
                                                                    {{ $jadwal->hari }}, {{ $jadwal->waktu }}<br>
                                                                    <i class="bi bi-geo-alt"></i> {{ $jadwal->ruang->nama_ruang ?? 'TBA' }}
                                                                </small>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <small class="text-warning">Belum dijadwalkan</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('mahasiswa.krs.store') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="Kode_mk" value="{{ $mk->Kode_mk }}">
                                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Semua mata kuliah sudah diambil</p>
                                <small class="text-muted">Atau belum ada mata kuliah yang tersedia untuk semester Anda</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-badge text-primary me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">{{ $mahasiswa->NIM }}</h6>
                                        <small class="text-muted">NIM</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-calendar-event text-success me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Semester {{ $mahasiswa->Semester }}</h6>
                                        <small class="text-muted">Semester Aktif</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people text-info me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">{{ $mahasiswa->golongan->nama_Gol ?? 'N/A' }}</h6>
                                        <small class="text-muted">Golongan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-award text-warning me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">{{ $krsAmbil->sum(fn($krs) => $krs->matakuliah->sks) }} SKS</h6>
                                        <small class="text-muted">Total SKS</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>