<?php

namespace App\Http\Middleware;

use App\Enums\PlanTier;
use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates a route behind a minimum plan tier.
 *
 * Usage in routes:
 *   Route::get('/invoice-generator', ...)->middleware('plan:pro');
 *   Route::get('/team-settings', ...)->middleware('plan:enterprise');
 */
class CheckPlan
{
    public function __construct(private SubscriptionService $subscriptions) {}

    public function handle(Request $request, Closure $next, string $minimumPlan): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $required  = PlanTier::tryFrom($minimumPlan);

        if (! $required) {
            abort(500, "Unknown plan tier [{$minimumPlan}] in CheckPlan middleware.");
        }

        $userPlan = $this->subscriptions->planFor($user);

        if (! $userPlan->includes($required)) {
            return redirect()
                ->route('dashboard.subscription')
                ->with('upgrade_prompt', [
                    'reason'       => 'plan_required',
                    'minimum_plan' => $required->label(),
                    'current_plan' => $userPlan->label(),
                ]);
        }

        return $next($request);
    }
}
