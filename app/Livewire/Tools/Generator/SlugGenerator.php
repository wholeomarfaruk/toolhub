<?php

namespace App\Livewire\Tools\Generator;

use App\Enums\Feature;
use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
use App\Models\SeoPage;
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
            // No slug in the URL (plain /tools/slug-generator) — use the
            // primary page if one is published. Null is fine: falls back
            // to the hardcoded tool defaults exactly as before.
            $this->seoPage = SeoPage::where('tool_slug', $this->toolSlug)
                ->where('is_primary', true)
                ->where('status', 'published')
                ->first();
        }

        $this->toolPreset = $this->seoPage?->tool_preset ?? [];
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
        // Check authentication first
        if (!$this->canAccessTool($this->toolSlug)) {
            $this->requireAuth($this->toolSlug);
            return;
        }

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
        $svc = app(SubscriptionService::class);

        // Get features only if user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            $features = [
                'hasCustomSeparator' => $svc->hasFeature($user, Feature::SlugCustomSeparator),
                'hasStopWords' => $svc->hasFeature($user, Feature::SlugStopWords),
                'hasBulkMode' => $svc->hasFeature($user, Feature::SlugBulkMode),
                'hasUnicode' => $svc->hasFeature($user, Feature::SlugUnicode),
            ];
        } else {
            // Default: unauthenticated users have no special features
            $features = [
                'hasCustomSeparator' => false,
                'hasStopWords' => false,
                'hasBulkMode' => false,
                'hasUnicode' => false,
            ];
        }

        return view('livewire.tools.generator.slug-generator', array_merge($features, [
            'seoPage' => $this->seoPage,
        ]))->layout('layouts.website.website', [
            'title' => $this->seoPage?->meta_title ?: 'Slug Generator',
            'description' => $this->seoPage?->meta_description ?: $this->defaultDescription(),
            'canonical_url' => $this->seoPage ? $this->seoPage->url() : route('tools.slug-generator'),
        ]);
    }

    private function defaultDescription(): string
    {
        return 'Free online slug generator. Convert text to SEO-friendly URL slugs instantly. Support for separators, stop words removal, and Unicode characters.';
    }
}
