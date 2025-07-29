<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Kelola Jadwal Akademik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Aset CSS Anda --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/vendors/styles/style.css') }}" />
    {{-- DataTables CSS --}}
    <link rel="stylesheet" type="text/css"
        href="{{ asset('bootstrap/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('bootstrap/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
</head>

<body>
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="../../../bootstrap/vendors/images/deskapp-logo.svg" alt="" />
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
                            <img src="../../../bootstrap/vendors/images/photo1.jpg" alt="" />
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
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow active">
                            <span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-mortarboard"></span><span class="mtext">Data Master</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.matakuliah.index') }}">Mata Kuliah</a></li>
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

    {{-- Main Content --}}
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Kelola Jadwal Akademik</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Jadwal</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kelola Jadwal</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                {{-- TABEL DAFTAR JADWAL --}}
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Daftar Jadwal Akademik</h4>
                    </div>
                    <div class="pb-20">
                        {{-- Tambahkan filter di sini jika perlu --}}
                        <div class="px-20 form-inline">
                            <label class="mr-2">Filter Hari: </label>
                            <select id="filterHari" class="form-control col-md-3">
                                <option value="">Semua Hari</option>
                                <option>Senin</option>
                                <option>Selasa</option>
                                <option>Rabu</option>
                                <option>Kamis</option>
                                <option>Jumat</option>
                            </select>
                        </div>
                        <br>
                        <table class="data-table table stripe hover nowrap" id="jadwalTable">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">No</th>
                                    <th>Hari</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen</th>
                                    <th>Ruangan</th>
                                    <th>Golongan</th>
                                    <th class="datatable-nosort">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwal as $index => $item)
                                    <tr id="row-{{ $item->id_jadwal }}">
                                        <td class="table-plus">{{ $index + 1 }}</td>
                                        <td>{{ $item->hari }}</td>
                                        <td>{{ $item->matakuliah->Nama_mk ?? 'N/A' }}</td>
                                        {{-- Mengambil nama dosen dari relasi --}}
                                        <td>{{ $item->matakuliah?->pengampu?->first()?->dosen?->Nama ?? 'Belum Ditentukan' }}
                                        </td>
                                        <td>{{ $item->ruang->nama_ruang ?? 'N/A' }}</td>
                                        <td>{{ $item->golongan->nama_Gol ?? 'N/A' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown"><i
                                                        class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    {{-- 1. Tambahkan data-nip pada tombol edit --}}
                                                    <a class="dropdown-item btn-edit" href="javascript:void(0)"
                                                        data-id="{{ $item->id_jadwal }}" data-hari="{{ $item->hari }}"
                                                        data-kodemk="{{ $item->Kode_mk }}"
                                                        data-nip="{{ $item->matakuliah?->pengampu?->first()?->NIP ?? '' }}"
                                                        data-idruang="{{ $item->id_ruang }}"
                                                        data-idgol="{{ $item->id_Gol }}">
                                                        <i class="dw dw-edit2"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item btn-delete" href="javascript:void(0)"
                                                        data-id="{{ $item->id_jadwal }}">
                                                        <i class="dw dw-delete-3"></i> Hapus
                                                    </a>
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

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jadwal Akademik</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" name="id_jadwal" id="edit_id_jadwal">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hari</label>
                                    <select class="form-control" name="hari" required>
                                        <option value="">-- Pilih Hari --</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mata Kuliah</label>
                                    <select class="form-control" name="Kode_mk" required>
                                        <option value="">-- Pilih Mata Kuliah --</option>
                                        @foreach($matakuliah as $mk)
                                            <option value="{{ $mk->Kode_mk }}">{{ $mk->Nama_mk }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dosen</label>
                                    <select class="form-control" name="NIP" required>
                                        <option value="">-- Pilih Dosen --</option>
                                        @foreach($dosen as $d)
                                            <option value="{{ $d->NIP }}">{{ $d->Nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ruangan</label>
                                    <select class="form-control" name="id_ruang" required>
                                        <option value="">-- Pilih Ruangan --</option>
                                        @foreach($ruang as $r)
                                            <option value="{{ $r->id_ruang }}">{{ $r->nama_ruang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Golongan</label>
                                    <select class="form-control" name="id_Gol" required>
                                        <option value="">-- Pilih Golongan --</option>
                                        @foreach($golongan as $gol)
                                            <option value="{{ $gol->id_Gol }}">{{ $gol->nama_Gol }}</option>
                                        @endforeach
                                    </select>
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

    {{-- Script JavaScript --}}
    <script src="{{ asset('bootstrap/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/layout-settings.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- DataTables JS --}}
    <script src="{{ asset('bootstrap/src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bootstrap/src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('bootstrap/src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('bootstrap/src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
    $(document).ready(function () {
        // Setup CSRF Token untuk semua request AJAX
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Inisialisasi DataTable
        var table = $('#jadwalTable').DataTable({
            // Opsi DataTable Anda bisa ditambahkan di sini
        });

        // Filter tabel berdasarkan hari
        $('#filterHari').on('change', function () {
            table.column(1).search(this.value).draw();
        });

        // FUNGSI EDIT: Mengisi modal saat tombol edit diklik
        $('body').on('click', '.btn-edit', function () {
            var id = $(this).data('id');
            $('#edit_id_jadwal').val(id);

            // Mengisi form di dalam modal dengan data dari tombol
            $('#editForm select[name="hari"]').val($(this).data('hari'));
            $('#editForm select[name="Kode_mk"]').val($(this).data('kodemk'));
            $('#editForm select[name="NIP"]').val($(this).data('nip'));
            $('#editForm select[name="id_ruang"]').val($(this).data('idruang'));
            $('#editForm select[name="id_Gol"]').val($(this).data('idgol'));
            
            $('#editModal').modal('show');
        });

        // FUNGSI UPDATE: Menyimpan perubahan dari modal
        $('#updateBtn').on('click', function () {
            var form = $('#editForm');
            var id = $('#edit_id_jadwal').val();

            // Validasi sederhana di sisi client
            if (!form.find('select[name="hari"]').val() || !form.find('select[name="Kode_mk"]').val() || !form.find('select[name="NIP"]').val() || !form.find('select[name="id_ruang"]').val() || !form.find('select[name="id_Gol"]').val()) {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Semua field wajib diisi!' });
                return;
            }
            
            // Membuat URL yang benar menggunakan route helper Laravel
            var url = "{{ route('admin.kelola-jadwal.update', ':id') }}";
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'POST', // Sesuai dengan route `Route::post` di web.php
                data: form.serialize(),
                success: function (response) {
                    $('#editModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message })
                        .then(() => {
                            location.reload(); // Muat ulang halaman untuk melihat perubahan
                        });
                },
                error: function (xhr) {
                    let errorMessage = 'Terjadi kesalahan pada server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: `Gagal! (Error ${xhr.status})`,
                        text: errorMessage,
                    });
                }
            });
        });

        // FUNGSI DELETE: Menghapus data
        $('body').on('click', '.btn-delete', function () {
            var id = $(this).data('id');
            
            // Membuat URL yang benar menggunakan route helper Laravel
            var url = "{{ route('admin.kelola-jadwal.destroy', ':id') }}";
            url = url.replace(':id', id);

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data jadwal ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE", // Method yang benar untuk menghapus
                        url: url,
                        success: function (response) {
                            Swal.fire('Dihapus!', response.message, 'success');
                            // Hapus baris dari DataTable secara dinamis tanpa reload
                            table.row('#row-' + id).remove().draw(false);
                        },
                        error: function (xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON.message || 'Terjadi kesalahan saat menghapus data.' });
                        }
                    });
                }
            });
        });
    });
</script>
</body>

</html>