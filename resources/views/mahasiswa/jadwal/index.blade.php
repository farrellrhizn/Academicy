<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Jadwal Kuliah - Mahasiswa</title>

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
											<h3>Info Jadwal</h3>
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
									<h4>Jadwal Kuliah Mingguan</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Jadwal Kuliah
										</li>
									</ol>
								</nav>
								<p class="text-muted mb-0">
									<i class="fa fa-info-circle"></i> Jadwal berdasarkan hari dalam minggu, bukan tanggal spesifik
								</p>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="pd-20">
									<h4 class="text-blue h4">{{ $mahasiswa->Nama }}</h4>
									<p class="mb-0">NIM: {{ $mahasiswa->NIM }} | Semester {{ $mahasiswa->Semester }}</p>
								</div>
							</div>
						</div>
					</div>

					@if($jadwalKuliah->count() > 0)
						<!-- Jadwal Hari Ini -->
						<div class="row pb-20">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<div class="d-flex justify-content-between align-items-center mb-20">
										<h4 class="text-blue h4">Jadwal Hari {{ $hariIndonesia[\Carbon\Carbon::now()->format('l')] ?? \Carbon\Carbon::now()->format('l') }} - {{ \Carbon\Carbon::now()->format('d F Y') }}</h4>
										<div>
											<a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-outline-primary btn-sm">
												<i class="fa fa-arrow-left"></i> Kembali ke KRS
											</a>
										</div>
									</div>
									<div class="alert alert-info alert-dismissible fade show" role="alert">
										<i class="fa fa-calendar"></i> 
										<strong>Jadwal Mingguan:</strong> Sistem menampilkan jadwal berdasarkan hari dalam seminggu. 
										Jika ada mata kuliah yang dijadwalkan pada hari {{ $hariIndonesia[\Carbon\Carbon::now()->format('l')] ?? \Carbon\Carbon::now()->format('l') }}, 
										maka akan ditampilkan di bawah ini tanpa mempertimbangkan tanggal spesifik.
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									
									@php
										$hariIni = \Carbon\Carbon::now()->format('l'); // Format: Monday, Tuesday, etc.
										$jadwalHariIni = $jadwalKuliah->filter(function($jadwal) use ($hariIni) {
											return strtolower($jadwal['hari']) == strtolower($hariIni);
										});
									@endphp
									
									@if($jadwalHariIni->count() > 0)
										<div class="table-responsive">
											<table class="table table-striped">
												<thead class="table-dark">
													<tr>
														<th>Waktu</th>
														<th>Mata Kuliah</th>
														<th>Kode MK</th>
														<th>Ruang</th>
														<th>SKS</th>
														<th>Dosen</th>
													</tr>
												</thead>
												<tbody>
													@foreach($jadwalHariIni->sortBy('waktu') as $jadwal)
														<tr>
															<td><span class="badge badge-primary">{{ $jadwal['waktu'] }}</span></td>
															<td><strong>{{ $jadwal['Nama_mk'] }}</strong></td>
															<td>{{ $jadwal['Kode_mk'] }}</td>
															<td><i class="fa fa-map-marker text-danger"></i> {{ $jadwal['nama_ruang'] }}</td>
															<td><span class="badge badge-success">{{ $jadwal['sks'] }} SKS</span></td>
															<td>{{ $jadwal['nama_Gol'] ?? 'TBA' }}</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									@else
										<div class="alert alert-info" role="alert">
											<i class="fa fa-info-circle"></i> Tidak ada jadwal kuliah pada hari {{ $hariIndonesia[\Carbon\Carbon::now()->format('l')] ?? \Carbon\Carbon::now()->format('l') }}. Anda bisa beristirahat! 😊
										</div>
									@endif
								</div>
							</div>
						</div>

						<!-- Jadwal Mingguan -->
						<div class="row pb-20">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<h4 class="text-blue h4 mb-20">Jadwal Mingguan Lengkap</h4>
									
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
										$warnaHari = [
											'Monday' => 'primary',
											'Tuesday' => 'success', 
											'Wednesday' => 'warning',
											'Thursday' => 'info',
											'Friday' => 'danger',
											'Saturday' => 'dark',
											'Sunday' => 'secondary'
										];
									@endphp
									
									<!-- Navigasi Tab -->
									<ul class="nav nav-tabs" id="jadwalTabs" role="tablist">
										@foreach($hari as $index => $hariEng)
											@php
												$jadwalHari = $jadwalKuliah->filter(function($jadwal) use ($hariEng) {
													return strtolower($jadwal['hari']) == strtolower($hariEng);
												});
											@endphp
											@if($jadwalHari->count() > 0)
												<li class="nav-item">
													<a class="nav-link {{ $index == 0 ? 'active' : '' }}" 
													   id="{{ strtolower($hariEng) }}-tab" 
													   data-toggle="tab" 
													   href="#{{ strtolower($hariEng) }}" 
													   role="tab" 
													   aria-controls="{{ strtolower($hariEng) }}"
													   aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
														<i class="fa fa-calendar"></i> {{ $hariIndonesia[$hariEng] }}
														<span class="badge badge-{{ $warnaHari[$hariEng] }} ml-2">{{ $jadwalHari->count() }}</span>
													</a>
												</li>
											@endif
										@endforeach
									</ul>
									
									<!-- Konten Tab -->
									<div class="tab-content mt-3" id="jadwalTabContent">
										@foreach($hari as $index => $hariEng)
											@php
												$jadwalHari = $jadwalKuliah->filter(function($jadwal) use ($hariEng) {
													return strtolower($jadwal['hari']) == strtolower($hariEng);
												})->sortBy('waktu');
											@endphp
											@if($jadwalHari->count() > 0)
												<div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" 
													 id="{{ strtolower($hariEng) }}" 
													 role="tabpanel" 
													 aria-labelledby="{{ strtolower($hariEng) }}-tab">
													<div class="table-responsive">
														<table class="table table-hover">
															<thead class="bg-{{ $warnaHari[$hariEng] }} text-white">
																<tr>
																	<th>Waktu</th>
																	<th>Mata Kuliah</th>
																	<th>Kode MK</th>
																	<th>Ruang</th>
																	<th>SKS</th>
																	<th>Golongan</th>
																</tr>
															</thead>
															<tbody>
																@foreach($jadwalHari as $jadwal)
																	<tr>
																		<td>
																			<span class="badge badge-{{ $warnaHari[$hariEng] }}">
																				{{ $jadwal['waktu'] }}
																			</span>
																		</td>
																		<td>
																			<strong>{{ $jadwal['Nama_mk'] }}</strong>
																		</td>
																		<td>{{ $jadwal['Kode_mk'] }}</td>
																		<td>
																			<i class="fa fa-map-marker text-danger"></i> 
																			{{ $jadwal['nama_ruang'] }}
																		</td>
																		<td>
																			<span class="badge badge-success">{{ $jadwal['sks'] }} SKS</span>
																		</td>
																		<td>{{ $jadwal['nama_Gol'] ?? 'TBA' }}</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													</div>
												</div>
											@endif
										@endforeach
									</div>
								</div>
							</div>
						</div>

						<!-- Ringkasan dan Quick Actions -->
						<div class="row">
							<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<h4 class="text-blue h4 mb-20">Ringkasan Jadwal</h4>
									<div class="row">
										<div class="col-md-3 col-sm-6 mb-20">
											<div class="bg-primary text-white pd-20 border-radius-5 text-center">
												<h5 class="text-white mb-0">{{ $jadwalKuliah->count() }}</h5>
												<small>Total Mata Kuliah</small>
											</div>
										</div>
										<div class="col-md-3 col-sm-6 mb-20">
											<div class="bg-success text-white pd-20 border-radius-5 text-center">
												<h5 class="text-white mb-0">{{ $jadwalKuliah->sum('sks') }}</h5>
												<small>Total SKS</small>
											</div>
										</div>
										<div class="col-md-3 col-sm-6 mb-20">
											<div class="bg-warning text-white pd-20 border-radius-5 text-center">
												<h5 class="text-white mb-0">{{ $jadwalKuliah->unique('hari')->count() }}</h5>
												<small>Hari Kuliah</small>
											</div>
										</div>
										<div class="col-md-3 col-sm-6 mb-20">
											<div class="bg-info text-white pd-20 border-radius-5 text-center">
												<h5 class="text-white mb-0">{{ \Carbon\Carbon::now()->format('Y') }}</h5>
												<small>Tahun Akademik</small>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<h4 class="text-blue h4 mb-20">Quick Actions</h4>
									<div class="list-group">
										<a href="{{ route('mahasiswa.krs.index') }}" class="list-group-item list-group-item-action">
											<i class="fa fa-arrow-left text-primary"></i> Kembali ke KRS
										</a>
										<a href="{{ route('mahasiswa.presensi.riwayat') }}" class="list-group-item list-group-item-action">
											<i class="fa fa-check-square text-success"></i> Lihat Riwayat Presensi
										</a>
										<a href="{{ route('mahasiswa.dashboard') }}" class="list-group-item list-group-item-action">
											<i class="fa fa-home text-info"></i> Dashboard
										</a>
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
									<div class="alert alert-warning" role="alert">
										<i class="fa fa-warning"></i> Silakan ambil mata kuliah terlebih dahulu di menu KRS.
									</div>
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