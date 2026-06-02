<div>
    @php
        // SEO Configuration for EMI Calculator
        if (empty($this->title)) {
            $this->title = 'EMI Calculator | Loan Payment & Repayment Schedule';
            $this->description = 'Free online EMI calculator. Calculate monthly loan payments, total interest, and get detailed amortization schedule instantly.';
            $this->keywords = 'EMI calculator, loan calculator, monthly EMI, interest calculator, loan repayment, amortization schedule';
            $this->og_title = 'EMI Calculator | Free Loan Payment Calculator';
            $this->og_description = 'Calculate your monthly EMI, total interest, and repayment schedule instantly.';
            $this->og_url = route('tools.emi-calculator');
            $this->og_image = asset('images/og-emi-calculator.jpg');
            $this->canonical_url = route('tools.emi-calculator');
        }
    @endphp

    {{-- Auth Modal Component --}}
    <livewire:components.auth-modal :is-open="$showAuthModal" :tool-name="$authModalToolName" />

    {{-- Hero --}}
    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white py-12">
        <div class="max-w-4xl mx-auto px-4">
            {{-- Breadcrumb with Schema --}}
            <x-breadcrumb :items="[
                ['name' => 'Home', 'url' => route('home'), 'icon' => 'bx bx-home-alt'],
                ['name' => 'Tools', 'url' => route('tools.index')],
                ['name' => 'EMI Calculator', 'url' => null],
            ]" :schema="true" />
            <h1 class="text-3xl font-bold mb-2">EMI Calculator</h1>
            <p class="text-indigo-200">
                Calculate your monthly loan installment, total interest, and full repayment breakdown instantly.
            </p>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="max-w-4xl mx-auto px-4 py-10">

        {{-- Rate limit error --}}
        @if ($errors->has('limit'))
            <div class="mb-6 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
                <i class="bx bx-error-circle text-lg text-amber-500"></i>
                {{ $errors->first('limit') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ── Input Card ───────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-5">Loan Details</h2>

                <div class="space-y-5">

                    {{-- Principal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Loan Amount
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm font-medium pointer-events-none">$</span>
                            <input
                                wire:model="principal"
                                type="number"
                                min="1"
                                placeholder="e.g. 500000"
                                class="w-full pl-7 pr-4 py-2.5 border {{ $errors->has('principal') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>
                        @error('principal')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Interest Rate --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Annual Interest Rate
                        </label>
                        <div class="relative">
                            <input
                                wire:model="annual_rate"
                                type="number"
                                step="0.01"
                                min="0.01"
                                max="100"
                                placeholder="e.g. 8.5"
                                class="w-full pr-8 pl-4 py-2.5 border {{ $errors->has('annual_rate') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 text-sm pointer-events-none">%</span>
                        </div>
                        @error('annual_rate')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tenure --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Loan Tenure
                        </label>
                        <div class="relative">
                            <input
                                wire:model="tenure_months"
                                type="number"
                                min="1"
                                max="360"
                                placeholder="e.g. 60"
                                class="w-full pr-16 pl-4 py-2.5 border {{ $errors->has('tenure_months') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 text-sm pointer-events-none">months</span>
                        </div>
                        @error('tenure_months')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button
                        wire:click="calculate"
                        wire:loading.attr="disabled"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="calculate">
                            <i class="bx bx-calculator mr-1"></i> Calculate EMI
                        </span>
                        <span wire:loading wire:target="calculate" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Calculating…
                        </span>
                    </button>

                </div>
            </div>

            {{-- ── Results Card ─────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-5">Results</h2>

                @if ($result)
                    {{-- EMI highlight --}}
                    <div class="bg-indigo-50 rounded-xl p-4 mb-5 text-center">
                        <p class="text-xs font-medium text-indigo-500 uppercase tracking-wide mb-1">Monthly EMI</p>
                        <p class="text-4xl font-bold text-indigo-700">
                            ${{ number_format($result['emi'], 2) }}
                        </p>
                    </div>

                    {{-- Breakdown rows --}}
                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Principal Amount</dt>
                            <dd class="font-semibold text-gray-900">${{ number_format($result['principal'], 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Total Interest</dt>
                            <dd class="font-semibold text-red-600">${{ number_format($result['total_interest'], 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <dt class="text-gray-500">Total Amount Payable</dt>
                            <dd class="font-bold text-gray-900">${{ number_format($result['total_payment'], 2) }}</dd>
                        </div>
                    </dl>

                    {{-- Visual bar --}}
                    <div class="mt-5">
                        <div class="flex rounded-full overflow-hidden h-2.5">
                            <div class="bg-indigo-500 transition-all duration-500"
                                 style="width: {{ $result['principal_pct'] }}%"></div>
                            <div class="bg-red-400 transition-all duration-500"
                                 style="width: {{ $result['interest_pct'] }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                                Principal {{ $result['principal_pct'] }}%
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-red-400"></span>
                                Interest {{ $result['interest_pct'] }}%
                            </span>
                        </div>
                    </div>

                @else
                    {{-- Empty state --}}
                    <div class="flex flex-col items-center justify-center h-48 text-gray-300">
                        <i class="bx bx-bar-chart-alt-2 text-5xl mb-3"></i>
                        <p class="text-sm text-gray-400">Enter loan details and click Calculate</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- ── Amortization Schedule ─────────────────────────────── --}}
        @if ($result && count($result['schedule']) > 0)
            <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Repayment Schedule</h2>
                    <span class="text-xs text-gray-400">
                        Showing first {{ count($result['schedule']) }}
                        of {{ $tenure_months }} months
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                                <th class="px-6 py-3 text-left font-medium">Month</th>
                                <th class="px-6 py-3 text-right font-medium">EMI</th>
                                <th class="px-6 py-3 text-right font-medium">Principal</th>
                                <th class="px-6 py-3 text-right font-medium">Interest</th>
                                <th class="px-6 py-3 text-right font-medium">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($result['schedule'] as $row)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-gray-500">{{ $row['month'] }}</td>
                                    <td class="px-6 py-3 text-right font-medium text-gray-900">${{ number_format($row['emi'], 2) }}</td>
                                    <td class="px-6 py-3 text-right text-indigo-600">${{ number_format($row['principal'], 2) }}</td>
                                    <td class="px-6 py-3 text-right text-red-500">${{ number_format($row['interest'], 2) }}</td>
                                    <td class="px-6 py-3 text-right text-gray-700">${{ number_format($row['balance'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
