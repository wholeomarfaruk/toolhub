<?php

namespace App\Livewire\Traits;

use App\Services\SubscriptionService;
use App\Services\UsageService;

trait WithToolRateLimit
{
    public bool $limitReached = false;

    /**
     * Call at the start of any generate/run action.
     *
     * Checks the plan-aware daily limit from SubscriptionService
     * (which reads from plan_features via the DB + cache).
     *
     * On breach:
     *   - sets $this->limitReached = true (caller checks this and returns early)
     *   - adds 'limit' error to Livewire $errors bag (blade displays it)
     */
    protected function enforceLimit(string $toolSlug): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $limit = app(SubscriptionService::class)->dailyLimitFor($user, $toolSlug);

        if ($limit === null) {
            return; // unlimited on this plan
        }

        $used = app(UsageService::class)->countToday($user->id, $toolSlug);

        if ($used >= $limit) {
            $this->limitReached = true;
            $plan = app(SubscriptionService::class)->planFor($user);

            $this->addError(
                'limit',
                "Daily limit of {$limit} reached on the {$plan->label()} plan. "
                . "Upgrade for a higher limit or try again tomorrow."
            );
        }
    }
}
