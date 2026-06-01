<?php

namespace App\Services;

use App\Models\ToolUsage;
use Illuminate\Support\Facades\Cache;

class UsageService
{
    // ── Recording ─────────────────────────────────────────────────────────

    public function record(int $userId, string $toolSlug): void
    {
        ToolUsage::create([
            'user_id'   => $userId,
            'tool_slug' => $toolSlug,
        ]);

        // Bust both daily and monthly caches on every new record
        Cache::forget($this->dailyKey($userId, $toolSlug));
        Cache::forget($this->monthlyKey($userId, $toolSlug));
    }

    // ── Counts ────────────────────────────────────────────────────────────

    public function countToday(int $userId, string $toolSlug): int
    {
        return Cache::remember(
            $this->dailyKey($userId, $toolSlug),
            now()->endOfDay(),
            fn () => ToolUsage::where('user_id', $userId)
                ->where('tool_slug', $toolSlug)
                ->whereDate('created_at', today())
                ->count()
        );
    }

    public function countThisMonth(int $userId, string $toolSlug): int
    {
        return Cache::remember(
            $this->monthlyKey($userId, $toolSlug),
            now()->endOfMonth(),
            fn () => ToolUsage::where('user_id', $userId)
                ->where('tool_slug', $toolSlug)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        );
    }

    public function countAllTime(int $userId, string $toolSlug): int
    {
        return ToolUsage::where('user_id', $userId)
            ->where('tool_slug', $toolSlug)
            ->count();
    }

    /**
     * Total uses today across ALL tools for a user.
     * Used by the dashboard stats card.
     */
    public function countAllToolsToday(int $userId): int
    {
        return Cache::remember(
            "tool_usage:all:daily:{$userId}:" . today()->toDateString(),
            now()->endOfDay(),
            fn () => ToolUsage::where('user_id', $userId)
                ->whereDate('created_at', today())
                ->count()
        );
    }

    /**
     * Returns the most-used tool slug for a user.
     */
    public function mostUsed(int $userId): ?object
    {
        return ToolUsage::where('user_id', $userId)
            ->selectRaw('tool_slug, COUNT(*) as total')
            ->groupBy('tool_slug')
            ->orderByDesc('total')
            ->first();
    }

    // ── Cache keys ────────────────────────────────────────────────────────

    private function dailyKey(int $userId, string $toolSlug): string
    {
        return "tool_usage:{$userId}:{$toolSlug}:" . today()->toDateString();
    }

    private function monthlyKey(int $userId, string $toolSlug): string
    {
        return "tool_usage_monthly:{$userId}:{$toolSlug}:" . now()->format('Y-m');
    }
}
