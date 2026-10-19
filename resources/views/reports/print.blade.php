<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport ASTS - {{ $student->name }}</title>
    <style>
        /* Pengaturan Kertas A4 & Cetak Presisi */
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm; /* Margin pas untuk 1 halaman A4 */
        }
    
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            line-height: 1.15;
            margin: 0; 
            padding: 0;
            color: #000; 
            background: #fff;
        }
    
        /* Kop Surat */
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            border-bottom: 3px double #000;
            padding-bottom: 6px;
        }
        .header-logo-left, .header-logo-right {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }
        .header-text {
            flex-grow: 1;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.15;
            padding: 0 10px;
        }
        .header-text .kemenag { font-size: 12pt; }
        .header-text .school { font-size: 14pt; }
        .header-text .address { font-size: 9pt; font-weight: normal; text-transform: none; }
    
        /* Identitas Siswa */
        .meta-table { width: 100%; margin-bottom: 10px; border-collapse: collapse; font-size: 10.5pt; }
        .meta-table td { padding: 2px 4px; vertical-align: top; }
        
        /* Tabel Nilai */
        .grade-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .grade-table th, .grade-table td { border: 1px solid #000; padding: 4px 6px; font-size: 10pt; }
        .grade-table th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        /* Tabel Tanda Tangan Presisi */
        .footer-table { 
            width: 100%; 
            margin-top: 20px; 
            border-collapse: collapse; 
            page-break-inside: avoid; 
            font-size: 10.5pt;
        }
        .footer-table td { 
            text-align: center; 
            vertical-align: top; 
        }
    
        /* Khusus Tampilan Cetak Browser/PDF */
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            html, body {
                height: 100%;
                overflow: hidden; /* Mencegah halaman ke-2 kosong */
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak Browser -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

   <!-- Header Kop Surat -->
    <div class="header-container">
        <!-- Logo Kemenag PNG/SVG -->
        <img src="{{ asset('images/logo-kemenag.png') }}" class="header-logo-left" alt="Logo Kemenag">
        
        <div class="header-text">
            <div class="kemenag">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
            <div class="school">MTSN 3 PURWOREJO</div>
            <div class="address">JL. MAGELANG KM. 9<br>Kecamatan Loano, Kabupaten Purworejo - Jawa Tengah</div>
        </div>
        
        <img src="{{ asset('images/logo-mtsn.png') }}" class="header-logo-right" alt="Logo Madrasah">
    </div>

    <!-- Identitas Siswa -->
    <table class="meta-table">
        <tr>
            <td width="15%">NAMA</td>
            <td width="2%">:</td>
            <td width="43%" class="font-bold">{{ strtoupper($student->name) }}</td>
            <td width="15%">Kelas</td>
            <td width="2%">:</td>
            <td width="23%">{{ $student->class->name }}</td>
        </tr>
        <tr>
            <td>NIS/NISN</td>
            <td>:</td>
            <td>{{ $student->nis }} / {{ $student->nisn }}</td>
            <td>Fase</td>
            <td>:</td>
            <td>{{ $student->class->phase }}</td>
        </tr>
        <tr>
            <td>Madrasah</td>
            <td>:</td>
            <td>MTsN 3 PURWOREJO</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $student->class->semester }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>JL. MAGELANG KM. 9</td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $student->class->academic_year }}</td>
        </tr>
    </table>

    <div style="text-align: center; font-weight: bold; font-size: 13pt; margin: 15px 0;">CAPAIAN HASIL BELAJAR</div>

    <!-- Tabel Nilai ASTS -->
    <table class="grade-table">
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="62%">Mata Pelajaran</th>
                <th width="30%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $totalScore = 0; @endphp
            @foreach($subjects as $subject)
                @if($subject->children->count() > 0)
                    <!-- Mapel Induk dengan Sub-Mapel (PAI / Mulok) -->
                    <tr>
                        <td class="text-center font-bold">{{ $no++ }}</td>
                        <td class="font-bold">{{ $subject->name }}</td>
                        <td></td>
                    </tr>
                    @foreach($subject->children as $child)
                        @php 
                            $score = $grades[$child->id] ?? null; 
                            if($score) $totalScore += $score;
                        @endphp
                        <tr>
                            <td></td>
                            <td style="padding-left: 25px;">{{ $child->name }}</td>
                            <td class="text-center font-bold">{{ $score ?? '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <!-- Mapel Reguler -->
                    @php 
                        $score = $grades[$subject->id] ?? null; 
                        if($score) $totalScore += $score;
                    @endphp
                    <tr>
                        <td class="text-center font-bold">{{ $no++ }}</td>
                        <td>{{ $subject->name }}</td>
                        <td class="text-center font-bold">{{ $score ?? '-' }}</td>
                    </tr>
                @endif
            @endforeach
            <!-- Jumlah Nilai -->
            <tr>
                <td colspan="2" class="font-bold" style="text-align: right; padding-right: 15px;">Jumlah</td>
                <td class="text-center font-bold" style="font-size: 12pt;">{{ $totalScore }}</td>
            </tr>
            </tbody>
    </table>
    <!-- Tabel Presensi & Catatan Wali Kelas -->
    <!-- Tabel Presensi & Catatan Wali Kelas (Simetris) -->
    <table style="width: 100%; margin-bottom: 12px; border-collapse: collapse;">
        <tr>
            <!-- Tabel Presensi Ketidakhadiran (Kiri) -->
            <td width="42%" style="vertical-align: top; padding-right: 8px;">
                <table class="grade-table" style="margin-bottom: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th colspan="2" style="padding: 4px;">Ketidakhadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="65%" style="padding: 4px 6px;">Sakit</td>
                            <td class="text-center font-bold" width="35%" style="padding: 4px 6px;">{{ $summary->sick_count ?? 0 }} hari</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 6px;">Izin</td>
                            <td class="text-center font-bold" style="padding: 4px 6px;">{{ $summary->permission_count ?? 0 }} hari</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 6px;">Tanpa Keterangan</td>
                            <td class="text-center font-bold" style="padding: 4px 6px;">{{ $summary->unexcused_count ?? 0 }} hari</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <!-- Catatan Wali Kelas (Kanan - Tinggi disesuaikan persis) -->
            <td width="58%" style="vertical-align: top; padding-left: 8px;">
                <table class="grade-table" style="margin-bottom: 0; width: 100%; height: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 4px;">Catatan Wali Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="vertical-align: top; font-style: italic; font-size: 9.5pt; padding: 6px 8px;">
                                {{ $summary->teacher_notes ?? '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

   <!-- Tanda Tangan Sejajar -->
    <table class="footer-table">
        <tr>
            <td width="40%">
            </td>
            <td width="20%">
            </td>
            <td width="40%">
                Purworejo, {{ $reportDate }}<br>
            </td>
        </tr>
        <tr>
            <!-- Kolom Kiri: Kepala Madrasah -->
            <td width="40%">
                Mengetahui<br>
                <strong>Kepala Madrasah</strong>
                <br><br><br><br><br>
                <span class="font-bold" style="text-decoration: underline;">{{ $headmasterName }}</span><br>
                NIP. {{ $headmasterNip }}
            </td>
            
            <!-- Kolom Tengah: Spasi -->
            <td width="20%"></td>
            
            <!-- Kolom Kanan: Wali Kelas -->
            <td width="40%">
                <br><strong>Wali Kelas</strong>
                <br><br><br><br><br>
                <span class="font-bold" style="text-decoration: underline;">{{ $student->class->teacher->name ?? 'Wali Kelas' }}</span><br>
                NIP. {{ $student->class->teacher->nip_nik ?? '-' }}
            </td>
        </tr>
    </table>

    <!--<div style="margin-top: 20px; font-size: 8.5pt; color: #555;">-->
    <!--    {{ $student->class->name }}_{{ strtoupper($student->name) }}_{{ $student->nisn }}-->
    <!--    <span style="float: right;">Halaman 1</span>-->
    <!--</div>-->

</body>
</html>