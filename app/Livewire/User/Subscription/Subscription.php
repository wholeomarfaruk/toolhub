<?php

namespace App\Livewire\User\Subscription;

use App\Services\SubscriptionService;
use App\Services\ToolRegistry;
use Livewire\Component;

class Subscription extends Component
{
    public function render()
    {
        $plan      = app(SubscriptionService::class)->planFor(auth()->user());
        $toolCount = count(app(ToolRegistry::class)->accessibleFor($plan));

        return view('livewire.user.subscription.subscription', [
            'plan'      => $plan,
            'toolCount' => $toolCount,
        ])->layout('layouts.user.user', ['title' => 'Subscription']);
    }
}
