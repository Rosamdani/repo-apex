<?php

namespace App\Imports;

use App\Models\SoalTryout;
use App\Models\BidangTryouts;
use App\Models\KompetensiTryouts;
use EightyNine\ExcelImport\DefaultRelationshipImport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportQuestions extends DefaultRelationshipImport implements ToCollection
{
    /**
     * Process the imported data from the Excel file.
     *
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection)
    {
        // Skip the first row if it's a header
        $rows = $collection->skip(1);

        foreach ($rows as $row) {
            $filteredRow = array_filter($row->toArray(), fn($value) => !is_null($value));

            // Log hasil setelah filter
            Log::info("Filtered Row Data: ", $filteredRow);

            // Skip jika semua kolom kosong
            if (empty($filteredRow)) {
                Log::info("Skipping empty row");
                continue;
            }
            Log::info("Disini");

            // Map column indices to field names
            $bidangName = strtoupper(trim($row[1] ?? ''));       // Column 0: Bidang
            $kompetensiName = strtoupper(trim($row[2] ?? ''));   // Column 1: Kompetensi
            $soal = $row[3] ?? '';                               // Column 2: Soal
            $pilihanA = $row[4] ?? '';                           // Column 3: Pilihan A
            $pilihanB = $row[5] ?? '';                           // Column 4: Pilihan B
            $pilihanC = $row[6] ?? '';                           // Column 5: Pilihan C
            $pilihanD = $row[7] ?? '';                           // Column 6: Pilihan D
            $pilihanE = $row[8] ?? '';                           // Column 7: Pilihan E
            $jawaban = strtolower(trim($row[9] ?? ''));          // Column 8: Jawaban

            $bidang = BidangTryouts::where('nama', $bidangName)->first();

            // Find the Kompetensi by name
            $kompetensi = KompetensiTryouts::where('nama', $kompetensiName)->first();

            SoalTryout::create([
                'bidang_id' => $bidang->id ?? null,
                'kompetensi_id' => $kompetensi->id ?? null,
                'soal' => $soal,
                'pilihan_a' => $pilihanA,
                'pilihan_b' => $pilihanB,
                'pilihan_c' => $pilihanC,
                'pilihan_d' => $pilihanD,
                'pilihan_e' => $pilihanE,
                'jawaban' => $jawaban,
            ]);
        }
    }
}
