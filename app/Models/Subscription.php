<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'status',
        'current_period_start', 'current_period_end',
        'trial_ends_at', 'cancelled_at',
        'stripe_subscription_id', 'stripe_customer_id',
    ];

    protected function casts(): array
    {
        return [
            'status'               => SubscriptionStatus::class,
            'current_period_start' => 'datetime',
            'current_period_end'   => 'datetime',
            'trial_ends_at'        => 'datetime',
            'cancelled_at'         => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function user(): BelongsTo   { return $this->belongsTo(User::class); }
    public function plan(): BelongsTo   { return $this->belongsTo(Plan::class); }

    // ── Status helpers ────────────────────────────────────────────────────

    public function isActive(): bool    { return $this->status->isAccessible(); }
    public function onTrial(): bool
    {
        return $this->status === SubscriptionStatus::Trialing
            && $this->trial_ends_at?->isFuture();
    }

    public function daysRemaining(): ?int
    {
        if ($this->current_period_end === null) return null;
        return (int) now()->diffInDays($this->current_period_end, absolute: false);
    }
}
