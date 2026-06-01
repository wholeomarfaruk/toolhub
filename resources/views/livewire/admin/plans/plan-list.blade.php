{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: 'Subscription Plans', slug: 'plans' }">
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

    <div class="flex-1 w-full bg-white rounded-lg min-h-[80vh] p-6">
        {{-- ======================== Content Start From Here ======================== --}}

        {{-- Header with Title and Create Button --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manage Plans</h2>
                <p class="text-gray-500 mt-1">Create, edit, and customize your subscription plans</p>
            </div>
            <a href="{{ route('admin.plans.create') }}"
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
                <i class="bx bx-plus"></i> Create Plan
            </a>
        </div>

        {{-- Plans Grid --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($plans as $plan)
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    {{-- Header with Badge --}}
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                            <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full {{ $plan->tier()->badgeClass() }}">
                                {{ $plan->tier()->label() }}
                            </span>
                        </div>
                        <button wire:click="toggleActive({{ $plan->id }})"
                                class="px-3 py-1 text-xs font-semibold rounded transition-colors {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </div>

                    {{-- Pricing --}}
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-2xl font-bold text-gray-900">{{ $plan->priceMonthlyFormatted() }}</span>
                            <span class="text-sm text-gray-500">/month</span>
                        </div>
                        @if($plan->price_yearly)
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold">${{ number_format($plan->price_yearly / 100, 2) }}</span> /year
                            </div>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="mb-6 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Active Subscribers:</span>
                            <span class="font-semibold text-gray-900">{{ $plan->subscriptions_count }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Features:</span>
                            <span class="font-semibold text-gray-900">{{ $plan->features_count ?? $plan->features()->count() }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 mb-3">
                        <a href="{{ route('admin.plans.edit', $plan) }}"
                           class="flex-1 px-3 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold text-sm rounded transition-colors text-center">
                            <i class="bx bx-edit-alt"></i> Edit
                        </a>
                        @if($confirmDeleteId === $plan->id)
                            <button wire:click="delete({{ $plan->id }})"
                                    class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded transition-colors">
                                <i class="bx bx-check"></i> Confirm
                            </button>
                            <button wire:click="$set('confirmDeleteId', null)"
                                    class="flex-1 px-3 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold text-sm rounded transition-colors">
                                Cancel
                            </button>
                        @else
                            <button wire:click="confirmDelete({{ $plan->id }})"
                                    :disabled="$plan->subscriptions_count > 0"
                                    class="flex-1 px-3 py-2 rounded transition-colors font-semibold text-sm"
                                    :class="$plan->subscriptions_count > 0 ? 'bg-red-100 text-red-700 opacity-50 cursor-not-allowed' : 'bg-red-100 text-red-700 hover:bg-red-200'"
                                    :title="$plan->subscriptions_count > 0 ? 'Cannot delete plan with subscribers' : ''">
                                <i class="bx bx-trash"></i>
                            </button>
                        @endif
                    </div>

                    {{-- Reorder Buttons --}}
                    @if(count($plans) > 1)
                        <div class="flex gap-2 justify-center pt-3 border-t border-gray-200">
                            @if($plan->sort_order > 1)
                                <button wire:click="moveUp({{ $plan->id }})"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors">
                                    <i class="bx bx-up-arrow"></i> Up
                                </button>
                            @endif
                            @if($plan->sort_order < count($plans) - 1)
                                <button wire:click="moveDown({{ $plan->id }})"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors">
                                    <i class="bx bx-down-arrow"></i> Down
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                    <i class="bx bx-inbox text-5xl text-gray-400 mb-4 block"></i>
                    <p class="text-gray-600 text-lg mb-4 font-semibold">No subscription plans found</p>
                    <p class="text-gray-500 mb-6">Start by creating your first subscription plan</p>
                    <a href="{{ route('admin.plans.create') }}"
                       class="inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                        Create First Plan
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
