<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Dashboard Dosen</title>

		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="../../bootstrap/vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="../../bootstrap/vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="../../bootstrap/vendors/images/favicon-16x16.png"
		/>

		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<link rel="stylesheet" type="text/css" href="../../bootstrap/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="../../bootstrap/vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="../../bootstrap/vendors/styles/style.css" />

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
							<a href="{{ route('dosen.dashboard') }}" class="dropdown-toggle no-arrow active">
								<span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
							</a>
						</li>
						<li>
							<a href="{{ route('dosen.jadwal.index') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-calendar3-week"></span><span class="mtext">Jadwal Mengajar</span>
							</a>
						</li>
						<li>
							<a href="{{ route('dosen.presensi.simple') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-journal-check"></span><span class="mtext">Input Presensi Cepat</span>
							</a>
						</li>
						
						<li>
							<a href="{{ route('dosen.mata-kuliah-diampu.index') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-book"></span><span class="mtext">Mata Kuliah Diampu</span>
							</a>
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
							<img src="../../bootstrap/vendors/images/banner-img.png" alt="" />
						</div>
						<div class="col-md-8">
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
								Selamat Datang Kembali,
								<div class="weight-600 font-30 text-blue">Prof. Dr. Budi Santoso, M.Kom!</div>
							</h4>
							<p class="font-18 max-width-600">
								Semoga hari Anda menyenangkan. Berikut adalah ringkasan aktivitas mengajar Anda.
							</p>
						</div>
					</div>
				</div>

				<div class="row pb-10">
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">4</div>
									<div class="font-14 text-secondary weight-500">Mata Kuliah Diampu</div>
								</div>
								<div class="widget-icon">
									<div class="icon"><i class="icon-copy bi bi-book" aria-hidden="true"></i></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">12 SKS</div>
									<div class="font-14 text-secondary weight-500">Total SKS Mengajar</div>
								</div>
								<div class="widget-icon">
									<div class="icon"><i class="icon-copy bi bi-stack" aria-hidden="true"></i></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">125</div>
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
									<div class="weight-700 font-24 text-dark">2 Kelas</div>
									<div class="font-14 text-secondary weight-500">Jadwal Hari Ini</div>
								</div>
								<div class="widget-icon">
									<div class="icon"><i class="icon-copy bi bi-clock-history" aria-hidden="true"></i></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-box mb-30">
					<div class="pd-20">
						<h4 class="text-blue h4">Jadwal Mengajar Hari Ini (Senin, 28 Juli 2025) 🗓️</h4>
					</div>
					<div class="pb-20">
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr>
									<th>Waktu</th>
									<th>Mata Kuliah</th>
									<th>Kelas</th>
									<th>Ruang</th>
									<th class="datatable-nosort">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>08:00 - 09:40</td>
									<td>Dasar Pemrograman</td>
									<td>IF-1A</td>
									<td>Lab Komputer 1</td>
									<td>
										<a href="#" class="btn btn-primary btn-sm">Input Presensi</a>
									</td>
								</tr>
								<tr>
									<td>10:00 - 11:40</td>
									<td>Struktur Data</td>
									<td>IF-2B</td>
									<td>Gedung A R.105</td>
									<td>
										<a href="#" class="btn btn-primary btn-sm">Input Presensi</a>
									</td>
								</tr>
								<tr>
									<td>Selesai</td>
									<td>Algoritma</td>
									<td>IF-3C</td>
									<td>Gedung C R.301</td>
									<td>
										<button class="btn btn-success btn-sm" disabled>Sudah Diisi</button>
									</td>
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

		<script src="../../bootstrap/vendors/scripts/core.js"></script>
		<script src="../../bootstrap/vendors/scripts/script.min.js"></script>
		<script src="../../bootstrap/vendors/scripts/process.js"></script>
		<script src="../../bootstrap/vendors/scripts/layout-settings.js"></script>
	</body>
</html>