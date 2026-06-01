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

    public function dailyLimit(): ?int
    {
        return null;
    }

    /**
     * Default plan-aware limit falls back to the flat dailyLimit().
     * Override in concrete tools to set per-plan quotas.
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
