<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Collection;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection(): Enumerable
    {
        return new Collection([
            [
                'nis' => '260001',
                'nisn' => '0123456789',
                'nama_siswa' => 'AHMAD FARKHAN NAJIHA',
                'jenis_kelamin' => 'L', // L / P
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'nis',
            'nisn',
            'nama_siswa',
            'jenis_kelamin',
        ];
    }
}