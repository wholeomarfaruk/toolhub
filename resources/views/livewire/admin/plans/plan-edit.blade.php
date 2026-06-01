{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: @json($isCreating ? 'Create Plan' : 'Edit Plan'), slug: 'plans-edit' }">
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
                <li>
                    <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                        href="{{ route('admin.plans.list') }}">
                        Plans
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

        {{-- Form Wrapper --}}
        <div class="px-4 py-4">
            <form wire:submit="save" class="space-y-6">

                {{-- Plan Details Section --}}
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Plan Details</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Plan Name *</label>
                            <input type="text" wire:model="name" wire:change="updatedName"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                   placeholder="e.g., Professional">
                            @error('name') <span class="text-red-600 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Slug (URL) *</label>
                            <input type="text" wire:model="slug"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-xs"
                                   placeholder="e.g., professional">
                            @error('slug') <span class="text-red-600 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                  placeholder="Brief description..."></textarea>
                    </div>

                    {{-- Pricing --}}
                    <div class="grid gap-4 md:grid-cols-2 mt-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Monthly Price (USD) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 font-semibold text-sm">$</span>
                                <input type="number" wire:model="priceMonthly" step="0.01" min="0"
                                       class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                       placeholder="0.00">
                            </div>
                            @error('priceMonthly') <span class="text-red-600 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Yearly Price (USD)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 font-semibold text-sm">$</span>
                                <input type="number" wire:model="priceYearly" step="0.01" min="0"
                                       class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                       placeholder="0.00 (optional)">
                            </div>
                        </div>
                    </div>

                    {{-- Status & Sort Order --}}
                    <div class="grid gap-4 md:grid-cols-2 mt-4">
                        <div class="flex items-center p-2 border border-gray-200 rounded-lg">
                            <input type="checkbox" wire:model="isActive" id="isActive"
                                   class="w-4 h-4 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                            <label for="isActive" class="ml-2 text-sm font-medium text-gray-700">
                                Active (visible to users)
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                            <input type="number" wire:model="sortOrder" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                   placeholder="0">
                            @error('sortOrder') <span class="text-red-600 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Features Section --}}
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Features</h3>

                    {{-- Boolean Features --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wide">Enabled Features</h4>
                        <div class="grid gap-2 md:grid-cols-2">
                            @foreach($booleanFeatures as $feature)
                                <label class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" wire:model="features.{{ $feature->value }}"
                                           class="w-4 h-4 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $feature->value)) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Quota Features --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wide">Quota Limits</h4>
                        <div class="space-y-2">
                            @foreach($quotaFeatures as $feature)
                                <div class="grid gap-2 md:grid-cols-3 items-end p-2 border border-gray-200 rounded">
                                    <div class="md:col-span-2">
                                        <label class="text-sm font-semibold text-gray-700">
                                            {{ ucwords(str_replace('_', ' ', $feature->value)) }}
                                        </label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="number" wire:model="features.{{ $feature->value }}" min="0"
                                               class="flex-1 px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                               placeholder="0">
                                        <span class="text-xs text-gray-500 whitespace-nowrap">/period</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.plans.list') }}"
                       class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded text-center hover:bg-gray-50 transition-colors text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold rounded transition-colors flex items-center justify-center gap-2 text-sm">
                        <i class="bx text-sm" :class="$wire.loading ? 'bx-loader-alt animate-spin' : 'bx-save'"></i>
                        <span wire:loading.remove>
                            {{ $isCreating ? 'Create Plan' : 'Save Changes' }}
                        </span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
