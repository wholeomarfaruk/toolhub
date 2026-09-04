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

        <div class="px-4 py-4">
            <h2 class="text-lg font-bold text-gray-900 mb-1">AI Providers</h2>
            <p class="text-sm text-gray-500 mb-4">Manage the AI providers used for programmatic SEO keyword and content generation.</p>

            {{-- Providers List --}}
            <div class="grid gap-4">
                @forelse($providers as $provider)
                    @php
                        $apiKey = $provider->getConfig('api_key');
                        $maskedKey = $apiKey ? str_repeat('•', 8) . substr($apiKey, -4) : null;
                    @endphp
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="flex items-start justify-between flex-wrap gap-4">
                            <div class="flex items-start gap-4">
                                @if($provider->icon_url)
                                    <img src="{{ $provider->icon_url }}" alt="{{ $provider->name }}" class="w-12 h-12 rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center">
                                        <i class="bx bx-bulb text-gray-400 text-xl"></i>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $provider->name }}</h3>
                                        <span class="px-2 py-1 text-xs font-bold rounded {{ $provider->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $provider->is_active ? 'ACTIVE' : 'INACTIVE' }}
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
                                        class="px-4 py-2 rounded-lg font-medium transition-colors text-sm {{ $provider->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                                    {{ $provider->is_active ? 'Disable' : 'Enable' }}
                                </button>
                                <button wire:click="editProvider({{ $provider->id }})"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors text-sm">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-8 text-center">
                        <i class="bx bx-inbox text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">No AI providers available</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Configuration Modal --}}
        @if($showForm && $selectedProvider)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    {{-- Modal Header --}}
                    <div class="border-b border-gray-200 p-6 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Configure {{ $selectedProvider->name }}</h2>
                        <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600">
                            <i class="bx bx-x text-2xl"></i>
                        </button>
                    </div>

                    {{-- Modal Content --}}
                    <form wire:submit="saveProvider" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Provider Name</label>
                            <input type="text" wire:model="formData.name" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('formData.name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="formData.is_active" id="is_active" class="rounded border-gray-300">
                            <label for="is_active" class="text-sm font-semibold text-gray-900">Active (available for use in generation screens)</label>
                        </div>

                        {{-- Configuration Fields --}}
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-900 mb-3">API Credentials</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                                    <input type="password" wire:model="formData.config.api_key"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm"
                                           placeholder="Enter API key" autocomplete="new-password">
                                    @error('formData.config.api_key') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Base URL</label>
                                    <input type="text" wire:model="formData.config.base_url"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm"
                                           placeholder="https://api.example.com/v1">
                                    @error('formData.config.base_url') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Default Model</label>
                                    <input type="text" wire:model="formData.config.default_model"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm"
                                           placeholder="e.g. gpt-4o-mini">
                                    @error('formData.config.default_model') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Tokens</label>
                                        <input type="number" wire:model="formData.config.max_tokens"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        @error('formData.config.max_tokens') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Timeout (s)</label>
                                        <input type="number" wire:model="formData.config.timeout"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        @error('formData.config.timeout') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Test Connection --}}
                        <div class="border-t border-gray-200 pt-4">
                            <button type="button" wire:click="testConnection" wire:loading.attr="disabled" wire:target="testConnection"
                                    class="px-4 py-2 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg font-medium transition-colors disabled:opacity-50 flex items-center gap-2">
                                <i class="bx" :class="$wire.testingConnection ? 'bx-loader-alt animate-spin' : 'bx-plug'"></i>
                                <span wire:loading.remove wire:target="testConnection">Test Connection</span>
                                <span wire:loading wire:target="testConnection">Testing...</span>
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Tests against the saved configuration — save your changes first, then test.</p>
                            @if($testResult)
                                <p class="mt-2 text-sm {{ str_contains($testResult, 'successful') ? 'text-emerald-600' : 'text-red-600' }}">
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

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
