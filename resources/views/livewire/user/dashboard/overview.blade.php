<div class="space-y-6">

    {{-- ── Stats Row ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $cards = [
                ['label' => 'Used Today',    'value' => $stats['today'],       'icon' => 'bx bx-run',          'color' => 'text-indigo-600',  'bg' => 'bg-indigo-50'],
                ['label' => 'This Month',    'value' => $stats['month'],       'icon' => 'bx bx-calendar',     'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                ['label' => 'All Time',      'value' => $stats['total'],       'icon' => 'bx bx-line-chart',   'color' => 'text-purple-600',  'bg' => 'bg-purple-50'],
                ['label' => 'Days Active',   'value' => $stats['days_active'], 'icon' => 'bx bx-calendar-check','color' => 'text-amber-600',  'bg' => 'bg-amber-50'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl {{ $card['bg'] }} flex items-center justify-center flex-shrink-0">
                    <i class="{{ $card['icon'] }} text-xl {{ $card['color'] }}"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($card['value']) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Quick Access Tools ──────────────────────────────────────── --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900">Available Tools</h2>
                <a href="{{ route('tools.index') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>

            @if (count($tools) === 0)
                <p class="text-sm text-gray-400 text-center py-8">No tools available yet.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach ($tools as $tool)
                        <a href="{{ route('tools.' . $tool->slug()) }}"
                           class="flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition-all group text-center">
                            <div class="w-9 h-9 rounded-xl bg-gray-50 group-hover:bg-white flex items-center justify-center transition-colors">
                                <i class="{{ $tool->icon() }} text-lg text-indigo-600"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700 leading-tight">
                                {{ $tool->name() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Recent Activity ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900">Recent Activity</h2>
                <a href="{{ route('dashboard.history') }}" class="text-xs text-indigo-600 hover:underline">See all</a>
            </div>

            @if ($recentUsages->isEmpty())
                <div class="flex flex-col items-center justify-center py-8 text-gray-300">
                    <i class="bx bx-history text-4xl mb-2"></i>
                    <p class="text-sm text-gray-400">No activity yet</p>
                    <a href="{{ route('tools.index') }}" class="mt-3 text-xs text-indigo-600 hover:underline">
                        Try a tool
                    </a>
                </div>
            @else
                <ul class="space-y-3">
                    @foreach ($recentUsages as $usage)
                        @php $tool = app(\App\Services\ToolRegistry::class)->tryFind($usage->tool_slug); @endphp
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <i class="{{ $tool ? $tool->icon() : 'bx bx-wrench' }} text-sm text-indigo-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">
                                    {{ $tool ? $tool->name() : $usage->tool_slug }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $usage->created_at->diffForHumans() }}</p>
                            </div>
                            @if ($tool)
                                <a href="{{ route('tools.' . $tool->slug()) }}"
                                   class="text-xs text-indigo-600 hover:underline flex-shrink-0">
                                    Run
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

    {{-- ── Most Used Tool ──────────────────────────────────────────────── --}}
    @if ($mostUsed)
        @php $topTool = app(\App\Services\ToolRegistry::class)->tryFind($mostUsed->tool_slug); @endphp
        @if ($topTool)
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-5 flex items-center justify-between">
                <div class="text-white">
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-200 mb-1">Your most used tool</p>
                    <p class="text-xl font-bold">{{ $topTool->name() }}</p>
                    <p class="text-indigo-200 text-sm mt-0.5">Used {{ $mostUsed->total }} {{ Str::plural('time', $mostUsed->total) }}</p>
                </div>
                <a href="{{ route('tools.' . $topTool->slug()) }}"
                   class="px-4 py-2 bg-white text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-50 transition-colors flex-shrink-0">
                    Use Again
                </a>
            </div>
        @endif
    @endif

</div>
