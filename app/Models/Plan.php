<?php

namespace App\Models;

use App\Enums\Feature;
use App\Enums\PlanTier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description',
        'price_monthly', 'price_yearly',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // ── Feature access ────────────────────────────────────────────────────

    /**
     * Returns true if the plan has the feature enabled (non-false, non-zero value).
     */
    public function hasFeature(string|Feature $key): bool
    {
        $key     = $key instanceof Feature ? $key->value : $key;
        $feature = $this->features->firstWhere('key', $key);

        if (! $feature) {
            return false;
        }

        return ! in_array($feature->value, ['false', '0', ''], strict: true);
    }

    /**
     * Returns the raw string value for a feature key, or null if not configured.
     * Callers cast to int/bool as needed.
     */
    public function featureValue(string|Feature $key): ?string
    {
        $key = $key instanceof Feature ? $key->value : $key;

        return $this->features->firstWhere('key', $key)?->value;
    }

    /**
     * Returns the integer quota for a limit feature, or null for unlimited/not set.
     */
    public function featureLimit(string|Feature $key): ?int
    {
        $value = $this->featureValue($key);

        if ($value === null || $value === 'unlimited') {
            return null;
        }

        return (int) $value;
    }

    // ── Enum helpers ──────────────────────────────────────────────────────

    public function tier(): PlanTier
    {
        return PlanTier::tryFrom($this->slug) ?? PlanTier::Free;
    }

    public function priceMonthlyCents(): int   { return $this->price_monthly ?? 0; }
    public function priceMonthlyFormatted(): string
    {
        if ($this->price_monthly === 0) return 'Free';
        return '$' . number_format($this->price_monthly / 100, 2) . '/mo';
    }

    // ── Static finders (cached) ───────────────────────────────────────────

    public static function forTier(PlanTier $tier): ?self
    {
        return Cache::rememberForever(
            "plan:tier:{$tier->value}",
            fn () => static::where('slug', $tier->value)
                ->with('features')
                ->first()
        );
    }

    public static function freePlan(): self
    {
        return static::forTier(PlanTier::Free)
            ?? new self(['slug' => 'free', 'name' => 'Free', 'price_monthly' => 0]);
    }

    public static function bustTierCache(PlanTier $tier): void
    {
        Cache::forget("plan:tier:{$tier->value}");
    }
}
