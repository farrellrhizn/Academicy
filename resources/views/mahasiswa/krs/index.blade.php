<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>KRS - Kartu Rencana Studi</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">

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
								<img src="../../../bootstrap/vendors/images/photo1.jpg" alt="" />
							</span>
							<span class="user-name">{{ $mahasiswa->Nama }}</span>
						</a>
						<div
							class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
						>
							<a class="dropdown-item" href="#"
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
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Kartu Rencana Studi (KRS)</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											KRS
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

					<!-- Alert Messages -->
					@if(session('success'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<i class="fa fa-check-circle"></i> {{ session('success') }}
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					@endif

					@if(session('error'))
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					@endif

					<div class="row">
						<!-- Mata Kuliah yang Sudah Diambil -->
						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-30">
							<div class="card-box pd-20 height-100-p">
								<div class="d-flex justify-content-between">
									<h4 class="text-blue h4">Mata Kuliah Diambil</h4>
									<div class="dropdown">
										<a href="{{ route('mahasiswa.krs.cetak') }}" class="btn btn-outline-primary btn-sm">
											<i class="fa fa-print"></i> Cetak KRS
										</a>
									</div>
								</div>
								<div class="pb-20">
									<div class="d-flex align-items-center justify-content-between mb-20">
										<div>
											<span class="badge badge-pill badge-success">{{ $krsAmbil->count() }} Mata Kuliah</span>
											<span class="badge badge-pill badge-primary ml-1">{{ $krsAmbil->sum(fn($krs) => $krs->matakuliah->sks) }} SKS</span>
										</div>
									</div>
									
									@if($krsAmbil->count() > 0)
										<div class="table-responsive">
											<table id="krs-mata-kuliah" class="table table-striped">
												<thead>
													<tr>
														<th>Kode</th>
														<th>Mata Kuliah</th>
														<th>SKS</th>
														<th>Jadwal</th>
														<th>Aksi</th>
													</tr>
												</thead>
																				<tbody>
									@foreach($krsAmbil as $krs)
										<tr id="krs-row-{{ $krs->Kode_mk }}">
											<td><span class="badge badge-primary">{{ $krs->Kode_mk }}</span></td>
											<td><strong>{{ $krs->matakuliah->Nama_mk }}</strong></td>
											<td>
												<span class="badge badge-success">{{ $krs->matakuliah->sks }} SKS</span>
											</td>
											<td>
												@if($krs->matakuliah->jadwalAkademik->isNotEmpty())
													@foreach($krs->matakuliah->jadwalAkademik as $jadwal)
														@if($jadwal->id_Gol == $mahasiswa->id_Gol)
															<small class="text-muted">
																{{ $jadwal->hari }}, {{ $jadwal->waktu }}<br>
																<i class="fa fa-map-marker"></i> {{ $jadwal->ruang->nama_ruang ?? 'TBA' }}
															</small>
														@endif
													@endforeach
												@else
													<small class="text-warning">Belum dijadwalkan</small>
												@endif
											</td>
											<td>
												<button type="button" class="btn btn-outline-danger btn-sm delete-btn" 
														data-kode="{{ $krs->Kode_mk }}" 
														data-nama="{{ $krs->matakuliah->Nama_mk }}">
													<i class="fa fa-trash"></i> Hapus
												</button>
											</td>
										</tr>
									@endforeach
								</tbody>
											</table>
										</div>
									@else
										<div class="text-center py-4">
											<i class="fa fa-book text-muted" style="font-size: 3rem;"></i>
											<p class="text-muted mt-2">Belum ada mata kuliah yang diambil</p>
											<small class="text-muted">Pilih mata kuliah dari daftar sebelah kanan</small>
										</div>
									@endif
								</div>
							</div>
						</div>

						<!-- Mata Kuliah Tersedia -->
						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-30">
							<div class="card-box pd-20 height-100-p">
								<h4 class="text-blue h4">Mata Kuliah Tersedia</h4>
								<div class="pb-20">
									<p class="mb-20">Semester {{ $mahasiswa->Semester }}</p>
									
									@if($matakuliahTersedia->count() > 0)
										<div class="table-responsive">
											<table class="table table-striped">
												<thead>
													<tr>
														<th>Kode</th>
														<th>Mata Kuliah</th>
														<th>SKS</th>
														<th>Jadwal</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
													@foreach($matakuliahTersedia as $matkul)
														<tr>
															<td><span class="badge badge-primary">{{ $matkul->Kode_mk }}</span></td>
															<td><strong>{{ $matkul->Nama_mk }}</strong></td>
															<td>
																<span class="badge badge-info">{{ $matkul->sks }} SKS</span>
															</td>
															<td>
																@if($matkul->jadwalAkademik->isNotEmpty())
																	@foreach($matkul->jadwalAkademik as $jadwal)
																		@if($jadwal->id_Gol == $mahasiswa->id_Gol)
																			<small class="text-muted">
																				{{ $jadwal->hari }}, {{ $jadwal->waktu }}<br>
																				<i class="fa fa-map-marker"></i> {{ $jadwal->ruang->nama_ruang ?? 'TBA' }}
																			</small>
																		@endif
																	@endforeach
																@else
																	<small class="text-warning">Belum dijadwalkan</small>
																@endif
															</td>
															<td>
																<form action="{{ route('mahasiswa.krs.store') }}" method="POST" class="d-inline add-form">
																	@csrf
																	<input type="hidden" name="Kode_mk" value="{{ $matkul->Kode_mk }}">
																	<button type="submit" class="btn btn-outline-success btn-sm add-btn" 
																			data-kode="{{ $matkul->Kode_mk }}" 
																			data-nama="{{ $matkul->Nama_mk }}"
																			data-sks="{{ $matkul->sks }}">
																		<i class="fa fa-plus"></i> Ambil
																	</button>
																</form>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									@else
										<div class="text-center py-4">
											<i class="fa fa-info-circle text-info" style="font-size: 3rem;"></i>
											<p class="text-muted mt-2">Tidak ada mata kuliah tersedia</p>
											<small class="text-muted">Semua mata kuliah sudah diambil atau belum ada yang dibuka</small>
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>

					<!-- Summary Card -->
					<div class="row">
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
							<div class="card-box pd-20">
								<h4 class="text-blue h4">Ringkasan KRS</h4>
								<div class="pb-20">
									<div class="d-flex align-items-center justify-content-between mb-10">
										<span>Total Mata Kuliah:</span>
										<strong class="text-primary">{{ $krsAmbil->count() }}</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between mb-10">
										<span>Total SKS:</span>
										<strong class="text-success">{{ $krsAmbil->sum(fn($krs) => $krs->matakuliah->sks) }}</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between mb-10">
										<span>SKS Maksimal:</span>
										<strong class="text-info">24</strong>
									</div>
									<div class="progress mb-10">
										<div class="progress-bar bg-success" role="progressbar" 
											 style="width: {{ ($krsAmbil->sum(fn($krs) => $krs->matakuliah->sks) / 24) * 100 }}%">
										</div>
									</div>
									<small class="text-muted">
										{{ 24 - $krsAmbil->sum(fn($krs) => $krs->matakuliah->sks) }} SKS tersisa
									</small>
								</div>
							</div>
						</div>
						
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
							<div class="card-box pd-20">
								<h4 class="text-blue h4">Quick Actions</h4>
								<div class="pb-20">
									<a href="{{ route('mahasiswa.krs.jadwal') }}" class="btn btn-primary btn-block mb-10">
										<i class="fa fa-calendar"></i> Lihat Jadwal Kuliah
									</a>
									@if($krsAmbil->count() > 0)
										<a href="{{ route('mahasiswa.krs.cetak') }}" class="btn btn-outline-primary btn-block mb-10">
											<i class="fa fa-print"></i> Cetak KRS
										</a>
									@endif
									<a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary btn-block">
										<i class="fa fa-arrow-left"></i> Kembali ke Dashboard
									</a>
								</div>
							</div>
						</div>
						
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
							<div class="card-box pd-20">
								<h4 class="text-blue h4">Informasi Penting</h4>
								<div class="pb-20">
									<div class="alert alert-info" role="alert">
										<i class="fa fa-info-circle"></i> <strong>Batas Akhir KRS</strong><br>
										Pengisian KRS semester ini ditutup pada <strong>30 Agustus 2025</strong>
									</div>
									<div class="alert alert-warning" role="alert">
										<i class="fa fa-exclamation-triangle"></i> <strong>Perhatian</strong><br>
										Pastikan total SKS tidak melebihi 24 SKS
									</div>
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

		<script src="../../../bootstrap/vendors/scripts/core.js"></script>
		<script src="../../../bootstrap/vendors/scripts/script.min.js"></script>
		<script src="../../../bootstrap/vendors/scripts/process.js"></script>
		<script src="../../../bootstrap/vendors/scripts/layout-settings.js"></script>
		<script src="../../../bootstrap/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../../../bootstrap/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="../../../bootstrap/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="../../../bootstrap/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		
		<!-- SweetAlert2 for better alerts -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		
		<script>
		$(document).ready(function() {
			// Setup CSRF Token untuk semua request AJAX
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			
			// Check SKS limit before adding
			function checkSksLimit(additionalSks) {
				const currentSks = {{ $krsAmbil->sum(fn($krs) => $krs->matakuliah->sks) }};
				const maxSks = 24;
				const totalAfterAdd = currentSks + additionalSks;
				
				return {
					isValid: totalAfterAdd <= maxSks,
					currentSks: currentSks,
					additionalSks: additionalSks,
					totalAfterAdd: totalAfterAdd,
					maxSks: maxSks,
					remaining: maxSks - totalAfterAdd
				};
			}
			// Handle delete button clicks
			$('.delete-btn').on('click', function(e) {
				e.preventDefault();
				
				const button = $(this);
				const kodeMatkuliah = button.data('kode');
				const namaMatkuliah = button.data('nama');
				
				Swal.fire({
					title: 'Konfirmasi Hapus',
					html: `Yakin ingin menghapus mata kuliah <strong>${namaMatkuliah}</strong> (${kodeMatkuliah}) dari KRS?`,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: 'Ya, Hapus!',
					cancelButtonText: 'Batal',
					reverseButtons: true
				}).then((result) => {
					if (result.isConfirmed) {
						// Show loading state
						Swal.fire({
							title: 'Menghapus...',
							text: 'Sedang memproses permintaan Anda',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});
						
						// Submit DELETE request via AJAX
						$.ajax({
							url: '{{ route("mahasiswa.krs.destroy") }}',
							type: 'DELETE',
							data: {
								'Kode_mk': kodeMatkuliah,
								'_token': $('meta[name="csrf-token"]').attr('content')
							},
							dataType: 'json',
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
								'Accept': 'application/json'
							},
							success: function(response) {
								Swal.fire({
									title: 'Berhasil!',
									text: `Mata kuliah ${namaMatkuliah} berhasil dihapus dari KRS`,
									icon: 'success',
									timer: 2000,
									showConfirmButton: false
								}).then(() => {
									// Remove the row instead of reloading
									$('#krs-row-' + kodeMatkuliah).fadeOut(500, function() {
										$(this).remove();
										// Update summary counters
										updateSummary();
									});
								});
							},
							error: function(xhr) {
								console.error('Delete request failed:', xhr);
								console.error('Status:', xhr.status);
								console.error('Response:', xhr.responseText);
								
								let errorMessage = 'Terjadi kesalahan saat menghapus mata kuliah';
								
								if (xhr.responseJSON && xhr.responseJSON.message) {
									errorMessage = xhr.responseJSON.message;
								} else if (xhr.status === 500) {
									errorMessage = 'Server error. Silakan coba lagi atau hubungi administrator.';
								} else if (xhr.status === 422) {
									errorMessage = 'Data yang dikirim tidak valid.';
								}
								
								Swal.fire({
									title: 'Gagal!',
									text: errorMessage,
									icon: 'error',
									confirmButtonText: 'OK'
								});
							}
						});
					}
				});
			});
			
			// Function to update summary without page reload
			function updateSummary() {
				const rowCount = $('#krs-mata-kuliah tbody tr').length;
				// This is a simple approach - you might want to make an AJAX call to get updated totals
				location.reload(); // For now, just reload to get accurate counts
			}
			
			// Handle add course button clicks
			$('.add-btn').on('click', function(e) {
				e.preventDefault();
				
				const form = $(this).closest('form');
				const button = $(this);
				const kodeMatkuliah = $(this).data('kode');
				const namaMatkuliah = $(this).data('nama');
				const sks = $(this).data('sks');
				const originalText = button.html();
				
				// Check SKS limit first
				const sksCheck = checkSksLimit(parseInt(sks));
				
				if (!sksCheck.isValid) {
					Swal.fire({
						title: 'Batas SKS Terlampaui!',
						html: `
							<div class="text-left">
								<p>Tidak dapat menambahkan mata kuliah karena akan melebihi batas maksimal SKS.</p>
								<hr>
								<p><strong>SKS saat ini:</strong> ${sksCheck.currentSks} SKS</p>
								<p><strong>SKS mata kuliah:</strong> ${sksCheck.additionalSks} SKS</p>
								<p><strong>Total setelah ditambah:</strong> ${sksCheck.totalAfterAdd} SKS</p>
								<p><strong>Batas maksimal:</strong> ${sksCheck.maxSks} SKS</p>
							</div>
						`,
						icon: 'warning',
						confirmButtonText: 'OK'
					});
					return;
				}
				
				// Show confirmation dialog
				Swal.fire({
					title: 'Konfirmasi Ambil',
					html: `
						<div class="text-left">
							<p>Yakin ingin mengambil mata kuliah <strong>${namaMatkuliah}</strong> (${kodeMatkuliah}) - ${sks} SKS?</p>
							<hr>
							<p><strong>SKS saat ini:</strong> ${sksCheck.currentSks} SKS</p>
							<p><strong>SKS setelah ditambah:</strong> ${sksCheck.totalAfterAdd} SKS</p>
							<p><strong>Sisa kuota SKS:</strong> ${sksCheck.remaining} SKS</p>
						</div>
					`,
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#28a745',
					cancelButtonColor: '#6c757d',
					confirmButtonText: 'Ya, Ambil!',
					cancelButtonText: 'Batal',
					reverseButtons: true
				}).then((result) => {
					if (result.isConfirmed) {
						// Show loading state
						button.html('<i class="fa fa-spinner fa-spin"></i> Menambah...');
						button.prop('disabled', true);
						
						// Submit form via AJAX
						$.ajax({
							url: form.attr('action'),
							type: 'POST',
							data: form.serialize(),
							success: function(response) {
								Swal.fire({
									title: 'Berhasil!',
									text: `Mata kuliah ${namaMatkuliah} berhasil ditambahkan ke KRS`,
									icon: 'success',
									timer: 2000,
									showConfirmButton: false
								}).then(() => {
									// Reload page to show updated data
									window.location.reload();
								});
							},
							error: function(xhr) {
								let errorMessage = 'Terjadi kesalahan saat menambahkan mata kuliah';
								
								if (xhr.responseJSON && xhr.responseJSON.message) {
									errorMessage = xhr.responseJSON.message;
								}
								
								Swal.fire({
									title: 'Gagal!',
									text: errorMessage,
									icon: 'error',
									confirmButtonText: 'OK'
								});
								
								// Reset button
								button.html(originalText);
								button.prop('disabled', false);
							}
						});
					}
				});
			});
		});
		</script>
	</body>
</html>