<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Leger Nilai Kelas {{ $class->name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 9.5pt; 
            line-height: 1.15;
            margin: 0; padding: 0; color: #000; background: #fff;
        }

        .header-title { text-align: center; font-weight: bold; font-size: 13pt; text-transform: uppercase; margin-bottom: 4px; }
        .header-sub { text-align: center; font-size: 10pt; font-weight: bold; margin-bottom: 15px; }

        .meta-table { width: 100%; margin-bottom: 10px; border-collapse: collapse; font-size: 9.5pt; }
        .meta-table td { padding: 2px 4px; vertical-align: top; }

        .leger-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .leger-table th, .leger-table td { border: 1px solid #000; padding: 3px 4px; font-size: 8.5pt; }
        .leger-table th { background-color: #e5e7eb; text-align: center; font-weight: bold; vertical-align: middle; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        .footer-table { width: 100%; margin-top: 20px; border-collapse: collapse; page-break-inside: avoid; font-size: 10pt; }
        .footer-table td { text-align: center; vertical-align: top; }

        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #059669; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak Leger Kelas {{ $class->name }}
        </button>
    </div>

    <!-- Judul Header Leger -->
    <div class="header-title">LEGER NILAI ASESMEN SUMATIF TENGAH SEMESTER (ASTS)</div>
    <div class="header-sub">MTS NEGERI 3 PURWOREJO - TAHUN AJARAN {{ $class->academic_year }}</div>

    <!-- Meta Informasi Kelas -->
    <table class="meta-table">
        <tr>
            <td width="10%">Kelas</td><td width="2%">:</td><td width="38%" class="font-bold">{{ $class->name }}</td>
            <td width="10%">Semester</td><td width="2%">:</td><td width="38%">{{ $class->semester }}</td>
        </tr>
        <tr>
            <td>Wali Kelas</td><td>:</td><td class="font-bold">{{ $class->teacher->name ?? '-' }}</td>
            <td>Fase</td><td>:</td><td>{{ $class->phase }}</td>
        </tr>
    </table>

    <!-- Tabel Rekap Leger Nilai -->
    <table class="leger-table">
        <thead>
            <tr>
                <th width="25px">NO</th>
                <th width="65px">NIS</th>
                <th>NAMA SISWA</th>
                @foreach($subjects as $subject)
                    <th title="{{ $subject->name }}" style="writing-mode: vertical-lr; transform: rotate(180deg); max-height: 80px; padding: 4px 2px;">
                        {{ $subject->code ?? $subject->name }}
                    </th>
                @endforeach
                <th width="45px">TOTAL</th>
                <th width="40px">RATA</th>
                <th width="35px">RANK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentData as $index => $row)
            <tr>
                <td class="text-center font-bold">{{ $index + 1 }}</td>
                <td class="text-center font-mono">{{ $row['student']->nis }}</td>
                <td class="font-bold">{{ $row['student']->name }}</td>
                
                @foreach($subjects as $subject)
                    @php $sScore = $row['grades'][$subject->id] ?? null; @endphp
                    <td class="text-center">{{ $sScore ?? '-' }}</td>
                @endforeach

                <td class="text-center font-bold" style="background-color: #f9fafb;">{{ $row['total_score'] }}</td>
                <td class="text-center font-bold" style="background-color: #f9fafb;">{{ $row['average_score'] }}</td>
                <td class="text-center font-bold" style="background-color: #fef3c7;">{{ $row['rank'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan Dinamis dari Pengaturan Raport -->
    <table class="footer-table">
        <tr>
            <td width="35%">
                Mengetahui,<br>
                <strong>Kepala Madrasah</strong>
                <br><br><br><br><br>
                <span class="font-bold" style="text-decoration: underline;">{{ $headmasterName }}</span><br>
                NIP. {{ $headmasterNip }}
            </td>
            <td width="30%"></td>
            <td width="35%">
                Purworejo, {{ $reportDate }}<br>
                <strong>Wali Kelas</strong>
                <br><br><br><br><br>
                <span class="font-bold" style="text-decoration: underline;">{{ $class->teacher->name ?? 'Wali Kelas' }}</span><br>
                NIP. {{ $class->teacher->nip_nik ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>