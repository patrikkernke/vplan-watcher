<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function weekendMeetingSchedule(Fpdf $pdf)
    {
        $meetings = Meeting::orderBy('start_at')->get();

        $pdf->SetMargins(20,20);
        $pdf->AddPage();

        // Header
        $pdf->SetFont('Helvetica', 'B');
        $pdf->SetFontSize(16);
        $pdf->Cell(120, 0, utf8_decode('Zusammenkunft für die Öffentlichkeit'));

        $pdf->SetXY(140, 18);
        $pdf->SetFont('Helvetica', '');
        $pdf->SetFontSize(9);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell(50, 4, utf8_decode(now()->translatedFormat('d. F Y')), 0, 1, 'R');

        // Events
        // Date
        $pdf->SetXY(20, 35);
        $pdf->SetFont('Helvetica', '');
        $pdf->SetFontSize(9);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->SetFillColor(200, 200, 200);

        foreach ($meetings as $meeting) {
            $pdf->cell(16, 4, utf8_decode($meeting->start_at->translatedFormat('j. M')), 0, 0, 'L', true);
            $pdf->cell(40, 4, utf8_decode($meeting->schedule()->first()->topic), 0, 0, 'L', true);
            $pdf->Ln();
        }


        return response($pdf->Output('S', 'test_pdf.pdf', true))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="test_pdf.pdf"')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate')
            ->header('Pragma', 'public');
    }
}
