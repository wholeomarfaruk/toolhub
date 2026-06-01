<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoicePdfController extends Controller
{
    /**
     * Generate and download invoice PDF
     */
    public function download(string $template = 'basic')
    {
        $invoice = session('invoice_data');

        if (!$invoice) {
            abort(404, 'Invoice data not found');
        }

        // Render the blade template to HTML
        $html = View::make('pdf.invoices.' . $template, [
            'invoice' => $invoice,
        ])->render();

        // Use wkhtmltopdf via symfony/process if available
        $tempFile = tempnam(sys_get_temp_dir(), 'invoice');
        $pdfFile = $tempFile . '.pdf';

        try {
            // Try wkhtmltopdf first (better quality)
            $process = new Process(['wkhtmltopdf', '-', $pdfFile]);
            $process->setInput($html);
            $process->run();

            if ($process->isSuccessful() && file_exists($pdfFile)) {
                $pdf = file_get_contents($pdfFile);
                unlink($pdfFile);
                unlink($tempFile);

                return response()->streamDownload(
                    fn () => print($pdf),
                    'invoice-' . $invoice['invoice_number'] . '.pdf',
                    ['Content-Type' => 'application/pdf']
                );
            }
        } catch (\Exception) {
            // Silently fall through to DomPDF
        }

        // Fallback to DomPDF
        try {
            $options = new Options();
            $options->set('isRemoteEnabled', false);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            unlink($tempFile);

            return response()->streamDownload(
                fn () => print($dompdf->output()),
                'invoice-' . $invoice['invoice_number'] . '.pdf',
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Exception $e) {
            unlink($tempFile);
            abort(500, 'PDF generation failed: ' . $e->getMessage());
        }
    }
}
