<?php

namespace App\Livewire\Admin\Seo;

use App\Jobs\GenerateSeoPageContentJob;
use App\Models\AiProvider;
use App\Models\SeoPage;
use App\Services\ToolRegistry;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class SeoPageList extends Component
{
    use WithPagination;

    public string $filterTool = '';
    public string $filterStatus = '';

    public function updatedFilterTool()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function toggleIndexable(int $id)
    {
        $page = SeoPage::findOrFail($id);
        $page->update(['is_indexable' => !$page->is_indexable]);
        Cache::forget('sitemap.xml');
        Cache::forget('sitemap-index.xml');
        $this->dispatch('toast', message: 'Page indexability updated.');
    }

    public function generateContent(int $id)
    {
        $page = SeoPage::findOrFail($id);

        if ($page->status !== 'draft') {
            $this->dispatch('toast', type: 'error', message: 'Only draft pages can be queued for AI generation.');
            return;
        }

        $provider = AiProvider::active()->first();
        if (!$provider) {
            $this->dispatch('toast', type: 'error', message: 'No AI providers configured — set one up in AI Settings.');
            return;
        }

        GenerateSeoPageContentJob::dispatch($page->id, auth()->id(), $provider->slug, null);
        $this->dispatch('toast', message: 'AI content generation queued using ' . $provider->name . '.');
    }

    public function delete(int $id)
    {
        SeoPage::whereKey($id)->delete();
        Cache::forget('sitemap.xml');
        Cache::forget('sitemap-index.xml');
        $this->dispatch('toast', message: 'Page deleted.');
    }

    public function render()
    {
        $pages = SeoPage::query()
            ->when($this->filterTool, fn ($q) => $q->where('tool_slug', $this->filterTool))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.admin.seo.seo-page-list', [
            'pages' => $pages,
            'tools' => app(ToolRegistry::class)->all(),
        ])->layout('layouts.admin.admin');
    }
}
