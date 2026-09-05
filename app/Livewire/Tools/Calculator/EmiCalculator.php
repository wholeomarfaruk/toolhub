<?php

namespace App\Livewire\Tools\Calculator;

use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
use App\Models\SeoPage;
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

    // Optional pre-fill values, supplied by programmatic SEO landing pages
    public array $toolPreset = [];

    // The SEO page driving this render (null for the main tool page)
    public ?SeoPage $seoPage = null;

    // Output
    public ?array $result = null;

    public function mount(?string $seoPageSlug = null): void
    {
        // Page loads without auth check (SEO friendly)
        if ($seoPageSlug !== null) {
            $page = SeoPage::where('tool_slug', $this->toolSlug)
                ->where('slug', $seoPageSlug)
                ->where('status', 'published')
                ->first();

            abort_unless($page, 404);

            $this->seoPage = $page;
        } else {
            // No slug in the URL (plain /tools/emi-calculator) — use the
            // primary page if one is published. Null is fine: falls back
            // to the hardcoded tool defaults exactly as before.
            $this->seoPage = SeoPage::where('tool_slug', $this->toolSlug)
                ->where('is_primary', true)
                ->where('status', 'published')
                ->first();
        }

        $this->toolPreset = $this->seoPage?->tool_preset ?? [];

        if (array_key_exists('principal', $this->toolPreset)) {
            $this->principal = (string) $this->toolPreset['principal'];
        }
        if (array_key_exists('annual_rate', $this->toolPreset)) {
            $this->annual_rate = (string) $this->toolPreset['annual_rate'];
        }
        if (array_key_exists('tenure_months', $this->toolPreset)) {
            $this->tenure_months = (string) $this->toolPreset['tenure_months'];
        }
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
        return view('livewire.tools.calculator.emi-calculator', [
            'seoPage' => $this->seoPage,
        ])->layout('layouts.website.website', [
            'title' => $this->seoPage?->meta_title ?: 'EMI Calculator',
            'description' => $this->seoPage?->meta_description ?: $this->defaultDescription(),
            'canonical_url' => $this->seoPage ? $this->seoPage->url() : route('tools.emi-calculator'),
        ]);
    }

    private function defaultDescription(): string
    {
        return 'Free online EMI calculator. Calculate monthly loan payments, total interest, and get detailed amortization schedule instantly.';
    }
}
