<?php

namespace App\Http\Middleware;

use App\Enums\Feature;
use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates a route behind a specific feature flag.
 *
 * Usage in routes:
 *   Route::post('/invoice/save', ...)->middleware('feature:save_invoice');
 *   Route::get('/api/v1/...', ...)->middleware('feature:api_access');
 *
 * Accepts both Feature enum values and raw string keys.
 */
class CheckFeature
{
    public function __construct(private SubscriptionService $subscriptions) {}

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $this->subscriptions->hasFeature($user, $feature)) {
            // API routes → 403 JSON response, not a redirect
            if ($request->expectsJson()) {
                return response()->json([
                    'error'   => 'feature_not_available',
                    'message' => "Your plan does not include [{$feature}]. Upgrade to continue.",
                ], 403);
            }

            $plan = $this->subscriptions->planFor($user);

            return redirect()
                ->route('dashboard.subscription')
                ->with('upgrade_prompt', [
                    'reason'       => 'feature_required',
                    'feature'      => $feature,
                    'current_plan' => $plan->label(),
                ]);
        }

        return $next($request);
    }
}
