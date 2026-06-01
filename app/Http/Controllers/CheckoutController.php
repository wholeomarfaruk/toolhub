<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class CheckoutController extends Controller
{
    /**
     * Complete checkout and activate subscription after payment
     */
    public function completeCheckout()
    {
        $checkout = session('checkout');

        if (!$checkout) {
            return redirect()->route('dashboard.billing')
                ->with('error', 'Checkout session expired');
        }

        $plan = Plan::find($checkout['plan_id']);
        $user = auth()->user();

        if (!$plan) {
            return redirect()->route('dashboard.billing')
                ->with('error', 'Plan not found');
        }

        // Assign plan to user
        app(\App\Services\SubscriptionService::class)->assignPlan($user, $plan);

        // Clear session
        session()->forget('checkout');

        return redirect()->route('dashboard.billing')
            ->with('success', "Welcome to {$plan->name} plan! You now have access to all features.");
    }
}
