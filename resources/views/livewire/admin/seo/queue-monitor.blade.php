{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: 'Queue Monitor', slug: 'seo-queue-monitor' }" wire:poll.15s>
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

        <div class="px-4 py-4">
            <h2 class="text-lg font-bold text-gray-900">Queue Worker Monitor</h2>
            <p class="text-sm text-gray-500">Auto-refreshes every 15 seconds. Confirms whether your queue worker (cron/systemd) is actually running.</p>
        </div>

        {{-- Worker Heartbeat --}}
        <div class="px-4 pb-4">
            @if($workerLikelyRunning)
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span>
                        <strong>Worker appears active</strong> — last AI job processed
                        {{ $lastProcessedAt->diffForHumans() }} ({{ $lastProcessedAt->format('Y-m-d H:i:s') }}).
                    </span>
                </div>
            @else
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
                    <i class="bx bx-error-circle text-lg text-amber-500"></i>
                    <span>
                        <strong>No recent worker activity detected.</strong>
                        @if($lastProcessedAt)
                            Last AI job processed {{ $lastProcessedAt->diffForHumans() }} ({{ $lastProcessedAt->format('Y-m-d H:i:s') }}).
                        @else
                            No AI job has ever been processed yet.
                        @endif
                        This indicator only updates when an AI generation job runs — if you haven't triggered one recently, this may be a false alarm. Trigger a keyword/content generation and check back, or verify your cron/systemd queue worker is configured.
                    </span>
                </div>
            @endif
        </div>

        {{-- Summary cards --}}
        <div class="px-4 pb-4 grid gap-4 grid-cols-2 md:grid-cols-2">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="text-xs text-gray-500">Pending Jobs (queued, not yet processed)</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($pendingCount) }}</div>
                @if($pendingCount > 5)
                    <div class="text-xs text-amber-600 mt-1">High pending count — worker may not be running.</div>
                @endif
            </div>
            <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                <div class="text-xs text-red-700">Failed Jobs</div>
                <div class="text-2xl font-bold text-red-800">{{ number_format($failedCount) }}</div>
            </div>
        </div>

        {{-- Pending Jobs --}}
        <div class="px-4 pb-4">
            <h3 class="text-sm font-bold text-gray-900 mb-2">Pending Jobs (latest 20)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                            <th class="px-4 py-2 text-left font-medium">Job</th>
                            <th class="px-4 py-2 text-center font-medium">Attempts</th>
                            <th class="px-4 py-2 text-left font-medium">Queued At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingJobs as $job)
                            <tr>
                                <td class="px-4 py-2 font-mono text-xs text-gray-700">{{ $job['job_class'] }}</td>
                                <td class="px-4 py-2 text-center text-gray-600">{{ $job['attempts'] }}</td>
                                <td class="px-4 py-2 text-gray-600">{{ \Carbon\Carbon::createFromTimestamp($job['created_at'])->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400">No pending jobs.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Failed Jobs --}}
        <div class="px-4 pb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-gray-900">Failed Jobs (latest 20)</h3>
                @if($failedCount > 0)
                    <div class="flex gap-2">
                        <button wire:click="retryAllFailed" wire:confirm="Retry all failed jobs?"
                                class="px-3 py-1.5 text-xs font-semibold bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded transition-colors">
                            Retry All
                        </button>
                        <button wire:click="clearAllFailed" wire:confirm="Permanently delete all failed job records?"
                                class="px-3 py-1.5 text-xs font-semibold bg-red-100 hover:bg-red-200 text-red-700 rounded transition-colors">
                            Clear All
                        </button>
                    </div>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                            <th class="px-4 py-2 text-left font-medium">Job</th>
                            <th class="px-4 py-2 text-left font-medium">Failed At</th>
                            <th class="px-4 py-2 text-left font-medium">Exception</th>
                            <th class="px-4 py-2 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($failedJobs as $job)
                            <tr>
                                <td class="px-4 py-2 font-mono text-xs text-gray-700 align-top">{{ $job['job_class'] }}</td>
                                <td class="px-4 py-2 text-gray-600 whitespace-nowrap align-top">{{ $job['failed_at'] }}</td>
                                <td class="px-4 py-2 text-xs text-red-600 font-mono whitespace-pre-wrap align-top">{{ $job['exception'] }}</td>
                                <td class="px-4 py-2 text-right align-top">
                                    <div class="flex justify-end gap-1">
                                        <button wire:click="retryFailed('{{ $job['uuid'] }}')"
                                                class="px-2 py-1 text-xs font-semibold bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded transition-colors">
                                            Retry
                                        </button>
                                        <button wire:click="deleteFailed({{ $job['id'] }})"
                                                wire:confirm="Delete this failed job record?"
                                                class="px-2 py-1 text-xs font-semibold bg-red-100 hover:bg-red-200 text-red-700 rounded transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400">No failed jobs.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
