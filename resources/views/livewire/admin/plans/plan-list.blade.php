<div class="space-y-6">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Subscription Plans</h1>
            <p class="text-gray-500 mt-2">Manage and customize your subscription plans</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
            <i class="bx bx-plus"></i> Create Plan
        </a>
    </div>

    {{-- Plans Grid --}}
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($plans as $plan)
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                        <span class="inline-block mt-1 px-3 py-1 text-xs font-semibold rounded-full {{ $plan->tier()->badgeClass() }}">
                            {{ $plan->tier()->label() }}
                        </span>
                    </div>
                    <button wire:click="toggleActive({{ $plan->id }})"
                            class="px-3 py-1 text-xs font-semibold rounded {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </div>

                {{-- Pricing --}}
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-gray-900">{{ $plan->priceMonthlyFormatted() }}</span>
                        <span class="text-sm text-gray-500">/month</span>
                    </div>
                    @if($plan->price_yearly)
                        <div class="text-sm text-gray-600 mt-1">
                            <span class="font-semibold">${{ number_format($plan->price_yearly / 100, 2) }}</span> /year
                        </div>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="mb-6 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Active Subscribers:</span>
                        <span class="font-semibold text-gray-900">{{ $plan->subscriptions_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Features:</span>
                        <span class="font-semibold text-gray-900">{{ $plan->features_count ?? $plan->features()->count() }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <a href="{{ route('admin.plans.edit', $plan) }}"
                       class="flex-1 px-3 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold text-sm rounded-lg transition-colors text-center">
                        <i class="bx bx-edit"></i> Edit
                    </a>

                    @if($confirmDeleteId === $plan->id)
                        <button wire:click="delete({{ $plan->id }})"
                                class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg transition-colors">
                            <i class="bx bx-check"></i> Confirm
                        </button>
                        <button wire:click="$set('confirmDeleteId', null)"
                                class="flex-1 px-3 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold text-sm rounded-lg transition-colors">
                            Cancel
                        </button>
                    @else
                        <button wire:click="confirmDelete({{ $plan->id }})"
                                :disabled="$plan->subscriptions_count > 0"
                                :class="{
                                    'opacity-50 cursor-not-allowed': $plan->subscriptions_count > 0,
                                    'hover:bg-red-200': $plan->subscriptions_count === 0
                                }"
                                class="flex-1 px-3 py-2 bg-red-100 text-red-700 font-semibold text-sm rounded-lg transition-colors"
                                :title="$plan->subscriptions_count > 0 ? 'Cannot delete plan with subscribers' : ''">
                            <i class="bx bx-trash"></i>
                        </button>
                    @endif
                </div>

                {{-- Reorder (if not only plan) --}}
                @if(count($plans) > 1)
                    <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2 justify-center">
                        @if($plan->sort_order > 1)
                            <button wire:click="moveUp({{ $plan->id }})"
                                    class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors">
                                <i class="bx bx-up-arrow"></i>
                            </button>
                        @endif
                        @if($plan->sort_order < count($plans) - 1)
                            <button wire:click="moveDown({{ $plan->id }})"
                                    class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors">
                                <i class="bx bx-down-arrow"></i>
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-gray-50 rounded-lg border border-gray-200 p-12 text-center">
                <i class="bx bx-inbox text-5xl text-gray-400 mb-4 block"></i>
                <p class="text-gray-600 text-lg mb-4">No plans found</p>
                <a href="{{ route('admin.plans.create') }}" class="inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                    Create First Plan
                </a>
            </div>
        @endforelse
    </div>
</div>
