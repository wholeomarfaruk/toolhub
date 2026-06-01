{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: 'Payment History', slug: 'payments' }">
    {{-- ======================== Page Header Start From Here ======================== --}}
    <div class="flex flex-wrap justify-between gap-6">
        <h1 class="text-gray-500 text-lg font-bold" x-cloak x-text="$store.pageName?.name ?? ''"></h1>
        <nav>
            <ol class="flex items-center gap-1.5">
                <li>
                    <a class="inline-flex items-center gap-1.5 text-sm text-gray-500"
                        href="{{ route('admin.dashboard') }}">
                        Dashboard
                        <svg class="stroke-current" width="17" height="16" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
                <li class="text-sm text-gray-800" x-text="$store.pageName?.name ?? ''"></li>
            </ol>
        </nav>
    </div>
    {{-- ======================== Page Header End Here ======================== --}}

    <div class="flex-1 w-full bg-white rounded-lg min-h-[80vh]">
        {{-- ======================== Content Start From Here ======================== --}}

        {{-- Search and Filters --}}
        <div class="grid grid-cols-2 gap-4 px-4 py-4">
            <div>
                <input type="text" wire:model.live.debounce="search" placeholder="Search by user name, email, or plan"
                       class="w-full rounded border-gray-300 px-3 py-2 shadow-sm text-sm">
            </div>
            <div class="flex gap-2 justify-end items-end">
                <select wire:model.live="statusFilter" class="rounded border-gray-300 px-3 py-2 text-sm">
                    <option value="">All Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Payments Table --}}
        <div class="overflow-x-auto rounded border border-gray-300 shadow-sm mx-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="ltr:text-left rtl:text-right bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 font-semibold text-gray-900 text-sm whitespace-nowrap">User</th>
                        <th class="px-3 py-3 font-semibold text-gray-900 text-sm whitespace-nowrap">Plan</th>
                        <th class="px-3 py-3 font-semibold text-gray-900 text-sm whitespace-nowrap">Amount</th>
                        <th class="px-3 py-3 font-semibold text-gray-900 text-sm whitespace-nowrap">Period</th>
                        <th class="px-3 py-3 font-semibold text-gray-900 text-sm whitespace-nowrap">Status</th>
                        <th class="px-3 py-3 font-semibold text-gray-900 text-sm whitespace-nowrap">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">{{ $payment->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $payment->user->email }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $payment->plan->name }}</td>
                            <td class="px-3 py-2 text-sm font-medium text-gray-900">
                                {{ $payment->currency }} {{ number_format($payment->amount / 100, 2) }}
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-700 capitalize">{{ $payment->billing_period }}</td>
                            <td class="px-3 py-2 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payment->status === 'completed')
                                        bg-emerald-100 text-emerald-700
                                    @elseif($payment->status === 'pending')
                                        bg-amber-100 text-amber-700
                                    @elseif($payment->status === 'failed')
                                        bg-red-100 text-red-700
                                    @else
                                        bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $payment->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-gray-500">
                                No payments found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($payments->hasPages())
            <div class="px-4 py-4">
                {{ $payments->links() }}
            </div>
        @endif

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
