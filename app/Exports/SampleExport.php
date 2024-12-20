<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            [1, 'Apa ibu kota Indonesia?', 'Bandung', 'Jakarta', 'Surabaya', 'Medan', 'Yogyakarta', 'b'],
            [2, 'Berapa hasil dari 2 + 2?', '3', '4', '5', '6', '7', 'b'],
        ]);
    }

    public function headings(): array
    {
        return [
            'nomor',
            'soal',
            'pilihan_a',
            'pilihan_b',
            'pilihan_c',
            'pilihan_d',
            'pilihan_e',
            'jawaban',
        ];
    }
}
