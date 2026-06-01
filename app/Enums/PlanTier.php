<?php

namespace App\Enums;

enum PlanTier: string
{
    case Free       = 'free';
    case Pro        = 'pro';
    case Enterprise = 'enterprise';

    public function includes(PlanTier $required): bool
    {
        $hierarchy = [self::Free->value, self::Pro->value, self::Enterprise->value];

        return array_search($this->value, $hierarchy) >= array_search($required->value, $hierarchy);
    }

    public function label(): string
    {
        return match($this) {
            self::Free       => 'Free',
            self::Pro        => 'Pro',
            self::Enterprise => 'Enterprise',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Free       => 'bg-gray-100 text-gray-600',
            self::Pro        => 'bg-indigo-100 text-indigo-700',
            self::Enterprise => 'bg-amber-100 text-amber-700',
        };
    }
}
