<?php

namespace App\Helpers;

use setasign\Fpdi\Fpdi;

class PdfHelper extends Fpdi
{
    protected $angle = 0;

    /**
     * Rotate the page or element.
     *
     * @param float $angle
     * @param float $x
     * @param float $y
     */
    public function rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) {
            $x = $this->GetX();
        }
        if ($y == -1) {
            $y = $this->GetY();
        }
        if ($this->angle != 0) {
            $this->_out('Q');
        }
        $this->angle = $angle;
        if ($angle != 0) {
            $angleRad = $angle * M_PI / 180;
            $c = cos($angleRad);
            $s = sin($angleRad);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf(
                'q %.3F %.3F %.3F %.3F %.3F %.3F cm 1 0 0 1 %.3F %.3F cm',
                $c,
                $s,
                -$s,
                $c,
                $cx,
                $cy,
                -$cx,
                -$cy
            ));
        }
    }

    /**
     * Reset rotation at the end of a page.
     */
    public function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    /**
     * Simulate transparency using color blending.
     *
     * @param float $opacity (0 = fully transparent, 1 = fully opaque)
     */
    public function setOpacity($opacity)
    {
        // Convert opacity to RGB range closer to white for transparency effect
        $color = 255 - intval($opacity * 255); // Closer to 255 = more transparent
        $this->SetTextColor($color, $color, $color);
    }

    /**
     * Merge multiple PDFs and add watermark to each page.
     *
     * @param array $files
     * @param string $outputFile
     * @param string $watermarkText
     */
    public static function mergeAndAddWatermark(array $files, string $outputFile, string $watermarkText)
    {
        $pdf = new self();

        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($tplId);

                // Add watermark
                $pdf->SetFont('Arial', '', 30);
                $pdf->SetLineWidth(0.3);
                $pdf->SetTextColor(200, 200, 200);
                $pdf->setOpacity(0.2);
                $pdf->rotate(45, 105, 150); // Rotate watermark
                $pdf->Text(100, 140, $watermarkText);
                $pdf->rotate(0); // Reset rotation

                $pdf->SetFont('Arial', '', 12);
                $pdf->SetTextColor(128, 128, 128);
                $pdf->setOpacity(0.5);
                $pdf->Text(5, 5, setting('generap.app_url') ?? 'apexmedika.co.id');
            }
        }

        $pdf->Output($outputFile, 'F');
    }
}
