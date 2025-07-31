<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Cetak KRS - {{ $mahasiswa->NIM }}</title>

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
		<link rel="stylesheet" type="text/css" href="../../../bootstrap/vendors/styles/style.css" />
		
		<style>
			@media print {
				.no-print {
					display: none !important;
				}
				.header, .left-side-bar, .mobile-menu-overlay, .main-container .pd-ltr-20 .footer-wrap {
					display: none !important;
				}
				.main-container {
					margin-left: 0 !important;
					padding: 0 !important;
				}
				body {
					background: white !important;
					font-size: 12px;
				}
				.table-krs {
					border-collapse: collapse !important;
				}
				.table-krs, .table-krs th, .table-krs td {
					border: 1px solid #000 !important;
				}
				.card-box {
					border: none !important;
					box-shadow: none !important;
				}
			}
			
			.kop-surat {
				text-align: center;
				border-bottom: 3px solid #000;
				padding-bottom: 20px;
				margin-bottom: 30px;
			}
			
			.info-mahasiswa {
				margin-bottom: 30px;
			}
			
			.table-krs {
				border-collapse: collapse;
				width: 100%;
			}
			
			.table-krs th, .table-krs td {
				border: 1px solid #000;
				padding: 8px;
				text-align: left;
			}
			
			.table-krs th {
				background-color: #f8f9fa;
				font-weight: bold;
			}
			
			.ttd-section {
				margin-top: 50px;
			}
			
			.ttd-box {
				width: 200px;
				height: 100px;
				border: 1px solid #000;
				display: inline-block;
				text-align: center;
				vertical-align: top;
				margin: 0 20px;
			}
		</style>
	</head>
	<body>
		<div class="pre-loader no-print">
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

		<div class="header no-print">
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
												KRS telah dicetak untuk semester ini.
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
									<img src="{{ asset('storage/profile_photos/' . $mahasiswa->profile_photo) }}" alt="Profile">
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
		
		<div class="left-side-bar no-print">
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
							<a href="{{ route('mahasiswa.krs.index') }}" class="dropdown-toggle no-arrow active">
								<span class="micon bi bi-card-list"></span><span class="mtext">KRS</span>
							</a>
						</li>
						<li>
							<a href="{{ route('mahasiswa.krs.jadwal') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-calendar-week"></span><span class="mtext">Jadwal Kuliah</span>
							</a>
						</li>
						<li>
							<a href="#" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-check2-square"></span><span class="mtext">Riwayat Presensi</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="mobile-menu-overlay no-print"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<!-- Page Header -->
					<div class="page-header no-print">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Cetak KRS</h4>
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
											Cetak KRS
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="pd-20">
									<button onclick="window.print()" class="btn btn-primary">
										<i class="fa fa-print"></i> Print KRS
									</button>
									<a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-outline-secondary">
										<i class="fa fa-arrow-left"></i> Kembali
									</a>
								</div>
							</div>
						</div>
					</div>

					<!-- KRS Document -->
					<div class="row">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
							<div class="card-box pd-20">
								<!-- Kop Surat -->
								<div class="kop-surat">
									<h3 style="margin: 0; font-weight: bold;">UNIVERSITAS CONTOH</h3>
									<h4 style="margin: 5px 0; font-weight: bold;">FAKULTAS TEKNOLOGI INFORMASI</h4>
									<h5 style="margin: 5px 0;">PROGRAM STUDI SISTEM INFORMASI</h5>
									<p style="margin: 5px 0; font-size: 14px;">
										Jl. Contoh No. 123, Kota Contoh, Provinsi Contoh 12345<br>
										Telp: (021) 123-4567 | Email: info@universitas-contoh.ac.id
									</p>
								</div>

								<!-- Judul Dokumen -->
								<div class="text-center mb-4">
									<h3 style="text-decoration: underline; font-weight: bold;">
										KARTU RENCANA STUDI (KRS)
									</h3>
									<p style="margin: 5px 0;">SEMESTER {{ $mahasiswa->Semester }} TAHUN AKADEMIK {{ date('Y') }}/{{ date('Y') + 1 }}</p>
								</div>

								<!-- Informasi Mahasiswa -->
								<div class="info-mahasiswa">
									<div class="row">
										<div class="col-md-6">
											<table style="width: 100%;">
												<tr>
													<td style="width: 30%; font-weight: bold;">NIM</td>
													<td style="width: 5%;">:</td>
													<td>{{ $mahasiswa->NIM }}</td>
												</tr>
												<tr>
													<td style="font-weight: bold;">Nama</td>
													<td>:</td>
													<td>{{ $mahasiswa->Nama }}</td>
												</tr>
												<tr>
													<td style="font-weight: bold;">Program Studi</td>
													<td>:</td>
													<td>Sistem Informasi</td>
												</tr>
											</table>
										</div>
										<div class="col-md-6">
											<table style="width: 100%;">
												<tr>
													<td style="width: 30%; font-weight: bold;">Semester</td>
													<td style="width: 5%;">:</td>
													<td>{{ $mahasiswa->Semester }}</td>
												</tr>
												<tr>
													<td style="font-weight: bold;">Golongan</td>
													<td>:</td>
													<td>{{ $mahasiswa->golongan->nama_Gol ?? 'N/A' }}</td>
												</tr>
												<tr>
													<td style="font-weight: bold;">Tanggal Cetak</td>
													<td>:</td>
													<td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
												</tr>
											</table>
										</div>
									</div>
								</div>

								<!-- Tabel KRS -->
								@if($krsData->count() > 0)
									<table class="table-krs">
										<thead>
											<tr>
												<th style="text-align: center; width: 5%;">No</th>
												<th style="text-align: center; width: 15%;">Kode MK</th>
												<th style="text-align: center; width: 40%;">Nama Mata Kuliah</th>
												<th style="text-align: center; width: 8%;">SKS</th>
												<th style="text-align: center; width: 15%;">Hari</th>
												<th style="text-align: center; width: 12%;">Waktu</th>
												<th style="text-align: center; width: 15%;">Ruang</th>
											</tr>
										</thead>
										<tbody>
											@foreach($krsData as $index => $krs)
												<tr>
													<td style="text-align: center;">{{ $index + 1 }}</td>
													<td>{{ $krs->Kode_mk }}</td>
													<td>{{ $krs->matakuliah->Nama_mk }}</td>
													<td style="text-align: center;">{{ $krs->matakuliah->sks }}</td>
													<td>
														@if($krs->matakuliah->jadwalAkademik->isNotEmpty())
															@foreach($krs->matakuliah->jadwalAkademik as $jadwal)
																@if($jadwal->id_Gol == $mahasiswa->id_Gol)
																	{{ $jadwal->hari }}
																	@break
																@endif
															@endforeach
														@else
															-
														@endif
													</td>
													<td>
														@if($krs->matakuliah->jadwalAkademik->isNotEmpty())
															@foreach($krs->matakuliah->jadwalAkademik as $jadwal)
																@if($jadwal->id_Gol == $mahasiswa->id_Gol)
																	{{ $jadwal->waktu }}
																	@break
																@endif
															@endforeach
														@else
															-
														@endif
													</td>
													<td>
														@if($krs->matakuliah->jadwalAkademik->isNotEmpty())
															@foreach($krs->matakuliah->jadwalAkademik as $jadwal)
																@if($jadwal->id_Gol == $mahasiswa->id_Gol)
																	{{ $jadwal->ruang->nama_ruang ?? 'TBA' }}
																	@break
																@endif
															@endforeach
														@else
															-
														@endif
													</td>
												</tr>
											@endforeach
										</tbody>
										<tfoot>
											<tr style="background-color: #f8f9fa;">
												<td colspan="3" style="text-align: center; font-weight: bold;">TOTAL SKS</td>
												<td style="text-align: center; font-weight: bold;">{{ $krsData->sum(fn($krs) => $krs->matakuliah->sks) }}</td>
												<td colspan="3"></td>
											</tr>
										</tfoot>
									</table>
								@else
									<div class="text-center py-4">
										<p>Belum ada mata kuliah yang diambil dalam KRS ini.</p>
									</div>
								@endif

								<!-- Catatan dan Syarat -->
								<div style="margin-top: 30px;">
									<h5 style="font-weight: bold; margin-bottom: 10px;">CATATAN:</h5>
									<ol style="font-size: 12px; line-height: 1.6;">
										<li>KRS ini berlaku untuk semester {{ $mahasiswa->Semester }} tahun akademik {{ date('Y') }}/{{ date('Y') + 1 }}</li>
										<li>Perubahan KRS dapat dilakukan sesuai dengan jadwal yang ditetapkan oleh akademik</li>
										<li>Mahasiswa wajib mengikuti seluruh mata kuliah yang tercantum dalam KRS ini</li>
										<li>KRS yang telah disetujui tidak dapat diubah tanpa persetujuan akademik</li>
										<li>Mahasiswa yang tidak hadir tanpa keterangan lebih dari 25% dari total pertemuan akan mendapat nilai E</li>
									</ol>
								</div>

								<!-- Tanda Tangan -->
								<div class="ttd-section">
									<div class="row">
										<div class="col-md-6 text-center">
											<p style="margin-bottom: 10px;"><strong>Mahasiswa</strong></p>
											<div class="ttd-box">
												<div style="padding-top: 70px; font-size: 12px;">
													<strong>{{ $mahasiswa->Nama }}</strong><br>
													NIM: {{ $mahasiswa->NIM }}
												</div>
											</div>
										</div>
										<div class="col-md-6 text-center">
											<p style="margin-bottom: 10px;"><strong>Pembimbing Akademik</strong></p>
											<div class="ttd-box">
												<div style="padding-top: 70px; font-size: 12px;">
													<strong>_________________</strong><br>
													NIP: __________________
												</div>
											</div>
										</div>
									</div>
									<div style="text-align: center; margin-top: 20px;">
										<p style="font-size: 12px;">
											Kota Contoh, {{ \Carbon\Carbon::now()->format('d F Y') }}
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="footer-wrap pd-20 mb-20 card-box no-print">
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