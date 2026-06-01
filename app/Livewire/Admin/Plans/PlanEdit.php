<?php

namespace App\Livewire\Admin\Plans;

use App\Enums\Feature;
use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PlanEdit extends Component
{
    public ?Plan $plan = null;
    public bool $isCreating = false;

    public string $name = '';
    public string $slug = '';
    public ?string $description = '';
    public int $priceMonthly = 0;
    public ?int $priceYearly = null;
    public bool $isActive = true;
    public int $sortOrder = 0;

    public array $features = [];

    public function mount(?Plan $plan = null)
    {
        $this->plan = $plan;
        $this->isCreating = !$plan;

        if ($plan) {
            $this->name = $plan->name;
            $this->slug = $plan->slug;
            $this->description = $plan->description;
            $this->priceMonthly = $plan->price_monthly / 100; // Convert cents to dollars
            $this->priceYearly = $plan->price_yearly ? $plan->price_yearly / 100 : null; // Convert cents to dollars
            $this->isActive = $plan->is_active;
            $this->sortOrder = $plan->sort_order;

            // Load features
            foreach ($plan->features as $feature) {
                $this->features[$feature->key] = $feature->value;
            }
        } else {
            $this->isActive = true;
            $this->sortOrder = Plan::max('sort_order') + 1;
        }

        // Initialize all features
        foreach (Feature::cases() as $feature) {
            if (!isset($this->features[$feature->value])) {
                $this->features[$feature->value] = $feature->isBoolean() ? false : '0';
            }
        }
    }

    public function updatedName($value)
    {
        if ($this->isCreating) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|alpha_dash|unique:plans,slug,' . ($this->plan?->id ?? 'NULL'),
            'priceMonthly' => 'required|numeric|min:0',
            'priceYearly' => 'nullable|numeric|min:0',
            'sortOrder' => 'required|integer|min:0',
        ]);

        // Upsert plan
        $plan = Plan::updateOrCreate(
            ['id' => $this->plan?->id],
            [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'price_monthly' => (int)($this->priceMonthly * 100),
                'price_yearly' => $this->priceYearly ? (int)($this->priceYearly * 100) : null,
                'is_active' => $this->isActive,
                'sort_order' => $this->sortOrder,
            ]
        );

        // Upsert features
        foreach (Feature::cases() as $feature) {
            $value = $this->features[$feature->value] ?? ($feature->isBoolean() ? false : '0');

            PlanFeature::updateOrCreate(
                ['plan_id' => $plan->id, 'key' => $feature->value],
                ['value' => (string)$value]
            );
        }

        // Bust cache
        Plan::bustTierCache($plan->tier());

        $this->dispatch('toast', message: 'Plan saved successfully!');
        return redirect()->route('admin.plans.list');
    }

    public function render()
    {
        return view('livewire.admin.plans.plan-edit', [
            'booleanFeatures' => collect(Feature::cases())->filter(fn($f) => $f->isBoolean()),
            'quotaFeatures' => collect(Feature::cases())->filter(fn($f) => $f->isQuota()),
        ]);
    }
}
