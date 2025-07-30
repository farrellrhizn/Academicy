<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Dashboard Mahasiswa</title>

		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="{{ asset('bootstrap/vendors/images/apple-touch-icon.png') }}"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="{{ asset('bootstrap/vendors/images/favicon-32x32.png') }}"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="{{ asset('bootstrap/vendors/images/favicon-16x16.png') }}"
		/>

		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
		<link
			rel="stylesheet"
			type="text/css"
			href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="{{ asset('bootstrap/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="{{ asset('bootstrap/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}"
		/>
		<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('css/profile-photo.css') }}" />

	</head>
	<body>
		<div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="{{ asset('bootstrap/vendors/images/deskapp-logo.svg') }}" alt="" />
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
				<div class="user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
							<i class="icon-copy dw dw-notification"></i>
							<span class="badge notification-active"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<div class="notification-list mx-h-350 customscroll">
								<ul>
									<li>
										<a href="#">
											<img src="{{ asset('bootstrap/vendors/images/img.jpg') }}" alt="" />
											<h3>Info Akademik</h3>
											<p>
												Batas akhir pengisian KRS adalah tanggal 30 Agustus 2025.
											</p>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="user-info-dropdown">
					<div class="dropdown">
						<a
							class="dropdown-toggle"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
							<span class="user-icon">
								@if($userData->profile_photo)
									<img src="{{ asset('storage/profile_photos/' . $userData->profile_photo) }}" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
								@else
									<i class="dw dw-user1"></i>
								@endif
							</span>
							<span class="user-name">{{ $userData->Nama ?? 'Mahasiswa' }}</span>
						</a>
						<div
							class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
						>
							<a class="dropdown-item" href="{{ route('mahasiswa.profile.edit') }}"
								><i class="dw dw-user1"></i> Profile</a
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
				<a href="{{ route('mahasiswa.dashboard') }}">
					<img src="{{ asset('bootstrap/vendors/images/deskapp-logo.svg') }}" alt="" class="dark-logo" />
					<img
						src="{{ asset('bootstrap/vendors/images/deskapp-logo-white.svg') }}"
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
							<a href="{{ route('mahasiswa.dashboard') }}" class="dropdown-toggle no-arrow active">
								<span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
							</a>
						</li>
						<li>
							<a href="{{ route('mahasiswa.krs.index') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-card-list"></span><span class="mtext">KRS</span>
							</a>
						</li>
						<li>
							<a href="{{ route('mahasiswa.jadwal.index') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-calendar-week"></span><span class="mtext">Jadwal Kuliah</span>
							</a>
						</li>
						<li>
							<a href="{{ route('mahasiswa.presensi.riwayat') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-check2-square"></span><span class="mtext">Riwayat Presensi</span>
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
							<img src="{{ asset('bootstrap/vendors/images/banner-img.png') }}" alt="" />
						</div>
						<div class="col-md-8">
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
								Selamat Datang,
								<div class="weight-600 font-30 text-blue">{{ $userData->Nama ?? 'Mahasiswa' }}!</div>
							</h4>
							<p class="font-18 max-width-600">
								Selamat datang di Sistem Informasi Akademik. Di sini Anda dapat mengelola semua kegiatan akademik Anda dengan mudah.
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-3 mb-30">
						<div class="card-box height-100-p widget-style1">
							<div class="d-flex flex-wrap align-items-center">
								<div class="progress-data">
									<div id="chart"></div>
								</div>
								<div class="widget-data">
									<div class="h4 mb-0">{{ $ipk }}</div>
									<div class="weight-600 font-14">IPK Kumulatif</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 mb-30">
						<div class="card-box height-100-p widget-style1">
							<div class="d-flex flex-wrap align-items-center">
								<div class="progress-data">
									<div id="chart2"></div>
								</div>
								<div class="widget-data">
									<div class="h4 mb-0">{{ $totalSks }}</div>
									<div class="weight-600 font-14">Total SKS Ditempuh</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 mb-30">
						<div class="card-box height-100-p widget-style1">
							<div class="d-flex flex-wrap align-items-center">
								<div class="progress-data">
									<div id="chart3"></div>
								</div>
								<div class="widget-data">
									<div class="h4 mb-0">{{ $mataKuliahSemesterIni }}</div>
									<div class="weight-600 font-14">Mata Kuliah Semester Ini</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 mb-30">
						<div class="card-box height-100-p widget-style1">
							<div class="d-flex flex-wrap align-items-center">
								<div class="progress-data">
									<div id="chart4"></div>
								</div>
								<div class="widget-data">
									<div class="h4 mb-0 {{ $statusClass }}">{{ $statusAkademik }}</div>
									<div class="weight-600 font-14">Status Akademik</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-8 mb-30">
						<div class="card-box height-100-p pd-20">
							<h2 class="h4 mb-20">Jadwal Kuliah Hari Ini ({{ ucfirst(\Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y')) }})</h2>
							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col">Waktu</th>
										<th scope="col">Mata Kuliah</th>
										<th scope="col">Ruang</th>
										<th scope="col">Dosen</th>
									</tr>
								</thead>
								<tbody>
									@forelse($jadwalHariIni as $jadwal)
									<tr>
										<td>{{ $jadwal->waktu }}</td>
										<td>{{ $jadwal->matakuliah->Nama_mk ?? 'N/A' }}</td>
										<td>{{ $jadwal->ruang->nama_ruang ?? 'N/A' }}</td>
										<td>
											@if($jadwal->matakuliah && $jadwal->matakuliah->pengampu->isNotEmpty())
												{{ $jadwal->matakuliah->pengampu->first()->dosen->Nama ?? 'N/A' }}
											@else
												N/A
											@endif
										</td>
									</tr>
									@empty
									<tr>
										<td colspan="4" class="text-center">Tidak ada jadwal hari ini</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-xl-4 mb-30">
						<div class="card-box height-100-p pd-20">
							<h2 class="h4 mb-20">Pengumuman Akademik 📢</h2>
							<div class="list-group">
								<a href="#" class="list-group-item list-group-item-action">
									<div class="d-flex w-100 justify-content-between">
										<h5 class="mb-1">Batas Akhir KRS</h5>
										<small>3 hari lagi</small>
									</div>
									<p class="mb-1">Pengisian KRS semester ganjil akan ditutup pada 30 Juli 2025.</p>
								</a>
								<a href="#" class="list-group-item list-group-item-action">
									<div class="d-flex w-100 justify-content-between">
										<h5 class="mb-1">Jadwal UTS</h5>
									</div>
									<p class="mb-1">Jadwal Ujian Tengah Semester telah dirilis. Silakan cek di menu Jadwal.</p>
								</a>
								<a href="#" class="list-group-item list-group-item-action">
									<div class="d-flex w-100 justify-content-between">
										<h5 class="mb-1">Beasiswa PPA</h5>
									</div>
									<p class="mb-1">Pendaftaran beasiswa Peningkatan Prestasi Akademik dibuka.</p>
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="footer-wrap pd-20 mb-20 card-box">
					Sistem Informasi Akademik - © 2025
				</div>
			</div>
		</div>

		<script src="{{ asset('bootstrap/vendors/scripts/core.js') }}"></script>
		<script src="{{ asset('bootstrap/vendors/scripts/script.min.js') }}"></script>
		<script src="{{ asset('bootstrap/vendors/scripts/process.js') }}"></script>
		<script src="{{ asset('bootstrap/vendors/scripts/layout-settings.js') }}"></script>
		<script src="{{ asset('bootstrap/src/plugins/apexcharts/apexcharts.min.js') }}"></script>
		<script src="{{ asset('bootstrap/vendors/scripts/dashboard.js') }}"></script>
	</body>
</html>