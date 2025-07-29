<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8" />
	<title>Kurikulum Management - DeskApp Dashboard</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="../../bootstrap/vendors/images/apple-touch-icon.png" />
	<link rel="icon" type="image/png" sizes="32x32" href="../../bootstrap/vendors/images/favicon-32x32.png" />
	<link rel="icon" type="image/png" sizes="16x16" href="../../bootstrap/vendors/images/favicon-16x16.png" />

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../../bootstrap/vendors/styles/core.css" />
	<link rel="stylesheet" type="text/css" href="../../bootstrap/vendors/styles/icon-font.min.css" />
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
			<div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
			<div class="header-search">
				<form>
					<div class="form-group mb-0">
						<i class="dw dw-search2 search-icon"></i>
						<input type="text" class="form-control search-input" placeholder="Search Here" />
					</div>
				</form>
			</div>
		</div>
		<div class="header-right">
			<div class="dashboard-setting user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
						<i class="dw dw-settings2"></i>
					</a>
				</div>
			</div>
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<img src="../../bootstrap/vendors/images/photo1.jpg" alt="" />
						</span>
						<span class="user-name">Admin User</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="#"><i class="dw dw-user1"></i> Profile</a>
						<a class="dropdown-item" href="#"><i class="dw dw-settings2"></i> Setting</a>
						<a class="dropdown-item" href="#"><i class="dw dw-logout"></i> Log Out</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="left-side-bar">
		<div class="brand-logo">
			<a href="index.html">
				<img src="../../../bootstrap/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
				<img src="../../../bootstrap/vendors/images/deskapp-logo-white.svg" alt="" class="light-logo" />
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>

		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">
					<li>
						<a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
							<span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
						</a>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon bi bi-mortarboard"></span><span class="mtext">Data Master</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('admin.matakuliah.index') }}" class="active">Mata Kuliah</a></li>
							<li><a href="{{ route('admin.dosen.index') }}">Dosen</a></li>
							<li><a href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon bi bi-calendar3-week"></span><span class="mtext">Jadwal</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('admin.buat-jadwal.index') }}">Buat Jadwal</a></li>
							<li><a href="{{ route('admin.kelola-jadwal.index') }}">Kelola Jadwal</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon bi bi-building"></span><span class="mtext">Ruang & Kelas</span>
						</a>
						<ul class="submenu">
							<li><a href="{{ route('admin.ruang.index') }}">Ruang</a></li>
							<li><a href="{{ route('admin.golongan.index') }}">Kelas/Golongan</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon bi bi-gear"></span><span class="mtext">Pengaturan</span>
						</a>
						<ul class="submenu">
							<li><a href="pengaturan-sistem.html">Sistem</a></li>
							<li><a href="profil-admin.html">Profil</a></li>
						</ul>
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
								<h4>Kurikulum Management</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item">
										<a href="index.html">Home</a>
									</li>
									<li class="breadcrumb-item">
										<a href="#">Akademik</a>
									</li>
									<li class="breadcrumb-item active" aria-current="page">
										Kurikulum
									</li>
								</ol>
							</nav>
						</div>
						<div class="col-md-6 col-sm-12 text-right">
							<div class="dropdown">
								<a class="btn btn-secondary dropdown-toggle" href="#" role="button"
									data-toggle="dropdown">
									Actions
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#">Export List</a>
									<a class="dropdown-item" href="#">Import Data</a>
									<a class="dropdown-item" href="#">Print Report</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Success Alert -->
				<div id="successAlert" class="alert alert-success alert-dismissible fade" role="alert"
					style="display: none;">
					<strong>Success!</strong> <span id="successMessage"></span>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<!-- Error Alert -->
				<div id="errorAlert" class="alert alert-danger alert-dismissible fade" role="alert"
					style="display: none;">
					<strong>Error!</strong> <span id="errorMessage">Please check the form and try again.</span>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<!-- Form Input Mata Kuliah -->
				<div class="pd-20 card-box mb-30">
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">Input Data Mata Kuliah</h4>
							<p class="mb-30">Masukkan informasi mata kuliah baru ke dalam sistem</p>
						</div>
					</div>
					<form id="kurikulumForm" method="POST" action="/kurikulum">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="form-group">
									<label>Kode Mata Kuliah <span class="text-danger">*</span></label>
									<input class="form-control" name="Kode_mk" id="Kode_mk" type="text"
										placeholder="Contoh: TIF001" maxlength="20" required />
									<small class="form-text text-muted">Maksimal 20 karakter</small>
									<div class="invalid-feedback" id="Kode_mk_error"></div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12">
								<div class="form-group">
									<label>Nama Mata Kuliah <span class="text-danger">*</span></label>
									<input class="form-control" name="Nama_mk" id="Nama_mk" type="text"
										placeholder="Contoh: Pemrograman Web" maxlength="100" required />
									<small class="form-text text-muted">Maksimal 100 karakter</small>
									<div class="invalid-feedback" id="Nama_mk_error"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="form-group">
									<label>SKS (Satuan Kredit Semester) <span class="text-danger">*</span></label>
									<select class="custom-select col-12" name="sks" id="sks" required>
										<option value="">Pilih SKS...</option>
										<option value="1">1 SKS</option>
										<option value="2">2 SKS</option>
										<option value="3">3 SKS</option>
										<option value="4">4 SKS</option>
										<option value="5">5 SKS</option>
										<option value="6">6 SKS</option>
									</select>
									<div class="invalid-feedback" id="sks_error"></div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12">
								<div class="form-group">
									<label>Semester <span class="text-danger">*</span></label>
									<select class="custom-select col-12" name="semester" id="semester" required>
										<option value="">Pilih Semester...</option>
										<option value="1">Semester 1</option>
										<option value="2">Semester 2</option>
										<option value="3">Semester 3</option>
										<option value="4">Semester 4</option>
										<option value="5">Semester 5</option>
										<option value="6">Semester 6</option>
										<option value="7">Semester 7</option>
										<option value="8">Semester 8</option>
									</select>
									<div class="invalid-feedback" id="semester_error"></div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-save"></i> Simpan Data
							</button>
							<button type="reset" class="btn btn-secondary ml-2">
								<i class="fa fa-refresh"></i> Reset Form
							</button>
						</div>
					</form>
				</div>

				<!-- Tabel Daftar Mata Kuliah -->
				<div class="card-box mb-30">
					<div class="pd-20">
						<div class="clearfix">
							<div class="pull-left">
								<h4 class="text-blue h4">Daftar Mata Kuliah</h4>
								<p class="mb-30">Data mata kuliah yang telah tersimpan dalam sistem</p>
							</div>
							<div class="pull-right">
								<div class="dropdown">
									<a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
										data-toggle="dropdown">
										Filter Semester
									</a>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="#" onclick="filterBySemester('all')">Semua
											Semester</a>
										<a class="dropdown-item" href="#" onclick="filterBySemester('1')">Semester 1</a>
										<a class="dropdown-item" href="#" onclick="filterBySemester('2')">Semester 2</a>
										<a class="dropdown-item" href="#" onclick="filterBySemester('3')">Semester 3</a>
										<a class="dropdown-item" href="#" onclick="filterBySemester('4')">Semester 4</a>
										<a class="dropdown-item" href="#" onclick="filterBySemester('5')">Semester 5</a>
										<a class="dropdown-item" href="#" onclick="filterBySemester('6')">Semester 6</a>
										<a class="dropdown-item" href="#" onclick="filterBySemester('7')">Semester 7</a>
										<a class="dropdown-item" href="#" onclick="filterBySemester('8')">Semester 8</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="pb-20">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th scope="col">Kode MK</th>
										<th scope="col">Nama MK</th>
										<th scope="col">SKS</th>
										<th scope="col">Semester</th>
										<th scope="col">Aksi</th>
									</tr>
								</thead>
								<tbody id="tableBody"> @foreach ($matakuliah as $mk)
									<tr id="row_{{ $mk->Kode_mk }}">
										<td>{{ $mk->Kode_mk }}</td>
										<td>{{ $mk->Nama_mk }}</td>
										<td>{{ $mk->sks }}</td>
										<td>{{ $mk->semester }}</td>
										<td>
											<div class="dropdown">
												<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
													href="#" role="button" data-toggle="dropdown">
													<i class="dw dw-more"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
													<a class="dropdown-item btn-edit" href="javascript:void(0)"
														data-id="{{ $mk->Kode_mk }}">
														<i class="dw dw-edit2"></i> Edit
													</a>
													<a class="dropdown-item btn-delete" href="javascript:void(0)"
														data-id="{{ $mk->Kode_mk }}">
														<i class="dw dw-delete-3"></i> Hapus
													</a>
												</div>
											</div>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
							<!-- Empty State -->
							<div id="emptyState" class="text-center py-5" style="display: none;">
								<img src="../../bootstrap/vendors/images/no-data.png" alt="No Data"
									style="width: 150px; opacity: 0.5;">
								<h5 class="text-muted mt-3">Belum ada data mata kuliah</h5>
								<p class="text-muted">Tambahkan mata kuliah pertama menggunakan form di atas</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				Academicy - © 2023
			</div>
		</div>
	</div>
	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editModalLabel">Edit Data Mata Kuliah</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="editForm">
						@csrf
						@method('PUT')

						<input type="hidden" id="edit_Kode_mk" name="Kode_mk">

						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="form-group">
									<label>Kode Mata Kuliah</label>
									<input class="form-control" id="edit_Kode_mk_display" type="text" readonly />
								</div>
							</div>
							<div class="col-md-6 col-sm-12">
								<div class="form-group">
									<label>Nama Mata Kuliah <span class="text-danger">*</span></label>
									<input class="form-control" name="Nama_mk" id="edit_Nama_mk" type="text"
										placeholder="Contoh: Pemrograman Web" maxlength="100" required />
									<div class="invalid-feedback" id="edit_Nama_mk_error"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="form-group">
									<label>SKS (Satuan Kredit Semester) <span class="text-danger">*</span></label>
									<select class="custom-select col-12" name="sks" id="edit_sks" required>
										<option value="">Pilih SKS...</option>
										<option value="1">1 SKS</option>
										<option value="2">2 SKS</option>
										<option value="3">3 SKS</option>
										<option value="4">4 SKS</option>
										<option value="5">5 SKS</option>
										<option value="6">6 SKS</option>
									</select>
									<div class="invalid-feedback" id="edit_sks_error"></div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12">
								<div class="form-group">
									<label>Semester <span class="text-danger">*</span></label>
									<select class="custom-select col-12" name="semester" id="edit_semester" required>
										<option value="">Pilih Semester...</option>
										<option value="1">Semester 1</option>
										<option value="2">Semester 2</option>
										<option value="3">Semester 3</option>
										<option value="4">Semester 4</option>
										<option value="5">Semester 5</option>
										<option value="6">Semester 6</option>
										<option value="7">Semester 7</option>
										<option value="8">Semester 8</option>
									</select>
									<div class="invalid-feedback" id="edit_semester_error"></div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary" id="updateBtn">Simpan Perubahan</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Delete Confirmation Modal -->
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-body text-center font-18">
					<h4 class="padding-top-30 mb-30 weight-500">
						Apakah Anda yakin ingin menghapus mata kuliah ini?
					</h4>
					<div class="padding-bottom-30 row" style="max-width: 170px; margin: 0 auto;">
						<div class="col-6">
							<button type="button" class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
								data-dismiss="modal">
								<i class="fa fa-times"></i>
							</button>
							NO
						</div>
						<div class="col-6">
							<button type="button" class="btn btn-primary border-radius-100 btn-block confirmation-btn"
								id="confirmDelete">
								<i class="fa fa-check"></i>
							</button>
							YES
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- js -->
	<script src="../../bootstrap/vendors/scripts/core.js"></script>
	<script src="../../bootstrap/vendors/scripts/script.min.js"></script>
	<script src="../../bootstrap/vendors/scripts/process.js"></script>
	<script src="../../bootstrap/vendors/scripts/layout-settings.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		$(document).ready(function () {

			// Setup CSRF Token untuk semua request AJAX
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').length > 0 ? $('meta[name="csrf-token"]').attr('content') : $('input[name="_token"]').val()
				}
			});

			// =================================================================
			// CREATE (SIMPAN DATA BARU)
			// =================================================================
			$('#kurikulumForm').on('submit', function (e) {
				e.preventDefault();
				$.ajax({
					url: "{{ route('admin.matakuliah.store') }}",
					type: 'POST',
					data: $(this).serialize(),
					success: function (response) {
						if (response.success) {
							Swal.fire({
								icon: 'success',
								title: 'Berhasil!',
								text: response.message
							});
							$('#kurikulumForm')[0].reset();
							$('.is-invalid').removeClass('is-invalid');
							$('.invalid-feedback').text('');

							// Kode untuk membuat baris baru.
							// Pastikan tombol di sini memiliki class 'btn-edit' dan 'btn-delete'.
							var newRow = `
                            <tr id="row_${response.data.Kode_mk}">
                                <td>${response.data.Kode_mk}</td>
                                <td>${response.data.Nama_mk}</td>
                                <td>${response.data.sks}</td>
                                <td>${response.data.semester}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="${response.data.Kode_mk}"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="${response.data.Kode_mk}"><i class="dw dw-delete-3"></i> Hapus</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
							$('#tableBody').append(newRow); // Menambahkan ke tbody
						}
					},
					error: function (xhr) {
						// ... (kode error handling) ...
					}
				});
			});

			// =================================================================
			// EDIT (EVENT DELEGATION)
			// PENTING: Listener dipasang di 'body' yang selalu ada, 
			// lalu didelegasikan ke '.btn-edit' yang mungkin baru muncul.
			// =================================================================
			$('body').on('click', '.btn-edit', function () {
				var kodeMk = $(this).data('id');

				// Buat URL yang benar menggunakan route helper
				var url = "{{ route('admin.matakuliah.edit', ['Kode_mk' => ':id']) }}";
				url = url.replace(':id', kodeMk);

				$.get(url, function (data) {
					$('#editModal').modal('show');
					$('#edit_Kode_mk').val(data.Kode_mk);
					$('#edit_Kode_mk_display').val(data.Kode_mk);
					$('#edit_Nama_mk').val(data.Nama_mk);
					$('#edit_sks').val(data.sks);
					$('#edit_semester').val(data.semester);
				});
			});

			// =================================================================
			// UPDATE
			// =================================================================
			$('#updateBtn').on('click', function () {
				var kodeMk = $('#edit_Kode_mk').val();

				// Buat URL yang benar
				var url = "{{ route('admin.matakuliah.update', ['Kode_mk' => ':id']) }}";
				url = url.replace(':id', kodeMk);

				$.ajax({
					url: url,
					type: 'PUT', // Gunakan method PUT untuk update resource
					data: $('#editForm').serialize(),
					success: function (response) {
						if (response.success) {
							$('#editModal').modal('hide');
							Swal.fire({
								icon: 'success',
								title: 'Berhasil!',
								text: response.message
							});
							var updatedRowContent = `
                            <td>${response.data.Kode_mk}</td>
                            <td>${response.data.Nama_mk}</td>
                            <td>${response.data.sks}</td>
                            <td>${response.data.semester}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="${response.data.Kode_mk}"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="${response.data.Kode_mk}"><i class="dw dw-delete-3"></i> Hapus</a>
                                    </div>
                                </div>
                            </td>
                        `;
							$('#row_' + kodeMk).html(updatedRowContent);
						}
					},
					// ... error handling ...
				});
			});

			// =================================================================
			// DELETE (EVENT DELEGATION)
			// PENTING: Sama seperti Edit, listener dipasang di 'body'.
			// =================================================================
			$('body').on('click', '.btn-delete', function () {
				var kodeMk = $(this).data('id');

				// Buat URL yang benar menggunakan route helper
				var url = "{{ route('admin.matakuliah.destroy', ['Kode_mk' => ':id']) }}";
				url = url.replace(':id', kodeMk);

				Swal.fire({
					title: 'Apakah Anda yakin?',
					text: "Data yang dihapus tidak dapat dikembalikan!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, hapus!',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							type: "DELETE",
							url: url, // <-- Gunakan URL yang sudah diperbaiki
							success: function (response) {
								if (response.success) {
									Swal.fire('Dihapus!', response.message, 'success');
									$('#row_' + kodeMk).remove();
								}
							},
							error: function (xhr) {
								Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan.', 'error');
							}
						});
					}
				});
			});

		});
	</script>
</body>

</html>