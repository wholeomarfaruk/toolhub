<?php

namespace App\Livewire\Tools\Analyzer;

use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
use App\Models\SeoPage;
use App\Tools\Analyzer\WordCounter\WordCounterTool;
use Livewire\Component;

class WordCounter extends Component
{
    use WithToolAccess;
    use WithToolRateLimit;
    use WithUsageTracking;

    public string $toolSlug = 'word-counter';

    // Input
    public string $text = '';

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
            // No slug in the URL (plain /tools/word-counter) — use the
            // primary page if one is published. Null is fine: falls back
            // to the hardcoded tool defaults exactly as before.
            $this->seoPage = SeoPage::where('tool_slug', $this->toolSlug)
                ->where('is_primary', true)
                ->where('status', 'published')
                ->first();
        }

        $this->toolPreset = $this->seoPage?->tool_preset ?? [];
    }

    public function analyze(): void
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

        $this->result = app(WordCounterTool::class)->run([
            'text' => $this->text,
        ]);

        $this->trackUsage($this->toolSlug);
    }

    public function clear(): void
    {
        $this->text = '';
        $this->result = null;
    }

    public function exportPdf()
    {
        // Check authentication first
        if (!$this->canAccessTool($this->toolSlug)) {
            $this->requireAuth($this->toolSlug);
            return;
        }

        if (!$this->result || !$this->text) {
            $this->addError('export', 'Please analyze text first before exporting.');
            return;
        }

        // Check if user has export feature
        $user = auth()->user();
        $hasExportFeature = app(\App\Services\SubscriptionService::class)->hasFeature(
            $user,
            \App\Enums\Feature::ExportFeature
        );

        if (!$hasExportFeature) {
            $this->addError('export', 'PDF export is only available on Pro and Enterprise plans.');
            return;
        }

        // Store report data in session
        session([
            'word_counter_report' => [
                'text' => $this->text,
                'result' => $this->result,
            ],
        ]);

        // Redirect to PDF download
        return redirect(route('word-counter.pdf'));
    }

    public function render()
    {
        // Get export feature only if user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            $hasExportFeature = app(\App\Services\SubscriptionService::class)->hasFeature(
                $user,
                \App\Enums\Feature::ExportFeature
            );
        } else {
            // Default: unauthenticated users can't export
            $hasExportFeature = false;
        }

        return view('livewire.tools.analyzer.word-counter', [
            'hasExportFeature' => $hasExportFeature,
            'seoPage' => $this->seoPage,
        ])->layout('layouts.website.website', [
            'title' => $this->seoPage?->meta_title ?: 'Word Counter',
            'description' => $this->seoPage?->meta_description ?: $this->defaultDescription(),
            'canonical_url' => $this->seoPage ? $this->seoPage->url() : route('tools.word-counter'),
        ]);
    }

    private function defaultDescription(): string
    {
        return 'Free online word counter and text analyzer. Count words, characters, sentences, paragraphs, reading time, and get detailed text statistics instantly.';
    }
}
