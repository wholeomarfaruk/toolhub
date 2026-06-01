<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium flex items-center gap-2">
                <i class="bx bx-left-arrow-alt"></i> Back
            </a>
        </div>

        {{-- Checkout Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
                Upgrade to {{ $plan->name }}
            </h1>
            <p class="text-gray-500">Select your billing period and complete checkout</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Checkout Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200 p-8">

                    {{-- Display Errors --}}
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-red-900 mb-2">Checkout Error</h4>
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit="processCheckout" class="space-y-8">

                        {{-- Billing Period Selection --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-4">Billing Period</label>

                            <div class="space-y-3">
                                {{-- Monthly --}}
                                <label class="relative flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                                    <input type="radio" wire:model.live="billingPeriod" value="monthly" checked
                                           class="mt-1 w-4 h-4 text-indigo-600 cursor-pointer">
                                    <div class="ml-4 flex-grow">
                                        <p class="font-semibold text-gray-900">Monthly Billing</p>
                                        <p class="text-sm text-gray-500">Renews every month</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-gray-900">
                                            ${{ number_format($plan->price_monthly / 100, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500">/month</p>
                                    </div>
                                </label>

                                {{-- Yearly --}}
                                <label class="relative flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                                    <input type="radio" wire:model.live="billingPeriod" value="yearly"
                                           class="mt-1 w-4 h-4 text-indigo-600 cursor-pointer">
                                    <div class="ml-4 flex-grow">
                                        <div class="flex items-center gap-2">
                                            <p class="font-semibold text-gray-900">Yearly Billing</p>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                                Save 17%
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500">Renews every year (best value)</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-gray-900">
                                            ${{ number_format($plan->price_yearly / 100, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500">/year</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-4">Payment Method</label>

                            <div class="space-y-3">
                                {{-- NOWPayments Crypto --}}
                                <label class="relative flex items-start p-4 border-2 border-indigo-500 bg-indigo-50 rounded-lg cursor-pointer hover:border-indigo-600 transition-colors">
                                    <input type="radio" wire:model="paymentMethod" value="nowpayments" checked
                                           class="mt-1 w-4 h-4 text-indigo-600 cursor-pointer">
                                    <div class="ml-4 flex-grow">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="font-semibold text-gray-900">NOWPayments - Cryptocurrency</p>
                                            <span class="inline-flex items-center px-2 py-0.5 bg-indigo-600 text-white text-xs font-bold rounded">
                                                ACTIVE
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Bitcoin, Ethereum, USDT, and 200+ cryptocurrencies
                                        </p>
                                    </div>
                                </label>

                                {{-- SSL Commerz Future --}}
                                <div class="p-4 border border-gray-200 rounded-lg opacity-60">
                                    <p class="font-semibold text-gray-600">SSL Commerz</p>
                                    <p class="text-sm text-gray-500">Card, bKash, Nagad (Coming Soon)</p>
                                </div>

                                {{-- Bank Transfer Future --}}
                                <div class="p-4 border border-gray-200 rounded-lg opacity-60">
                                    <p class="font-semibold text-gray-600">Direct Bank Transfer</p>
                                    <p class="text-sm text-gray-500">International wire transfer (Coming Soon)</p>
                                </div>
                            </div>
                        </div>

                        {{-- Billing Info --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-800">
                                <strong>Next billing date:</strong> {{ now()->addMonth()->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-blue-800 mt-1">
                                You can cancel or downgrade your plan anytime from your billing dashboard.
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-4">
                            <a href="{{ route('home') }}"
                               class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors text-center">
                                Cancel
                            </a>
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="bx" :class="$wire.loading ? 'bx-loader-alt animate-spin' : 'bx-lock'"></i>
                                <span wire:loading.remove>Continue to Payment</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div>
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-8">

                    <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>

                    {{-- Plan Details --}}
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3">{{ $plan->name }} Plan</h4>

                        <ul class="space-y-2 text-sm text-gray-600">
                            @if($plan->slug === 'free')
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>5 PDF exports/month</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>3 invoices/day</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>2 templates</span>
                                </li>
                            @elseif($plan->slug === 'pro')
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>250 PDF exports/month</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>20 invoices/day</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>All 3 templates</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>No watermark</span>
                                </li>
                            @else
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>Unlimited everything</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>API access</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>Team access</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bx bx-check text-emerald-500 mt-0.5 shrink-0"></i>
                                    <span>24/7 support</span>
                                </li>
                            @endif
                        </ul>
                    </div>

                    {{-- Pricing Breakdown --}}
                    <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $plan->name }} Plan</span>
                            <span class="font-semibold text-gray-900">
                                @if($billingPeriod === 'monthly')
                                    ${{ number_format($plan->price_monthly / 100, 2) }}
                                @else
                                    ${{ number_format($plan->price_yearly / 100, 2) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal</span>
                            <span>
                                @if($billingPeriod === 'monthly')
                                    ${{ number_format($plan->price_monthly / 100, 2) }}
                                @else
                                    ${{ number_format($plan->price_yearly / 100, 2) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Total Due</span>
                        <span class="text-2xl font-bold text-indigo-600">
                            @if($billingPeriod === 'monthly')
                                ${{ number_format($plan->price_monthly / 100, 2) }}
                            @else
                                ${{ number_format($plan->price_yearly / 100, 2) }}
                            @endif
                        </span>
                    </div>

                    {{-- Billing Period --}}
                    <p class="text-xs text-gray-500 mt-4 text-center">
                        @if($billingPeriod === 'monthly')
                            Billed monthly
                        @else
                            Billed yearly
                        @endif
                    </p>

                </div>
            </div>

        </div>

    </div>
</div>
