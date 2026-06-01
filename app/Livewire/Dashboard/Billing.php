<?php

namespace App\Livewire\Dashboard;

use App\Models\Plan;
use Livewire\Component;

class Billing extends Component
{
    public $currentPlan;
    public $allPlans;
    public $subscription;

    public function mount()
    {
        $user = auth()->user();
        $this->currentPlan = $user->activePlan();
        $this->subscription = $user->subscription;
        $this->allPlans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function upgradePlan($planSlug)
    {
        $plan = Plan::where('slug', $planSlug)->firstOrFail();

        if ($plan->id === $this->currentPlan->id) {
            $this->dispatch('notify', message: 'You are already on this plan');
            return;
        }

        return redirect()->route('checkout.show', ['plan' => $plan->slug]);
    }

    public function downgradeToPlan($planSlug)
    {
        $plan = Plan::where('slug', $planSlug)->firstOrFail();

        if ($plan->id === $this->currentPlan->id) {
            $this->dispatch('notify', message: 'You are already on this plan');
            return;
        }

        // Assign new plan
        app(\App\Services\SubscriptionService::class)->assignPlan(auth()->user(), $plan);

        $this->mount();
        $this->dispatch('notify', message: "Downgraded to {$plan->name} plan");
    }

    public function render()
    {
        return view('livewire.dashboard.billing');
    }
}
