<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PlanList extends Component
{
    public $plans = [];
    public ?int $confirmDeleteId = null;

    public function mount()
    {
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = Plan::withCount('subscriptions')
            ->with('features')
            ->orderBy('sort_order')
            ->get();
    }

    public function toggleActive(Plan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        Plan::bustTierCache($plan->tier());
        $this->dispatch('toast', message: 'Plan status updated!');
        $this->loadPlans();
    }

    public function confirmDelete(int $id)
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(int $id)
    {
        $plan = Plan::findOrFail($id);

        if ($plan->subscriptions()->count() > 0) {
            $this->dispatch('toast', type: 'error', message: 'Cannot delete plan with active subscriptions.');
            return;
        }

        // Delete features first
        $plan->features()->delete();

        // Delete the plan
        $plan->delete();

        Plan::bustTierCache($plan->tier());
        $this->confirmDeleteId = null;

        $this->dispatch('toast', message: 'Plan deleted successfully!');
        $this->loadPlans();
    }

    public function moveUp(Plan $plan)
    {
        $previous = Plan::where('sort_order', '<', $plan->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if (!$previous) {
            return;
        }

        $temp = $plan->sort_order;
        $plan->update(['sort_order' => $previous->sort_order]);
        $previous->update(['sort_order' => $temp]);

        $this->loadPlans();
    }

    public function moveDown(Plan $plan)
    {
        $next = Plan::where('sort_order', '>', $plan->sort_order)
            ->orderBy('sort_order')
            ->first();

        if (!$next) {
            return;
        }

        $temp = $plan->sort_order;
        $plan->update(['sort_order' => $next->sort_order]);
        $next->update(['sort_order' => $temp]);

        $this->loadPlans();
    }

    public function render()
    {
        return view('livewire.admin.plans.plan-list');
    }
}
