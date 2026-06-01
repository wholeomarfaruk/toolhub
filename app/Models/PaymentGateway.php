<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'environment',
        'config',
        'icon_url',
        'sort_order',
        'total_transactions',
        'total_amount',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
        'total_amount' => 'decimal:2',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get active gateway
     */
    public static function active()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get gateway by slug
     */
    public static function bySlug(string $slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * Get gateway config value
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Update gateway config
     */
    public function updateConfig(array $config)
    {
        $this->config = array_merge($this->config ?? [], $config);
        return $this->save();
    }

    /**
     * Record transaction
     */
    public function recordTransaction(float $amount)
    {
        $this->increment('total_transactions');
        $this->increment('total_amount', $amount);
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Test connection
     */
    public function testConnection(): bool
    {
        // Override in subclasses
        return true;
    }
}
