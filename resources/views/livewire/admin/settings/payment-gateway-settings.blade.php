<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Payment Gateway Settings</h1>
        <p class="text-gray-500 mt-2">Manage and configure payment gateways for your platform</p>
    </div>

    {{-- Gateways List --}}
    <div class="grid gap-4">
        @forelse($gateways as $gateway)
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        {{-- Gateway Icon --}}
                        @if($gateway->icon_url)
                            <img src="{{ $gateway->icon_url }}" alt="{{ $gateway->name }}" class="w-12 h-12 rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center">
                                <i class="bx bx-credit-card text-gray-400 text-xl"></i>
                            </div>
                        @endif

                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $gateway->name }}</h3>
                                <span class="px-2 py-1 text-xs font-bold rounded {{ $gateway->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $gateway->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                </span>
                            </div>
                            @if($gateway->description)
                                <p class="text-sm text-gray-600">{{ $gateway->description }}</p>
                            @endif
                            <div class="mt-2 text-xs text-gray-500">
                                <p><strong>Environment:</strong> {{ ucfirst($gateway->environment) }}</p>
                                <p><strong>Transactions:</strong> {{ $gateway->total_transactions }} ({{ $gateway->total_amount }})</p>
                                @if($gateway->last_used_at)
                                    <p><strong>Last used:</strong> {{ $gateway->last_used_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                        <button wire:click="toggleGateway({{ $gateway->id }})"
                                class="px-4 py-2 rounded-lg font-medium transition-colors {{ $gateway->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                            {{ $gateway->is_active ? 'Disable' : 'Enable' }}
                        </button>
                        <button wire:click="editGateway({{ $gateway->id }})"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                            Configure
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-8 text-center">
                <i class="bx bx-inbox text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">No payment gateways available</p>
            </div>
        @endforelse
    </div>

    {{-- Configuration Modal --}}
    @if($showForm && $selectedGateway)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4">
                {{-- Modal Header --}}
                <div class="border-b border-gray-200 p-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Configure {{ $selectedGateway->name }}</h2>
                    <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                {{-- Modal Content --}}
                <form wire:submit="saveGateway" class="p-6 space-y-4">
                    {{-- Basic Settings --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Gateway Name</label>
                        <input type="text" wire:model="formData.name" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('formData.name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                        <textarea wire:model="formData.description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                    </div>

                    {{-- Environment --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Environment</label>
                            <select wire:model="formData.environment" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="sandbox">Sandbox (Testing)</option>
                                <option value="production">Production</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Sort Order</label>
                            <input type="number" wire:model="formData.sort_order" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    {{-- Configuration Fields --}}
                    @if(!empty($formData['config']))
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-900 mb-3">API Credentials</h4>
                            <div class="space-y-3">
                                @foreach($formData['config'] as $key => $value)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                        <input type="text" wire:model="formData.config.{{ $key }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm"
                                               placeholder="Enter {{ $key }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Test Connection --}}
                    <div class="border-t border-gray-200 pt-4">
                        <button type="button" wire:click="testConnection"
                                {{ $testingConnection ? 'disabled' : '' }}
                                class="px-4 py-2 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg font-medium transition-colors disabled:opacity-50">
                            {{ $testingConnection ? 'Testing...' : 'Test Connection' }}
                        </button>
                        @if($testResult)
                            <p class="mt-2 text-sm {{ str_contains($testResult, '✅') ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $testResult }}
                            </p>
                        @endif
                    </div>

                    {{-- Form Actions --}}
                    <div class="border-t border-gray-200 pt-4 flex gap-3">
                        <button type="button" wire:click="closeForm" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
