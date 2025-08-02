<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Buat Jadwal Akademik</title>
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
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
                            <li><a href="{{ route('admin.buat-jadwal.index') }}" class="active">Buat Jadwal</a></li>
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
                                <h4>Buat Jadwal Akademik</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Jadwal</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Buat Jadwal</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <h4 class="text-blue h4">Input Jadwal Akademik</h4>
                </div>
                <form id="jadwalForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Semester</label>
                                <select class="form-control" name="semester_filter" id="semester_filter">
                                    <option value="all">-- Semua Semester --</option>
                                    @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hari</label>
                                <select class="form-control" name="hari" required>
                                    <option value="">-- Pilih --</option>
                                    <option>Senin</option>
                                    <option>Selasa</option>
                                    <option>Rabu</option>
                                    <option>Kamis</option>
                                    <option>Jumat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Waktu</label>
                                <input type="time" class="form-control" name="waktu" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mata Kuliah</label>
                                <select class="form-control" name="Kode_mk" id="matakuliah_dropdown" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($matakuliah as $mk)
                                    <option value="{{ $mk->Kode_mk }}" data-semester="{{ $mk->semester }}">{{ $mk->Nama_mk }} (Sem {{ $mk->semester }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ruangan</label>
                                <select class="form-control" name="id_ruang" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($ruang as $r)
                                    <option value="{{ $r->id_ruang }}">{{ $r->nama_ruang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Golongan</label>
                                <select class="form-control" name="id_Gol" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($golongan as $gol)
                                    <option value="{{ $gol->id_Gol }}">{{ $gol->nama_Gol }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </form>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Daftar Jadwal Akademik</h4>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Filter Semester:</label>
                                <select class="form-control" id="table_semester_filter">
                                    <option value="all" {{ ($semesterFilter == 'all' || !$semesterFilter) ? 'selected' : '' }}>-- Semua Semester --</option>
                                    @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ $semesterFilter == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-20">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Mata Kuliah</th>
                                    <th>Semester</th>
                                    <th>Dosen</th>
                                    <th>Ruang</th>
                                    <th>Golongan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach ($jadwal as $item)
                                <tr id="row-{{ $item->id_jadwal }}">
                                    <td>{{ $item->hari }}</td>
                                    <td>{{ date('H:i', strtotime($item->waktu)) }}</td>
                                    <td>{{ $item->matakuliah->Nama_mk ?? 'N/A' }}</td>
                                    <td><span class="badge badge-primary">Sem {{ $item->matakuliah->semester ?? 'N/A' }}</span></td>
                                    <td>{{ $item->matakuliah?->pengampu->first()?->dosen?->Nama ?? 'N/A' }}</td>
                                    <td>{{ $item->ruang->nama_ruang ?? 'N/A' }}</td>
                                    <td>{{ $item->golongan->nama_Gol ?? 'N/A' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item btn-edit" href="javascript:void(0)"
                                                    data-id="{{ $item->id_jadwal }}"
                                                    data-hari="{{ $item->hari }}"
                                                    data-waktu="{{ date('H:i', strtotime($item->waktu)) }}"
                                                    data-kodemk="{{ $item->Kode_mk }}"
                                                    data-idruang="{{ $item->id_ruang }}"
                                                    data-idgol="{{ $item->id_Gol }}">
                                                    <i class="dw dw-edit2"></i> Edit
                                                </a>
                                                <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="{{ $item->id_jadwal }}"><i class="dw dw-delete-3"></i> Hapus</a>
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal Akademik</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" name="id_jadwal" id="edit_id_jadwal">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"><label>Hari</label><select class="form-control" name="hari" id="edit_hari" required>
                                        <option>Senin</option>
                                        <option>Selasa</option>
                                        <option>Rabu</option>
                                        <option>Kamis</option>
                                        <option>Jumat</option>
                                    </select></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label>Waktu</label><input type="time" class="form-control" name="waktu" id="edit_waktu" required></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label>Mata Kuliah</label><select class="form-control" name="Kode_mk" id="edit_kode_mk" required>
                                        @foreach($matakuliah as $mk)<option value="{{ $mk->Kode_mk }}">{{ $mk->Nama_mk }}</option>@endforeach
                                    </select></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>Ruangan</label><select class="form-control" name="id_ruang" id="edit_id_ruang" required>
                                        @foreach($ruang as $r)<option value="{{ $r->id_ruang }}">{{ $r->nama_ruang }}</option>@endforeach
                                    </select></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label>Golongan</label><select class="form-control" name="id_Gol" id="edit_id_gol" required>
                                        @foreach($golongan as $gol)<option value="{{ $gol->id_Gol }}">{{ $gol->nama_Gol }}</option>@endforeach
                                    </select></div>
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

    <script src="{{ asset('bootstrap/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('bootstrap/vendors/scripts/layout-settings.js') }}"></script>
    {{-- DataTables JS --}}
    <script src="{{ asset('bootstrap/src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bootstrap/src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('bootstrap/src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('bootstrap/src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function () {
        // Setup CSRF Token untuk semua request AJAX
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // FUNGSI BARU UNTUK MENAMPILKAN ERROR VALIDASI
        function printErrorMsg(msg, formId) {
            // Hapus pesan error lama
            $(`#${formId} .text-danger`).remove();

            // Tampilkan pesan error baru
            $.each(msg, function (key, value) {
                // Tambahkan pesan error di bawah input yang sesuai
                $(`#${formId} [name="${key}"]`).closest('.form-group').append(`<small class="text-danger">${value[0]}</small>`);
            });
        }

        // FUNGSI FILTER MATA KULIAH BERDASARKAN SEMESTER
        function filterMataKuliah(semester) {
            const matakuliahDropdown = $('#matakuliah_dropdown');
            const options = matakuliahDropdown.find('option');
            
            // Reset dropdown
            matakuliahDropdown.val('');
            
            options.each(function() {
                const option = $(this);
                const optionSemester = option.data('semester');
                
                if (semester === 'all' || semester === '' || optionSemester == semester || option.val() === '') {
                    option.show();
                } else {
                    option.hide();
                }
            });
        }

        // HANDLE PERUBAHAN SEMESTER FILTER
        $('#semester_filter').on('change', function() {
            const semester = $(this).val();
            filterMataKuliah(semester);
        });

        // HANDLE FILTER SEMESTER UNTUK TABEL
        $('#table_semester_filter').on('change', function() {
            const semester = $(this).val();
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('semester', semester);
            window.location.href = currentUrl.toString();
        });

        // CREATE
        $('#jadwalForm').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            
            $.ajax({
                url: "{{ route('admin.buat-jadwal.store') }}",
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 1500, showConfirmButton: false });
                    form[0].reset();
                    $('#tableBody').prepend(createTableRow(response.data));
                    // Hapus pesan error jika sukses
                    form.find('.text-danger').remove();
                },
                error: function (xhr) {
                    if(xhr.status === 422) { // Kode status 422 artinya error validasi
                        printErrorMsg(xhr.responseJSON.errors, 'jadwalForm'); // Panggil fungsi error
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan pada server.' });
                    }
                }
            });
        });

        // EDIT (Populate Modal)
        $('body').on('click', '.btn-edit', function () {
            // Hapus pesan error lama di modal
            $('#editForm .text-danger').remove();

            $('#edit_id_jadwal').val($(this).data('id'));
            $('#edit_hari').val($(this).data('hari'));
            $('#edit_waktu').val($(this).data('waktu'));
            $('#edit_kode_mk').val($(this).data('kodemk'));
            $('#edit_id_ruang').val($(this).data('idruang'));
            $('#edit_id_gol').val($(this).data('idgol'));
            $('#editModal').modal('show');
        });

        // UPDATE
        $('#updateBtn').on('click', function () {
            var form = $('#editForm');
            var id = $('#edit_id_jadwal').val();
            var url = "{{ route('admin.buat-jadwal.update', ':id') }}";
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize() + "&_method=PUT",
                success: function (response) {
                    $('#editModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 1500, showConfirmButton: false });
                    $('#row-' + id).replaceWith(createTableRow(response.data));
                },
                error: function (xhr) {
                    if(xhr.status === 422) {
                        printErrorMsg(xhr.responseJSON.errors, 'editForm'); // Panggil fungsi error
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Gagal memperbarui data.' });
                    }
                }
            });
        });

            // DELETE
            $('body').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                var url = "{{ route('admin.buat-jadwal.destroy', ':id') }}";
                url = url.replace(':id', id);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                _method: 'DELETE'
                            }, // Method spoofing untuk request DELETE
                            success: function(response) {
                                Swal.fire('Dihapus!', response.message, 'success');
                                $('#row-' + id).fadeOut(500, function() {
                                    $(this).remove();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Gagal menghapus data.'
                                });
                            }
                        });
                    }
                });
            });

            // Fungsi untuk membuat baris tabel baru secara dinamis
            function createTableRow(data) {
                var dosenName = data.matakuliah?.pengampu?.[0]?.dosen?.Nama ?? 'N/A';
                var formattedTime = data.waktu.substring(0, 5);
                var semester = data.matakuliah?.semester ?? 'N/A';

                return `
            <tr id="row-${data.id_jadwal}">
                <td>${data.hari}</td>
                <td>${formattedTime}</td>
                <td>${data.matakuliah.Nama_mk}</td>
                <td><span class="badge badge-primary">Sem ${semester}</span></td>
                <td>${dosenName}</td>
                <td>${data.ruang.nama_ruang}</td>
                <td>${data.golongan.nama_Gol}</td>
                <td>
                    <div class="dropdown">
                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                            <a class="dropdown-item btn-edit" href="javascript:void(0)" 
                               data-id="${data.id_jadwal}"
                               data-hari="${data.hari}"
                               data-waktu="${formattedTime}"
                               data-kodemk="${data.Kode_mk}"
                               data-idruang="${data.id_ruang}"
                               data-idgol="${data.id_Gol}">
                                <i class="dw dw-edit2"></i> Edit
                            </a>
                            <a class="dropdown-item btn-delete" href="javascript:void(0)" data-id="${data.id_jadwal}">
                                <i class="dw dw-delete-3"></i> Hapus
                            </a>
                        </div>
                    </div>
                </td>
            </tr>`;
            }
        });
    </script>
</body>

</html>