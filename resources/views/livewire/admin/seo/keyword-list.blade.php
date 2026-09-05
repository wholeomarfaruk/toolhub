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

        <div class="space-y-6">
            {{-- Top Controls --}}
            <div class="flex flex-wrap items-center justify-between gap-4 p-6 border-b border-gray-100">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Keywords</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Review, approve, and manage SEO target keywords.</p>
                </div>
                <a href="{{ route('admin.seo.keywords.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <i class="bx bx-plus text-lg"></i>
                    Add Keyword
                </a>
            </div>

            {{-- Filters --}}
            <div class="mx-6 px-6 py-4 bg-gray-50/50 border border-gray-100 rounded-xl flex flex-wrap gap-3">
                <select wire:model.live="filterTool" class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                    <option value="">All Tools</option>
                    @foreach($tools as $tool)
                        <option value="{{ $tool->slug() }}">{{ $tool->name() }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterStatus" class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>

                <select wire:model.live="filterGroup" class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- AI Keyword Generation --}}
            <div class="mx-6 p-5 bg-indigo-50 border border-indigo-100 rounded-xl">
                <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-1.5">
                    <i class="bx bx-bulb text-indigo-600"></i> Generate Keywords with AI
                </h3>
                @if($activeProviders->isEmpty())
                    <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 flex items-center gap-1.5">
                        <i class="bx bx-error-circle"></i>
                        No AI providers configured — set one up in <a href="{{ route('admin.seo.ai-settings') }}" class="font-semibold underline">AI Settings</a>.
                    </p>
                @else
                    <div class="grid gap-3 md:grid-cols-4 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Seed Topic</label>
                            <input type="text" wire:model="aiSeedTopic"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                   placeholder="e.g., car loan EMI">
                            @error('aiSeedTopic') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Count</label>
                            <input type="number" wire:model="aiCount" min="1" max="50"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            @error('aiCount') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <button wire:click="generateWithAi" wire:loading.attr="disabled"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                                <i class="bx bx-bulb"></i> Generate
                            </button>
                        </div>
                    </div>
                    <div class="grid gap-3 md:grid-cols-4 items-end mt-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">AI Provider</label>
                            <select wire:model="aiProviderSlug" class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                <option value="">Select provider…</option>
                                @foreach($activeProviders as $provider)
                                    <option value="{{ $provider->slug }}">{{ $provider->name }}</option>
                                @endforeach
                            </select>
                            @error('aiProviderSlug') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Model (optional)</label>
                            <input type="text" wire:model="aiModel"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                   placeholder="Leave blank for provider default">
                        </div>
                    </div>
                @endif
                <p class="text-xs text-gray-500 mt-3">Uses the tool selected in the filter above (defaults to EMI Calculator). Runs in the background — refresh this list shortly after.</p>
            </div>

            {{-- Bulk CSV Import shortcut --}}
            <div class="mx-6 p-4 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600 flex items-center gap-2">
                <i class="bx bx-info-circle text-gray-400"></i>
                <span>To bulk-import keywords from a CSV file (columns: keyword, search_intent, country, language, priority),
                open the <a href="{{ route('admin.seo.keywords.create') }}" class="text-indigo-600 hover:underline font-medium">Add Keyword</a> page.</span>
            </div>

            {{-- Table --}}
            <div class="px-6 pb-6">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider">
                                <th class="px-4 py-3.5 text-left">Keyword</th>
                                <th class="px-4 py-3.5 text-left">Tool</th>
                                <th class="px-4 py-3.5 text-left">Group</th>
                                <th class="px-4 py-3.5 text-left">Intent</th>
                                <th class="px-4 py-3.5 text-center">Priority</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($keywords as $keyword)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3.5 font-medium text-gray-900">{{ $keyword->keyword }}</td>
                                    <td class="px-4 py-3.5 text-gray-600">{{ $keyword->tool_slug }}</td>
                                    <td class="px-4 py-3.5 text-gray-600">{{ $keyword->group?->name ?? '—' }}</td>
                                    <td class="px-4 py-3.5 text-gray-600">{{ ucfirst($keyword->search_intent) }}</td>
                                    <td class="px-4 py-3.5 text-center text-gray-700">{{ $keyword->priority }}</td>
                                    <td class="px-4 py-3.5">
                                        <span @class([
                                            'inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full',
                                            'bg-amber-100 text-amber-700' => $keyword->status === 'pending',
                                            'bg-emerald-100 text-emerald-700' => $keyword->status === 'approved',
                                            'bg-red-100 text-red-700' => $keyword->status === 'rejected',
                                        ])>
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            {{ ucfirst($keyword->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex justify-end gap-1.5 flex-wrap">
                                            @if($keyword->status !== 'approved')
                                                <button wire:click="approve({{ $keyword->id }})"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition-colors">
                                                    <i class="bx bx-check"></i> Approve
                                                </button>
                                            @endif
                                            @if($keyword->status !== 'rejected')
                                                <button wire:click="reject({{ $keyword->id }})"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                                                    <i class="bx bx-x"></i> Reject
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.seo.keywords.edit', $keyword) }}"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors">
                                                <i class="bx bx-edit-alt"></i> Edit
                                            </a>
                                            <button wire:click="delete({{ $keyword->id }})"
                                                    wire:confirm="Delete this keyword?"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors">
                                                <i class="bx bx-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                                <i class="bx bx-inbox text-3xl text-gray-300"></i>
                                            </div>
                                            <p class="text-gray-600 font-semibold mb-1">No keywords yet</p>
                                            <p class="text-sm text-gray-400 mb-4">Add a keyword manually or generate some with AI above.</p>
                                            <a href="{{ route('admin.seo.keywords.create') }}"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                                <i class="bx bx-plus"></i> Add your first keyword
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $keywords->links() }}
                </div>
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
