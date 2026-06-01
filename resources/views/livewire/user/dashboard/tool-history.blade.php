<div class="space-y-4">

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-3">
        <i class="bx bx-filter-alt text-gray-400 text-lg"></i>
        <select wire:model.live="filterSlug"
            class="flex-1 text-sm border-0 focus:ring-0 text-gray-700 bg-transparent cursor-pointer">
            <option value="">All Tools</option>
            @foreach ($slugs as $slug)
                @php $t = $registry->tryFind($slug); @endphp
                <option value="{{ $slug }}">{{ $t ? $t->name() : $slug }}</option>
            @endforeach
        </select>
        @if ($filterSlug)
            <button wire:click="$set('filterSlug', '')" class="text-xs text-indigo-600 hover:underline">
                Clear
            </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        @if ($history->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <i class="bx bx-history text-5xl text-gray-200 mb-3"></i>
                <p class="text-gray-400 text-sm">No usage history yet.</p>
                <a href="{{ route('tools.index') }}" class="mt-3 text-sm text-indigo-600 hover:underline">
                    Use a tool to get started
                </a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Tool</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide hidden sm:table-cell">Category</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wide">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($history as $usage)
                        @php $tool = $registry->tryFind($usage->tool_slug); @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                        <i class="{{ $tool ? $tool->icon() : 'bx bx-wrench' }} text-sm text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $tool ? $tool->name() : $usage->tool_slug }}
                                        </p>
                                        @if ($tool)
                                            <a href="{{ route('tools.' . $tool->slug()) }}"
                                               class="text-xs text-indigo-600 hover:underline">
                                                Use again
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 hidden sm:table-cell">
                                @if ($tool)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                        {{ $tool->category()->label() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right text-gray-400 text-xs">
                                <span title="{{ $usage->created_at->format('Y-m-d H:i') }}">
                                    {{ $usage->created_at->diffForHumans() }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($history->hasPages())
                <div class="px-5 py-4 border-t border-gray-50">
                    {{ $history->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
