<?php

namespace App\Livewire\Admin\Seo;

use App\Models\AiProvider;
use App\Services\AI\AiProviderService;
use Livewire\Component;

class AiSettings extends Component
{
    public $providers = [];
    public $selectedProvider = null;
    public $showForm = false;
    public $formData = [];
    public $testResult = null;
    public $testingConnection = false;

    public function mount()
    {
        $this->loadProviders();
    }

    public function loadProviders()
    {
        $this->providers = AiProvider::orderBy('sort_order')->get();
    }

    public function editProvider(AiProvider $provider)
    {
        $this->selectedProvider = $provider;
        $this->formData = [
            'name' => $provider->name,
            'is_active' => $provider->is_active,
            'config' => [
                'api_key' => $provider->getConfig('api_key', ''),
                'base_url' => $provider->getConfig('base_url', ''),
                'default_model' => $provider->getConfig('default_model', ''),
                'max_tokens' => $provider->getConfig('max_tokens', 2000),
                'timeout' => $provider->getConfig('timeout', 60),
            ],
        ];
        $this->testResult = null;
        $this->showForm = true;
    }

    public function saveProvider()
    {
        $this->validate([
            'formData.name' => 'required|string|max:255',
            'formData.is_active' => 'boolean',
            'formData.config.api_key' => 'nullable|string',
            'formData.config.base_url' => 'nullable|url',
            'formData.config.default_model' => 'nullable|string',
            'formData.config.max_tokens' => 'nullable|integer|min:1',
            'formData.config.timeout' => 'nullable|integer|min:1',
        ]);

        $this->selectedProvider->update([
            'name' => $this->formData['name'],
            'is_active' => $this->formData['is_active'],
        ]);

        $this->selectedProvider->updateConfig($this->formData['config']);

        $this->dispatch('toast', message: 'AI provider updated successfully!');
        $this->showForm = false;
        $this->loadProviders();
    }

    public function toggleProvider(AiProvider $provider)
    {
        $provider->update(['is_active' => !$provider->is_active]);
        $this->dispatch('toast', message: 'Provider status updated!');
        $this->loadProviders();
    }

    public function testConnection()
    {
        if (!$this->selectedProvider) {
            return;
        }

        $this->testingConnection = true;

        // Tests against the currently-saved config in the database, not unsaved
        // form edits — if you change the API key, Save first, then Test.
        $ok = app(AiProviderService::class)->testConnection($this->selectedProvider->slug);
        $this->testResult = $ok ? 'Connection successful!' : 'Connection failed. Check your API key and settings.';

        $this->testingConnection = false;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->selectedProvider = null;
        $this->formData = [];
        $this->testResult = null;
    }

    public function render()
    {
        return view('livewire.admin.seo.ai-settings')->layout('layouts.admin.admin');
    }
}
