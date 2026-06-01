<?php

namespace App\Livewire\Traits;

use App\Services\SubscriptionService;
use App\Services\ToolRegistry;

trait WithToolAccess
{
    public bool $showAuthModal = false;
    public string $authModalToolName = '';

    /**
     * Check if user is authenticated and has access to the tool.
     * Returns true if user can access, false otherwise.
     * Does NOT abort - use this for deferred auth checks.
     */
    protected function canAccessTool(string $toolSlug): bool
    {
        // Not authenticated
        if (! auth()->check()) {
            return false;
        }

        $tool = app(ToolRegistry::class)->find($toolSlug);
        $plan = app(SubscriptionService::class)->planFor(auth()->user());

        // Plan doesn't include access
        if (! $plan->includes($tool->requiredPlan())) {
            return false;
        }

        return true;
    }

    /**
     * Show auth modal with tool name.
     * Call this when user tries to perform an action but isn't authenticated.
     */
    protected function requireAuth(string $toolSlug): void
    {
        if (! auth()->check()) {
            $tool = app(ToolRegistry::class)->find($toolSlug);
            $this->authModalToolName = $tool->name();
            $this->showAuthModal = true;
            $this->dispatch('openAuthModal', toolName: $tool->name());
            return;
        }

        // User is authenticated, check plan access
        $tool = app(ToolRegistry::class)->find($toolSlug);
        $plan = app(SubscriptionService::class)->planFor(auth()->user());

        if (! $plan->includes($tool->requiredPlan())) {
            abort(403, "Your plan does not include access to [{$tool->name()}]. Upgrade to continue.");
        }
    }

    /**
     * Legacy method - now calls requireAuth() for backward compatibility.
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
