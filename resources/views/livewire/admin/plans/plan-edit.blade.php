<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $isCreating ? 'Create Plan' : 'Edit Plan' }}
            </h1>
            <p class="text-gray-500 mt-2">
                {{ $isCreating ? 'Create a new subscription plan' : 'Update plan details and features' }}
            </p>
        </div>
        <a href="{{ route('admin.plans.list') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
            ← Back to Plans
        </a>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="bg-white rounded-lg border border-gray-200 p-8 space-y-8">

        {{-- Plan Details Section --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Plan Details</h2>

            <div class="grid gap-6 md:grid-cols-2">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Plan Name</label>
                    <input type="text" wire:model="name" wire:change="updatedName"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="e.g., Professional">
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Slug (URL)</label>
                    <input type="text" wire:model="slug"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-sm"
                           placeholder="e.g., professional">
                    @error('slug') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea wire:model="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Brief description of this plan..."></textarea>
            </div>

            {{-- Pricing --}}
            <div class="grid gap-6 md:grid-cols-2 mt-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Monthly Price (USD)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500 font-semibold">$</span>
                        <input type="number" wire:model="priceMonthly" step="0.01" min="0"
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="0.00">
                    </div>
                    @error('priceMonthly') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Stored as cents in database</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Yearly Price (USD)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500 font-semibold">$</span>
                        <input type="number" wire:model="priceYearly" step="0.01" min="0"
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="0.00 (optional)">
                    </div>
                </div>
            </div>

            {{-- Status & Sort Order --}}
            <div class="grid gap-6 md:grid-cols-2 mt-6">
                <div class="flex items-center">
                    <input type="checkbox" wire:model="isActive" id="isActive"
                           class="w-4 h-4 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                    <label for="isActive" class="ml-2 text-sm font-medium text-gray-700">
                        Active (visible to users)
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                    <input type="number" wire:model="sortOrder" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="0">
                    @error('sortOrder') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Features Section --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Features</h2>

            {{-- Boolean Features --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Enabled Features</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach($booleanFeatures as $feature)
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <input type="checkbox" wire:model="features.{{ $feature->value }}"
                                   id="{{ $feature->value }}"
                                   class="w-4 h-4 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                            <label for="{{ $feature->value }}" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                                {{ ucwords(str_replace('_', ' ', $feature->value)) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Quota Features --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quota Limits</h3>
                <div class="space-y-4">
                    @foreach($quotaFeatures as $feature)
                        <div class="grid gap-4 md:grid-cols-3 items-end">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ ucwords(str_replace('_', ' ', $feature->value)) }}
                                </label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" wire:model="features.{{ $feature->value }}" min="0"
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="0">
                                <span class="text-sm text-gray-500 whitespace-nowrap">/period</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.plans.list') }}"
               class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 text-center transition-colors">
                Cancel
            </a>
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition-colors">
                <i class="bx" :class="$wire.loading ? 'bx-loader-alt animate-spin' : 'bx-save'"></i>
                <span wire:loading.remove>
                    {{ $isCreating ? 'Create Plan' : 'Save Changes' }}
                </span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>
