<?php

namespace App\Livewire\Traits;

use App\Services\UsageService;

trait WithUsageTracking
{
    protected function trackUsage(string $toolSlug): void
    {
        app(UsageService::class)->record(auth()->id(), $toolSlug);
    }

    public function usageToday(string $toolSlug): int
    {
        return app(UsageService::class)->countToday(auth()->id(), $toolSlug);
    }
}
