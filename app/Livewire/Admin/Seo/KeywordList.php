<?php

namespace App\Livewire\Admin\Seo;

use App\Jobs\GenerateSeoKeywordsJob;
use App\Models\SeoKeyword;
use App\Models\SeoKeywordGroup;
use App\Services\ToolRegistry;
use Livewire\Component;
use Livewire\WithPagination;

class KeywordList extends Component
{
    use WithPagination;

    public string $filterTool = '';
    public string $filterStatus = '';
    public string $filterGroup = '';

    // AI generation trigger
    public string $aiSeedTopic = '';
    public int $aiCount = 15;
    public ?int $aiGroupId = null;

    public function updatedFilterTool()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterGroup()
    {
        $this->resetPage();
    }

    public function approve(int $id)
    {
        SeoKeyword::whereKey($id)->update(['status' => 'approved']);
        $this->dispatch('toast', message: 'Keyword approved.');
    }

    public function reject(int $id)
    {
        SeoKeyword::whereKey($id)->update(['status' => 'rejected']);
        $this->dispatch('toast', message: 'Keyword rejected.');
    }

    public function delete(int $id)
    {
        SeoKeyword::whereKey($id)->delete();
        $this->dispatch('toast', message: 'Keyword deleted.');
    }

    public function generateWithAi()
    {
        $this->validate([
            'aiSeedTopic' => 'required|string|max:255',
            'aiCount' => 'required|integer|min:1|max:50',
        ]);

        GenerateSeoKeywordsJob::dispatch(
            $this->filterTool ?: 'emi-calculator',
            $this->aiGroupId,
            $this->aiSeedTopic,
            $this->aiCount,
            auth()->id()
        );

        $this->dispatch('toast', message: 'Keyword generation queued — refresh shortly.');
        $this->aiSeedTopic = '';
    }

    public function render()
    {
        $keywords = SeoKeyword::query()
            ->with('group')
            ->when($this->filterTool, fn ($q) => $q->where('tool_slug', $this->filterTool))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterGroup, fn ($q) => $q->where('seo_keyword_group_id', $this->filterGroup))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.admin.seo.keyword-list', [
            'keywords' => $keywords,
            'tools' => app(ToolRegistry::class)->all(),
            'groups' => SeoKeywordGroup::orderBy('name')->get(),
        ])->layout('layouts.admin.admin');
    }
}
