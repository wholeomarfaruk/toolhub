{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: 'SEO Pages', slug: 'seo-pages' }">
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
                    <h2 class="text-xl font-bold text-gray-900">SEO Pages</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Manage programmatic landing pages per tool.</p>
                </div>
                <a href="{{ route('admin.seo.pages.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <i class="bx bx-plus text-lg"></i>
                    Create Page
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
                    <option value="draft">Draft</option>
                    <option value="ai_generated">AI Generated</option>
                    <option value="published">Published</option>
                </select>
            </div>

            {{-- Table --}}
            <div class="px-6 pb-6">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider">
                                <th class="px-4 py-3.5 text-left">Slug</th>
                                <th class="px-4 py-3.5 text-left">Tool</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-center">Indexable</th>
                                <th class="px-4 py-3.5 text-left">Published</th>
                                <th class="px-4 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pages as $page)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3.5 font-mono text-xs text-gray-700">
                                        /{{ $page->tool_slug }}/{{ $page->slug }}
                                        @if($page->is_primary)
                                            <span class="ml-1 inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700 align-middle">
                                                <span class="w-1 h-1 rounded-full bg-current"></span>
                                                Primary
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600">{{ $page->tool_slug }}</td>
                                    <td class="px-4 py-3.5">
                                        <span @class([
                                            'inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full',
                                            'bg-gray-200 text-gray-700' => $page->status === 'draft',
                                            'bg-indigo-100 text-indigo-700' => $page->status === 'ai_generated',
                                            'bg-emerald-100 text-emerald-700' => $page->status === 'published',
                                        ])>
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            {{ ucwords(str_replace('_', ' ', $page->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <button wire:click="toggleIndexable({{ $page->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $page->is_indexable ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                            {{ $page->is_indexable ? 'Yes' : 'No' }}
                                        </button>
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-500 text-xs">
                                        {{ $page->published_at?->format('Y-m-d H:i') ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex justify-end gap-1.5 flex-wrap">
                                            @if($page->status === 'draft')
                                                <button wire:click="generateContent({{ $page->id }})"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg transition-colors">
                                                    <i class="bx bx-bulb"></i> Generate AI Content
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.seo.pages.edit', $page) }}"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors">
                                                <i class="bx bx-edit-alt"></i> Edit
                                            </a>
                                            <button wire:click="delete({{ $page->id }})"
                                                    wire:confirm="Delete this page?"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors">
                                                <i class="bx bx-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                                <i class="bx bx-inbox text-3xl text-gray-300"></i>
                                            </div>
                                            <p class="text-gray-600 font-semibold mb-1">No SEO pages yet</p>
                                            <p class="text-sm text-gray-400 mb-4">Create a page to start building programmatic SEO landing pages.</p>
                                            <a href="{{ route('admin.seo.pages.create') }}"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                                <i class="bx bx-plus"></i> Create your first page
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $pages->links() }}
                </div>
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
