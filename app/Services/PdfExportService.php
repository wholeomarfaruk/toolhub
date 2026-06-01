<?php

namespace App\Services;

use App\Enums\Feature;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class PdfExportService
{
    /**
     * Check if user can export PDF this month.
     * Returns [can_export: bool, remaining: ?int, limit: ?int]
     */
    public function checkQuota(User $user): array
    {
        $subscriptionService = app(SubscriptionService::class);
        $plan = $subscriptionService->planModelFor($user);
        $limit = $plan->featureLimit(Feature::MonthlyPdfLimit->value);

        if ($limit === null) {
            return ['can_export' => true, 'remaining' => null, 'limit' => null];
        }

        $used = $this->countThisMonth($user->id);
        $remaining = max(0, $limit - $used);

        return [
            'can_export' => $remaining > 0,
            'remaining' => $remaining,
            'limit' => $limit,
        ];
    }

    /**
     * Record a PDF export for quota tracking.
     * Bust the monthly cache so next check is fresh.
     */
    public function record(User $user): void
    {
        Cache::forget($this->monthlyKey($user->id));

        // Insert into pdf_exports table (create this migration separately if needed)
        // For now, we'll just track via cache or a simple counter
        $key = $this->monthlyKey($user->id);
        Cache::put($key, ($this->countThisMonth($user->id) + 1), now()->endOfMonth());
    }

    /**
     * Count PDF exports this month (cached until end of month).
     */
    public function countThisMonth(int $userId): int
    {
        return Cache::remember(
            $this->monthlyKey($userId),
            now()->endOfMonth(),
            fn () => 0  // Placeholder: in production, query pdf_exports table
        );
    }

    private function monthlyKey(int $userId): string
    {
        return "pdf_exports:{$userId}:" . now()->format('Y-m');
    }
}
