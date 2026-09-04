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

        <div class="px-4 py-4 max-w-xl">
            <h2 class="text-lg font-bold text-gray-900 mb-4">OpenRouter Configuration</h2>

            <dl class="space-y-3 text-sm bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between">
                    <dt class="text-gray-500">API Key</dt>
                    <dd class="font-mono text-gray-900">{{ $maskedKey ?? 'Not configured' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Base URL</dt>
                    <dd class="font-mono text-gray-900">{{ $baseUrl }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Default Model</dt>
                    <dd class="font-mono text-gray-900">{{ $defaultModel }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Max Tokens</dt>
                    <dd class="font-mono text-gray-900">{{ $maxTokens }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Timeout (s)</dt>
                    <dd class="font-mono text-gray-900">{{ $timeout }}</dd>
                </div>
            </dl>

            <p class="text-xs text-gray-500 mt-3">
                Configure these values via the <code class="bg-gray-100 px-1 rounded">OPENROUTER_*</code> environment variables.
            </p>

            <button wire:click="testConnection" wire:loading.attr="disabled"
                    class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2">
                <i class="bx" :class="$wire.loading ? 'bx-loader-alt animate-spin' : 'bx-plug'"></i>
                <span wire:loading.remove wire:target="testConnection">Test Connection</span>
                <span wire:loading wire:target="testConnection">Testing...</span>
            </button>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
