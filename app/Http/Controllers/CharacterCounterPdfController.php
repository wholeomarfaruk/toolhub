<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class CharacterCounterPdfController extends Controller
{
    public function download()
    {
        $data = session('character_counter_report');

        if (!$data) {
            abort(404, 'Character Counter report data not found');
        }

        $text = $data['text'] ?? '';
        $result = $data['result'] ?? [];

        // Render the blade template to HTML
        $html = View::make('pdf.character-counter-report', [
            'text' => $text,
            'result' => $result,
        ])->render();

        // Use DomPDF for PDF generation
        try {
            $options = new Options();
            $options->set('isRemoteEnabled', false);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(
                fn () => print($dompdf->output()),
                'character-counter-report-' . now()->format('Y-m-d-His') . '.pdf',
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Exception $e) {
            abort(500, 'PDF generation failed: ' . $e->getMessage());
        }
    }
}
