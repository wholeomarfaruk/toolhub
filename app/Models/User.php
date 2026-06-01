<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'phone',
        'country_code',
        'address',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verified_at' => 'datetime',
        ];
    }
    // ── Subscription ─────────────────────────────────────────────────────

    /**
     * The user's current active or trialing subscription, eager-loads plan + features.
     * Returns null when the user has no subscription (treated as Free plan).
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['active', 'trialing'])
            ->with('plan.features')
            ->latestOfMany();
    }

    /**
     * Returns the user's active Plan model.
     * Falls back to the Free plan if no subscription exists.
     */
    public function activePlan(): Plan
    {
        return $this->subscription?->plan ?? Plan::freePlan();
    }

    // ── Panels ────────────────────────────────────────────────────────────

    // User has many panels
    public function panels()
    {
        return $this->belongsToMany(Panel::class);
    }

    public function hasPanel(string $panelSlug): bool
    {
        return $this->panels()->where('slug', $panelSlug)->exists();
    }
    //roleName
    public function roleName(): ?string
    {
        return $this->getRoleNames()->first();
    }

}
