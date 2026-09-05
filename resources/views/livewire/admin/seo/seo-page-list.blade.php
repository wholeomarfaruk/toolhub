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

        {{-- Top Controls --}}
        <div class="grid grid-cols-2 gap-4 px-4 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Manage SEO Pages</h2>
            </div>
            <div class="flex justify-end items-end">
                <a href="{{ route('admin.seo.pages.create') }}"
                   class="flex items-center gap-2 pb-1 text-gray-700 transition-colors hover:border-gray-400 hover:text-gray-900 cursor-pointer rounded border border-gray-300 px-4 py-2">
                    <i class="bx bx-plus"></i>
                    <span class="text-sm font-medium">Create Page</span>
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
                <option value="draft">Draft</option>
                <option value="ai_generated">AI Generated</option>
                <option value="published">Published</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="px-4 pb-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <th class="px-4 py-3 text-left font-medium">Slug</th>
                        <th class="px-4 py-3 text-left font-medium">Tool</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-center font-medium">Indexable</th>
                        <th class="px-4 py-3 text-left font-medium">Published</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pages as $page)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-gray-700">
                                /{{ $page->tool_slug }}/{{ $page->slug }}
                                @if($page->is_primary)
                                    <span class="ml-1 inline-block px-1.5 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-700 rounded-full align-middle">Primary</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $page->tool_slug }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-block px-2 py-0.5 text-xs font-semibold rounded-full',
                                    'bg-gray-200 text-gray-700' => $page->status === 'draft',
                                    'bg-indigo-100 text-indigo-700' => $page->status === 'ai_generated',
                                    'bg-emerald-100 text-emerald-700' => $page->status === 'published',
                                ])>
                                    {{ ucwords(str_replace('_', ' ', $page->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggleIndexable({{ $page->id }})"
                                        class="px-2 py-1 text-xs font-semibold rounded transition-colors {{ $page->is_indexable ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                    {{ $page->is_indexable ? 'Yes' : 'No' }}
                                </button>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                {{ $page->published_at?->format('Y-m-d H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-1 flex-wrap">
                                    @if($page->status === 'draft')
                                        <button wire:click="generateContent({{ $page->id }})"
                                                class="px-2 py-1 text-xs font-semibold bg-purple-100 hover:bg-purple-200 text-purple-700 rounded transition-colors">
                                            Generate AI Content
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.seo.pages.edit', $page) }}"
                                       class="px-2 py-1 text-xs font-semibold bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded transition-colors">
                                        Edit
                                    </a>
                                    <button wire:click="delete({{ $page->id }})"
                                            wire:confirm="Delete this page?"
                                            class="px-2 py-1 text-xs font-semibold bg-red-100 hover:bg-red-200 text-red-700 rounded transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                <i class="bx bx-inbox text-3xl mb-2 block"></i>
                                No SEO pages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $pages->links() }}
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
