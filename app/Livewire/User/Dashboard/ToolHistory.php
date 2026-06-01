<?php

namespace App\Livewire\User\Dashboard;

use App\Models\ToolUsage;
use App\Services\ToolRegistry;
use Livewire\Component;
use Livewire\WithPagination;

class ToolHistory extends Component
{
    use WithPagination;

    public string $filterSlug = '';

    public function updatedFilterSlug(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ToolUsage::where('user_id', auth()->id())
            ->orderByDesc('created_at');

        if ($this->filterSlug !== '') {
            $query->where('tool_slug', $this->filterSlug);
        }

        $history  = $query->paginate(15);
        $registry = app(ToolRegistry::class);

        return view('livewire.user.dashboard.tool-history', [
            'history'  => $history,
            'registry' => $registry,
            'slugs'    => ToolUsage::where('user_id', auth()->id())
                ->selectRaw('DISTINCT tool_slug')
                ->pluck('tool_slug'),
        ])->layout('layouts.user.user', ['title' => 'Usage History']);
    }
}
