<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Jadwal Mengajar Saya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    {{-- Aset CSS Anda --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
</head>
<body>
    <div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="../../bootstrap/vendors/images/deskapp-logo.svg" alt="" />
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
						<a
							class="dropdown-toggle"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
							<span class="user-icon">
								<img src="../../bootstrap/vendors/images/photo1.jpg" alt="" />
							</span>
							<span class="user-name">[Nama Dosen]</span>
						</a>
						<div
							class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
						>
							<a class="dropdown-item" href="profile.html"
								><i class="dw dw-user1"></i> Profil</a
							>
							<a class="dropdown-item" href="{{ route('login') }}"
								><i class="dw dw-logout"></i> Log Out</a
							>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="left-side-bar">
			<div class="brand-logo">
				<a href="index.html">
					<img src="../../bootstrap/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
					<img
						src="../../bootstrap/vendors/images/deskapp-logo-white.svg"
						alt=""
						class="light-logo"
					/>
				</a>
				<div class="close-sidebar" data-toggle="left-sidebar-close">
					<i class="ion-close-round"></i>
				</div>
			</div>
			<div class="menu-block customscroll">
				<div class="sidebar-menu">
					<ul id="accordion-menu">
						<li>
							<a href="{{ route('dosen.dashboard') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
							</a>
						</li>
						<li>
							<a href="{{ route('dosen.jadwal.index') }}" class="dropdown-toggle no-arrow active">
								<span class="micon bi bi-calendar3-week"></span><span class="mtext">Jadwal Mengajar</span>
							</a>
						</li>
						<li>
							<a href="{{ route('dosen.presensi.simple') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-journal-check"></span><span class="mtext">Input Presensi Cepat</span>
							</a>
						</li>
						<li>
							<a href="{{ route('dosen.presensi.index') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-journal"></span><span class="mtext">Input Presensi Detail</span>
							</a>
						</li>
						<li>
							<a href="matkul-diampu.html" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-book"></span><span class="mtext">Mata Kuliah Diampu</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-9 col-sm-12">
                            <div class="title"><h4>Jadwal Mengajar Saya</h4></div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Jadwal Mengajar</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-3 col-sm-12 text-right">
                             {{-- Alternatif Aksi: Tombol Cetak --}}
                            <button class="btn btn-primary" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Jadwal
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Daftar Jadwal Mengajar</h4>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap" id="jadwalDosenTable">
                            <thead>
                                <tr>
                                    <th class="table-plus">No</th>
                                    <th>Hari</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Ruangan</th>
                                    <th>Golongan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jadwal as $index => $item)
                                <tr>
                                    <td class="table-plus">{{ $index + 1 }}</td>
                                    <td>{{ $item->hari }}</td>
                                    <td>{{ $item->matakuliah->Nama_mk ?? 'N/A' }}</td>
                                    <td>{{ $item->matakuliah->sks ?? 'N/A' }}</td>
                                    <td>{{ $item->ruang->nama_ruang ?? 'N/A' }}</td>
                                    <td>{{ $item->golongan->nama_Gol ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada jadwal mengajar yang ditugaskan untuk Anda.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript --}}
    <script src="../../bootstrap/vendors/scripts/core.js"></script>
		<script src="../../bootstrap/vendors/scripts/script.min.js"></script>
		<script src="../../bootstrap/vendors/scripts/process.js"></script>
		<script src="../../bootstrap/vendors/scripts/layout-settings.js"></script>

    <script>
    $(document).ready(function() {
        $('#jadwalDosenTable').DataTable({
            responsive: true,
            "language": {
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ jadwal",
                "paginate": { "next": "Berikutnya", "previous": "Sebelumnya" },
                "zeroRecords": "Tidak ada data untuk ditampilkan",
            }
        });
    });
    </script>
</body>
</html>