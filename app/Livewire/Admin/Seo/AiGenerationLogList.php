<?php

namespace App\Livewire\Admin\Seo;

use App\Models\AiGenerationLog;
use Livewire\Component;
use Livewire\WithPagination;

class AiGenerationLogList extends Component
{
    use WithPagination;

    public string $filterType = '';
    public string $filterStatus = '';
    public string $filterProvider = '';

    public ?int $viewingId = null;

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterProvider()
    {
        $this->resetPage();
    }

    public function view(int $id)
    {
        $this->viewingId = $this->viewingId === $id ? null : $id;
    }

    public function render()
    {
        $logs = AiGenerationLog::query()
            ->with(['seoPage', 'keywordGroup', 'triggeredBy'])
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterProvider, fn ($q) => $q->where('provider', $this->filterProvider))
            ->orderByDesc('created_at')
            ->paginate(20);

        $totals = AiGenerationLog::query()
            ->selectRaw('COUNT(*) as total_requests')
            ->selectRaw("SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as total_success")
            ->selectRaw("SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as total_failed")
            ->selectRaw('SUM(total_tokens) as total_tokens')
            ->selectRaw('SUM(estimated_cost_usd) as total_cost')
            ->first();

        return view('livewire.admin.seo.ai-generation-log-list', [
            'logs' => $logs,
            'totals' => $totals,
            'providers' => AiGenerationLog::query()->distinct()->pluck('provider'),
        ])->layout('layouts.admin.admin');
    }
}
