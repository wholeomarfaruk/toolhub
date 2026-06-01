<?php

namespace App\Http\Controllers;

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;

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

            // Generate image using Browsershot
            $image = Browsershot::html($html)
                ->width(1000)
                ->height(1210)
                ->devicePixelRatio(2)
                ->windowSize(1000, 1210)
                ->screenshot();

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
            abort(500, 'Failed to generate image: ' . $e->getMessage());
        }
    }
}
