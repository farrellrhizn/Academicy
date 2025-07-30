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
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Edit Profile</h4>
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
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
                        <div class="card-box height-100-p overflow-hidden">
                            <div class="profile-tab height-100-p">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="setting" role="tabpanel">
                                        <div class="profile-setting">
                                            <form method="POST" action="{{ route('mahasiswa.profile.update') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="profile-photo text-center">
                                                            <div class="avatar-upload">
                                                                <div class="avatar-edit">
                                                                    <input type='file' id="imageUpload" name="profile_photo" accept=".png, .jpg, .jpeg" />
                                                                    <label for="imageUpload"></label>
                                                                </div>
                                                                <div class="avatar-preview">
                                                                    <div id="imagePreview" style="background-image: url('{{ $mahasiswa->profile_photo ? asset('storage/profile_photos/' . $mahasiswa->profile_photo) : asset('bootstrap/vendors/images/photo2.jpg') }}');">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if($mahasiswa->profile_photo)
                                                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deletePhoto()">
                                                                    <i class="fa fa-trash"></i> Hapus Foto
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="NIM">NIM</label>
                                                            <input class="form-control" type="text" value="{{ $mahasiswa->NIM }}" readonly />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Nama">Nama Lengkap</label>
                                                            <input class="form-control" type="text" name="Nama" value="{{ old('Nama', $mahasiswa->Nama) }}" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Alamat">Alamat</label>
                                                            <textarea class="form-control" name="Alamat" rows="3" required>{{ old('Alamat', $mahasiswa->Alamat) }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Nohp">No. HP</label>
                                                            <input class="form-control" type="text" name="Nohp" value="{{ old('Nohp', $mahasiswa->Nohp) }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Semester">Semester</label>
                                                            <input class="form-control" type="text" value="{{ $mahasiswa->Semester }}" readonly />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Golongan">Golongan</label>
                                                            <input class="form-control" type="text" value="{{ $mahasiswa->golongan->nama_Gol ?? 'Tidak ada' }}" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Ubah Password (Opsional)</label>
                                                            <input class="form-control" type="password" name="password" placeholder="Password baru (kosongkan jika tidak ingin mengubah)" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Konfirmasi Password</label>
                                                            <input class="form-control" type="password" name="password_confirmation" placeholder="Konfirmasi password baru" />
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-save"></i> Simpan Perubahan
                                                    </button>
                                                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                                                        <i class="fa fa-arrow-left"></i> Kembali
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
        .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 50px auto;
        }
        .avatar-upload .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }
        .avatar-upload .avatar-edit input {
            display: none;
        }
        .avatar-upload .avatar-edit input + label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.2s ease-in-out;
        }
        .avatar-upload .avatar-edit input + label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }
        .avatar-upload .avatar-edit input + label:after {
            content: "\f040";
            font-family: 'FontAwesome';
            color: #757575;
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }
        .avatar-upload .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }
        .avatar-upload .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
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
            $('form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');
                var originalText = submitBtn.text();
                var formData = new FormData(this);
                
                // Show loading state
                submitBtn.prop('disabled', true).text('Menyimpan...');
                
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
                            alert(response.message);
                            
                            // Show delete button if photo was uploaded and it's hidden
                            if (response.profile_photo_url && $('.btn-danger').is(':hidden')) {
                                $('.btn-danger').show();
                            }
                        } else {
                            alert('Gagal memperbarui profile');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.responseText);
                        if (xhr.status === 422) {
                            // Validation errors
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = 'Validation errors:\n';
                            for (var field in errors) {
                                errorMessage += '- ' + errors[field][0] + '\n';
                            }
                            alert(errorMessage);
                        } else {
                            alert('Terjadi kesalahan saat memperbarui profile');
                        }
                    },
                    complete: function() {
                        // Re-enable button
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Delete photo functionality
            window.deletePhoto = function() {
                if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                    $.ajax({
                        url: '{{ route("profile.delete-photo") }}',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            user_type: 'mahasiswa'
                        },
                        beforeSend: function() {
                            $('.btn-danger').prop('disabled', true).text('Menghapus...');
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update preview image
                                $('#imagePreview').css('background-image', 'url({{ asset('bootstrap/vendors/images/photo2.jpg') }})');
                                
                                // Update navbar avatar
                                $('.user-icon img').remove();
                                $('.user-icon').html('<i class="dw dw-user1"></i>');
                                
                                // Hide delete button
                                $('.btn-danger').hide();
                                
                                // Reset file input
                                $('#imageUpload').val('');
                                
                                alert('Foto profil berhasil dihapus');
                            } else {
                                alert('Gagal menghapus foto profil: ' + response.message);
                                $('.btn-danger').prop('disabled', false).text('Hapus Foto');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus foto profil');
                            $('.btn-danger').prop('disabled', false).text('Hapus Foto');
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>