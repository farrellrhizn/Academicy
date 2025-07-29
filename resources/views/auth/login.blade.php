<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Academicy - Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                <a href="#">
                    <img src="{{ asset('bootstrap/vendors/images/deskapp-logo.svg') }}" alt="Academicy" />
                </a>
            </div>
        </div>
    </div>

    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{ asset('bootstrap/vendors/images/login-page-img.png') }}" alt="Login Image" />
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Login To Academicy</h2>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="input-group custom">
                                <select name="user_type" class="form-control form-control-lg">
                                    <option value="mahasiswa">Login sebagai Mahasiswa</option>
                                    <option value="dosen">Login sebagai Dosen</option>
                                    <option value="admin">Login sebagai Admin</option>
                                </select>
                            </div>
                            <div class="pb-2"></div> <div class="input-group custom">
                                <input type="text"
                                    class="form-control form-control-lg @error('login_id') is-invalid @enderror"
                                    name="login_id"
                                    id="login_id"
                                    placeholder="NIP / NIM / Username"
                                    value="{{ old('login_id') }}"
                                    required
                                    autofocus />
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        <i class="icon-copy dw dw-user1"></i>
                                    </span>
                                </div>
                            </div>
                            @error('login_id')
                                <div class="alert alert-danger py-2" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="input-group custom">
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="**********"
                                    required />
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        <i class="dw dw-padlock1"></i>
                                    </span>
                                </div>
                            </div>
                            @error('password')
                                <div class="alert alert-danger py-2" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            name="remember"
                                            id="remember"
                                        />
                                        <label class="custom-control-label" for="remember">Remember</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/layout-settings.js') }}"></script>

</body>
</html>