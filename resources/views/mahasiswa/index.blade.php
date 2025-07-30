<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Manajemen Data Mahasiswa</title>
    {{-- Aset CSS dan lainnya tetap sama --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('bootstrap/vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('bootstrap/vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('bootstrap/vendors/images/favicon-16x16.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
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
        </div>
        <div class="header-right">
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        							<span class="user-icon">
								<i class="dw dw-user1"></i>
							</span>
                        <span class="user-name">Administrator</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="profil-admin.html"><i class="dw dw-user1"></i> Profil</a>
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
                <img src="{{ asset('bootstrap/vendors/images/deskapp-logo.svg') }}" alt="" class="dark-logo" />
                <img src="{{ asset('bootstrap/vendors/images/deskapp-logo-white.svg') }}" alt="" class="light-logo" />
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
                    <li class="dropdown active">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-mortarboard"></span><span class="mtext">Data Master</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.matakuliah.index') }}">Mata Kuliah</a></li>
                            <li><a href="{{ route('admin.dosen.index') }}">Dosen</a></li>
                            <li class="active"><a href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a></li>
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
                                <h4>Manajemen Data Mahasiswa</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Mahasiswa</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                {{-- FORM INPUT MAHASISWA --}}
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Input Data Mahasiswa</h4>
                            <p class="mb-30">Masukkan informasi mahasiswa baru ke dalam sistem</p>
                        </div>
                    </div>
                    <form id="mahasiswaForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>NIM <span class="text-danger">*</span></label>
                                    <input class="form-control" name="NIM" id="NIM" type="text" placeholder="Contoh: 2110512001" maxlength="20" required />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input class="form-control" name="Nama" id="Nama" type="text" placeholder="Contoh: Budi Santoso" maxlength="100" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <input class="form-control" name="Password" id="Password" type="password" placeholder="Minimal 8 karakter" required />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>No. Handphone</label>
                                    <input class="form-control" name="Nohp" id="Nohp" type="text" placeholder="Contoh: 081234567890" maxlength="20" />
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Semester <span class="text-danger">*</span></label>
                                    <input class="form-control" name="Semester" id="Semester" type="number" placeholder="Contoh: 3" required />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Golongan <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_Gol" id="id_Gol" required>
                                        <option value="">-- Pilih Golongan --</option>
                                        @foreach($golongan as $gol)
                                            <option value="{{ $gol->id_Gol }}">{{ $gol->nama_Gol }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="Alamat" id="Alamat" placeholder="Contoh: Jl. Merdeka No. 17, Nganjuk"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Data</button>
                            <button type="reset" class="btn btn-secondary ml-2"><i class="fa fa-refresh"></i> Reset Form</button>
                        </div>
                    </form>
                </div>

                {{-- TABEL DAFTAR MAHASISWA --}}
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Daftar Mahasiswa</h4>
                        <p class="mb-30">Data mahasiswa yang telah tersimpan dalam sistem</p>
                    </div>
                    <div class="pb-20">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">NIM</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Semester</th>
                                        <th scope="col">Golongan</th>
                                        <th scope="col">No. HP</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($mahasiswa as $item)
                                    <tr id="row_{{ $item->NIM }}">
                                        <td>{{ $item->NIM }}</td>
                                        <td>{{ $item->Nama }}</td>
                                        <td>{{ $item->Semester }}</td>
                                        <td>{{ $item->golongan->nama_Gol }}</td>
                                        <td>{{ $item->Nohp }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="{{ $item->NIM }}"><i class="dw dw-edit2"></i> Edit</a>
                                                    <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="{{ $item->NIM }}"><i class="dw dw-delete-3"></i> Hapus</a>
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

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_NIM" name="NIM">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>NIM</label>
                                    <input class="form-control" id="edit_NIM_display" type="text" readonly />
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
                                    <input class="form-control" name="Password" id="edit_Password" type="password" placeholder="Isi jika ingin mengubah password" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>No. Handphone</label>
                                    <input class="form-control" name="Nohp" id="edit_Nohp" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Semester <span class="text-danger">*</span></label>
                                    <input class="form-control" name="Semester" id="edit_Semester" type="number" required />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                 <div class="form-group">
                                    <label>Golongan <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_Gol" id="edit_id_Gol" required>
                                        <option value="">-- Pilih Golongan --</option>
                                         @foreach($golongan as $gol)
                                            <option value="{{ $gol->id_Gol }}">{{ $gol->nama_Gol }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="Alamat" id="edit_Alamat"></textarea>
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

    {{-- Script JavaScript --}}
    <script src="{{ asset('bootstrap/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/layout-settings.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // CREATE
            $('#mahasiswaForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('mahasiswa.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message });
                            $('#mahasiswaForm')[0].reset();

                            var newRow = `
                                <tr id="row_${response.data.NIM}">
                                    <td>${response.data.NIM}</td>
                                    <td>${response.data.Nama}</td>
                                    <td>${response.data.Semester}</td>
                                    <td>${response.data.golongan.nama_Gol}</td>
                                    <td>${response.data.Nohp || ''}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="${response.data.NIM}"><i class="dw dw-edit2"></i> Edit</a>
                                                <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="${response.data.NIM}"><i class="dw dw-delete-3"></i> Hapus</a>
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
                        $.each(errors, function(key, value) { errorMsg += value[0] + '\n'; });
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: errorMsg });
                    }
                });
            });

            // EDIT
            $('body').on('click', '.btn-edit', function() {
                var nim = $(this).data('id');
                $.get("/mahasiswa/" + nim + "/edit", function(data) {
                    $('#editModal').modal('show');
                    $('#edit_NIM').val(data.NIM);
                    $('#edit_NIM_display').val(data.NIM);
                    $('#edit_Nama').val(data.Nama);
                    $('#edit_Alamat').val(data.Alamat);
                    $('#edit_Nohp').val(data.Nohp);
                    $('#edit_Semester').val(data.Semester);
                    $('#edit_id_Gol').val(data.id_Gol);
                    $('#edit_Password').val('');
                });
            });

            // UPDATE
            $('#updateBtn').on('click', function() {
                var nim = $('#edit_NIM').val();
                $.ajax({
                    url: "/mahasiswa/" + nim,
                    type: 'PUT',
                    data: $('#editForm').serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editModal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message });

                            var updatedRowContent = `
                                <td>${response.data.NIM}</td>
                                <td>${response.data.Nama}</td>
                                <td>${response.data.Semester}</td>
                                <td>${response.data.golongan.nama_Gol}</td>
                                <td>${response.data.Nohp || ''}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="${response.data.NIM}"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="${response.data.NIM}"><i class="dw dw-delete-3"></i> Hapus</a>
                                        </div>
                                    </div>
                                </td>`;
                            $('#row_' + nim).html(updatedRowContent);
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMsg = '';
                        $.each(errors, function(key, value) { errorMsg += value[0] + '\n'; });
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: errorMsg });
                    }
                });
            });

            // DELETE
            $('body').on('click', '.btn-delete', function() {
                var nim = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?', text: "Data yang dihapus tidak dapat dikembalikan!", icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "/mahasiswa/" + nim,
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Dihapus!', response.message, 'success');
                                    $('#row_' + nim).remove();
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