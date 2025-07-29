<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Academicy - Register</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('bootstrap/vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('bootstrap/vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('bootstrap/vendors/images/favicon-16x16.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
</head>
<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('login') }}">
                    <img src="{{ asset('bootstrap/vendors/images/deskapp-logo.svg') }}" alt="" />
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="register-page-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{ asset('bootstrap/vendors/images/register-page-img.png') }}" alt="" />
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="register-box bg-white box-shadow border-radius-10">
                        <div class="p-4">
                            <h2 class="text-center text-primary mb-3">Register Account</h2>

                            {{-- Menampilkan error validasi jika ada --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label>Saya mendaftar sebagai:</label>
                                    <div class="d-flex">
                                        <div class="custom-control custom-radio mr-4">
                                            <input type="radio" id="dosen" name="user_type" value="dosen" class="custom-control-input" {{ old('user_type') == 'dosen' ? 'checked' : '' }} required>
                                            <label class="custom-control-label" for="dosen">Dosen</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="mahasiswa" name="user_type" value="mahasiswa" class="custom-control-input" {{ old('user_type', 'mahasiswa') == 'mahasiswa' ? 'checked' : '' }} required>
                                            <label class="custom-control-label" for="mahasiswa">Mahasiswa</label>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Input untuk Dosen --}}
                                <div id="dosen-fields" style="display: none;">
                                    <div class="form-group">
                                        <label>NIP</label>
                                        <input class="form-control" type="text" name="NIP" value="{{ old('NIP') }}">
                                    </div>
                                </div>

                                {{-- Input untuk Mahasiswa --}}
                                <div id="mahasiswa-fields" style="display: none;">
                                    <div class="form-group">
                                        <label>NIM</label>
                                        <input class="form-control" type="text" name="NIM" value="{{ old('NIM') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Semester</label>
                                        <input class="form-control" type="number" name="Semester" value="{{ old('Semester') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>ID Golongan</label>
                                        <input class="form-control" type="number" name="id_Gol" value="{{ old('id_Gol') }}">
                                    </div>
                                </div>

                                {{-- Input Umum --}}
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input class="form-control" type="text" name="Nama" value="{{ old('Nama') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea class="form-control" name="Alamat" required>{{ old('Alamat') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>No. HP</label>
                                    <input class="form-control" type="text" name="Nohp" value="{{ old('Nohp') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="Password" required>
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Password</label>
                                    <input class="form-control" type="password" name="Password_confirmation" required>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="input-group mb-0">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">Register</button>
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
    <script src="{{ asset('bootstrap/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/script.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
            const dosenFields = document.getElementById('dosen-fields');
            const mahasiswaFields = document.getElementById('mahasiswa-fields');
            
            function toggleFields() {
                const selectedType = document.querySelector('input[name="user_type"]:checked').value;
                if (selectedType === 'dosen') {
                    dosenFields.style.display = 'block';
                    mahasiswaFields.style.display = 'none';
                } else {
                    dosenFields.style.display = 'none';
                    mahasiswaFields.style.display = 'block';
                }
            }

            userTypeRadios.forEach(radio => radio.addEventListener('change', toggleFields));
            
            // Panggil sekali saat halaman dimuat untuk menyesuaikan dengan nilai `old()`
            toggleFields();
        });
    </script>
</body>
</html>