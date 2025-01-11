<?php

namespace App\Helpers;

use setasign\Fpdi\Fpdi;

class PdfHelper
{
    public static function mergeAndAddWatermark(array $files, $outputFile, $watermarkText)
    {
        $pdf = new Fpdi();

        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($tplId);

                // Tambahkan watermark
                $pdf->SetFont('Arial', 'B', 50);
                $pdf->SetTextColor(200, 200, 200);
                $pdf->SetXY(30, 140);
                $pdf->Text(30, 140, $watermarkText);
            }
        }

        $pdf->Output($outputFile, 'F');
    }
}
