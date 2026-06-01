<?php

namespace App\Services;

use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoicePdfBuilder
{
    /**
     * Generate PDF from invoice data.
     * @param array $invoice Invoice result array from InvoiceGeneratorTool::handle()
     * @param string $template Template name (basic, modern, corporate)
     * @return StreamedResponse PDF download response
     */
    public static function generate(array $invoice, string $template = 'basic'): StreamedResponse
    {
        // Render the blade template to HTML
        $html = View::make('pdf.invoices.' . $template, [
            'invoice' => $invoice,
        ])->render();

        // Use dompdf through Laravel service
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'invoice-' . $invoice['invoice_number'] . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
