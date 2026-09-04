{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: 'Keywords', slug: 'seo-keywords' }">
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

        {{-- Top Controls --}}
        <div class="grid grid-cols-2 gap-4 px-4 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Manage Keywords</h2>
            </div>
            <div class="flex justify-end items-end">
                <a href="{{ route('admin.seo.keywords.create') }}"
                   class="flex items-center gap-2 pb-1 text-gray-700 transition-colors hover:border-gray-400 hover:text-gray-900 cursor-pointer rounded border border-gray-300 px-4 py-2">
                    <i class="bx bx-plus"></i>
                    <span class="text-sm font-medium">Add Keyword</span>
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-4 pb-4 grid gap-3 md:grid-cols-3">
            <select wire:model.live="filterTool" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Tools</option>
                @foreach($tools as $tool)
                    <option value="{{ $tool->slug() }}">{{ $tool->name() }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterStatus" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>

            <select wire:model.live="filterGroup" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Groups</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- AI Keyword Generation --}}
        <div class="mx-4 mb-4 p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
            <h3 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-1.5">
                <i class="bx bx-bulb text-indigo-600"></i> Generate Keywords with AI
            </h3>
            @if($activeProviders->isEmpty())
                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                    No AI providers configured — set one up in <a href="{{ route('admin.seo.ai-settings') }}" class="font-semibold underline">AI Settings</a>.
                </p>
            @else
                <div class="grid gap-3 md:grid-cols-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Seed Topic</label>
                        <input type="text" wire:model="aiSeedTopic"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                               placeholder="e.g., car loan EMI">
                        @error('aiSeedTopic') <span class="text-red-600 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Count</label>
                        <input type="number" wire:model="aiCount" min="1" max="50"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        @error('aiCount') <span class="text-red-600 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <button wire:click="generateWithAi" wire:loading.attr="disabled"
                                class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors">
                            Generate
                        </button>
                    </div>
                </div>
                <div class="grid gap-3 md:grid-cols-4 items-end mt-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">AI Provider</label>
                        <select wire:model="aiProviderSlug" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">Select provider…</option>
                            @foreach($activeProviders as $provider)
                                <option value="{{ $provider->slug }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        @error('aiProviderSlug') <span class="text-red-600 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Model (optional)</label>
                        <input type="text" wire:model="aiModel"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                               placeholder="Leave blank for provider default">
                    </div>
                </div>
            @endif
            <p class="text-xs text-gray-500 mt-2">Uses the tool selected in the filter above (defaults to EMI Calculator). Runs in the background — refresh this list shortly after.</p>
        </div>

        {{-- Bulk CSV Import shortcut --}}
        <div class="mx-4 mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600">
            <i class="bx bx-info-circle"></i> To bulk-import keywords from a CSV file (columns: keyword, search_intent, country, language, priority),
            open the <a href="{{ route('admin.seo.keywords.create') }}" class="text-indigo-600 hover:underline font-medium">Add Keyword</a> page.
        </div>

        {{-- Table --}}
        <div class="px-4 pb-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <th class="px-4 py-3 text-left font-medium">Keyword</th>
                        <th class="px-4 py-3 text-left font-medium">Tool</th>
                        <th class="px-4 py-3 text-left font-medium">Group</th>
                        <th class="px-4 py-3 text-left font-medium">Intent</th>
                        <th class="px-4 py-3 text-center font-medium">Priority</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($keywords as $keyword)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $keyword->keyword }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $keyword->tool_slug }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $keyword->group?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ ucfirst($keyword->search_intent) }}</td>
                            <td class="px-4 py-3 text-center text-gray-700">{{ $keyword->priority }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-block px-2 py-0.5 text-xs font-semibold rounded-full',
                                    'bg-amber-100 text-amber-700' => $keyword->status === 'pending',
                                    'bg-emerald-100 text-emerald-700' => $keyword->status === 'approved',
                                    'bg-red-100 text-red-700' => $keyword->status === 'rejected',
                                ])>
                                    {{ ucfirst($keyword->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-1 flex-wrap">
                                    @if($keyword->status !== 'approved')
                                        <button wire:click="approve({{ $keyword->id }})"
                                                class="px-2 py-1 text-xs font-semibold bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded transition-colors">
                                            Approve
                                        </button>
                                    @endif
                                    @if($keyword->status !== 'rejected')
                                        <button wire:click="reject({{ $keyword->id }})"
                                                class="px-2 py-1 text-xs font-semibold bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition-colors">
                                            Reject
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.seo.keywords.edit', $keyword) }}"
                                       class="px-2 py-1 text-xs font-semibold bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded transition-colors">
                                        Edit
                                    </a>
                                    <button wire:click="delete({{ $keyword->id }})"
                                            wire:confirm="Delete this keyword?"
                                            class="px-2 py-1 text-xs font-semibold bg-red-100 hover:bg-red-200 text-red-700 rounded transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                <i class="bx bx-inbox text-3xl mb-2 block"></i>
                                No keywords found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $keywords->links() }}
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
