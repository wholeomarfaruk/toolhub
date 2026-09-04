<?php

namespace App\Livewire\Admin\Seo;

use App\Services\AI\OpenRouterService;
use Livewire\Component;

class AiSettings extends Component
{
    public function testConnection()
    {
        $ok = app(OpenRouterService::class)->testConnection();

        if ($ok) {
            $this->dispatch('toast', message: 'OpenRouter connection successful!');
        } else {
            $this->dispatch('toast', type: 'error', message: 'OpenRouter connection failed. Check your API key and settings.');
        }
    }

    public function render()
    {
        $apiKey = config('services.openrouter.api_key');
        $maskedKey = $apiKey ? str_repeat('•', 8) . substr($apiKey, -4) : null;

        return view('livewire.admin.seo.ai-settings', [
            'maskedKey' => $maskedKey,
            'baseUrl' => config('services.openrouter.base_url'),
            'defaultModel' => config('services.openrouter.default_model'),
            'maxTokens' => config('services.openrouter.max_tokens'),
            'timeout' => config('services.openrouter.timeout'),
        ])->layout('layouts.admin.admin');
    }
}
