<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Input Presensi Cepat</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('bootstrap/vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('bootstrap/vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('bootstrap/vendors/images/favicon-16x16.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/profile-photo.css') }}" />
    <style>
        .attendance-group {
            display: flex;
            border-radius: 0.375rem;
            overflow: hidden;
        }
        .attendance-btn {
            border-radius: 0 !important;
            border-right: 1px solid #dee2e6;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            position: relative;
        }
        .attendance-btn:last-child {
            border-right: none;
        }
        .attendance-btn:first-child {
            border-top-left-radius: 0.375rem !important;
            border-bottom-left-radius: 0.375rem !important;
        }
        .attendance-btn:last-child {
            border-top-right-radius: 0.375rem !important;
            border-bottom-right-radius: 0.375rem !important;
        }
        .attendance-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .attendance-btn:active {
            transform: translateY(0);
        }
        .btn-success:hover {
            background-color: #198754 !important;
            border-color: #146c43 !important;
        }
        .btn-warning:hover {
            background-color: #ffc107 !important;
            border-color: #ffca2c !important;
        }
        .btn-danger:hover {
            background-color: #dc3545 !important;
            border-color: #bb2d3b !important;
        }
    </style>
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

    {{-- Header dan Sidebar --}}
    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
        </div>
        <div class="header-right">
            <div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							@if($dosen->profile_photo)
								<img src="{{ asset('storage/profile_photos/' . $dosen->profile_photo) }}" alt="Profile"
									class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
							@else
								<i class="dw dw-user1"></i>
							@endif
						</span>
						<span class="user-name">{{ $dosen->Nama ?? 'Dosen' }}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="{{ route('dosen.profile.edit') }}"><i class="dw dw-user1"></i>
							Profile</a>
						<a class="dropdown-item" href="{{ route('dosen.profile.edit') }}"><i
								class="dw dw-settings2"></i> Setting</a>
						<form method="POST" action="{{ route('logout') }}" style="display: inline;">
							@csrf
							<button type="submit" class="dropdown-item"
								style="border: none; background: none; width: 100%; text-align: left;">
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
            <a href="index.html">
                <img src="../../bootstrap/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
                <img src="../../bootstrap/vendors/images/deskapp-logo-white.svg" alt="" class="light-logo" />
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
                        <a href="{{ route('dosen.presensi.simple') }}" class="dropdown-toggle no-arrow active">
                            <span class="micon bi bi-journal-check"></span><span class="mtext">Input Presensi Cepat</span>
                        </a>
                    </li>
                    
                                            <li>
                            <a href="{{ route('dosen.mata-kuliah-diampu.index') }}" class="dropdown-toggle no-arrow">
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
                                <h4>Input Presensi Cepat</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dosen.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Input Presensi Cepat</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-6 col-sm-12 text-right">
                            <div class="dropdown">
                                <span class="btn btn-success">
                                    <i class="fa fa-calendar"></i> {{ date('l, d F Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFO CARD --}}
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Input Presensi dengan Pilihan Tanggal</h4>
                            <p class="mb-20">Pilih mata kuliah dan tanggal untuk input atau edit presensi mahasiswa.</p>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <strong>Informasi:</strong> Anda dapat memilih tanggal tertentu untuk input presensi atau mengedit presensi yang sudah ada sebelumnya.
                    </div>
                </div>

                {{-- FORM PILIH MATA KULIAH DAN TANGGAL --}}
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Pilih Mata Kuliah dan Tanggal</h4>
                            <p class="mb-30">Silakan pilih mata kuliah dan tanggal untuk input/edit presensi</p>
                        </div>
                    </div>
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Mata Kuliah <span class="text-danger">*</span></label>
                                    <select class="form-control" name="kode_mk" id="kode_mk" required>
                                        <option value="">-- Pilih Mata Kuliah --</option>
                                        @foreach($matakuliah as $mk)
                                            <option value="{{ $mk->Kode_mk }}" {{ $selectedMk == $mk->Kode_mk ? 'selected' : '' }}>
                                                {{ $mk->Kode_mk }} - {{ $mk->Nama_mk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ $selectedDate }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fa fa-search"></i> Tampilkan Mahasiswa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- FORM INPUT PRESENSI --}}
                @if($mahasiswa && count($mahasiswa) > 0)
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Input Presensi Mahasiswa</h4>
                        <p class="mb-30">
                            Mata Kuliah: <strong>{{ $selectedMkName }}</strong> | 
                            Tanggal: <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</strong> |
                            Hari: <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('l') }}</strong>
                        </p>
                    </div>
                    <div class="pb-20">
                        <form id="presensiForm">
                            @csrf
                            <input type="hidden" name="kode_mk" value="{{ $selectedMk }}">
                            <input type="hidden" name="tanggal" value="{{ $selectedDate }}">
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 15%;">NIM</th>
                                            <th style="width: 35%;">Nama Mahasiswa</th>
                                            <th style="width: 15%;">Golongan</th>
                                            <th style="width: 30%;">Status Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mahasiswa as $index => $mhs)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $mhs->NIM }}</td>
                                            <td>{{ $mhs->Nama }}</td>
                                            <td>{{ $mhs->golongan->nama_Gol ?? '-' }}</td>
                                            <td>
                                                <input type="hidden" name="mahasiswa[{{ $index }}][nim]" value="{{ $mhs->NIM }}">
                                                @php
                                                    $currentStatus = $existingPresensi[$mhs->NIM] ?? null;
                                                @endphp
                                                <div class="btn-group attendance-group" data-nim="{{ $mhs->NIM }}">
                                                    <button type="button" class="btn {{ $currentStatus == 'Hadir' ? 'btn-success' : 'btn-outline-success' }} btn-sm attendance-btn" data-status="Hadir">
                                                        <input type="radio" name="mahasiswa[{{ $index }}][status]" value="Hadir" {{ $currentStatus == 'Hadir' ? 'checked' : '' }} style="display: none;"> Hadir
                                                    </button>
                                                    <button type="button" class="btn {{ $currentStatus == 'Izin' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm attendance-btn" data-status="Izin">
                                                        <input type="radio" name="mahasiswa[{{ $index }}][status]" value="Izin" {{ $currentStatus == 'Izin' ? 'checked' : '' }} style="display: none;"> Izin
                                                    </button>
                                                    <button type="button" class="btn {{ $currentStatus == 'Alpa' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm attendance-btn" data-status="Alpa">
                                                        <input type="radio" name="mahasiswa[{{ $index }}][status]" value="Alpa" {{ $currentStatus == 'Alpa' ? 'checked' : '' }} style="display: none;"> Alpa
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="pd-20">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success" id="markAllHadir">
                                            <i class="fa fa-check"></i> Tandai Semua Hadir
                                        </button>
                                        <button type="button" class="btn btn-secondary ml-2" id="resetForm">
                                            <i class="fa fa-refresh"></i> Reset
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fa fa-save"></i> Simpan Presensi ({{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }})
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- INFO JIKA BELUM ADA DATA --}}
                @if(!$mahasiswa || count($mahasiswa) == 0)
                <div class="card-box mb-30">
                    <div class="pd-20 text-center">
                        <img src="{{ asset('bootstrap/vendors/images/sitting-girl.png') }}" alt="" style="max-width: 200px;">
                        <h4 class="text-muted mt-3">Tidak Ada Data Mahasiswa</h4>
                        <p class="text-muted">Silakan pilih mata kuliah terlebih dahulu untuk menampilkan daftar mahasiswa.</p>
                    </div>
                </div>
                @endif
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
        $(document).ready(function () {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Filter Form Submit
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                var kodeMk = $('#kode_mk').val();
                var tanggal = $('#tanggal').val();
                
                if (!kodeMk) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Silakan pilih mata kuliah terlebih dahulu.'
                    });
                    return;
                }
                
                if (!tanggal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Silakan pilih tanggal terlebih dahulu.'
                    });
                    return;
                }
                
                window.location.href = "{{ route('dosen.presensi.simple') }}?kode_mk=" + kodeMk + "&tanggal=" + tanggal;
            });

            // Attendance button click handler
            $(document).on('click', '.attendance-btn', function() {
                var $button = $(this);
                var $group = $button.closest('.attendance-group');
                var status = $button.data('status');
                var $radio = $button.find('input[type="radio"]');
                
                // Remove active class from all buttons in this group
                $group.find('.attendance-btn').removeClass('btn-success btn-warning btn-danger').addClass(function() {
                    if ($(this).data('status') === 'Hadir') return 'btn-outline-success';
                    if ($(this).data('status') === 'Izin') return 'btn-outline-warning';
                    if ($(this).data('status') === 'Alpa') return 'btn-outline-danger';
                });
                
                // Add active class to clicked button
                $button.removeClass('btn-outline-success btn-outline-warning btn-outline-danger');
                if (status === 'Hadir') {
                    $button.addClass('btn-success');
                } else if (status === 'Izin') {
                    $button.addClass('btn-warning');
                } else if (status === 'Alpa') {
                    $button.addClass('btn-danger');
                }
                
                // Check the corresponding radio button
                $radio.prop('checked', true);
            });

            // Mark All Hadir
            $('#markAllHadir').on('click', function() {
                $('.attendance-group').each(function() {
                    var $group = $(this);
                    var $hadirBtn = $group.find('[data-status="Hadir"]');
                    
                    // Reset all buttons in group
                    $group.find('.attendance-btn').removeClass('btn-success btn-warning btn-danger')
                          .addClass(function() {
                              if ($(this).data('status') === 'Hadir') return 'btn-outline-success';
                              if ($(this).data('status') === 'Izin') return 'btn-outline-warning';
                              if ($(this).data('status') === 'Alpa') return 'btn-outline-danger';
                          });
                    
                    // Activate hadir button
                    $hadirBtn.removeClass('btn-outline-success').addClass('btn-success');
                    $hadirBtn.find('input[type="radio"]').prop('checked', true);
                });
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Semua mahasiswa telah ditandai hadir.',
                    timer: 1500,
                    showConfirmButton: false
                });
            });

            // Reset Form
            $('#resetForm').on('click', function() {
                $('.attendance-group').each(function() {
                    var $group = $(this);
                    
                    // Reset all buttons to outline style
                    $group.find('.attendance-btn').removeClass('btn-success btn-warning btn-danger')
                          .addClass(function() {
                              if ($(this).data('status') === 'Hadir') return 'btn-outline-success';
                              if ($(this).data('status') === 'Izin') return 'btn-outline-warning';
                              if ($(this).data('status') === 'Alpa') return 'btn-outline-danger';
                          });
                    
                    // Uncheck all radio buttons
                    $group.find('input[type="radio"]').prop('checked', false);
                });
            });

            // Submit Presensi Form
            $('#presensiForm').on('submit', function (e) {
                e.preventDefault();
                
                // Validasi - pastikan semua mahasiswa sudah dipilih statusnya
                var totalMahasiswa = $('input[name*="[nim]"]').length;
                var selectedCount = $('input[type="radio"]:checked').length;
                
                if (selectedCount !== totalMahasiswa) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Silakan pilih status kehadiran untuk semua mahasiswa.'
                    });
                    return;
                }

                // Konfirmasi sebelum menyimpan
                Swal.fire({
                    title: 'Konfirmasi Simpan',
                    text: 'Apakah Anda yakin ingin menyimpan data presensi untuk tanggal ' + '{{ \Carbon\Carbon::parse($selectedDate)->format("d F Y") }}' + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menyimpan Data...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit via AJAX
                        $.ajax({
                            url: "{{ route('dosen.presensi.store-simple') }}",
                            type: 'POST',
                            data: $(this).serialize(),
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Refresh halaman untuk menampilkan data terbaru
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.close();
                                var errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                                
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    var errors = xhr.responseJSON.errors;
                                    errorMsg = '';
                                    $.each(errors, function (key, value) {
                                        errorMsg += value[0] + '\n';
                                    });
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>