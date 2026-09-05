{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: 'AI Settings', slug: 'seo-ai-settings' }">
    {{-- ======================== Page Header Start From Here ======================== --}}
    <div class="flex flex-wrap justify-between gap-6">
        {{-- Page Name --}}
        <h1 class="text-gray-500 text-lg font-bold" x-cloak x-text="$store.pageName?.name ?? ''"></h1>

        {{-- Breadcrumb --}}
        <nav>
            <ol class="flex items-center gap-1.5">
                <li>
                    <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                        href="{{ route('admin.dashboard') }}">
                        Dashboard
                        <svg class="stroke-current" width="17" height="16" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
                <li class="text-sm text-gray-800 dark:text-white/90" x-text="$store.pageName?.name ?? ''"></li>
            </ol>
        </nav>
    </div>
    {{-- ======================== Page Header End Here ======================== --}}

    <div class="flex-1 w-full bg-white rounded-lg min-h-[80vh]">
        {{-- ======================== Content Start From Here ======================== --}}

        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">AI Providers</h2>
                <p class="text-sm text-gray-500 mt-0.5">Manage the AI providers used for programmatic SEO keyword and content generation.</p>
            </div>

            {{-- Providers List --}}
            <div class="grid gap-4">
                @forelse($providers as $provider)
                    @php
                        $apiKey = $provider->getConfig('api_key');
                        $maskedKey = $apiKey ? str_repeat('•', 8) . substr($apiKey, -4) : null;
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between flex-wrap gap-4">
                            <div class="flex items-start gap-4">
                                @if($provider->icon_url)
                                    <img src="{{ $provider->icon_url }}" alt="{{ $provider->name }}" class="w-12 h-12 rounded-lg">
                                @else
                                    <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                                        <i class="bx bx-credit-card text-indigo-400 text-xl"></i>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $provider->name }}</h3>
                                        <span @class([
                                            'inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full',
                                            'bg-emerald-100 text-emerald-700' => $provider->is_active,
                                            'bg-gray-100 text-gray-600' => ! $provider->is_active,
                                        ])>
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            {{ $provider->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    @if($provider->description)
                                        <p class="text-sm text-gray-600">{{ $provider->description }}</p>
                                    @endif
                                    <div class="mt-2 text-xs text-gray-500 space-y-0.5">
                                        <p><strong>API Key:</strong> <span class="font-mono">{{ $maskedKey ?? 'Not configured' }}</span></p>
                                        <p><strong>Default Model:</strong> <span class="font-mono">{{ $provider->getConfig('default_model') }}</span></p>
                                        <p><strong>Requests:</strong> {{ $provider->total_requests }}</p>
                                        @if($provider->last_used_at)
                                            <p><strong>Last used:</strong> {{ $provider->last_used_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2">
                                <button wire:click="toggleProvider({{ $provider->id }})"
                                        class="inline-flex items-center gap-1 px-3.5 py-2 rounded-lg font-semibold transition-colors text-sm {{ $provider->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                    {{ $provider->is_active ? 'Disable' : 'Enable' }}
                                </button>
                                <button wire:click="editProvider({{ $provider->id }})"
                                        class="inline-flex items-center gap-1 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-colors text-sm">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center bg-white rounded-xl border border-gray-100">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                            <i class="bx bx-inbox text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-600 font-semibold mb-1">No AI providers yet</p>
                        <p class="text-sm text-gray-400">AI providers are seeded by the system — none are currently available.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Configuration Modal --}}
        @if($showForm && $selectedProvider)
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    {{-- Modal Header --}}
                    <div class="border-b border-gray-100 p-6 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Configure {{ $selectedProvider->name }}</h2>
                        <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="bx bx-x text-2xl"></i>
                        </button>
                    </div>

                    {{-- Modal Content --}}
                    <form wire:submit="saveProvider" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Provider Name</label>
                            <input type="text" wire:model="formData.name" class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            @error('formData.name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="formData.is_active" id="is_active" class="rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500">
                            <label for="is_active" class="text-sm font-semibold text-gray-700">Active (available for use in generation screens)</label>
                        </div>

                        {{-- Configuration Fields --}}
                        <div class="border-t border-gray-100 pt-4">
                            <h4 class="font-semibold text-gray-900 mb-3">API Credentials</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">API Key</label>
                                    <input type="password" wire:model="formData.config.api_key"
                                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg font-mono text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                           placeholder="Enter API key" autocomplete="new-password">
                                    @error('formData.config.api_key') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Base URL</label>
                                    <input type="text" wire:model="formData.config.base_url"
                                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg font-mono text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                           placeholder="https://api.example.com/v1">
                                    @error('formData.config.base_url') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Default Model</label>
                                    <input type="text" wire:model="formData.config.default_model"
                                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg font-mono text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                           placeholder="e.g. gpt-4o-mini">
                                    @error('formData.config.default_model') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Max Tokens</label>
                                        <input type="number" wire:model="formData.config.max_tokens"
                                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                        @error('formData.config.max_tokens') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Timeout (s)</label>
                                        <input type="number" wire:model="formData.config.timeout"
                                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                        @error('formData.config.timeout') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Test Connection --}}
                        <div class="border-t border-gray-100 pt-4">
                            <button type="button" wire:click="testConnection" wire:loading.attr="disabled" wire:target="testConnection"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg font-semibold transition-colors disabled:opacity-50 text-sm">
                                <i class="bx" :class="$wire.testingConnection ? 'bx-loader-alt animate-spin' : 'bx-plug'"></i>
                                <span wire:loading.remove wire:target="testConnection">Test Connection</span>
                                <span wire:loading wire:target="testConnection">Testing...</span>
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Tests against the saved configuration — save your changes first, then test.</p>
                            @if($testResult)
                                <p class="mt-2 text-sm flex items-center gap-1.5 {{ str_contains($testResult, 'successful') ? 'text-emerald-600' : 'text-red-600' }}">
                                    <i class="bx {{ str_contains($testResult, 'successful') ? 'bx-check-circle' : 'bx-x-circle' }}"></i>
                                    {{ $testResult }}
                                </p>
                            @endif
                        </div>

                        {{-- Form Actions --}}
                        <div class="border-t border-gray-100 pt-4 flex gap-3">
                            <button type="button" wire:click="closeForm" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-sm">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 shadow-sm transition-colors text-sm">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
