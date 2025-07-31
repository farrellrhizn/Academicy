<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Jadwal Kuliah - KRS</title>

		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="../../../bootstrap/vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="../../../bootstrap/vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="../../../bootstrap/vendors/images/favicon-16x16.png"
		/>

		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<link rel="stylesheet" type="text/css" href="../../../bootstrap/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="../../../bootstrap/vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../../../bootstrap/src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../../../bootstrap/src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
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
											<img src="../../../bootstrap/vendors/images/img.jpg" alt="" />
											<h3>Info Akademik</h3>
											<p>
												Jadwal kuliah sudah tersedia. Pastikan Anda hadir tepat waktu.
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
								                        @if($mahasiswa->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . $mahasiswa->profile_photo) }}" alt="Profile" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
								@else
									<i class="dw dw-user1"></i>
								@endif
							</span>
							<span class="user-name">{{ $mahasiswa->Nama }}</span>
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
					<img src="../../../bootstrap/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
					<img
						src="../../../bootstrap/vendors/images/deskapp-logo-white.svg"
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
							<a href="{{ route('mahasiswa.dashboard') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
							</a>
						</li>
						<li>
							<a href="{{ route('mahasiswa.krs.index') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-card-list"></span><span class="mtext">KRS</span>
							</a>
						</li>
						<li>
							<a href="{{ route('mahasiswa.jadwal.index') }}" class="dropdown-toggle no-arrow active">
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
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Jadwal Kuliah</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a>
										</li>
										<li class="breadcrumb-item">
											<a href="{{ route('mahasiswa.krs.index') }}">KRS</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Jadwal Kuliah
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="pd-20">
									<h4 class="text-blue h4">NIM: {{ $mahasiswa->NIM }}</h4>
									<p class="mb-0">Semester {{ $mahasiswa->Semester }}</p>
								</div>
							</div>
						</div>
					</div>

					@if($jadwalKuliah->count() > 0)
						<!-- Jadwal Hari ini -->
						<div class="row pb-10">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<div class="d-flex justify-content-between align-items-center mb-20">
										<h4 class="text-blue h4">Jadwal Hari Ini - {{ \Carbon\Carbon::now()->format('l, d F Y') }}</h4>
										<div>
											<a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-outline-primary btn-sm">
												<i class="fa fa-arrow-left"></i> Kembali ke KRS
											</a>
											<a href="{{ route('mahasiswa.krs.cetak') }}" class="btn btn-primary btn-sm">
												<i class="fa fa-print"></i> Cetak Jadwal
											</a>
										</div>
									</div>
									
									@php
										$hariIni = \Carbon\Carbon::now()->format('l');
										$jadwalHariIni = $jadwalKuliah->filter(function($jadwal) use ($hariIni) {
											return $jadwal['hari'] == $hariIni;
										});
									@endphp
									
									@if($jadwalHariIni->count() > 0)
										<div class="row">
											@foreach($jadwalHariIni as $jadwal)
												<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-20">
													<div class="card-box pd-20" style="border-left: 4px solid #007bff;">
														<h5 class="text-blue h5">{{ $jadwal['Nama_mk'] }}</h5>
														<p class="mb-0"><span class="badge badge-primary">{{ $jadwal['Kode_mk'] }}</span></p>
														<hr class="my-2">
														<div class="d-flex align-items-center mb-2">
															<i class="fa fa-clock-o text-primary mr-2"></i>
															<span>{{ $jadwal['waktu'] }}</span>
														</div>
														<div class="d-flex align-items-center mb-2">
															<i class="fa fa-map-marker text-danger mr-2"></i>
															<span>{{ $jadwal['nama_ruang'] }}</span>
														</div>
														<div class="d-flex align-items-center">
															<i class="fa fa-graduation-cap text-success mr-2"></i>
															<span>{{ $jadwal['sks'] }} SKS</span>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									@else
										<div class="alert alert-info" role="alert">
											<i class="fa fa-info-circle"></i> Tidak ada jadwal kuliah hari ini.
										</div>
									@endif
								</div>
							</div>
						</div>

						<!-- Jadwal Mingguan -->
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<h4 class="text-blue h4">Jadwal Mingguan</h4>
									<div class="pb-20">
										@php
											$hari = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
											$hariIndonesia = [
												'Monday' => 'Senin',
												'Tuesday' => 'Selasa', 
												'Wednesday' => 'Rabu',
												'Thursday' => 'Kamis',
												'Friday' => 'Jumat',
												'Saturday' => 'Sabtu',
												'Sunday' => 'Minggu'
											];
										@endphp
										
										@foreach($hari as $hariEng)
											@php
												$jadwalHari = $jadwalKuliah->filter(function($jadwal) use ($hariEng) {
													return $jadwal['hari'] == $hariEng;
												})->sortBy('waktu');
											@endphp
											
											@if($jadwalHari->count() > 0)
												<div class="mb-30">
													<div class="bg-primary text-white pd-10 border-radius-4 mb-20">
														<h5 class="text-white h5 mb-0">{{ $hariIndonesia[$hariEng] }}</h5>
													</div>
													<div class="row">
														@foreach($jadwalHari as $jadwal)
															<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-20">
																<div class="card-box pd-20" style="border-left: 4px solid #28a745;">
																	<div class="d-flex justify-content-between align-items-start">
																		<div class="flex-grow-1">
																			<h6 class="text-blue h6">{{ $jadwal['Nama_mk'] }}</h6>
																			<p class="mb-2"><span class="badge badge-primary">{{ $jadwal['Kode_mk'] }}</span></p>
																		</div>
																		<span class="badge badge-success">{{ $jadwal['sks'] }} SKS</span>
																	</div>
																	<hr class="my-2">
																	<div class="row">
																		<div class="col-6">
																			<small class="text-muted">
																				<i class="fa fa-clock-o"></i> {{ $jadwal['waktu'] }}
																			</small>
																		</div>
																		<div class="col-6">
																			<small class="text-muted">
																				<i class="fa fa-map-marker"></i> {{ $jadwal['nama_ruang'] }}
																			</small>
																		</div>
																	</div>
																</div>
															</div>
														@endforeach
													</div>
												</div>
											@endif
										@endforeach
									</div>
								</div>
							</div>
						</div>

						<!-- Ringkasan Jadwal -->
						<div class="row">
							<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<h4 class="text-blue h4">Statistik Jadwal</h4>
									<div class="pb-20">
										<div class="d-flex align-items-center justify-content-between mb-10">
											<span>Total Jadwal:</span>
											<strong class="text-primary">{{ $jadwalKuliah->count() }}</strong>
										</div>
										<div class="d-flex align-items-center justify-content-between mb-10">
											<span>Mata Kuliah:</span>
											<strong class="text-success">{{ $jadwalKuliah->count() }}</strong>
										</div>
										<div class="d-flex align-items-center justify-content-between mb-10">
											<span>Total SKS:</span>
											<strong class="text-info">{{ $jadwalKuliah->sum('sks') }}</strong>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<h4 class="text-blue h4">Quick Actions</h4>
									<div class="pb-20">
										<a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-outline-primary btn-block mb-10">
											<i class="fa fa-arrow-left"></i> Kembali ke KRS
										</a>
										<a href="{{ route('mahasiswa.krs.cetak') }}" class="btn btn-primary btn-block mb-10">
											<i class="fa fa-print"></i> Cetak Jadwal
										</a>
										<a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary btn-block">
											<i class="fa fa-home"></i> Dashboard
										</a>
									</div>
								</div>
							</div>
							
							<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<h4 class="text-blue h4">Catatan Penting</h4>
									<div class="pb-20">
										<div class="alert alert-success" role="alert">
											<i class="fa fa-check-circle"></i> <strong>Hadir Tepat Waktu</strong><br>
											Pastikan Anda hadir 15 menit sebelum kelas dimulai
										</div>
										<div class="alert alert-info" role="alert">
											<i class="fa fa-info-circle"></i> <strong>Perubahan Jadwal</strong><br>
											Pantau pengumuman untuk perubahan jadwal
										</div>
									</div>
								</div>
							</div>
						</div>
					@else
						<!-- Tidak ada jadwal -->
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20 text-center">
									<i class="fa fa-calendar-times-o text-muted" style="font-size: 5rem;"></i>
									<h4 class="text-blue h4 mt-20">Belum Ada Jadwal</h4>
									<p class="text-muted mb-20">Anda belum mengambil mata kuliah atau jadwal belum tersedia</p>
									<a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-primary">
										<i class="fa fa-plus"></i> Ambil Mata Kuliah
									</a>
								</div>
							</div>
						</div>
					@endif
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
		<script src="../../../bootstrap/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../../../bootstrap/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="../../../bootstrap/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="../../../bootstrap/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	</body>
</html>