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
        $this->authorizeToolAccess($this->toolSlug);
    }

    public function analyze(): void
    {
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

    public function render()
    {
        return view('livewire.tools.analyzer.word-counter')
            ->layout('layouts.website.website', ['title' => 'Word Counter']);
    }
}
