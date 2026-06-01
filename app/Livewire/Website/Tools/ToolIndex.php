<?php

namespace App\Livewire\Website\Tools;

use App\Services\ToolRegistry;
use Livewire\Component;

class ToolIndex extends Component
{
    public function render()
    {
        return view('livewire.website.tools.tool-index', [
            'grouped' => app(ToolRegistry::class)->groupedByCategory(),
        ])->layout('layouts.website.website', ['title' => 'All Tools']);
    }
}
