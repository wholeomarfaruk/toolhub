<?php

namespace App\Services;

use App\Contracts\ToolContract;
use App\Enums\PlanTier;
use App\Enums\ToolCategory;
use RuntimeException;

class ToolRegistry
{
    /** @var array<string, ToolContract> */
    private array $tools = [];

    public function register(ToolContract $tool): void
    {
        $this->tools[$tool->slug()] = $tool;
    }

    public function find(string $slug): ToolContract
    {
        return $this->tools[$slug]
            ?? throw new RuntimeException("Tool [{$slug}] is not registered.");
    }

    public function tryFind(string $slug): ?ToolContract
    {
        return $this->tools[$slug] ?? null;
    }

    /** @return array<string, ToolContract> */
    public function all(): array
    {
        return $this->tools;
    }

    /** @return ToolContract[] */
    public function byCategory(ToolCategory $category): array
    {
        return array_values(array_filter(
            $this->tools,
            fn(ToolContract $t) => $t->category() === $category
        ));
    }

    /**
     * Tools accessible to a given plan tier.
     * @return ToolContract[]
     */
    public function accessibleFor(PlanTier $plan): array
    {
        return array_values(array_filter(
            $this->tools,
            fn(ToolContract $t) => $plan->includes($t->requiredPlan())
        ));
    }

    /**
     * All tools grouped by category value.
     * @return array<string, ToolContract[]>
     */
    public function groupedByCategory(): array
    {
        $grouped = [];

        foreach ($this->tools as $tool) {
            $grouped[$tool->category()->value][] = $tool;
        }

        return $grouped;
    }
}
