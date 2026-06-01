<div>
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Billing & Subscription</h1>
        <p class="text-gray-500 mt-2">Manage your plan, view invoices, and billing history</p>
    </div>

    {{-- Current Plan Card --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

        {{-- Left: Current Plan Details --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 p-8">

                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $currentPlan->name }} Plan</h2>
                        <p class="text-gray-500 mt-1">
                            @if($subscription && $subscription->status === 'active')
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                    Active since {{ $subscription->created_at->format('M d, Y') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                    No active subscription
                                </span>
                            @endif
                        </p>
                    </div>

                    {{-- Plan Badge --}}
                    @if($currentPlan->slug === 'free')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-700 rounded-full">
                            <i class="bx bx-star text-sm"></i>
                            Free Plan
                        </span>
                    @elseif($currentPlan->slug === 'pro')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 border border-indigo-200 text-xs font-semibold text-indigo-700 rounded-full">
                            <i class="bx bx-crown text-sm"></i>
                            Pro
                        </span>
                    @elseif($currentPlan->slug === 'enterprise')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 border border-purple-200 text-xs font-semibold text-purple-700 rounded-full">
                            <i class="bx bx-zap text-sm"></i>
                            Enterprise
                        </span>
                    @endif
                </div>

                {{-- Plan Features --}}
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4">What's Included</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($currentPlan->slug === 'free')
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">5 PDF exports</p>
                                    <p class="text-xs text-gray-500">/month</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">3 invoices/day</p>
                                    <p class="text-xs text-gray-500">Daily limit</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">2 templates</p>
                                    <p class="text-xs text-gray-500">Basic + Modern</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Free watermark</p>
                                    <p class="text-xs text-gray-500">On PDFs</p>
                                </div>
                            </div>
                        @elseif($currentPlan->slug === 'pro')
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">250 PDF exports</p>
                                    <p class="text-xs text-gray-500">/month</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">20 invoices/day</p>
                                    <p class="text-xs text-gray-500">Daily limit</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">All 3 templates</p>
                                    <p class="text-xs text-gray-500">Plus Corporate</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">No watermark</p>
                                    <p class="text-xs text-gray-500">Clean PDFs</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Priority support</p>
                                    <p class="text-xs text-gray-500">Email & chat</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Unlimited everything</p>
                                    <p class="text-xs text-gray-500">All features</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">API access</p>
                                    <p class="text-xs text-gray-500">Full API</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Team access</p>
                                    <p class="text-xs text-gray-500">Multi-user</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-900">24/7 support</p>
                                    <p class="text-xs text-gray-500">Premium support</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Billing Info --}}
                @if($subscription && $subscription->status === 'active')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">Billing Information</h4>
                        <ul class="space-y-1 text-sm text-blue-800">
                            <li><strong>Current period:</strong> {{ $subscription->current_period_start->format('M d, Y') }} - {{ $subscription->current_period_end->format('M d, Y') }}</li>
                            <li><strong>Next billing:</strong> {{ $subscription->current_period_end->format('M d, Y') }}</li>
                        </ul>
                    </div>
                @endif

            </div>
        </div>

        {{-- Right: Plan Summary --}}
        <div>
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-8">

                <h3 class="font-bold text-gray-900 mb-4">Plan Summary</h3>

                {{-- Price --}}
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <p class="text-gray-500 text-sm">Monthly cost</p>
                    <p class="text-3xl font-bold text-gray-900">
                        ${{ number_format($currentPlan->price_monthly / 100, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">${{ number_format($currentPlan->price_yearly / 100, 2) }}/year</p>
                </div>

                {{-- Next Billing --}}
                @if($subscription && $subscription->status === 'active')
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <p class="text-gray-500 text-sm">Next billing date</p>
                        <p class="font-semibold text-gray-900">{{ $subscription->current_period_end->format('M d, Y') }}</p>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="space-y-3">
                    @if($currentPlan->slug !== 'pro')
                        <button wire:click="upgradePlan('pro')"
                                class="w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors text-sm">
                            Upgrade to Pro
                        </button>
                    @endif

                    @if($currentPlan->slug !== 'enterprise')
                        <button wire:click="upgradePlan('enterprise')"
                                class="w-full px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors text-sm">
                            Upgrade to Enterprise
                        </button>
                    @endif

                    @if($currentPlan->slug !== 'free')
                        <button wire:click="downgradeToPlan('free')"
                                class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors text-sm">
                            Downgrade to Free
                        </button>
                    @endif
                </div>

                {{-- Help --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center">
                        Questions? <a href="#" class="text-indigo-600 hover:underline">Contact support</a>
                    </p>
                </div>

            </div>
        </div>

    </div>

    {{-- Billing History --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-8">

        <h3 class="text-xl font-bold text-gray-900 mb-6">Billing History</h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Date</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Description</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Amount</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($subscription && $subscription->status === 'active')
                        <tr>
                            <td class="py-4 px-4 text-sm text-gray-900">{{ now()->format('M d, Y') }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $currentPlan->name }} Plan Subscription</td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-900">${{ number_format($currentPlan->price_monthly / 100, 2) }}</td>
                            <td class="py-4 px-4 text-sm">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Paid
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm text-indigo-600 hover:underline">
                                <a href="#">Download</a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                                No billing history yet. Start with the Free plan or upgrade to Pro.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>

</div>
