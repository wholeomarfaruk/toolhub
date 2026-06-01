<?php

namespace App\Livewire\User\Dashboard;

use App\Models\ToolUsage;
use App\Services\SubscriptionService;
use App\Services\ToolRegistry;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $userId = auth()->id();

        $stats = [
            'today' => ToolUsage::where('user_id', $userId)
                ->whereDate('created_at', today())
                ->count(),

            'month' => ToolUsage::where('user_id', $userId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'total' => ToolUsage::where('user_id', $userId)->count(),

            'days_active' => ToolUsage::where('user_id', $userId)
                ->distinct()
                ->count(DB::raw('DATE(created_at)')),
        ];

        $recentUsages = ToolUsage::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $mostUsed = ToolUsage::where('user_id', $userId)
            ->selectRaw('tool_slug, COUNT(*) as total')
            ->groupBy('tool_slug')
            ->orderByDesc('total')
            ->first();

        $registry = app(ToolRegistry::class);
        $plan     = app(SubscriptionService::class)->planFor(auth()->user());

        return view('livewire.user.dashboard.overview', [
            'stats'       => $stats,
            'recentUsages'=> $recentUsages,
            'mostUsed'    => $mostUsed,
            'tools'       => $registry->accessibleFor($plan),
        ])->layout('layouts.user.user', ['title' => 'Dashboard']);
    }
}
