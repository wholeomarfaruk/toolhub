<?php

namespace App\Http\Controllers;

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class AgeCardImageController extends Controller
{
    public function download()
    {
        $data = session('age_calculator_image');

        if (!$data) {
            abort(404, 'Age card data not found');
        }

        $result = $data['result'] ?? [];

        try {
            // Render the HTML template with result data
            $html = View::make('exports.age-card-export', [
                'result' => $result,
            ])->render();

            // Log the HTML for debugging
            Log::info('Age card HTML rendered', ['length' => strlen($html)]);

            // Generate image using Browsershot
            $image = Browsershot::html($html)
                ->windowSize(1000, 1210)
                ->screenshot();

            Log::info('Age card image generated', ['size' => strlen($image)]);

            // Generate filename
            $filename = 'age-card-' . now()->format('Y-m-d-His') . '.png';

            // Return image as download
            return response($image, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } catch (\Exception $e) {
            Log::error('Age card image generation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500, 'Failed to generate image: ' . $e->getMessage());
        }
    }
}
