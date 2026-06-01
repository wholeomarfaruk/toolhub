<?php

namespace App\Livewire\Tools\Calculator;

use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
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

    // Output
    public ?array $result = null;

    public function mount(): void
    {
        $this->authorizeToolAccess($this->toolSlug);
    }

    public function calculate(): void
    {
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

    public function exportPdf()
    {
        if (!$this->result || !$this->dob) {
            $this->addError('export', 'Please calculate your age first before exporting.');
            return;
        }

        // Check if user has export feature
        $user = auth()->user();
        $hasExportFeature = app(\App\Services\SubscriptionService::class)->hasFeature(
            $user,
            \App\Enums\Feature::AgeCalculatorExport
        );

        if (!$hasExportFeature) {
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
            $this->addError('export', 'Failed to generate PDF: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        $user = auth()->user();
        $hasExportFeature = app(\App\Services\SubscriptionService::class)->hasFeature(
            $user,
            \App\Enums\Feature::AgeCalculatorExport
        );

        return view('livewire.tools.calculator.age-calculator', [
            'hasExportFeature' => $hasExportFeature,
        ])->layout('layouts.website.website', ['title' => 'Age Calculator']);
    }
}
