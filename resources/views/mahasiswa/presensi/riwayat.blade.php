<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Riwayat Presensi - Mahasiswa</title>

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
											<h3>Info Presensi</h3>
											<p>
												Pantau kehadiran Anda secara rutin untuk memenuhi syarat mengikuti ujian.
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
									<img src="{{ asset('storage/profile_photos/' . $mahasiswa->profile_photo) }}" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
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
							<a href="{{ route('mahasiswa.jadwal.index') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-calendar-week"></span><span class="mtext">Jadwal Kuliah</span>
							</a>
						</li>
						<li>
							<a href="{{ route('mahasiswa.presensi.riwayat') }}" class="dropdown-toggle no-arrow active">
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
									<h4>Riwayat Presensi</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Riwayat Presensi
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="pd-20">
									<h4 class="text-blue h4">{{ $mahasiswa->Nama }}</h4>
									<p class="mb-0">NIM: {{ $mahasiswa->NIM }} | Semester {{ $mahasiswa->Semester }}</p>
								</div>
							</div>
						</div>
					</div>

					<!-- Filter Mata Kuliah -->
					<div class="row pb-20">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
							<div class="card-box pd-20">
								<h4 class="text-blue h4 mb-20">Filter Mata Kuliah</h4>
								<form method="GET" action="{{ route('mahasiswa.presensi.riwayat') }}">
									<div class="row align-items-end">
										<div class="col-md-8">
											<div class="form-group">
												<label for="kode_mk">Pilih Mata Kuliah</label>
												<select class="form-control" id="kode_mk" name="kode_mk" required>
													<option value="">-- Pilih Mata Kuliah --</option>
													@foreach($matakuliahDiambil as $mk)
														<option value="{{ $mk->Kode_mk }}" 
															{{ $selectedKodeMk == $mk->Kode_mk ? 'selected' : '' }}>
															{{ $mk->Kode_mk }} - {{ $mk->Nama_mk }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<button type="submit" class="btn btn-primary btn-block">
													<i class="fa fa-search"></i> Tampilkan Riwayat
												</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

					@if($selectedKodeMk && $riwayatPresensi->count() > 0)
						<!-- Statistik Presensi -->
						<div class="row pb-20">
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
								<div class="card-box pd-20 text-center">
									<div class="widget-style3">
										<div class="widget-data">
											<div class="widget-data-icon">
												<i class="icon-copy dw dw-calendar1 text-primary"></i>
											</div>
											<div class="widget-data-info">
												<h3 class="text-primary">{{ $statistik['total_pertemuan'] }}</h3>
												<p>Total Pertemuan</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
								<div class="card-box pd-20 text-center">
									<div class="widget-style3">
										<div class="widget-data">
											<div class="widget-data-icon">
												<i class="icon-copy dw dw-check text-success"></i>
											</div>
											<div class="widget-data-info">
												<h3 class="text-success">{{ $statistik['hadir'] }}</h3>
												<p>Hadir</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
								<div class="card-box pd-20 text-center">
									<div class="widget-style3">
										<div class="widget-data">
											<div class="widget-data-icon">
												<i class="icon-copy dw dw-warning text-warning"></i>
											</div>
											<div class="widget-data-info">
												<h3 class="text-warning">{{ $statistik['izin'] }}</h3>
												<p>Izin</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
								<div class="card-box pd-20 text-center">
									<div class="widget-style3">
										<div class="widget-data">
											<div class="widget-data-icon">
												<i class="icon-copy dw dw-close text-danger"></i>
											</div>
											<div class="widget-data-info">
												<h3 class="text-danger">{{ $statistik['alpa'] }}</h3>
												<p>Alpa</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Persentase Kehadiran -->
						<div class="row pb-20">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<div class="d-flex align-items-center justify-content-between mb-20">
										<h4 class="text-blue h4">Persentase Kehadiran</h4>
										<div class="badge 
											{{ $statistik['persentase_kehadiran'] >= 75 ? 'badge-success' : ($statistik['persentase_kehadiran'] >= 50 ? 'badge-warning' : 'badge-danger') }} 
											font-18 pd-10">
											{{ $statistik['persentase_kehadiran'] }}%
										</div>
									</div>
									<div class="progress mb-20" style="height: 25px;">
										<div class="progress-bar 
											{{ $statistik['persentase_kehadiran'] >= 75 ? 'bg-success' : ($statistik['persentase_kehadiran'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
											role="progressbar" 
											style="width: {{ $statistik['persentase_kehadiran'] }}%"
											aria-valuenow="{{ $statistik['persentase_kehadiran'] }}" 
											aria-valuemin="0" 
											aria-valuemax="100">
											{{ $statistik['persentase_kehadiran'] }}%
										</div>
									</div>
									@if($statistik['persentase_kehadiran'] < 75)
										<div class="alert alert-warning" role="alert">
											<i class="fa fa-warning"></i> <strong>Perhatian!</strong> 
											Persentase kehadiran Anda masih di bawah 75%. Pastikan untuk hadir di pertemuan selanjutnya agar memenuhi syarat mengikuti ujian.
										</div>
									@else
										<div class="alert alert-success" role="alert">
											<i class="fa fa-check-circle"></i> <strong>Baik!</strong> 
											Persentase kehadiran Anda sudah memenuhi syarat minimum untuk mengikuti ujian.
										</div>
									@endif
								</div>
							</div>
						</div>

						<!-- Tabel Riwayat Presensi -->
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20">
									<div class="clearfix mb-20">
										<div class="pull-left">
											<h4 class="text-blue h4">Detail Riwayat Presensi</h4>
											<p class="mb-30">Mata Kuliah: <strong>{{ $riwayatPresensi->first()->matakuliah->Nama_mk ?? '' }}</strong></p>
										</div>
									</div>
									<table class="table stripe hover nowrap" id="presensiTable">
										<thead>
											<tr>
												<th class="table-plus">No</th>
												<th>Hari</th>
												<th>Tanggal</th>
												<th>Status Kehadiran</th>
												<th>Keterangan</th>
											</tr>
										</thead>
										<tbody>
											@foreach($riwayatPresensi as $index => $presensi)
												<tr>
													<td class="table-plus">{{ $index + 1 }}</td>
													<td>
														@php
															$hariIndonesia = [
																'Monday' => 'Senin',
																'Tuesday' => 'Selasa', 
																'Wednesday' => 'Rabu',
																'Thursday' => 'Kamis',
																'Friday' => 'Jumat',
																'Saturday' => 'Sabtu',
																'Sunday' => 'Minggu'
															];
															$hariEng = \Carbon\Carbon::parse($presensi->tanggal)->format('l');
														@endphp
														{{ $hariIndonesia[$hariEng] ?? $presensi->hari }}
													</td>
													<td>{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d F Y') }}</td>
													<td>
														@if($presensi->status_kehadiran == 'Hadir')
															<span class="badge badge-success">{{ $presensi->status_kehadiran }}</span>
														@elseif($presensi->status_kehadiran == 'Izin')
															<span class="badge badge-warning">{{ $presensi->status_kehadiran }}</span>
														@elseif($presensi->status_kehadiran == 'Alpa')
															<span class="badge badge-danger">{{ $presensi->status_kehadiran }}</span>
														@else
															<span class="badge badge-secondary">{{ $presensi->status_kehadiran }}</span>
														@endif
													</td>
													<td>
														@if($presensi->status_kehadiran == 'Hadir')
															<i class="fa fa-check-circle text-success"></i> Mengikuti perkuliahan
														@elseif($presensi->status_kehadiran == 'Izin')
															<i class="fa fa-info-circle text-warning"></i> Tidak hadir dengan keterangan
														@elseif($presensi->status_kehadiran == 'Alpa')
															<i class="fa fa-times-circle text-danger"></i> Tidak hadir tanpa keterangan
														@else
															<i class="fa fa-question-circle text-secondary"></i> Status belum ditentukan
														@endif
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					@elseif($selectedKodeMk && $riwayatPresensi->count() == 0)
						<!-- Tidak ada data presensi -->
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20 text-center">
									<i class="fa fa-calendar-times-o text-muted" style="font-size: 5rem;"></i>
									<h4 class="text-blue h4 mt-20">Belum Ada Data Presensi</h4>
									<p class="text-muted mb-20">Belum ada data presensi untuk mata kuliah yang dipilih</p>
									<div class="alert alert-info" role="alert">
										<i class="fa fa-info-circle"></i> Data presensi akan muncul setelah dosen melakukan pencatatan kehadiran.
									</div>
								</div>
							</div>
						</div>
					@else
						<!-- Belum memilih mata kuliah -->
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
								<div class="card-box pd-20 text-center">
									<i class="fa fa-search text-muted" style="font-size: 5rem;"></i>
									<h4 class="text-blue h4 mt-20">Pilih Mata Kuliah</h4>
									<p class="text-muted mb-20">Silakan pilih mata kuliah untuk melihat riwayat presensi Anda</p>
									@if($matakuliahDiambil->count() == 0)
										<div class="alert alert-warning" role="alert">
											<i class="fa fa-warning"></i> Anda belum mengambil mata kuliah pada semester ini. 
											<a href="{{ route('mahasiswa.krs.index') }}" class="alert-link">Silakan ambil mata kuliah terlebih dahulu.</a>
										</div>
									@endif
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
		
		<script>
			$(document).ready(function() {
				$('#presensiTable').DataTable({
					responsive: true,
					ordering: false,
					language: {
						"lengthMenu": "Tampilkan _MENU_ data per halaman",
						"zeroRecords": "Data tidak ditemukan",
						"info": "Menampilkan halaman _PAGE_ dari _PAGES_",
						"infoEmpty": "Tidak ada data yang tersedia",
						"infoFiltered": "(difilter dari _MAX_ total data)",
						"search": "Cari:",
						"paginate": {
							"first": "Pertama",
							"last": "Terakhir",
							"next": "Selanjutnya",
							"previous": "Sebelumnya"
						}
					}
				});
			});
		</script>
	</body>
</html>