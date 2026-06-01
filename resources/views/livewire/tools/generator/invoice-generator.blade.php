<div>

    {{-- Auth Modal --}}
    <livewire:components.auth-modal :is-open="$showAuthModal" :tool-name="$authModalToolName" />

    {{-- ── Hero ──────────────────────────────────────────────────────────── --}}
    <div class="bg-linear-to-br from-indigo-600 to-purple-700 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-indigo-200 text-sm mb-3">
                <a href="{{ route('tools.index') }}" class="hover:text-white transition-colors">Tools</a>
                <i class="bx bx-chevron-right"></i>
                <span>Invoice Generator</span>
            </div>
            <h1 class="text-3xl font-bold mb-1">Invoice Generator</h1>
            <p class="text-indigo-200 text-sm">Build professional invoices with tax and discount — download as PDF when ready.</p>
        </div>
    </div>

    {{-- ── Body ───────────────────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Daily limit error --}}
        @if ($errors->has('limit'))
            <div class="mb-5 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
                <i class="bx bx-error-circle text-lg text-amber-500 shrink-0"></i>
                {{ $errors->first('limit') }}
            </div>
        @endif

        {{-- ── 2-column grid ──────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">

            {{-- ════════════════════════════════════════════════════════════ --}}
            {{-- LEFT — INPUT FORM                                            --}}
            {{-- ════════════════════════════════════════════════════════════ --}}
            <div class="space-y-4">

                {{-- ── Invoice Details ──────────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bx bx-file text-indigo-500"></i> Invoice Details
                    </h3>
                    <div class="grid grid-cols-2 gap-3">

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Invoice Number</label>
                            <input wire:model="invoice_number" type="text"
                                class="w-full px-3 py-2 border {{ $errors->has('invoice_number') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('invoice_number') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Currency</label>
                            <select wire:model="currency"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                <option value="USD">USD — US Dollar</option>
                                <option value="EUR">EUR — Euro</option>
                                <option value="GBP">GBP — British Pound</option>
                                <option value="AED">AED — UAE Dirham</option>
                                <option value="INR">INR — Indian Rupee</option>
                                <option value="BDT">BDT — Bangladeshi Taka</option>
                                <option value="CAD">CAD — Canadian Dollar</option>
                                <option value="AUD">AUD — Australian Dollar</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Invoice Date</label>
                            <input wire:model="invoice_date" type="date"
                                class="w-full px-3 py-2 border {{ $errors->has('invoice_date') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('invoice_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Due Date</label>
                            <input wire:model="due_date" type="date"
                                class="w-full px-3 py-2 border {{ $errors->has('due_date') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('due_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                {{-- ── From (Sender) ─────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bx bx-building text-indigo-500"></i> From <span class="text-gray-400 font-normal">(your business)</span>
                    </h3>
                    <div class="space-y-3">

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Business Name <span class="text-red-400">*</span></label>
                            <input wire:model="sender.name" type="text" placeholder="Acme Corp"
                                class="w-full px-3 py-2 border {{ $errors->has('sender.name') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('sender.name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                                <input wire:model="sender.email" type="email" placeholder="hello@acme.com"
                                    class="w-full px-3 py-2 border {{ $errors->has('sender.email') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('sender.email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Phone</label>
                                <input wire:model="sender.phone" type="text" placeholder="+1 555 0100"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Address</label>
                            <textarea wire:model="sender.address" rows="2" placeholder="123 Main St, New York, NY 10001"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                        </div>

                    </div>
                </div>

                {{-- ── To (Client) ───────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bx bx-user text-indigo-500"></i> Bill To <span class="text-gray-400 font-normal">(client)</span>
                    </h3>
                    <div class="space-y-3">

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Client Name <span class="text-red-400">*</span></label>
                            <input wire:model="client.name" type="text" placeholder="Jane Doe / Company Ltd"
                                class="w-full px-3 py-2 border {{ $errors->has('client.name') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('client.name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                            <input wire:model="client.email" type="email" placeholder="client@example.com"
                                class="w-full px-3 py-2 border {{ $errors->has('client.email') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('client.email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Address</label>
                            <textarea wire:model="client.address" rows="2" placeholder="456 Client Ave, Los Angeles, CA"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                        </div>

                    </div>
                </div>

                {{-- ── Line Items ─────────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                            <i class="bx bx-list-ul text-indigo-500"></i> Items
                        </h3>
                        <button wire:click="addItem"
                            class="flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 border border-indigo-200 hover:border-indigo-400 px-3 py-1.5 rounded-lg transition-colors">
                            <i class="bx bx-plus text-sm"></i> Add Item
                        </button>
                    </div>

                    {{-- Column header --}}
                    <div class="hidden sm:grid gap-2 mb-2 px-1" style="grid-template-columns: 1fr 64px 96px 80px 32px;">
                        <span class="text-xs font-medium text-gray-400">Description</span>
                        <span class="text-xs font-medium text-gray-400">Qty</span>
                        <span class="text-xs font-medium text-gray-400">Unit Price</span>
                        <span class="text-xs font-medium text-gray-400 text-right">Total</span>
                        <span></span>
                    </div>

                    {{-- Rows --}}
                    <div class="space-y-2">
                        @foreach ($items as $index => $item)
                            @php $lineTotal = ($item['qty'] ?? 0) * ($item['unit_price'] ?? 0); @endphp

                            <div wire:key="item-{{ $index }}"
                                 class="grid gap-2 items-start" style="grid-template-columns: 1fr 64px 96px 80px 32px;">

                                {{-- Description --}}
                                <div>
                                    <input wire:model="items.{{ $index }}.description"
                                        type="text" placeholder="Service or product description"
                                        class="w-full px-2.5 py-2 border {{ $errors->has('items.'.$index.'.description') ? 'border-red-400' : 'border-gray-200' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @error('items.'.$index.'.description')
                                        <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Qty --}}
                                <div>
                                    <input wire:model.lazy="items.{{ $index }}.qty"
                                        type="number" min="0.01" step="0.01" placeholder="1"
                                        class="w-full px-2.5 py-2 border {{ $errors->has('items.'.$index.'.qty') ? 'border-red-400' : 'border-gray-200' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 text-center">
                                    @error('items.'.$index.'.qty')
                                        <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Unit Price --}}
                                <div>
                                    <input wire:model.lazy="items.{{ $index }}.unit_price"
                                        type="number" min="0" step="0.01" placeholder="0.00"
                                        class="w-full px-2.5 py-2 border {{ $errors->has('items.'.$index.'.unit_price') ? 'border-red-400' : 'border-gray-200' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 text-right">
                                    @error('items.'.$index.'.unit_price')
                                        <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Running line total (PHP-computed, updates on blur) --}}
                                <div class="flex items-center justify-end pt-2">
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ number_format($lineTotal, 2) }}
                                    </span>
                                </div>

                                {{-- Delete --}}
                                <div class="flex items-center justify-center pt-1.5">
                                    <button wire:click="removeItem({{ $index }})"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors {{ count($items) <= 1 ? 'opacity-30 cursor-not-allowed' : '' }}"
                                        {{ count($items) <= 1 ? 'disabled' : '' }}>
                                        <i class="bx bx-trash text-base"></i>
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    @error('items')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    {{-- Running subtotal hint --}}
                    @php $runningSubtotal = collect($items)->sum(fn($i) => ($i['qty'] ?? 0) * ($i['unit_price'] ?? 0)); @endphp
                    <div class="mt-3 pt-3 border-t border-gray-50 flex justify-end">
                        <span class="text-xs text-gray-400">Subtotal: </span>
                        <span class="text-xs font-semibold text-gray-700 ml-1">{{ number_format($runningSubtotal, 2) }}</span>
                    </div>

                </div>

                {{-- ── Tax & Discount ─────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bx bx-percent text-indigo-500"></i> Tax & Discount
                    </h3>
                    <div class="grid grid-cols-3 gap-3">

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tax Rate (%)</label>
                            <div class="relative">
                                <input wire:model.lazy="tax_rate" type="number" min="0" max="100" step="0.01" placeholder="0"
                                    class="w-full pl-3 pr-7 py-2 border {{ $errors->has('tax_rate') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">%</span>
                            </div>
                            @error('tax_rate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Discount Type</label>
                            <select wire:model="discount_type"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                <option value="percent">% Percent</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">
                                Discount {{ $discount_type === 'percent' ? '(%)' : '(amount)' }}
                            </label>
                            <input wire:model.lazy="discount" type="number" min="0" step="0.01" placeholder="0"
                                class="w-full px-3 py-2 border {{ $errors->has('discount') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('discount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                {{-- ── Notes & Terms ──────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bx bx-note text-indigo-500"></i> Notes & Terms
                    </h3>
                    <div class="grid grid-cols-1 gap-3">

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Notes</label>
                            <textarea wire:model="notes" rows="2"
                                placeholder="Thank you for your business!"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Payment Terms</label>
                            <textarea wire:model="terms" rows="2"
                                placeholder="Payment is due within 30 days of the invoice date."
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                        </div>

                    </div>
                </div>

                {{-- ── Action Buttons ─────────────────────────────────────── --}}
                <div class="flex items-center gap-3">
                    <button
                        wire:click="generate"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="generate">
                            <i class="bx bx-file-blank mr-1"></i> Generate Invoice
                        </span>
                        <span wire:loading wire:target="generate" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Generating…
                        </span>
                    </button>

                    <button
                        wire:click="resetForm"
                        class="px-5 py-3 border border-gray-200 hover:border-gray-300 text-gray-600 hover:text-gray-900 text-sm font-medium rounded-xl transition-colors">
                        Clear
                    </button>
                </div>

            </div>
            {{-- /LEFT --}}

            {{-- ════════════════════════════════════════════════════════════ --}}
            {{-- RIGHT — INVOICE PREVIEW                                      --}}
            {{-- ════════════════════════════════════════════════════════════ --}}
            <div class="sticky top-20">

                @if (! $result)

                    {{-- Empty state --}}
                    <div class="bg-white rounded-2xl border border-gray-100 flex flex-col items-center justify-center py-20 text-center px-8">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4">
                            <i class="bx bx-receipt text-3xl text-indigo-400"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Invoice preview</h3>
                        <p class="text-sm text-gray-400 max-w-xs">
                            Fill in the form on the left, then click
                            <span class="font-medium text-indigo-600">Generate Invoice</span>
                            to see your invoice here.
                        </p>
                    </div>

                @else

                    {{-- ── Template Indicator ───────────────────────────── --}}
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg px-4 py-2 mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="bx bx-palette text-indigo-600"></i>
                            <span class="text-xs font-semibold text-indigo-700">
                                Template:
                                <span class="capitalize ml-1">
                                    @if ($template === 'basic')
                                        Basic – Simple & Clean
                                    @elseif ($template === 'modern')
                                        Modern – Contemporary
                                    @else
                                        Corporate – Professional
                                    @endif
                                </span>
                            </span>
                        </div>
                        <span class="text-xs text-indigo-600 font-medium">Live Preview</span>
                    </div>

                    {{-- ── Invoice Document ──────────────────────────────── --}}
                    @php $cur = $result['currency']; @endphp

                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

                        {{-- Coloured top bar (changes by template) --}}
                        <div class="h-2 bg-linear-to-r {{ $template === 'basic' ? 'from-indigo-500 to-purple-600' : ($template === 'modern' ? 'from-slate-700 to-slate-800' : 'from-amber-600 to-amber-700') }}"></div>

                        <div class="p-7">

                            {{-- ── Header: Sender ↔ INVOICE label ──────── --}}
                            <div class="flex items-start justify-between mb-8">

                                <div>
                                    <p class="text-lg font-bold text-gray-900">{{ $result['sender']['name'] }}</p>
                                    @if ($result['sender']['address'])
                                        <p class="text-xs text-gray-500 mt-0.5 whitespace-pre-line">{{ $result['sender']['address'] }}</p>
                                    @endif
                                    @if ($result['sender']['email'])
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $result['sender']['email'] }}</p>
                                    @endif
                                    @if ($result['sender']['phone'])
                                        <p class="text-xs text-gray-400">{{ $result['sender']['phone'] }}</p>
                                    @endif
                                </div>

                                <div class="text-right">
                                    <p class="text-2xl font-extrabold text-indigo-600 uppercase tracking-wide">Invoice</p>
                                    <p class="text-xs font-mono text-gray-500 mt-1">#{{ $result['invoice_number'] }}</p>
                                </div>

                            </div>

                            {{-- ── Bill To + Invoice Dates ─────────────── --}}
                            <div class="grid grid-cols-2 gap-6 bg-gray-50 rounded-xl p-4 mb-7">

                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Bill To</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $result['client']['name'] }}</p>
                                    @if ($result['client']['address'])
                                        <p class="text-xs text-gray-500 mt-0.5 whitespace-pre-line">{{ $result['client']['address'] }}</p>
                                    @endif
                                    @if ($result['client']['email'])
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $result['client']['email'] }}</p>
                                    @endif
                                </div>

                                <div class="space-y-1.5 text-right">
                                    <div>
                                        <p class="text-xs text-gray-400">Invoice Date</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $result['invoice_date'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400">Due Date</p>
                                        <p class="text-sm font-semibold text-red-600">{{ $result['due_date'] }}</p>
                                    </div>
                                    <div>
                                        <span class="inline-block text-xs font-semibold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full">
                                            {{ $result['currency'] }}
                                        </span>
                                    </div>
                                </div>

                            </div>

                            {{-- ── Line Items Table ─────────────────────── --}}
                            <table class="w-full text-sm mb-6">
                                <thead>
                                    <tr class="border-b-2 border-gray-100">
                                        <th class="text-left py-2 text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">#</th>
                                        <th class="text-left py-2 text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">Description</th>
                                        <th class="text-center py-2 text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">Qty</th>
                                        <th class="text-right py-2 text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">Unit Price</th>
                                        <th class="text-right py-2 text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($result['lines'] as $i => $line)
                                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                            <td class="py-3 text-xs text-gray-400 pr-2">{{ $i + 1 }}</td>
                                            <td class="py-3 text-gray-800 font-medium">{{ $line['description'] }}</td>
                                            <td class="py-3 text-center text-gray-600">{{ $line['qty'] }}</td>
                                            <td class="py-3 text-right text-gray-600">{{ $cur }} {{ number_format($line['unit_price'], 2) }}</td>
                                            <td class="py-3 text-right font-semibold text-gray-900">{{ $cur }} {{ number_format($line['line_total'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- ── Totals Block ─────────────────────────── --}}
                            <div class="flex justify-end mb-6">
                                <div class="w-64 space-y-1.5">

                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Subtotal</span>
                                        <span class="text-gray-900 font-medium">{{ $cur }} {{ number_format($result['subtotal'], 2) }}</span>
                                    </div>

                                    @if ($result['discount_amount'] > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">
                                                Discount
                                                @if ($result['discount_type'] === 'percent')
                                                    ({{ $result['discount'] }}%)
                                                @endif
                                            </span>
                                            <span class="text-red-500 font-medium">− {{ $cur }} {{ number_format($result['discount_amount'], 2) }}</span>
                                        </div>
                                    @endif

                                    @if ($result['tax_rate'] > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Tax ({{ $result['tax_rate'] }}%)</span>
                                            <span class="text-gray-900 font-medium">{{ $cur }} {{ number_format($result['tax_amount'], 2) }}</span>
                                        </div>
                                    @endif

                                    <div class="pt-2 border-t-2 border-gray-200 flex justify-between">
                                        <span class="font-bold text-gray-900">Total Due</span>
                                        <span class="font-extrabold text-indigo-700 text-base">
                                            {{ $cur }} {{ number_format($result['total'], 2) }}
                                        </span>
                                    </div>

                                </div>
                            </div>

                            {{-- ── Notes & Terms ────────────────────────── --}}
                            @if ($result['notes'] || $result['terms'])
                                <div class="border-t border-gray-100 pt-5 space-y-3">
                                    @if ($result['notes'])
                                        <div>
                                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Notes</p>
                                            <p class="text-xs text-gray-600 whitespace-pre-line">{{ $result['notes'] }}</p>
                                        </div>
                                    @endif
                                    @if ($result['terms'])
                                        <div>
                                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Payment Terms</p>
                                            <p class="text-xs text-gray-600 whitespace-pre-line">{{ $result['terms'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- ── Template & Export ────────────────────── --}}
                            <div class="mt-6 pt-4 border-t border-gray-50 space-y-4">

                                {{-- Template selector --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">PDF Template</label>
                                    <select wire:model.live="template"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                        <option value="basic">Basic – Simple and clean</option>
                                        <option value="modern">Modern – Contemporary design</option>
                                        <option value="corporate">Corporate – Professional branding {{ auth()->user()->subscription?->plan?->slug === 'pro' || auth()->user()->subscription?->plan?->slug === 'enterprise' ? '' : '(Pro)' }}</option>
                                    </select>
                                    @if ($template === 'corporate' && (auth()->user()->subscription?->plan?->slug === 'free' || !auth()->user()->subscription))
                                        <p class="text-xs text-amber-600 mt-1">
                                            <i class="bx bx-info-circle text-sm"></i> Preview available. Pro plan required to export.
                                        </p>
                                    @endif
                                </div>

                                {{-- PDF quota display for Free users --}}
                                @if ($pdfExportsLimit !== null)
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <p class="text-xs font-semibold text-blue-700 mb-2">Monthly PDF Export Quota</p>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs text-blue-600">{{ $pdfExportsRemaining }} / {{ $pdfExportsLimit }} remaining</span>
                                            <span class="text-xs text-blue-600 font-medium">
                                                {{ $pdfExportsLimit > 0 ? round(($pdfExportsRemaining / $pdfExportsLimit) * 100) : 0 }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-blue-200 rounded-full h-1.5">
                                            <div class="bg-blue-600 h-1.5 rounded-full transition-all"
                                                style="width: {{ $pdfExportsLimit > 0 ? (($pdfExportsRemaining / $pdfExportsLimit) * 100) : 0 }}%">
                                            </div>
                                        </div>
                                        @if ($pdfExportsRemaining === 0)
                                            <p class="text-xs text-blue-700 mt-2">
                                                <i class="bx bx-lock text-sm"></i> Quota reached. Upgrade to Pro for unlimited exports.
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Download button --}}
                                <button
                                    wire:click="downloadPdf"
                                    wire:loading.attr="disabled"
                                    {{ ($pdfExportsRemaining === 0 && $pdfExportsLimit !== null) ? 'disabled' : '' }}
                                    class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="downloadPdf">
                                        <i class="bx bx-download"></i> Download PDF
                                    </span>
                                    <span wire:loading wire:target="downloadPdf" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        Generating…
                                    </span>
                                </button>

                                {{-- Error messages --}}
                                @if ($errors->has('pdf_quota'))
                                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                        <p class="text-xs text-amber-700">
                                            <i class="bx bx-exclamation-circle"></i> {{ $errors->first('pdf_quota') }}
                                        </p>
                                    </div>
                                @endif
                                @if ($errors->has('template'))
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-xs text-red-700">
                                            <i class="bx bx-x-circle"></i> {{ $errors->first('template') }}
                                        </p>
                                    </div>
                                @endif

                                <p class="text-xs text-gray-400 text-center">Generated by {{ config('app.name') }}</p>

                            </div>

                        </div>
                    </div>

                @endif

            </div>
            {{-- /RIGHT --}}

        </div>
    </div>
</div>
