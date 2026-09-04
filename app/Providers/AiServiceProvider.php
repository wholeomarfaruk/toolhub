<?php

namespace App\Providers;

use App\Services\AI\OpenRouterService;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OpenRouterService::class);
    }
}
