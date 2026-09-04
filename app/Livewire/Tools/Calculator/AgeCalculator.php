<?php

namespace App\Livewire\Tools\Calculator;

use App\Enums\Feature;
use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
use App\Services\SubscriptionService;
use App\Tools\Calculator\AgeCalculator\AgeCalculatorTool;
use Livewire\Component;

class AgeCalculator extends Component
{
    use WithToolAccess;
    use WithToolRateLimit;
    use WithUsageTracking;

    public string $toolSlug = 'age-calculator';

    // Input
    public string $dob = '';

    // Optional pre-fill values, supplied by programmatic SEO landing pages
    public array $toolPreset = [];

    // Output
    public ?array $result = null;

    public function mount(array $toolPreset = []): void
    {
        $this->toolPreset = $toolPreset;

        if (array_key_exists('dob', $toolPreset)) {
            $this->dob = (string) $toolPreset['dob'];
        }
    }

    public function calculate(): void
    {
        // Check authentication before allowing tool use
        if (! $this->canAccessTool($this->toolSlug)) {
            $this->requireAuth($this->toolSlug);

            return;
        }

        $this->resetErrorBag();
        $this->result = null;
        $this->limitReached = false;

        $this->enforceLimit($this->toolSlug);

        if ($this->limitReached) {
            return;
        }

        $this->result = app(AgeCalculatorTool::class)->run([
            'dob' => $this->dob,
        ]);

        $this->trackUsage($this->toolSlug);
    }

    public function clear(): void
    {
        $this->dob = '';
        $this->result = null;
    }

    public function exportAgeImage()
    {
        if (! $this->result || ! $this->dob) {
            $this->addError('export', 'Please calculate your age first before exporting.');

            return;
        }

        try {
            // Store image data in session
            session([
                'age_calculator_image' => [
                    'result' => $this->result,
                ],
            ]);

            // Redirect to controller that handles the image download
            return redirect()->route('age-card-image.download');
        } catch (\Exception $e) {
            \Log::error('Age card export failed: '.$e->getMessage());
            $this->addError('export', 'Failed to generate image. Please try again.');

            return;
        }
    }

    public function exportPdf()
    {
        // Check authentication first
        if (! $this->canAccessTool($this->toolSlug)) {
            $this->requireAuth($this->toolSlug);

            return;
        }

        if (! $this->result || ! $this->dob) {
            $this->addError('export', 'Please calculate your age first before exporting.');

            return;
        }

        // Check if user has export feature
        $user = auth()->user();
        $hasExportFeature = app(SubscriptionService::class)->hasFeature(
            $user,
            Feature::AgeCalculatorExport
        );

        if (! $hasExportFeature) {
            $this->addError('export', 'PDF export is only available on Pro and Enterprise plans.');

            return;
        }

        try {
            // Store report data in session
            session([
                'age_calculator_report' => [
                    'dob' => $this->dob,
                    'result' => $this->result,
                ],
            ]);

            // Flash and redirect
            return redirect()->route('age-calculator.pdf');
        } catch (\Exception $e) {
            $this->addError('export', 'Failed to generate PDF: '.$e->getMessage());

            return;
        }
    }

    public function render()
    {
        // Get export feature only if user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            $hasExportFeature = app(SubscriptionService::class)->hasFeature(
                $user,
                Feature::AgeCalculatorExport
            );
        } else {
            // Default: unauthenticated users can't export
            $hasExportFeature = false;
        }

        return view('livewire.tools.calculator.age-calculator', [
            'hasExportFeature' => $hasExportFeature,
        ])->layout('layouts.website.website', ['title' => 'Age Calculator']);
    }
}
