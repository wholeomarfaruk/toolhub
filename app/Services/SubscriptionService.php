<?php

namespace App\Services;

use App\Enums\Feature;
use App\Enums\PlanTier;
use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class SubscriptionService
{
    private const CACHE_TTL_MINUTES = 15;

    // ── Plan resolution ───────────────────────────────────────────────────

    /**
     * Returns the user's active Plan model with features eager-loaded.
     * Cached for 15 minutes per user — busted by bustCache() on subscription change.
     * Falls back to Free plan for users with no active subscription.
     */
    public function planModelFor(User $user): Plan
    {
        return Cache::remember(
            $this->planCacheKey($user->id),
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            fn () => $user->activePlan()
        );
    }

    /**
     * Returns the PlanTier enum for the user's current plan.
     * Backward-compatible — used by WithToolAccess and existing callers.
     */
    public function planFor(User $user): PlanTier
    {
        return $this->planModelFor($user)->tier();
    }

    // ── Feature access ────────────────────────────────────────────────────

    /**
     * Returns true if the user's plan has the given feature enabled.
     *
     * Usage: $subscriptionService->hasFeature($user, Feature::PdfExport)
     *        $subscriptionService->hasFeature($user, 'pdf_export')
     */
    public function hasFeature(User $user, string|Feature $feature): bool
    {
        return $this->planModelFor($user)->hasFeature($feature);
    }

    /**
     * Returns the raw string value of a quota feature (e.g. '20', 'unlimited').
     * Returns null if the feature is not defined for the user's plan.
     */
    public function featureValue(User $user, string|Feature $feature): ?string
    {
        return $this->planModelFor($user)->featureValue($feature);
    }

    /**
     * Returns the integer daily limit for a specific tool, or null for unlimited.
     *
     * Lookup: Tool-specific feature key 'daily_{toolSlug}_limit' (e.g. 'daily_invoice_limit')
     * If not found, returns null (unlimited).
     */
    public function dailyLimitFor(User $user, string $toolSlug): ?int
    {
        $plan = $this->planModelFor($user);

        // Normalize slug to feature key: 'invoice-generator' → 'daily_invoice_generator_limit'
        $toolKey = 'daily_' . str_replace('-', '_', $toolSlug) . '_limit';

        $value = $plan->featureValue($toolKey);

        if ($value === null || $value === 'unlimited') {
            return null;
        }

        return (int) $value;
    }

    // ── Subscription management ───────────────────────────────────────────

    /**
     * Assign a plan to a user manually (admin action, no billing).
     * Creates or updates the user's active subscription.
     * Call this from admin panel until Cashier is integrated.
     */
    public function assignPlan(User $user, Plan $plan): Subscription
    {
        // Cancel existing active subscriptions
        Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active', 'trialing'])
            ->update(['status' => SubscriptionStatus::Cancelled->value]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status'  => SubscriptionStatus::Active->value,
        ]);

        $this->bustCache($user->id);

        return $subscription;
    }

    /**
     * Cancel the user's active subscription.
     */
    public function cancel(User $user): void
    {
        Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active', 'trialing'])
            ->update([
                'status'       => SubscriptionStatus::Cancelled->value,
                'cancelled_at' => now(),
            ]);

        $this->bustCache($user->id);
    }

    /**
     * Bust the plan cache for a user.
     * Must be called after any subscription change (upgrade, cancel, webhook).
     */
    public function bustCache(int|User $user): void
    {
        $id = $user instanceof User ? $user->id : $user;
        Cache::forget($this->planCacheKey($id));
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function planCacheKey(int $userId): string
    {
        return "subscription:plan:{$userId}";
    }
}
