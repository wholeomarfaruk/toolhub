<?php

namespace App\Livewire\Tools\Analyzer;

use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
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

    // Output
    public ?array $result = null;

    public function mount(): void
    {
        // Page loads without auth check (SEO friendly)
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
        $user = auth()->user();
        $hasExportFeature = app(\App\Services\SubscriptionService::class)->hasFeature(
            $user,
            \App\Enums\Feature::ExportFeature
        );

        return view('livewire.tools.analyzer.word-counter', [
            'hasExportFeature' => $hasExportFeature,
        ])->layout('layouts.website.website', ['title' => 'Word Counter']);
    }
}
