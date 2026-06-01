<?php

namespace App\Contracts;

use App\Enums\PlanTier;
use App\Enums\ToolCategory;

interface ToolContract
{
    /** URL-safe identifier. Must be unique across all tools. */
    public function slug(): string;

    public function name(): string;

    public function description(): string;

    public function category(): ToolCategory;

    /** Boxicon class string e.g. 'bx bx-calculator' */
    public function icon(): string;

    /** Minimum plan required. Free tools are accessible to all authenticated users. */
    public function requiredPlan(): PlanTier;

    /**
     * Flat daily limit (all plans). Kept for backward compatibility.
     * Prefer dailyLimitFor() which is plan-aware.
     *
     * @deprecated Use SubscriptionService::dailyLimitFor() instead.
     */
    public function dailyLimit(): ?int;

    /**
     * Plan-aware daily limit. Override per tool to set per-plan limits.
     * null = unlimited for that plan tier.
     */
    public function dailyLimitFor(PlanTier $plan): ?int;

    /** FQCN of the Livewire component that renders this tool. */
    public function livewireComponent(): string;

    /** Laravel validation rules applied before handle() is called. */
    public function rules(): array;

    /** Pure business logic. Receives validated input, returns structured output. */
    public function handle(array $input): array;
}
