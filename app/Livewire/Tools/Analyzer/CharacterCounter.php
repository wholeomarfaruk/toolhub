<?php

namespace App\Livewire\Tools\Analyzer;

use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
use App\Tools\Analyzer\CharacterCounter\CharacterCounterTool;
use Livewire\Component;

class CharacterCounter extends Component
{
    use WithToolAccess;
    use WithToolRateLimit;
    use WithUsageTracking;

    public string $toolSlug = 'character-counter';

    public string $text = '';
    public ?array $result = null;

    public function mount(): void
    {
        // Page loads without auth check (SEO friendly)
    }

    public function analyze(): void
    {
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

        $this->result = app(CharacterCounterTool::class)->run([
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
            'character_counter_report' => [
                'text' => $this->text,
                'result' => $this->result,
            ],
        ]);

        // Redirect to PDF download
        return redirect(route('character-counter.pdf'));
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

        return view('livewire.tools.analyzer.character-counter', [
            'hasExportFeature' => $hasExportFeature,
        ])
            ->layout('layouts.website.website', ['title' => 'Character Counter']);
    }
}
