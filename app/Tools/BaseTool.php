<?php

namespace App\Tools;

use App\Contracts\ToolContract;
use App\Enums\PlanTier;
use Illuminate\Support\Facades\Validator;

abstract class BaseTool implements ToolContract
{
    public function requiredPlan(): PlanTier
    {
        return PlanTier::Free;
    }

    /**
     * Returns the default daily limit for this tool across all plans.
     * This is a tool-level default that can be overridden per plan in the admin panel.
     *
     * Return null for unlimited.
     *
     * Example: Age Calculator returns 20 as default, but admin can override
     * to 5 for Free plan, 100 for Pro plan, unlimited for Enterprise.
     */
    public function dailyLimit(): ?int
    {
        return null;
    }

    /**
     * Returns the daily limit for a specific plan tier.
     * By default, returns the same as dailyLimit() (plan-agnostic).
     *
     * Override in concrete tools to set different limits per plan tier.
     * Note: Database overrides (plan_features) take precedence over this method.
     *
     * Example usage:
     *   - dailyLimitFor(PlanTier::Free) returns 5
     *   - dailyLimitFor(PlanTier::Pro) returns 100
     *   - dailyLimitFor(PlanTier::Enterprise) returns null (unlimited)
     */
    public function dailyLimitFor(PlanTier $plan): ?int
    {
        return $this->dailyLimit();
    }

    public function rules(): array
    {
        return [];
    }

    /**
     * Validates input then delegates to handle().
     * Livewire components always call run(), never handle() directly.
     */
    final public function run(array $input): array
    {
        $rules = $this->rules();

        $validated = $rules
            ? Validator::make($input, $rules)->validate()
            : $input;

        return $this->handle($validated);
    }
}
