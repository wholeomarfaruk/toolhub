<?php

namespace App\Livewire\Traits;

use App\Services\UsageService;

trait WithUsageTracking
{
    protected function trackUsage(string $toolSlug): void
    {
        if (! auth()->check()) {
            return;
        }

        app(UsageService::class)->record(auth()->id(), $toolSlug);
    }

    public function usageToday(string $toolSlug): int
    {
        if (! auth()->check()) {
            return 0;
        }

        return app(UsageService::class)->countToday(auth()->id(), $toolSlug);
    }
}
