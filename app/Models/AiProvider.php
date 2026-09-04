<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiProvider extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'config',
        'icon_url',
        'sort_order',
        'total_requests',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get all active providers, ordered for display/selection.
     */
    public static function active()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get provider by slug.
     */
    public static function bySlug(string $slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * Get a provider config value.
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Merge new values into the provider config.
     */
    public function updateConfig(array $config)
    {
        $this->config = array_merge($this->config ?? [], $config);
        return $this->save();
    }

    /**
     * Record a usage event (successful or attempted generation call).
     */
    public function recordUsage()
    {
        $this->increment('total_requests');
        $this->update(['last_used_at' => now()]);
    }
}
