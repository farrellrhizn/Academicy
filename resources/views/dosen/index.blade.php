<!DOCTYPE html>
<html>

<head>

    <head>
        <meta charset="utf-8" />
        <title>Manajemen Data Dosen</title>

        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('bootstrap/vendors/images/apple-touch-icon.png') }}" />
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('bootstrap/vendors/images/favicon-32x32.png') }}" />
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('bootstrap/vendors/images/favicon-16x16.png') }}" />

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;808&display=swap" rel="stylesheet" />

        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
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
								<i class="dw dw-user1"></i>
							</span>
                        <span class="user-name">{{ $userData->nama_lengkap ?? 'Administrator' }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
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
            <a href="{{ route('admin.dashboard') }}">
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
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow ">
                            <span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-mortarboard"></span><span class="mtext">Data Master</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.matakuliah.index') }}">Mata Kuliah</a></li>
                            <li><a href="{{ route('admin.dosen.index') }}" class="active">Dosen</a></li>
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
                                <h4>Manajemen Data Dosen</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Dosen</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Input Data Dosen</h4>
                            <p class="mb-30">Masukkan informasi dosen baru ke dalam sistem</p>
                        </div>
                    </div>
                    <form id="dosenForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>NIP <span class="text-danger">*</span></label>
                                    <input class="form-control" name="NIP" id="NIP" type="text" placeholder="Contoh: 19750310199503001" maxlength="20" required />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input class="form-control" name="Nama" id="Nama" type="text" placeholder="Contoh: Dr. Bambang Sutrisno, M.Sc" maxlength="100" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <input class="form-control" name="password" id="password" type="password" ... />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>No. Handphone <span class="text-danger">*</span></label>
                                    <input class="form-control" name="Nohp" id="Nohp" type="text" placeholder="Contoh: 081234567890" maxlength="15" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="Alamat" id="Alamat" placeholder="Contoh: Jl. Thamrin No. 300, Jakarta" required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Data</button>
                            <button type="reset" class="btn btn-secondary ml-2"><i class="fa fa-refresh"></i> Reset Form</button>
                        </div>
                    </form>
                </div>

                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Daftar Dosen</h4>
                        <p class="mb-30">Data dosen yang telah tersimpan dalam sistem</p>
                    </div>
                    <div class="pb-20">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">NIP</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Alamat</th>
                                        <th scope="col">No. HP</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($dosen as $item)
                                    <tr id="row_{{ $item->NIP }}">
                                        <td>{{ $item->NIP }}</td>
                                        <td>{{ $item->Nama }}</td>
                                        <td>{{ $item->Alamat }}</td>
                                        <td>{{ $item->Nohp }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="{{ $item->NIP }}"><i class="dw dw-edit2"></i> Edit</a>
                                                    <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="{{ $item->NIP }}"><i class="dw dw-delete-3"></i> Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_NIP" name="NIP">

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input class="form-control" id="edit_NIP_display" type="text" readonly />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input class="form-control" name="Nama" id="edit_Nama" type="text" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Password Baru (Opsional)</label>
                                    <input class="form-control" name="password" id="edit_password" type="password" ... placeholder="Isi jika ingin mengubah password" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>No. Handphone <span class="text-danger">*</span></label>
                                    <input class="form-control" name="Nohp" id="edit_Nohp" type="text" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="Alamat" id="edit_Alamat" required></textarea>
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

    <script src="{{ asset('bootstrap/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/layout-settings.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        // Setup CSRF Token untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // =================================================================
        // CREATE (SIMPAN DATA BARU)
        // =================================================================
        $('#dosenForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('admin.dosen.store') }}", // URL sudah diperbaiki
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        });
                        $('#dosenForm')[0].reset();

                        // Baris baru untuk tabel
                        var newRow = `
                            <tr id="row_${response.data.NIP}">
                                <td>${response.data.NIP}</td>
                                <td>${response.data.Nama}</td>
                                <td>${response.data.Alamat}</td>
                                <td>${response.data.Nohp}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="${response.data.NIP}"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="${response.data.NIP}"><i class="dw dw-delete-3"></i> Hapus</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                        $('#tableBody').append(newRow);
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg
                    });
                }
            });
        });

        // =================================================================
        // EDIT
        // =================================================================
        $('body').on('click', '.btn-edit', function() {
            var nip = $(this).data('id');
            // Membuat URL dinamis dan benar
            var url = "{{ route('admin.dosen.edit', ':id') }}";
            url = url.replace(':id', nip);

            $.get(url, function(data) {
                $('#editModal').modal('show');
                $('#edit_NIP').val(data.NIP);
                $('#edit_NIP_display').val(data.NIP);
                $('#edit_Nama').val(data.Nama);
                $('#edit_Alamat').val(data.Alamat);
                $('#edit_Nohp').val(data.Nohp);
                $('#edit_password').val(''); // Selalu kosongkan field password saat edit
            });
        });

        // =================================================================
        // UPDATE
        // =================================================================
        $('#updateBtn').on('click', function() {
            var nip = $('#edit_NIP').val();
            // Membuat URL dinamis dan benar
            var url = "{{ route('admin.dosen.update', ':id') }}";
            url = url.replace(':id', nip);

            $.ajax({
                url: url,
                type: 'PUT', // Method yang benar untuk update
                data: $('#editForm').serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        });

                        // Memperbarui baris data di tabel
                        var updatedRowContent = `
                            <td>${response.data.NIP}</td>
                            <td>${response.data.Nama}</td>
                            <td>${response.data.Alamat}</td>
                            <td>${response.data.Nohp}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="${response.data.NIP}"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="${response.data.NIP}"><i class="dw dw-delete-3"></i> Hapus</a>
                                    </div>
                                </div>
                            </td>`;
                        $('#row_' + nip).html(updatedRowContent);
                    }
                },
                error: function(xhr) {
                     var errors = xhr.responseJSON.errors;
                    var errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg
                    });
                }
            });
        });

        // =================================================================
        // DELETE
        // =================================================================
        $('body').on('click', '.btn-delete', function() {
            var nip = $(this).data('id');
            // Membuat URL dinamis dan benar
            var url = "{{ route('admin.dosen.destroy', ':id') }}";
            url = url.replace(':id', nip);

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
                        url: url, // Menggunakan URL yang sudah benar
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Dihapus!', response.message, 'success');
                                $('#row_' + nip).remove();
                            }
                        },
                        error: function(xhr) {
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