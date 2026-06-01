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

    <div class="flex-1 w-full bg-white rounded-lg min-h-[80vh]">
        {{-- ======================== Content Start From Here ======================== --}}

        {{-- Top Controls: Title and Create Button --}}
        <div class="grid grid-cols-2 gap-4 px-4 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Manage Plans</h2>
            </div>
            <div class="flex justify-end items-end">
                <a href="{{ route('admin.plans.create') }}"
                   class="flex items-center gap-2 pb-1 text-gray-700 transition-colors hover:border-gray-400 hover:text-gray-900 cursor-pointer rounded border border-gray-300 px-4 py-2">
                    <i class="bx bx-plus"></i>
                    <span class="text-sm font-medium">Create Plan</span>
                </a>
            </div>
        </div>

        {{-- Plans Grid --}}
        <div class="px-4 pb-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse($plans as $plan)
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 hover:shadow-lg transition-shadow">
                        {{-- Plan Name and Badge --}}
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">{{ $plan->name }}</h3>
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $plan->tier()->badgeClass() }}">
                                    {{ $plan->tier()->label() }}
                                </span>
                            </div>
                            <button wire:click="toggleActive({{ $plan->id }})"
                                    class="px-2 py-1 text-xs font-semibold rounded transition-colors {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </div>

                        {{-- Pricing --}}
                        <div class="mb-3 pb-3 border-b border-gray-200">
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-gray-900">{{ $plan->priceMonthlyFormatted() }}</span>
                                <span class="text-xs text-gray-500">/month</span>
                            </div>
                            @if($plan->price_yearly)
                                <div class="text-xs text-gray-600 mt-0.5">
                                    <span class="font-semibold">${{ number_format($plan->price_yearly / 100, 2) }}</span> /year
                                </div>
                            @endif
                        </div>

                        {{-- Stats --}}
                        <div class="mb-4 space-y-1 text-xs">
                            <div class="flex justify-between text-gray-600">
                                <span>Subscribers:</span>
                                <span class="font-semibold text-gray-900">{{ $plan->subscriptions_count }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Features:</span>
                                <span class="font-semibold text-gray-900">{{ $plan->features_count ?? $plan->features()->count() }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-1 mb-2">
                            <a href="{{ route('admin.plans.edit', $plan) }}"
                               class="flex-1 px-2 py-1 text-xs font-semibold bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded transition-colors text-center">
                                Edit
                            </a>
                            @if($confirmDeleteId === $plan->id)
                                <button wire:click="delete({{ $plan->id }})"
                                        class="flex-1 px-2 py-1 text-xs font-semibold bg-red-600 hover:bg-red-700 text-white rounded transition-colors">
                                    Confirm
                                </button>
                                <button wire:click="$set('confirmDeleteId', null)"
                                        class="flex-1 px-2 py-1 text-xs font-semibold bg-gray-300 hover:bg-gray-400 text-gray-800 rounded transition-colors">
                                    Cancel
                                </button>
                            @else
                                <button wire:click="confirmDelete({{ $plan->id }})"
                                        :disabled="$plan->subscriptions_count > 0"
                                        class="flex-1 px-2 py-1 text-xs font-semibold rounded transition-colors"
                                        :class="$plan->subscriptions_count > 0 ? 'bg-red-100 text-red-700 opacity-50 cursor-not-allowed' : 'bg-red-100 text-red-700 hover:bg-red-200'"
                                        :title="$plan->subscriptions_count > 0 ? 'Cannot delete plan with subscribers' : ''">
                                    Delete
                                </button>
                            @endif
                        </div>

                        {{-- Reorder Buttons --}}
                        @if(count($plans) > 1)
                            <div class="flex gap-1 pt-2 border-t border-gray-200">
                                @if($plan->sort_order > 1)
                                    <button wire:click="moveUp({{ $plan->id }})"
                                            class="flex-1 px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors">
                                        <i class="bx bx-up-arrow text-xs"></i> Up
                                    </button>
                                @endif
                                @if($plan->sort_order < count($plans) - 1)
                                    <button wire:click="moveDown({{ $plan->id }})"
                                            class="flex-1 px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors">
                                        <i class="bx bx-down-arrow text-xs"></i> Down
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-8 text-center">
                        <i class="bx bx-inbox text-3xl text-gray-400 mb-2 block"></i>
                        <p class="text-gray-600 font-semibold mb-2">No subscription plans found</p>
                        <a href="{{ route('admin.plans.create') }}"
                           class="inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded transition-colors">
                            Create First Plan
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
