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

                {{-- Check if payment data is loaded --}}
                @if (!$payment->payment_address || !$payment->crypto_amount)
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
                        <i class="bx bx-loader-alt text-4xl text-amber-600 animate-spin mx-auto mb-3"></i>
                        <p class="text-amber-800 font-semibold mb-2">Loading Payment Details...</p>
                        <p class="text-sm text-amber-700">Please wait while we generate your payment invoice.</p>
                        <button wire:click="$refresh" class="mt-3 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded">
                            Retry
                        </button>
                    </div>
                @else
                    {{-- Payment Address & QR Code --}}
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        {{-- QR Code --}}
                        @if ($payment->payment_url)
                            <div class="text-center mb-6">
                                <p class="text-sm text-gray-600 mb-3">Scan to pay:</p>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($payment->payment_url) }}"
                                     alt="Payment QR Code"
                                     class="mx-auto border border-gray-200 rounded">
                            </div>
                        @endif

                        {{-- Payment Address --}}
                        <div class="mb-6">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Wallet Address:</p>
                            <div class="flex items-center gap-2">
                                <input type="text"
                                       value="{{ $payment->payment_address }}"
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm"
                                       readonly>
                                <button onclick="navigator.clipboard.writeText('{{ $payment->payment_address }}')"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                                    <i class="bx bx-copy text-lg"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Amount to Send --}}
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2">Amount to Send:</p>
                            <div class="bg-white border border-gray-300 rounded-lg p-4">
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $payment->crypto_amount }} {{ $payment->crypto_currency ?? 'BTC' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    (USD ${{ number_format($payment->amount / 100, 2) }})
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Instructions --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-2">Payment Instructions:</h4>
                    <ol class="text-sm text-blue-800 space-y-2 list-decimal list-inside">
                        <li>Copy the wallet address above</li>
                        <li>Open your crypto wallet (Metamask, Trust Wallet, Coinbase, etc.)</li>
                        <li>Send the exact amount shown above</li>
                        <li>Confirm the transaction</li>
                        <li>Wait for blockchain confirmation (usually 5-30 minutes)</li>
                        <li>Your subscription will activate automatically</li>
                    </ol>
                </div>

                {{-- Status --}}
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-sm text-amber-800">
                        <strong>Status:</strong>
                        <span class="inline-block px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-semibold ml-2">
                            Waiting for Payment
                        </span>
                    </p>
                    <p class="text-xs text-amber-700 mt-2">
                        Invoice expires: {{ $payment->expires_at->format('M d, Y h:i A') }}
                    </p>
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

        {{-- Check Status Button --}}
        <div class="text-center">
            <button wire:click="checkPaymentStatus"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                ✓ I've Sent the Payment, Check Status
            </button>
            <p class="text-sm text-gray-500 mt-3">
                <a href="{{ route('home') }}" class="text-indigo-600 hover:underline">Go back</a>
            </p>
        </div>

    </div>
</div>
