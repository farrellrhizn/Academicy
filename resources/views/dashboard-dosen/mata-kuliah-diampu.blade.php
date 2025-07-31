<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Mata Kuliah Diampu - Dashboard Dosen</title>

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
		<link rel="stylesheet" type="text/css" href="../../bootstrap/src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
		<link rel="stylesheet" type="text/css" href="../../bootstrap/src/plugins/datatables/css/responsive.bootstrap4.min.css" />
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
			</div>
		</div>

		<div class="header">
			<div class="header-left">
				<div class="menu-icon bi bi-list"></div>
			</div>
			<div class="header-right">
				<div class="dashboard-setting user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="javascript:;"
							data-toggle="dropdown"
						>
							<i class="dw dw-settings2"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
							<a class="dropdown-item" href="{{ route('dosen.profile.edit') }}"
								><i class="dw dw-user1"></i> Profile</a
							>
							<a class="dropdown-item" href="{{ route('dosen.profile.edit') }}"
								><i class="dw dw-settings2"></i> Setting</a
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
				<div class="user-info-dropdown">
					<div class="dropdown">
						<a
							class="dropdown-toggle"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
							<span class="user-icon">
								                        @if($dosen->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . $dosen->profile_photo) }}" alt="Profile" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
								@else
									<i class="dw dw-user1"></i>
								@endif
							</span>
							<span class="user-name">{{ $dosen->Nama ?? 'Dosen' }}</span>
						</a>
						<div
							class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
						>
							<a class="dropdown-item" href="{{ route('dosen.profile.edit') }}"
								><i class="dw dw-user1"></i> Profile</a
							>
							<a class="dropdown-item" href="{{ route('dosen.profile.edit') }}"
								><i class="dw dw-settings2"></i> Setting</a
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
				<a href="{{ route('dosen.dashboard') }}">
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
							<a href="{{ route('dosen.mata-kuliah-diampu.index') }}" class="dropdown-toggle no-arrow active">
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
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Mata Kuliah Diampu</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="{{ route('dosen.dashboard') }}">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Mata Kuliah Diampu
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>

					<!-- Statistik Cards -->
					<div class="row pb-10">
						<div class="col-xl-4 col-lg-4 col-md-6 mb-20">
							<div class="card-box height-100-p widget-style3">
								<div class="d-flex flex-wrap">
									<div class="widget-data">
										<div class="weight-700 font-24 text-dark">{{ $totalMataKuliah }}</div>
										<div class="font-14 text-secondary weight-500">Total Mata Kuliah</div>
									</div>
									<div class="widget-icon">
										<div class="icon" style="background-color: #1b00ff;"><i class="icon-copy bi bi-book" aria-hidden="true"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 mb-20">
							<div class="card-box height-100-p widget-style3">
								<div class="d-flex flex-wrap">
									<div class="widget-data">
										<div class="weight-700 font-24 text-dark">{{ $totalSKS }} SKS</div>
										<div class="font-14 text-secondary weight-500">Total SKS Mengajar</div>
									</div>
									<div class="widget-icon">
										<div class="icon" style="background-color: #00d4aa;"><i class="icon-copy bi bi-stack" aria-hidden="true"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 mb-20">
							<div class="card-box height-100-p widget-style3">
								<div class="d-flex flex-wrap">
									<div class="widget-data">
										<div class="weight-700 font-24 text-dark">{{ $matakuliahPerSemester->count() }}</div>
										<div class="font-14 text-secondary weight-500">Semester Aktif</div>
									</div>
									<div class="widget-icon">
										<div class="icon" style="background-color: #ffb64d;"><i class="icon-copy bi bi-calendar3" aria-hidden="true"></i></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Data Table -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<h4 class="text-blue h4">Daftar Mata Kuliah yang Diampu</h4>
							<p class="mb-0">
								Berikut adalah daftar mata kuliah yang diampu oleh <strong>{{ $dosen->Nama }}</strong>
							</p>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Kode MK</th>
										<th>Nama Mata Kuliah</th>
										<th>SKS</th>
										<th>Semester</th>
									</tr>
								</thead>
								<tbody>
									@forelse($matakuliahDiampu as $index => $mk)
									<tr>
										<td class="table-plus">{{ $index + 1 }}</td>
										<td>{{ $mk->Kode_mk }}</td>
										<td>
											<div class="name-avatar d-flex align-items-center">
												<div class="avatar mr-2 flex-shrink-0">
													<div class="avatar-title bg-light-blue text-blue rounded-circle">
														<i class="bi bi-book"></i>
													</div>
												</div>
												<div class="txt">
													<div class="weight-600">{{ $mk->Nama_mk }}</div>
												</div>
											</div>
										</td>
										<td>
											<span class="badge badge-pill" 
												  style="background-color: 
												  @if($mk->sks == 2) #28a745 
												  @elseif($mk->sks == 3) #ffc107 
												  @elseif($mk->sks >= 4) #dc3545 
												  @else #6c757d 
												  @endif; color: white;">
												{{ $mk->sks }} SKS
											</span>
										</td>
										<td>
											<span class="badge badge-outline-primary">Semester {{ $mk->semester }}</span>
										</td>
									</tr>
									@empty
									<tr>
										<td colspan="5" class="text-center">
											<div class="pd-20">
												<img src="../../bootstrap/vendors/images/calendar-empty-state.svg" alt="" class="mb-3" style="max-width: 100px;">
												<h5 class="text-secondary">Tidak ada mata kuliah</h5>
												<p class="text-secondary">Anda belum memiliki mata kuliah yang diampu.</p>
											</div>
										</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>

					<!-- Data by Semester -->
					@if($matakuliahPerSemester->count() > 0)
					<div class="row">
						@foreach($matakuliahPerSemester as $semester => $matakuliahList)
						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-30">
							<div class="card-box height-100-p pd-20">
								<div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
									<div class="h5 mb-md-0">Semester {{ $semester }}</div>
									<div class="form-group mb-md-0">
										<span class="badge badge-primary">{{ $matakuliahList->count() }} Mata Kuliah</span>
									</div>
								</div>
								<div class="user-list">
									@foreach($matakuliahList as $mk)
									<ul>
										<li class="d-flex align-items-center justify-content-between">
											<div class="name-avatar d-flex align-items-center pr-2">
												<div class="avatar mr-2 flex-shrink-0">
													<div class="avatar-title bg-light-primary text-primary rounded-circle">
														{{ $mk->sks }}
													</div>
												</div>
												<div class="txt">
													<span class="badge badge-pill badge-sm" data-bgcolor="#e7f5ff" data-color="#1c7cd6">{{ $mk->Kode_mk }}</span>
													<div class="font-14 weight-600">{{ $mk->Nama_mk }}</div>
												</div>
											</div>
											<div class="cta flex-shrink-0">
												<span class="badge badge-light">{{ $mk->sks }} SKS</span>
											</div>
										</li>
									</ul>
									@endforeach
								</div>
							</div>
						</div>
						@endforeach
					</div>
					@endif
				</div>
				
				<div class="footer-wrap pd-20 mb-20 card-box">
					SI Akademik - Dashboard Dosen
				</div>
			</div>
		</div>

		<script src="../../bootstrap/vendors/scripts/core.js"></script>
		<script src="../../bootstrap/vendors/scripts/script.min.js"></script>
		<script src="../../bootstrap/vendors/scripts/process.js"></script>
		<script src="../../bootstrap/vendors/scripts/layout-settings.js"></script>
		<script src="../../bootstrap/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../../bootstrap/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="../../bootstrap/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="../../bootstrap/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>

		<script>
			$(document).ready(function() {
				$('.data-table').DataTable({
					scrollCollapse: true,
					autoWidth: false,
					responsive: true,
					columnDefs: [{
						targets: "datatable-nosort",
						orderable: false,
					}],
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					"language": {
						"info": "_START_-_END_ of _TOTAL_ entries",
						searchPlaceholder: "Search",
						paginate: {
							next: '<i class="ion-chevron-right"></i>',
							previous: '<i class="ion-chevron-left"></i>'  
						}
					},
				});
			});
		</script>
	</body>
</html>