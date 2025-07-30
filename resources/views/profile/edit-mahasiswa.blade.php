<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Edit Profile Mahasiswa</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('bootstrap/vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('bootstrap/vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('bootstrap/vendors/images/favicon-16x16.png') }}" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/profile-photo.css') }}" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="{{ asset('bootstrap/vendors/images/deskapp-logo.svg') }}" alt="" />
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
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            @if($mahasiswa->profile_photo)
                                <img src="{{ asset('storage/profile_photos/' . $mahasiswa->profile_photo) }}" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                            @else
                                <i class="dw dw-user1"></i>
                            @endif
                        </span>
                        <span class="user-name">{{ $mahasiswa->Nama }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="{{ route('mahasiswa.profile.edit') }}"><i class="dw dw-user1"></i> Profile</a>
                        <a class="dropdown-item" href="{{ route('mahasiswa.dashboard') }}"><i class="dw dw-analytics-21"></i> Dashboard</a>
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
                        <a href="{{ route('mahasiswa.dashboard') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mahasiswa.krs.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-book"></span><span class="mtext">KRS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mahasiswa.jadwal.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-calendar"></span><span class="mtext">Jadwal Kuliah</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mahasiswa.presensi.riwayat') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-check-circle"></span><span class="mtext">Riwayat Presensi</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ route('mahasiswa.profile.edit') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-person"></span><span class="mtext">Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="title">
                                <h4><i class="icon-copy bi bi-person-circle"></i> Edit Profile</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="icon-copy bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="icon-copy bi bi-exclamation-triangle-fill"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Profile Overview Card -->
                <div class="row mb-30">
                    <div class="col-xl-12">
                        <div class="card-box height-100-p">
                            <div class="profile-info-container">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                                        <div class="profile-photo-section">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="profile_photo" accept=".png, .jpg, .jpeg, .gif" />
                                                    <label for="imageUpload" title="Upload Photo"><i class="bi bi-camera-fill"></i></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <div id="imagePreview" style="background-image: url('{{ $mahasiswa->profile_photo ? asset('storage/profile_photos/' . $mahasiswa->profile_photo) : asset('bootstrap/vendors/images/photo2.jpg') }}');">
                                                    </div>
                                                </div>
                                            </div>
                                            @if($mahasiswa->profile_photo)
                                                <button type="button" class="btn btn-danger btn-sm mt-3" onclick="deletePhoto()">
                                                    <i class="icon-copy bi bi-trash"></i> Hapus Foto
                                                </button>
                                            @endif
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="icon-copy bi bi-info-circle"></i> 
                                                    Upload foto profil (max 2MB, format: JPG, PNG, GIF)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="profile-overview">
                                            <h5 class="text-blue">{{ $mahasiswa->Nama }}</h5>
                                            <p class="text-muted mb-1"><i class="icon-copy bi bi-card-text"></i> NIM: {{ $mahasiswa->NIM }}</p>
                                            <p class="text-muted mb-1"><i class="icon-copy bi bi-mortarboard"></i> Semester: {{ $mahasiswa->Semester }}</p>
                                            <p class="text-muted mb-1"><i class="icon-copy bi bi-award"></i> Golongan: {{ $mahasiswa->golongan->nama_Gol ?? 'Tidak ada' }}</p>
                                            <p class="text-muted"><i class="icon-copy bi bi-geo-alt"></i> {{ $mahasiswa->Alamat ?: 'Alamat belum diisi' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <div class="card-header">
                                <h5 class="card-title"><i class="icon-copy bi bi-pencil-square"></i> Edit Informasi Profile</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('mahasiswa.profile.update') }}" enctype="multipart/form-data" id="profileForm">
                                    @csrf
                                    
                                    <!-- Personal Information Section -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-blue border-bottom pb-2 mb-4"><i class="icon-copy bi bi-person-fill"></i> Informasi Personal</h6>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="NIM"><i class="icon-copy bi bi-card-text"></i> NIM <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="{{ $mahasiswa->NIM }}" readonly style="background-color: #f8f9fa;" />
                                                <small class="text-muted">NIM tidak dapat diubah</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Semester"><i class="icon-copy bi bi-mortarboard"></i> Semester <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="{{ $mahasiswa->Semester }}" readonly style="background-color: #f8f9fa;" />
                                                <small class="text-muted">Semester diatur oleh admin</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="Nama"><i class="icon-copy bi bi-person"></i> Nama Lengkap <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="Nama" value="{{ old('Nama', $mahasiswa->Nama) }}" required placeholder="Masukkan nama lengkap Anda" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="Golongan"><i class="icon-copy bi bi-award"></i> Golongan <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="{{ $mahasiswa->golongan->nama_Gol ?? 'Tidak ada' }}" readonly style="background-color: #f8f9fa;" />
                                                <small class="text-muted">Golongan diatur oleh admin</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information Section -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="text-blue border-bottom pb-2 mb-4"><i class="icon-copy bi bi-telephone-fill"></i> Informasi Kontak</h6>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="Alamat"><i class="icon-copy bi bi-geo-alt-fill"></i> Alamat Lengkap <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="Alamat" rows="3" required placeholder="Masukkan alamat lengkap Anda">{{ old('Alamat', $mahasiswa->Alamat) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="Nohp"><i class="icon-copy bi bi-phone"></i> No. HP</label>
                                                <input class="form-control" type="text" name="Nohp" value="{{ old('Nohp', $mahasiswa->Nohp) }}" placeholder="Contoh: 08123456789" />
                                                <small class="text-muted">Nomor HP untuk komunikasi</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Security Section -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="text-blue border-bottom pb-2 mb-4"><i class="icon-copy bi bi-shield-lock-fill"></i> Keamanan Akun</h6>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password"><i class="icon-copy bi bi-lock"></i> Password Baru</label>
                                                <input class="form-control" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" />
                                                <small class="text-muted">Minimal 6 karakter</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password_confirmation"><i class="icon-copy bi bi-lock-fill"></i> Konfirmasi Password Baru</label>
                                                <input class="form-control" type="password" name="password_confirmation" placeholder="Ulangi password baru" />
                                                <small class="text-muted">Harus sama dengan password baru</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="form-group d-flex justify-content-between align-items-center">
                                                <div>
                                                    <button type="submit" class="btn btn-primary btn-lg">
                                                        <i class="icon-copy bi bi-check-circle"></i> Simpan Perubahan
                                                    </button>
                                                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary btn-lg ml-2">
                                                        <i class="icon-copy bi bi-arrow-left"></i> Kembali
                                                    </a>
                                                </div>
                                                <div class="text-muted">
                                                    <small><i class="icon-copy bi bi-info-circle"></i> Semua perubahan akan disimpan secara permanen</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/layout-settings.js') }}"></script>

    <style>
        /* Enhanced Profile Page Styling */
        .profile-info-container {
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
            margin-bottom: 0;
        }
        
        .profile-overview h5 {
            color: white !important;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .profile-overview p {
            color: rgba(255, 255, 255, 0.9) !important;
            margin-bottom: 8px;
        }
        
        .profile-photo-section {
            padding: 20px 0;
        }
        
        .avatar-upload {
            position: relative;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .avatar-upload .avatar-edit {
            position: absolute;
            right: 15px;
            z-index: 1;
            top: 15px;
        }
        
        .avatar-upload .avatar-edit input {
            display: none;
        }
        
        .avatar-upload .avatar-edit input + label {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin-bottom: 0;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #2196F3;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2196F3;
            font-size: 16px;
        }
        
        .avatar-upload .avatar-edit input + label:hover {
            background: #2196F3;
            color: white;
            transform: scale(1.1);
        }
        
        .avatar-upload .avatar-preview {
            width: 180px;
            height: 180px;
            position: relative;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
            margin: 0 auto;
        }
        
        .avatar-upload .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        
        .card-box {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: none;
        }
        
        .card-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px 30px;
            border: none;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            border: 2px solid #6c757d;
            background: transparent;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }
        
        .text-blue {
            color: #667eea !important;
        }
        
        .border-bottom {
            border-bottom: 2px solid #e9ecef !important;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
            color: white;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-info-container {
                padding: 20px;
            }
            
            .avatar-upload .avatar-preview {
                width: 150px;
                height: 150px;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .btn-lg {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
        
        /* Loading States */
        .btn-loading {
            position: relative;
            color: transparent;
        }
        
        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: button-loading-spinner 1s ease infinite;
        }
        
        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            // Profile photo preview functionality
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(650);
                        
                        // Show delete button if hidden
                        if ($('.btn-danger').is(':hidden')) {
                            $('.btn-danger').show();
                        }
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            
            $("#imageUpload").change(function() {
                var file = this.files[0];
                if (file) {
                    // Validate file type
                    var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Hanya file gambar (JPEG, PNG, GIF) yang diizinkan!');
                        $(this).val('');
                        return;
                    }
                    
                    // Validate file size (2MB max)
                    var maxSize = 2 * 1024 * 1024; // 2MB in bytes
                    if (file.size > maxSize) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB.');
                        $(this).val('');
                        return;
                    }
                    
                    readURL(this);
                }
            });

            // Enhanced form submission with AJAX
            $('#profileForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');
                var originalText = submitBtn.html();
                var formData = new FormData(this);
                
                // Add photo file if selected
                var photoFile = $('#imageUpload')[0].files[0];
                if (photoFile) {
                    formData.append('profile_photo', photoFile);
                }
                
                // Show loading state
                submitBtn.prop('disabled', true)
                         .addClass('btn-loading')
                         .html('<i class="icon-copy bi bi-hourglass-split"></i> Menyimpan...');
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update navbar avatar if new photo was uploaded
                            if (response.profile_photo_url) {
                                var navbarImg = $('.user-icon img');
                                if (navbarImg.length) {
                                    navbarImg.attr('src', response.profile_photo_url);
                                } else {
                                    $('.user-icon').html('<img src="' + response.profile_photo_url + '" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">');
                                }
                            }
                            
                            // Show success message
                            showAlert('success', '<i class="icon-copy bi bi-check-circle"></i> ' + response.message);
                            
                            // Show delete button if photo was uploaded and it's hidden
                            if (response.profile_photo_url && $('.btn-danger').is(':hidden')) {
                                $('.btn-danger').show();
                            }
                            
                            // Update profile overview
                            if (response.user_name) {
                                $('.profile-overview h5').text(response.user_name);
                                $('.user-name').text(response.user_name);
                            }
                        } else {
                            showAlert('danger', '<i class="icon-copy bi bi-exclamation-triangle"></i> Gagal memperbarui profile');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.responseText);
                        if (xhr.status === 422) {
                            // Validation errors
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = 'Kesalahan validasi:<br>';
                            for (var field in errors) {
                                errorMessage += '• ' + errors[field][0] + '<br>';
                            }
                            showAlert('danger', '<i class="icon-copy bi bi-exclamation-triangle"></i> ' + errorMessage);
                        } else {
                            showAlert('danger', '<i class="icon-copy bi bi-exclamation-triangle"></i> Terjadi kesalahan saat memperbarui profile');
                        }
                    },
                    complete: function() {
                        // Re-enable button
                        submitBtn.prop('disabled', false)
                                 .removeClass('btn-loading')
                                 .html(originalText);
                    }
                });
            });

            // Delete photo functionality
            window.deletePhoto = function() {
                if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                    var deleteBtn = $('.btn-danger');
                    var originalText = deleteBtn.html();
                    
                    $.ajax({
                        url: '{{ route("profile.delete-photo") }}',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            user_type: 'mahasiswa'
                        },
                        beforeSend: function() {
                            deleteBtn.prop('disabled', true).html('<i class="icon-copy bi bi-hourglass-split"></i> Menghapus...');
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update preview image
                                $('#imagePreview').css('background-image', 'url({{ asset('bootstrap/vendors/images/photo2.jpg') }})');
                                
                                // Update navbar avatar
                                $('.user-icon img').remove();
                                $('.user-icon').html('<i class="dw dw-user1"></i>');
                                
                                // Hide delete button
                                deleteBtn.hide();
                                
                                // Reset file input
                                $('#imageUpload').val('');
                                
                                showAlert('success', '<i class="icon-copy bi bi-check-circle"></i> Foto profil berhasil dihapus');
                            } else {
                                showAlert('danger', '<i class="icon-copy bi bi-exclamation-triangle"></i> Gagal menghapus foto profil: ' + response.message);
                                deleteBtn.prop('disabled', false).html(originalText);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            showAlert('danger', '<i class="icon-copy bi bi-exclamation-triangle"></i> Terjadi kesalahan saat menghapus foto profil');
                            deleteBtn.prop('disabled', false).html(originalText);
                        }
                    });
                }
            }
            
            // Function to show alert messages
            function showAlert(type, message) {
                var alertClass = 'alert-' + type;
                var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                                '<i class="icon-copy bi bi-' + (type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill') + '"></i> ' + message +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div>';
                
                // Remove existing alerts
                $('.alert').remove();
                
                // Add new alert
                $('.page-header').after(alertHtml);
                
                // Auto hide after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut();
                }, 5000);
            }
        });
    </script>
</body>
</html>