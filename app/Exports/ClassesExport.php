<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Collection;

class ClassesExport implements FromCollection, WithHeadings
{
    public function collection(): Enumerable
    {
        return new Collection([
            [
                'nama_kelas' => 'VIIIA',
                'fase' => 'D',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'nip_wali_kelas' => '197807122005012005',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'nama_kelas',
            'fase',
            'tahun_ajaran',
            'semester',
            'nip_wali_kelas',
        ];
    }
}