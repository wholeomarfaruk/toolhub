<?php

namespace App\Livewire\Tools\Calculator;

use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
use App\Tools\Calculator\EmiCalculator\EmiCalculatorTool;
use Livewire\Component;

class EmiCalculator extends Component
{
    use WithToolAccess;
    use WithToolRateLimit;
    use WithUsageTracking;

    public string $toolSlug = 'emi-calculator';

    // Inputs
    public string $principal     = '';
    public string $annual_rate   = '';
    public string $tenure_months = '';

    // Output
    public ?array $result = null;

    public function mount(): void
    {
        // Page loads without auth check (SEO friendly)
    }

    public function calculate(): void
    {
        // Check authentication before allowing tool use
        if (!$this->canAccessTool($this->toolSlug)) {
            $this->requireAuth($this->toolSlug);
            return;
        }

        $this->resetErrorBag();
        $this->result       = null;
        $this->limitReached = false;

        $this->enforceLimit($this->toolSlug);

        if ($this->limitReached) {
            return;
        }

        $this->result = app(EmiCalculatorTool::class)->run([
            'principal'     => $this->principal,
            'annual_rate'   => $this->annual_rate,
            'tenure_months' => $this->tenure_months,
        ]);

        $this->trackUsage($this->toolSlug);
    }

    public function render()
    {
        return view('livewire.tools.calculator.emi-calculator')
            ->layout('layouts.website.website', ['title' => 'EMI Calculator']);
    }
}
