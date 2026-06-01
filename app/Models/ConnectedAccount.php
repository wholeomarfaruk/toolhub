<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConnectedAccount extends Model
{
    protected $fillable = ['user_id', 'provider', 'provider_id', 'provider_email', 'provider_data'];

    protected $casts = [
        'provider_data' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a provider account is connected
     */
    public static function isConnected(string $provider, int $userId): bool
    {
        return self::where('provider', $provider)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get connected provider account
     */
    public static function getByProvider(string $provider, int $userId): ?self
    {
        return self::where('provider', $provider)
            ->where('user_id', $userId)
            ->first();
    }
}
