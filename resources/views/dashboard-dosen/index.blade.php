<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<title>Dashboard Dosen</title>

	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('bootstrap/vendors/images/apple-touch-icon.png') }}" />
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('bootstrap/vendors/images/favicon-32x32.png') }}" />
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('bootstrap/vendors/images/favicon-16x16.png') }}" />

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/profile-photo.css') }}" />

</head>

<body>
	<div class="pre-loader">
		<div class="pre-loader-box">
			<div class="loader-logo">
				<span style="font-size: 40px; font-family: 'Poppins', sans-serif; font-weight: bold;">Academicy</span>
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
							@if($userData->profile_photo)
								<img src="{{ asset('storage/profile_photos/' . $userData->profile_photo) }}" alt="Profile"
									class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
							@else
								<i class="dw dw-user1"></i>
							@endif
						</span>
						<span class="user-name">{{ $userData->Nama ?? 'Dosen' }}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="{{ route('dosen.profile.edit') }}"><i class="dw dw-user1"></i>
							Profil</a>
						<form method="POST" action="{{ route('logout') }}" style="display: inline;">
							@csrf
							<button type="submit" class="dropdown-item"
								style="border: none; background: none; width: 100%; text-align: left;">
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
			<a href="{{ route('dosen.dashboard') }}">
					<span style="font-size: 36px; font-family: 'Poppins', sans-serif; font-weight: bold;">Academicy</span>
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
							<span class="micon bi bi-check-circle "></span><span class="mtext">Presensi</span>
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
						<img src="{{ asset('bootstrap/vendors/images/banner-img.png') }}" alt="" />
					</div>
					<div class="col-md-8">
						<h4 class="font-20 weight-500 mb-10 text-capitalize">
							Selamat Datang Kembali,
							<div class="weight-600 font-30 text-blue">{{ $userData->Nama ?? 'Dosen' }}!</div>
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
								<div class="weight-700 font-24 text-dark">{{ $totalMataKuliahDiampu }}</div>
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
								<div class="weight-700 font-24 text-dark">{{ $totalSks }} SKS</div>
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
								<div class="weight-700 font-24 text-dark">{{ $totalMahasiswa }}</div>
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
								<div class="weight-700 font-24 text-dark">{{ count($jadwalHariIni) }} Kelas</div>
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
					<h4 class="text-blue h4">Jadwal Mengajar Hari Ini
						({{ ucfirst(\Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y')) }}) 🗓️</h4>
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
							@forelse($jadwalHariIni as $jadwal)
								<tr>
									<td>{{ $jadwal->waktu }}</td>
									<td>{{ $jadwal->matakuliah->Nama_mk ?? 'N/A' }}</td>
									<td>{{ $jadwal->golongan->nama_Gol ?? 'N/A' }}</td>
									<td>{{ $jadwal->ruang->nama_ruang ?? 'N/A' }}</td>
									<td>
										<a href="{{ route('dosen.presensi.simple') }}" class="btn btn-primary btn-sm">Input
											Presensi</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="5" class="text-center">Tidak ada jadwal hari ini</td>
								</tr>
							@endforelse
						</tbody>
					</table>
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
</body>

</html>