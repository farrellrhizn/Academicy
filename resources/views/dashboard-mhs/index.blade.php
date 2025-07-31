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
									@forelse(array_slice($dynamicAnnouncements, 0, 3) as $announcement)
									<li>
										<a href="#">
											<img src="{{ asset('bootstrap/vendors/images/img.jpg') }}" alt="" />
											<h3>{{ $announcement['title'] }}</h3>
											<p>{{ $announcement['message'] }}</p>
										</a>
									</li>
									@empty
									<li>
										<a href="#">
											<img src="{{ asset('bootstrap/vendors/images/img.jpg') }}" alt="" />
											<h3>Sistem Akademik</h3>
											<p>Tidak ada notifikasi baru saat ini.</p>
										</a>
									</li>
									@endforelse
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
									<img src="{{ asset('storage/profile_photos/' . $userData->profile_photo) }}" alt="Profile">
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
				<!-- Alert untuk error atau sukses -->
				@if(session('error'))
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Error!</strong> {{ session('error') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				@endif
				
				@if(session('success'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong>Sukses!</strong> {{ session('success') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				@endif

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
							<!-- Informasi Umum Mahasiswa -->
							<div class="row mt-3">
								<div class="col-md-6">
									<small class="text-muted">NIM:</small> <strong>{{ $userData->NIM ?? '-' }}</strong><br>
									<small class="text-muted">Semester:</small> <strong>{{ $userData->Semester ?? '-' }}</strong><br>
									<small class="text-muted">Golongan:</small> <strong>{{ $userData->golongan->nama_Gol ?? 'Tidak Diketahui' }}</strong>
								</div>
								<div class="col-md-6">
									<small class="text-muted">Total SKS:</small> <strong>{{ $totalSks ?? 0 }}</strong><br>
									<small class="text-muted">Mata Kuliah:</small> <strong>{{ $totalMataKuliah ?? 0 }}</strong><br>
									<small class="text-muted">Status:</small> <strong class="{{ $statusClass ?? 'text-muted' }}">{{ $statusAkademik ?? 'Aktif' }}</strong>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-8 mb-30">
						<div class="card-box height-100-p pd-20">
							<h2 class="h4 mb-20">
								<i class="icon-copy dw dw-calendar1"></i> 
								Jadwal Kuliah Hari Ini 
								<small class="text-muted">({{ ucfirst(\Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y')) }})</small>
							</h2>
							@if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<thead class="bg-light">
											<tr>
												<th scope="col"><i class="icon-copy dw dw-clock"></i> Waktu</th>
												<th scope="col"><i class="icon-copy dw dw-book"></i> Mata Kuliah</th>
												<th scope="col"><i class="icon-copy dw dw-home"></i> Ruang</th>
												<th scope="col"><i class="icon-copy dw dw-user1"></i> Dosen</th>
											</tr>
										</thead>
										<tbody>
											@foreach($jadwalHariIni as $jadwal)
											<tr>
												<td><span class="badge badge-primary">{{ $jadwal->waktu ?? 'N/A' }}</span></td>
												<td><strong>{{ $jadwal->matakuliah->Nama_mk ?? 'N/A' }}</strong></td>
												<td>{{ $jadwal->ruang->nama_ruang ?? 'N/A' }}</td>
												<td>
													@if($jadwal->matakuliah && $jadwal->matakuliah->pengampu && $jadwal->matakuliah->pengampu->isNotEmpty())
														{{ $jadwal->matakuliah->pengampu->first()->dosen->Nama ?? 'N/A' }}
													@else
														N/A
													@endif
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<div class="text-center py-5">
									<i class="icon-copy dw dw-calendar1 fa-3x text-muted mb-3"></i>
									<h5 class="text-muted">Tidak ada jadwal kuliah hari ini</h5>
									<p class="text-muted">Silakan periksa jadwal kuliah Anda di menu <a href="{{ route('mahasiswa.jadwal.index') }}" class="text-primary">Jadwal Kuliah</a></p>
								</div>
							@endif
						</div>
					</div>
					<div class="col-xl-4 mb-30">
						<div class="card-box height-100-p pd-20">
							<h2 class="h4 mb-20">
								<i class="icon-copy dw dw-notification"></i> 
								Informasi Akademik
							</h2>
							
							<!-- Informasi Umum Mahasiswa -->
							<div class="alert alert-info mb-3">
								<h6 class="alert-heading"><i class="icon-copy dw dw-user1"></i> Info Mahasiswa</h6>
								<small class="d-block"><strong>NIM:</strong> {{ $userData->NIM ?? '-' }}</small>
								<small class="d-block"><strong>Semester:</strong> {{ $userData->Semester ?? '-' }}</small>
								<small class="d-block"><strong>Status:</strong> <span class="{{ $statusClass ?? 'text-muted' }}">{{ $statusAkademik ?? 'Aktif' }}</span></small>
								<small class="d-block"><strong>Total SKS:</strong> {{ $totalSks ?? 0 }}</small>
							</div>

							<!-- Pengumuman Dinamis -->
							<div class="list-group">
								@if(isset($dynamicAnnouncements) && count($dynamicAnnouncements) > 0)
									@foreach($dynamicAnnouncements as $announcement)
									<div class="list-group-item">
										<div class="d-flex w-100 justify-content-between">
											<h6 class="mb-1">{{ $announcement['title'] ?? 'Pengumuman' }}</h6>
											<small class="badge badge-{{ $announcement['type'] == 'warning' ? 'warning' : ($announcement['type'] == 'success' ? 'success' : 'info') }}">
												{{ $announcement['time'] ?? 'Info' }}
											</small>
										</div>
										<p class="mb-1 small">{{ $announcement['message'] ?? 'Tidak ada pesan' }}</p>
									</div>
									@endforeach
								@else
								<div class="list-group-item text-center">
									<i class="icon-copy dw dw-notification fa-2x text-muted mb-2"></i>
									<p class="mb-0 text-muted">Tidak ada pengumuman saat ini.</p>
								</div>
								@endif
							</div>

							<!-- Quick Links -->
							<div class="mt-3">
								<h6><i class="icon-copy dw dw-link"></i> Menu Cepat</h6>
								<div class="btn-group-vertical w-100" role="group">
									<a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-outline-primary btn-sm">
										<i class="icon-copy dw dw-card-list"></i> Kelola KRS
									</a>
									<a href="{{ route('mahasiswa.jadwal.index') }}" class="btn btn-outline-success btn-sm">
										<i class="icon-copy dw dw-calendar-week"></i> Lihat Jadwal
									</a>
									<a href="{{ route('mahasiswa.presensi.riwayat') }}" class="btn btn-outline-info btn-sm">
										<i class="icon-copy dw dw-check2-square"></i> Riwayat Presensi
									</a>
								</div>
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