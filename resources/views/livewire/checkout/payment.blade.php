<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
                Complete Your Payment
            </h1>
            <p class="text-gray-500">Send crypto to the address below</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-8 mb-8">

            {{-- Order Summary --}}
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Plan:</span>
                        <span class="font-semibold text-gray-900">{{ $payment->plan->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Billing Period:</span>
                        <span class="font-semibold text-gray-900 capitalize">{{ $payment->billing_period }}</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-gray-200">
                        <span class="text-gray-900 font-semibold">Total Due:</span>
                        <span class="text-2xl font-bold text-indigo-600">
                            ${{ number_format($payment->amount / 100, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- NOWPayments Widget --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Send Cryptocurrency Payment</h3>

                {{-- Open Payment Page Button --}}
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-8 text-center mb-6">
                    <div class="mb-6">
                        <i class="bx bx-wallet text-5xl text-indigo-600 mx-auto mb-4"></i>
                        <h4 class="text-lg font-bold text-indigo-900 mb-2">Ready to Pay?</h4>
                        <p class="text-indigo-700 text-sm mb-4">
                            Click the button below to open the payment page where you can select your preferred cryptocurrency and complete the payment.
                        </p>
                    </div>

                    <a href="{{ $payment->payment_url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors text-lg">
                        <i class="bx bx-link-external text-xl"></i>
                        Open Payment Page
                    </a>

                    <p class="text-xs text-indigo-600 mt-4">
                        Opens NOWPayments payment page in a new tab
                    </p>
                </div>

                {{-- QR Code (fallback) --}}
                @if ($payment->payment_url)
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <p class="text-center text-sm text-gray-600 mb-4 font-semibold">Or scan the QR code:</p>
                        <div class="text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($payment->payment_url) }}"
                                 alt="Payment QR Code"
                                 class="mx-auto border border-gray-200 rounded">
                        </div>
                        <p class="text-center text-xs text-gray-500 mt-3">
                            Scan with your mobile device to open the payment page
                        </p>
                    </div>
                @endif

                {{-- Instructions --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-2">How It Works:</h4>
                    <ol class="text-sm text-blue-800 space-y-2 list-decimal list-inside">
                        <li>Click "Open Payment Page" above</li>
                        <li>Select your preferred cryptocurrency (Bitcoin, Ethereum, USDT, etc.)</li>
                        <li>You'll get the wallet address and amount to send</li>
                        <li>Send the payment from your crypto wallet</li>
                        <li>Wait for blockchain confirmation (usually 5-30 minutes)</li>
                        <li>Your subscription will activate automatically once confirmed</li>
                    </ol>
                </div>

                {{-- Status --}}
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-sm text-amber-800 mb-2">
                        <strong>Status:</strong>
                        <span class="inline-block px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-semibold ml-2">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </p>
                    <p class="text-xs text-amber-700">
                        <strong>Invoice expires:</strong> {{ $payment->expires_at->format('M d, Y h:i A') }}
                    </p>
                    @if($payment->status === 'pending')
                        <p class="text-xs text-amber-700 mt-2">
                            💡 Tip: Your subscription will activate automatically once payment is confirmed on the blockchain (usually 5-30 minutes).
                        </p>
                    @endif
                </div>
            </div>

            {{-- Help Links --}}
            <div class="border-t border-gray-200 pt-6">
                <p class="text-sm text-gray-600">
                    <strong>Need help?</strong>
                    <a href="https://nowpayments.io/faq" target="_blank" class="text-indigo-600 hover:underline">
                        View NOWPayments FAQ
                    </a>
                    or
                    <a href="#" class="text-indigo-600 hover:underline">Contact Support</a>
                </p>
            </div>

        </div>

        {{-- Check Status & Actions --}}
        <div class="space-y-4">
            {{-- Check Status Button --}}
            @if($payment->status === 'pending')
                <div>
                    <button wire:click="checkPaymentStatus"
                            wire:loading.attr="disabled"
                            class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition-colors">
                        <i class="bx" :class="$wire.loading ? 'bx-loader-alt animate-spin' : 'bx-check-circle'"></i>
                        <span wire:loading.remove>Check Payment Status</span>
                        <span wire:loading>Checking...</span>
                    </button>
                    <p class="text-xs text-gray-500 mt-2 text-center">
                        Click after sending payment to verify it's been received
                    </p>
                </div>
            @else
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-center">
                    <i class="bx bx-check-circle text-4xl text-emerald-600 mb-2 block"></i>
                    <p class="text-emerald-800 font-semibold">Payment Confirmed!</p>
                    <p class="text-sm text-emerald-700 mt-1">Your subscription is now active.</p>
                </div>
            @endif

            {{-- Navigation --}}
            <div class="flex gap-3">
                <a href="{{ route('home') }}"
                   class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 text-center transition-colors">
                    Back Home
                </a>
                @if($payment->status !== 'pending')
                    <a href="{{ route('dashboard.billing') }}"
                       class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-center transition-colors">
                        View Billing
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
