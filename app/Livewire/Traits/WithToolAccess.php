<?php

namespace App\Livewire\Traits;

use App\Services\SubscriptionService;
use App\Services\ToolRegistry;

trait WithToolAccess
{
    /**
     * Call from mount(). Aborts with 403 if the user's plan
     * does not meet the tool's required plan tier.
     */
    protected function authorizeToolAccess(string $toolSlug): void
    {
        $tool = app(ToolRegistry::class)->find($toolSlug);
        $plan = app(SubscriptionService::class)->planFor(auth()->user());

        if (! $plan->includes($tool->requiredPlan())) {
            abort(403, "Your plan does not include access to [{$tool->name()}]. Upgrade to continue.");
        }
    }
}
