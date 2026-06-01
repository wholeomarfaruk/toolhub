<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Active    = 'active';
    case Trialing  = 'trialing';
    case PastDue   = 'past_due';
    case Cancelled = 'cancelled';
    case Expired   = 'expired';

    public function isAccessible(): bool
    {
        return in_array($this, [self::Active, self::Trialing]);
    }

    public function label(): string
    {
        return match($this) {
            self::Active    => 'Active',
            self::Trialing  => 'Trial',
            self::PastDue   => 'Past Due',
            self::Cancelled => 'Cancelled',
            self::Expired   => 'Expired',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Active    => 'bg-emerald-100 text-emerald-700',
            self::Trialing  => 'bg-blue-100 text-blue-700',
            self::PastDue   => 'bg-amber-100 text-amber-700',
            self::Cancelled => 'bg-red-100 text-red-700',
            self::Expired   => 'bg-gray-100 text-gray-600',
        };
    }
}
