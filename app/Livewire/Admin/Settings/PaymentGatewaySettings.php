<?php

namespace App\Livewire\Admin\Settings;

use App\Models\PaymentGateway;
use Livewire\Component;

class PaymentGatewaySettings extends Component
{
    public $gateways = [];
    public $selectedGateway = null;
    public $showForm = false;
    public $formData = [];
    public $testingConnection = false;
    public $testResult = null;

    public function mount()
    {
        $this->loadGateways();
    }

    public function loadGateways()
    {
        $this->gateways = PaymentGateway::orderBy('sort_order')->get();
    }

    public function editGateway(PaymentGateway $gateway)
    {
        $this->selectedGateway = $gateway;
        $this->formData = [
            'name' => $gateway->name,
            'description' => $gateway->description,
            'is_active' => $gateway->is_active,
            'environment' => $gateway->environment,
            'icon_url' => $gateway->icon_url,
            'sort_order' => $gateway->sort_order,
        ];

        // Load config fields based on gateway type
        $this->loadConfigFields($gateway->slug);
        $this->showForm = true;
    }

    public function loadConfigFields(string $slug)
    {
        // Define fields for each gateway
        $fields = match($slug) {
            'nowpayments' => [
                'api_key' => $this->selectedGateway?->getConfig('api_key', ''),
                'webhook_secret' => $this->selectedGateway?->getConfig('webhook_secret', ''),
            ],
            'ssl_commerz' => [
                'store_id' => $this->selectedGateway?->getConfig('store_id', ''),
                'store_password' => $this->selectedGateway?->getConfig('store_password', ''),
            ],
            default => []
        };

        $this->formData['config'] = $fields;
    }

    public function saveGateway()
    {
        $validated = $this->validate([
            'formData.name' => 'required|string|max:255',
            'formData.description' => 'nullable|string',
            'formData.is_active' => 'boolean',
            'formData.environment' => 'required|in:sandbox,production',
            'formData.icon_url' => 'nullable|url',
            'formData.sort_order' => 'integer',
        ]);

        $this->selectedGateway->update([
            'name' => $this->formData['name'],
            'description' => $this->formData['description'],
            'is_active' => $this->formData['is_active'],
            'environment' => $this->formData['environment'],
            'icon_url' => $this->formData['icon_url'],
            'sort_order' => $this->formData['sort_order'],
        ]);

        // Update config
        if (!empty($this->formData['config'])) {
            $this->selectedGateway->updateConfig($this->formData['config']);
        }

        $this->dispatch('notify', message: 'Payment gateway updated successfully!');
        $this->showForm = false;
        $this->loadGateways();
    }

    public function testConnection()
    {
        if (!$this->selectedGateway) {
            return;
        }

        $this->testingConnection = true;
        $this->testResult = null;

        try {
            // Test based on gateway type
            $result = match($this->selectedGateway->slug) {
                'nowpayments' => $this->testNOWPayments(),
                'ssl_commerz' => $this->testSSLCommerz(),
                default => false
            };

            $this->testResult = $result ? 'Connection successful! ✅' : 'Connection failed ❌';
        } catch (\Exception $e) {
            $this->testResult = 'Error: ' . $e->getMessage();
        } finally {
            $this->testingConnection = false;
        }
    }

    private function testNOWPayments()
    {
        $apiKey = $this->formData['config']['api_key'] ?? null;
        if (!$apiKey) {
            throw new \Exception('API Key is required');
        }

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'x-api-key' => $apiKey,
        ])->get('https://api.nowpayments.io/v1/status');

        return $response->successful();
    }

    private function testSSLCommerz()
    {
        $storeId = $this->formData['config']['store_id'] ?? null;
        if (!$storeId) {
            throw new \Exception('Store ID is required');
        }

        // SSL Commerz test (placeholder)
        return true;
    }

    public function toggleGateway(PaymentGateway $gateway)
    {
        // Only allow one gateway to be active if needed
        if ($gateway->is_active) {
            $gateway->update(['is_active' => false]);
        } else {
            // Optional: disable others if only one should be active
            // PaymentGateway::where('id', '!=', $gateway->id)->update(['is_active' => false]);
            $gateway->update(['is_active' => true]);
        }

        $this->dispatch('notify', message: 'Gateway status updated!');
        $this->loadGateways();
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->selectedGateway = null;
        $this->formData = [];
        $this->testResult = null;
    }

    public function render()
    {
        return view('livewire.admin.settings.payment-gateway-settings')->layout('layouts.admin.admin', ['title' => 'Payment Gateway Settings']);
    }
}
