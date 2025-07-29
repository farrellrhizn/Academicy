<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KRS - {{ $mahasiswa->NIM }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .container {
                max-width: 100% !important;
            }
            body {
                background: white !important;
                font-size: 12px;
            }
            .table {
                border-collapse: collapse !important;
            }
            .table, .table th, .table td {
                border: 1px solid #000 !important;
            }
        }
        
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .info-mahasiswa {
            margin-bottom: 30px;
        }
        
        .table-krs {
            border-collapse: collapse;
            width: 100%;
        }
        
        .table-krs th, .table-krs td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        .table-krs th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .ttd-section {
            margin-top: 50px;
        }
        
        .ttd-box {
            width: 200px;
            height: 100px;
            border: 1px solid #000;
            display: inline-block;
            text-align: center;
            vertical-align: top;
            margin: 0 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Tombol Print (disembunyikan saat print) -->
        <div class="row mb-3 no-print">
            <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary me-2">
                    <i class="bi bi-printer"></i> Print KRS
                </button>
                <a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Kop Surat -->
        <div class="kop-surat">
            <h3 style="margin: 0; font-weight: bold;">UNIVERSITAS CONTOH</h3>
            <h4 style="margin: 5px 0; font-weight: bold;">FAKULTAS TEKNOLOGI INFORMASI</h4>
            <h5 style="margin: 5px 0;">PROGRAM STUDI SISTEM INFORMASI</h5>
            <p style="margin: 5px 0; font-size: 14px;">
                Jl. Contoh No. 123, Kota Contoh, Provinsi Contoh 12345<br>
                Telp: (021) 123-4567 | Email: info@universitas-contoh.ac.id
            </p>
        </div>

        <!-- Judul Dokumen -->
        <div class="text-center mb-4">
            <h3 style="text-decoration: underline; font-weight: bold;">
                KARTU RENCANA STUDI (KRS)
            </h3>
            <p style="margin: 5px 0;">SEMESTER {{ $mahasiswa->Semester }} TAHUN AKADEMIK {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        </div>

        <!-- Informasi Mahasiswa -->
        <div class="info-mahasiswa">
            <div class="row">
                <div class="col-md-6">
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td style="width: 30%; padding: 5px 0; border: none;"><strong>NIM</strong></td>
                            <td style="padding: 5px 0; border: none;">: {{ $mahasiswa->NIM }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; border: none;"><strong>Nama</strong></td>
                            <td style="padding: 5px 0; border: none;">: {{ $mahasiswa->Nama }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; border: none;"><strong>Semester</strong></td>
                            <td style="padding: 5px 0; border: none;">: {{ $mahasiswa->Semester }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td style="width: 30%; padding: 5px 0; border: none;"><strong>Golongan</strong></td>
                            <td style="padding: 5px 0; border: none;">: {{ $mahasiswa->golongan->nama_Gol ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; border: none;"><strong>Total SKS</strong></td>
                            <td style="padding: 5px 0; border: none;">: {{ $totalSks }} SKS</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; border: none;"><strong>Tanggal Cetak</strong></td>
                            <td style="padding: 5px 0; border: none;">: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel KRS -->
        @if($krsData->count() > 0)
            <table class="table-krs">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 15%;">Kode MK</th>
                        <th style="width: 40%;">Nama Mata Kuliah</th>
                        <th style="width: 10%; text-align: center;">SKS</th>
                        <th style="width: 15%;">Hari/Waktu</th>
                        <th style="width: 15%;">Ruang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($krsData as $index => $krs)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $krs->Kode_mk }}</td>
                            <td>{{ $krs->matakuliah->Nama_mk }}</td>
                            <td style="text-align: center;">{{ $krs->matakuliah->sks }}</td>
                            <td>
                                @if($krs->matakuliah->jadwalAkademik->isNotEmpty())
                                    @foreach($krs->matakuliah->jadwalAkademik as $jadwal)
                                        @if($jadwal->id_Gol == $mahasiswa->id_Gol)
                                            {{ $jadwal->hari }}<br>{{ $jadwal->waktu }}
                                        @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($krs->matakuliah->jadwalAkademik->isNotEmpty())
                                    @foreach($krs->matakuliah->jadwalAkademik as $jadwal)
                                        @if($jadwal->id_Gol == $mahasiswa->id_Gol)
                                            {{ $jadwal->ruang->nama_ruang ?? '-' }}
                                        @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8f9fa;">
                        <td colspan="3" style="text-align: center; font-weight: bold;">TOTAL SKS</td>
                        <td style="text-align: center; font-weight: bold;">{{ $totalSks }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div style="text-align: center; padding: 50px; border: 1px solid #000;">
                <p style="font-style: italic; color: #666;">Belum ada mata kuliah yang diambil</p>
            </div>
        @endif

        <!-- Catatan -->
        <div style="margin-top: 30px;">
            <h6 style="font-weight: bold; text-decoration: underline;">CATATAN:</h6>
            <ol style="padding-left: 20px; line-height: 1.6;">
                <li>KRS ini berlaku untuk semester {{ $mahasiswa->Semester }} tahun akademik {{ date('Y') }}/{{ date('Y') + 1 }}</li>
                <li>Perubahan KRS dapat dilakukan sesuai dengan jadwal yang ditetapkan oleh akademik</li>
                <li>Mahasiswa wajib mengikuti seluruh mata kuliah yang tercantum dalam KRS ini</li>
                <li>KRS yang telah disetujui tidak dapat diubah tanpa persetujuan akademik</li>
            </ol>
        </div>

        <!-- Tanda Tangan -->
        <div class="ttd-section">
            <div style="display: flex; justify-content: space-between; margin-top: 50px;">
                <div style="text-align: center; width: 30%;">
                    <p style="margin-bottom: 80px;">Mengetahui,<br><strong>Ketua Program Studi</strong></p>
                    <div style="border-bottom: 1px solid #000; margin-bottom: 5px;"></div>
                    <p style="margin: 0; font-size: 12px;">NIP. ________________</p>
                </div>
                
                <div style="text-align: center; width: 30%;">
                    <p style="margin-bottom: 80px;">Menyetujui,<br><strong>Dosen Pembimbing Akademik</strong></p>
                    <div style="border-bottom: 1px solid #000; margin-bottom: 5px;"></div>
                    <p style="margin: 0; font-size: 12px;">NIP. ________________</p>
                </div>
                
                <div style="text-align: center; width: 30%;">
                    <p style="margin-bottom: 80px;">{{ \Carbon\Carbon::now()->format('d F Y') }}<br><strong>Mahasiswa</strong></p>
                    <div style="border-bottom: 1px solid #000; margin-bottom: 5px;"></div>
                    <p style="margin: 0; font-size: 12px;">{{ $mahasiswa->Nama }}</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
            <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Akademik</p>
            <p>Tanggal cetak: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>