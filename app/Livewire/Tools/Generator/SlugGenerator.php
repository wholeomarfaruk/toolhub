<?php

namespace App\Livewire\Tools\Generator;

use App\Enums\Feature;
use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
use App\Services\SubscriptionService;
use App\Tools\Generator\SlugGenerator\SlugGeneratorTool;
use Livewire\Component;

class SlugGenerator extends Component
{
    use WithToolAccess;
    use WithToolRateLimit;
    use WithUsageTracking;

    public string $toolSlug = 'slug-generator';

    // Input properties
    public string $text = '';
    public string $separator = '-';
    public bool $useStopWords = false;
    public bool $useUnicode = false;
    public string $bulkText = '';
    public bool $bulkMode = false;

    // Output
    public ?array $result = null;

    public function mount(): void
    {
        // Page loads without auth check (SEO friendly)
    }

    public function generate(): void
    {
        // Check authentication before allowing tool use
        if (!$this->canAccessTool($this->toolSlug)) {
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

        $this->result = app(SlugGeneratorTool::class)->run([
            'text' => $this->text,
            'separator' => $this->separator,
            'stop_words' => $this->useStopWords,
            'unicode' => $this->useUnicode,
            'is_bulk' => false,
        ]);

        $this->trackUsage($this->toolSlug);
    }

    public function generateBulk(): void
    {
        // Check if user has bulk mode feature
        $user = auth()->user();
        $hasBulkMode = app(SubscriptionService::class)->hasFeature($user, Feature::SlugBulkMode);

        if (!$hasBulkMode) {
            $this->addError('bulk', 'Bulk mode is only available on Pro and Enterprise plans.');
            return;
        }

        $this->resetErrorBag();
        $this->result = null;
        $this->limitReached = false;

        $this->enforceLimit($this->toolSlug);

        if ($this->limitReached) {
            return;
        }

        $this->result = app(SlugGeneratorTool::class)->run([
            'text' => '',
            'bulk_text' => $this->bulkText,
            'separator' => $this->separator,
            'stop_words' => $this->useStopWords,
            'unicode' => $this->useUnicode,
            'is_bulk' => true,
        ]);

        $this->trackUsage($this->toolSlug);
    }

    public function clear(): void
    {
        $this->text = '';
        $this->bulkText = '';
        $this->result = null;
        $this->bulkMode = false;
    }

    public function copyToClipboard(string $text): void
    {
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Slug copied to clipboard!',
        ]);
    }

    public function render()
    {
        $user = auth()->user();
        $svc = app(SubscriptionService::class);

        return view('livewire.tools.generator.slug-generator', [
            'hasCustomSeparator' => $svc->hasFeature($user, Feature::SlugCustomSeparator),
            'hasStopWords' => $svc->hasFeature($user, Feature::SlugStopWords),
            'hasBulkMode' => $svc->hasFeature($user, Feature::SlugBulkMode),
            'hasUnicode' => $svc->hasFeature($user, Feature::SlugUnicode),
        ])->layout('layouts.website.website', ['title' => 'Slug Generator']);
    }
}
