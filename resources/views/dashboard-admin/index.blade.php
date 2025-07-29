<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Dashboard Admin</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../../../bootstrap/vendors/images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../../../bootstrap/vendors/images/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../../../bootstrap/vendors/images/favicon-16x16.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;808&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../../../bootstrap/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="../../../bootstrap/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="../../../bootstrap/vendors/styles/style.css" />
</head>

<body>
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="../../../bootstrap/vendors/images/deskapp-logo.svg" alt="" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div>

    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
        </div>
        <div class="header-right">
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <img src="../../../bootstrap/vendors/images/photo1.jpg" alt="" />
                        </span>
                        <span class="user-name">Administrator</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="profil-admin.html"><i class="dw dw-user1"></i> Profil</a>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                <i class="dw dw-logout"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="index.html">
                <img src="../../../bootstrap/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
                <img src="../../../bootstrap/vendors/images/deskapp-logo-white.svg" alt="" class="light-logo" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>

        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow active">
                            <span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-mortarboard"></span><span class="mtext">Data Master</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.matakuliah.index') }}">Mata Kuliah</a></li>
                            <li><a href="{{ route('admin.dosen.index') }}">Dosen</a></li>
                            <li><a href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-calendar3-week"></span><span class="mtext">Jadwal</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.buat-jadwal.index') }}">Buat Jadwal</a></li>
                            <li><a href="{{ route('admin.kelola-jadwal.index') }}">Kelola Jadwal</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-building"></span><span class="mtext">Ruang & Kelas</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.ruang.index') }}">Ruang</a></li>
                            <li><a href="{{ route('admin.golongan.index') }}">Kelas/Golongan</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-gear"></span><span class="mtext">Pengaturan</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="pengaturan-sistem.html">Sistem</a></li>
                            <li><a href="profil-admin.html">Profil</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="card-box pd-20 height-100-p mb-30">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <img src="../../../bootstrap/vendors/images/banner-img.png" alt="" />
                    </div>
                    <div class="col-md-8">
                        <h4 class="font-20 weight-500 mb-10 text-capitalize">
                            Selamat Datang,
                            <div class="weight-600 font-30 text-blue">Administrator!</div>
                        </h4>
                        <p class="font-18 max-width-600">
                            Kelola sistem akademik dengan mudah. Berikut adalah ringkasan data sistem.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row pb-10">
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">45</div>
                                <div class="font-14 text-secondary weight-500">Total Mata Kuliah</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon"><i class="icon-copy bi bi-book-half" aria-hidden="true"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">28</div>
                                <div class="font-14 text-secondary weight-500">Total Dosen</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon"><i class="icon-copy bi bi-person-badge" aria-hidden="true"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">1,245</div>
                                <div class="font-14 text-secondary weight-500">Total Mahasiswa</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon"><i class="icon-copy bi bi-people-fill" aria-hidden="true"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">15</div>
                                <div class="font-14 text-secondary weight-500">Total Ruang</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon"><i class="icon-copy bi bi-building" aria-hidden="true"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Aktivitas Terbaru 📊</h4>
                </div>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Aktivitas</th>
                                <th>User</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>08:30</td>
                                <td>Input data mata kuliah baru</td>
                                <td>Admin</td>
                                <td><span class="badge badge-success">Berhasil</span></td>
                            </tr>
                            <tr>
                                <td>09:15</td>
                                <td>Update profil dosen</td>
                                <td>Dr. Andi, M.Kom</td>
                                <td><span class="badge badge-success">Berhasil</span></td>
                            </tr>
                            <tr>
                                <td>10:00</td>
                                <td>Penyusunan jadwal semester</td>
                                <td>Admin</td>
                                <td><span class="badge badge-warning">Proses</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="footer-wrap pd-20 mb-20 card-box">
                Sistem Informasi Akademik - © 2025
            </div>
        </div>
    </div>

    <script src="../../../bootstrap/vendors/scripts/core.js"></script>
    <script src="../../../bootstrap/vendors/scripts/script.min.js"></script>
    <script src="../../../bootstrap/vendors/scripts/process.js"></script>
    <script src="../../../bootstrap/vendors/scripts/layout-settings.js"></script>
</body>

</html>