<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Edit Profile Dosen</title>

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
    
    <style>
        /* Profile Page Styling - Consistent with Dashboard */
        .main-container {
            min-height: 100vh;
        }
        
        .profile-header-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(34, 41, 47, 0.1);
            color: #495057;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid #e3e6f0;
        }
        
        .profile-title {
            color: #5a5c69;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .profile-photo-container {
            position: relative;
            z-index: 2;
        }
        
        .avatar-upload {
            position: relative;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .avatar-upload .avatar-edit {
            position: absolute;
            right: 10px;
            z-index: 1;
            top: 10px;
        }
        
        .avatar-upload .avatar-edit input {
            display: none;
        }
        
        .avatar-upload .avatar-edit input + label {
            display: inline-block;
            width: 45px;
            height: 45px;
            margin-bottom: 0;
            border-radius: 50%;
            background: #858796;
            border: 3px solid #fff;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }
        
        .avatar-upload .avatar-edit input + label:hover {
            background: #5a5c69;
            transform: scale(1.1);
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.3);
        }
        
        .avatar-upload .avatar-preview {
            width: 200px;
            height: 200px;
            position: relative;
            border-radius: 50%;
            border: 6px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
            overflow: hidden;
        }
        
        .avatar-upload .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            transition: all 0.3s ease;
        }
        
        .profile-info-section {
            position: relative;
            z-index: 2;
        }
        
        .profile-info-section h2 {
            color: #5a5c69;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.75rem;
        }
        
        .profile-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
            color: #5a5c69;
            font-size: 0.875rem;
        }
        
        .profile-info-item i {
            margin-right: 0.8rem;
            font-size: 1rem;
            color: #858796;
            width: 20px;
        }
        
        .edit-form-card {
            background: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: 1px solid #e3e6f0;
            margin-bottom: 2rem;
        }
        
        .card-header-custom {
            background-color: #f8f9fc;
            color: #5a5c69;
            border-radius: 0.35rem 0.35rem 0 0 !important;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .card-header-custom h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .card-body-custom {
            padding: 1.25rem;
        }
        
        .section-divider {
            border: none;
            height: 1px;
            background: #e3e6f0;
            margin: 1.5rem 0;
            border-radius: 1px;
        }
        
        .section-title {
            color: #5a5c69;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 0.5rem;
            font-size: 1rem;
        }
        
        .form-group label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
        }
        
        .form-group label i {
            margin-right: 0.5rem;
            color: #858796;
        }
        
        .form-control {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.15s ease-in-out;
            background: #fff;
        }
        
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            background: white;
        }
        
        .form-control[readonly] {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
            font-weight: 400;
            font-size: 0.875rem;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        .btn-secondary {
            background-color: #858796;
            border-color: #858796;
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
            font-weight: 400;
            font-size: 0.875rem;
            transition: all 0.15s ease-in-out;
        }
        
        .btn-secondary:hover {
            background-color: #717384;
            border-color: #6b6d7d;
        }
        
        .btn-danger {
            background-color: #e74a3b;
            border-color: #e74a3b;
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
            font-weight: 400;
            font-size: 0.875rem;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .btn-danger:hover {
            background-color: #d63031;
            border-color: #c13029;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 1.2rem 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        
        .upload-info {
            background: #f8f9fc;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
        }
        
        .upload-info small {
            color: #858796;
            font-size: 0.75rem;
        }
        
        /* Loading States */
        .btn-loading {
            position: relative;
            color: transparent;
        }
        
        .btn-loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-header-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .profile-info-section h2 {
                font-size: 1.8rem;
            }
            
            .avatar-upload .avatar-preview {
                width: 160px;
                height: 160px;
            }
            
            .card-body-custom {
                padding: 1.5rem;
            }
            
            .btn-primary, .btn-secondary {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .profile-header-card {
                padding: 1rem;
            }
            
            .avatar-upload .avatar-preview {
                width: 140px;
                height: 140px;
            }
            
            .profile-info-section h2 {
                font-size: 1.6rem;
            }
        }
    </style>
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
                            @if($dosen->profile_photo)
                                <img src="{{ asset('storage/profile_photos/' . $dosen->profile_photo) }}" alt="Profile" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                            @else
                                <i class="dw dw-user1"></i>
                            @endif
                        </span>
                        <span class="user-name">{{ $dosen->Nama }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="{{ route('dosen.profile.edit') }}"><i class="dw dw-user1"></i> Profile</a>
                        <a class="dropdown-item" href="{{ route('dosen.dashboard') }}"><i class="dw dw-analytics-21"></i> Dashboard</a>
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
                        <a href="{{ route('dosen.dashboard') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dosen.jadwal.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-calendar"></span><span class="mtext">Jadwal Mengajar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dosen.presensi.simple') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-check-circle"></span><span class="mtext">Presensi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dosen.mata-kuliah-diampu.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-book"></span><span class="mtext">Mata Kuliah Diampu</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ route('dosen.profile.edit') }}" class="dropdown-toggle no-arrow">
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
                                    <li class="breadcrumb-item"><a href="{{ route('dosen.dashboard') }}">Dashboard</a></li>
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="icon-copy bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
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

                <!-- Profile Header Card -->
                <div class="row">
                    <div class="col-12">
                        <div class="profile-header-card">
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-md-5 col-sm-12">
                                    <div class="profile-photo-container text-center">
                                        <div class="avatar-upload">
                                            <div class="avatar-edit">
                                                <input type='file' id="imageUpload" name="profile_photo" accept=".png, .jpg, .jpeg, .gif" />
                                                <label for="imageUpload" title="Upload Photo"><i class="bi bi-camera-fill"></i></label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="imagePreview" style="background-image: url('{{ $dosen->profile_photo ? asset('storage/profile_photos/' . $dosen->profile_photo) : asset('bootstrap/vendors/images/photo2.jpg') }}');">
                                                </div>
                                            </div>
                                        </div>
                                        @if($dosen->profile_photo)
                                            <button type="button" class="btn btn-danger btn-sm mt-3" onclick="deletePhoto()">
                                                <i class="icon-copy bi bi-trash"></i> Hapus Foto
                                            </button>
                                        @endif
                                        <div class="upload-info">
                                            <small>
                                                <i class="icon-copy bi bi-info-circle"></i> 
                                                Upload foto profil (max 2MB, format: JPG, PNG, GIF)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-12">
                                    <div class="profile-info-section">
                                        <h2>{{ $dosen->Nama }}</h2>
                                        <div class="profile-info-item">
                                            <i class="icon-copy bi bi-card-text"></i>
                                            <span><strong>NIP:</strong> {{ $dosen->NIP }}</span>
                                        </div>
                                        <div class="profile-info-item">
                                            <i class="icon-copy bi bi-phone"></i>
                                            <span><strong>No. HP:</strong> {{ $dosen->Nohp ?: 'Belum diisi' }}</span>
                                        </div>
                                        <div class="profile-info-item">
                                            <i class="icon-copy bi bi-geo-alt"></i>
                                            <span><strong>Alamat:</strong> {{ $dosen->Alamat ?: 'Alamat belum diisi' }}</span>
                                        </div>
                                        <div class="profile-info-item">
                                            <i class="icon-copy bi bi-person-badge"></i>
                                            <span><strong>Status:</strong> Dosen Aktif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="edit-form-card">
                            <div class="card-header-custom">
                                <h5><i class="icon-copy bi bi-pencil-square"></i> Edit Informasi Profile</h5>
                            </div>
                            <div class="card-body-custom">
                                <form method="POST" action="{{ route('dosen.profile.update') }}" enctype="multipart/form-data" id="profileForm">
                                    @csrf
                                    
                                    <!-- Personal Information Section -->
                                    <div class="section-title">
                                        <i class="icon-copy bi bi-person-fill"></i>
                                        Informasi Personal
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="NIP">
                                                    <i class="icon-copy bi bi-card-text"></i> 
                                                    NIP <span class="text-danger">*</span>
                                                </label>
                                                <input class="form-control" type="text" value="{{ $dosen->NIP }}" readonly />
                                                <small class="text-muted">NIP tidak dapat diubah</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Nama">
                                                    <i class="icon-copy bi bi-person"></i> 
                                                    Nama Lengkap <span class="text-danger">*</span>
                                                </label>
                                                <input class="form-control" type="text" name="Nama" value="{{ old('Nama', $dosen->Nama) }}" required placeholder="Masukkan nama lengkap Anda" />
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="section-divider">

                                    <!-- Contact Information Section -->
                                    <div class="section-title">
                                        <i class="icon-copy bi bi-telephone-fill"></i>
                                        Informasi Kontak
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="Alamat">
                                                    <i class="icon-copy bi bi-geo-alt-fill"></i> 
                                                    Alamat Lengkap <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control" name="Alamat" rows="3" required placeholder="Masukkan alamat lengkap Anda">{{ old('Alamat', $dosen->Alamat) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="Nohp">
                                                    <i class="icon-copy bi bi-phone"></i> 
                                                    No. HP <span class="text-danger">*</span>
                                                </label>
                                                <input class="form-control" type="text" name="Nohp" value="{{ old('Nohp', $dosen->Nohp) }}" required placeholder="Contoh: 08123456789" />
                                                <small class="text-muted">Nomor HP untuk komunikasi</small>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="section-divider">

                                    <!-- Security Section -->
                                    <div class="section-title">
                                        <i class="icon-copy bi bi-shield-lock-fill"></i>
                                        Keamanan Akun
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">
                                                    <i class="icon-copy bi bi-lock"></i> 
                                                    Password Baru
                                                </label>
                                                <input class="form-control" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" />
                                                <small class="text-muted">Minimal 6 karakter</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password_confirmation">
                                                    <i class="icon-copy bi bi-lock-fill"></i> 
                                                    Konfirmasi Password Baru
                                                </label>
                                                <input class="form-control" type="password" name="password_confirmation" placeholder="Ulangi password baru" />
                                                <small class="text-muted">Harus sama dengan password baru</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr class="section-divider">
                                    
                                    <!-- Action Buttons -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="submit" class="btn btn-primary btn-lg">
                                                        <i class="icon-copy bi bi-check-circle"></i> Simpan Perubahan
                                                    </button>
                                                    <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary btn-lg">
                                                        <i class="icon-copy bi bi-arrow-left"></i> Kembali
                                                    </a>
                                                </div>
                                                <div class="text-muted mt-2 mt-lg-0">
                                                    <small>
                                                        <i class="icon-copy bi bi-info-circle"></i> 
                                                        Semua perubahan akan disimpan secara permanen
                                                    </small>
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
                                $('.profile-info-section h2').text(response.user_name);
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
                            user_type: 'dosen'
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
                                message +
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