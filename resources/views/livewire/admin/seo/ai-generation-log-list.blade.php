{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: 'AI Generation Logs', slug: 'seo-ai-logs' }">
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
            <h2 class="text-lg font-bold text-gray-900">AI Generation Logs</h2>
            <p class="text-sm text-gray-500">Audit trail of every AI keyword/content generation request — cost, tokens, and status.</p>
        </div>

        {{-- Summary cards --}}
        <div class="px-4 pb-4 grid gap-4 grid-cols-2 md:grid-cols-5">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <div class="text-xs text-gray-500">Total Requests</div>
                <div class="text-lg font-bold text-gray-900">{{ number_format($totals->total_requests ?? 0) }}</div>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-3">
                <div class="text-xs text-emerald-700">Success</div>
                <div class="text-lg font-bold text-emerald-800">{{ number_format($totals->total_success ?? 0) }}</div>
            </div>
            <div class="bg-red-50 border border-red-100 rounded-lg p-3">
                <div class="text-xs text-red-700">Failed</div>
                <div class="text-lg font-bold text-red-800">{{ number_format($totals->total_failed ?? 0) }}</div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <div class="text-xs text-gray-500">Total Tokens</div>
                <div class="text-lg font-bold text-gray-900">{{ number_format($totals->total_tokens ?? 0) }}</div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <div class="text-xs text-gray-500">Est. Cost (USD)</div>
                <div class="text-lg font-bold text-gray-900">${{ number_format($totals->total_cost ?? 0, 4) }}</div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-4 pb-4 grid gap-3 md:grid-cols-3">
            <select wire:model.live="filterType" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Types</option>
                <option value="keyword_generation">Keyword Generation</option>
                <option value="page_content_generation">Page Content Generation</option>
            </select>

            <select wire:model.live="filterStatus" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="success">Success</option>
                <option value="failed">Failed</option>
            </select>

            <select wire:model.live="filterProvider" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Providers</option>
                @foreach($providers as $provider)
                    <option value="{{ $provider }}">{{ ucfirst($provider) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Table --}}
        <div class="px-4 pb-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                        <th class="px-4 py-3 text-left font-medium">Type</th>
                        <th class="px-4 py-3 text-left font-medium">Subject</th>
                        <th class="px-4 py-3 text-left font-medium">Provider / Model</th>
                        <th class="px-4 py-3 text-right font-medium">Tokens</th>
                        <th class="px-4 py-3 text-right font-medium">Cost</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Triggered By</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $log->type === 'keyword_generation' ? 'Keywords' : 'Page Content' }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($log->seoPage)
                                    <a href="{{ route('admin.seo.pages.edit', $log->seoPage) }}" class="text-indigo-600 hover:underline">
                                        {{ $log->seoPage->slug }}
                                    </a>
                                @elseif($log->keywordGroup)
                                    {{ $log->keywordGroup->name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                <div class="font-medium text-gray-800">{{ ucfirst($log->provider) }}</div>
                                <div class="text-xs text-gray-400 font-mono">{{ $log->model }}</div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ $log->total_tokens ? number_format($log->total_tokens) : '—' }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ $log->estimated_cost_usd ? '$'.number_format($log->estimated_cost_usd, 4) : '—' }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-block px-2 py-0.5 text-xs font-semibold rounded-full',
                                    'bg-amber-100 text-amber-700' => $log->status === 'pending',
                                    'bg-emerald-100 text-emerald-700' => $log->status === 'success',
                                    'bg-red-100 text-red-700' => $log->status === 'failed',
                                ])>
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->triggeredBy?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                @if($log->error_message || $log->prompt)
                                    <button wire:click="view({{ $log->id }})"
                                            class="px-2 py-1 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors">
                                        {{ $viewingId === $log->id ? 'Hide' : 'Details' }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @if($viewingId === $log->id)
                            <tr>
                                <td colspan="9" class="px-4 py-3 bg-gray-50">
                                    @if($log->error_message)
                                        <div class="mb-2">
                                            <div class="text-xs font-semibold text-red-700 mb-1">Error</div>
                                            <div class="text-xs text-red-600 font-mono whitespace-pre-wrap">{{ $log->error_message }}</div>
                                        </div>
                                    @endif
                                    @if($log->prompt)
                                        <div>
                                            <div class="text-xs font-semibold text-gray-700 mb-1">Prompt</div>
                                            <div class="text-xs text-gray-600 font-mono whitespace-pre-wrap max-h-48 overflow-y-auto">{{ $log->prompt }}</div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-400">
                                <i class="bx bx-inbox text-3xl mb-2 block"></i>
                                No AI generation activity yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
