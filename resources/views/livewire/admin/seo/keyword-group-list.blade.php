{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: 'Keyword Groups', slug: 'seo-keyword-groups' }">
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
            {{-- Top Controls: Title and Create Button --}}
            <div class="flex flex-wrap items-center justify-between gap-4 p-6 border-b border-gray-100">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Keyword Groups</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Organize keywords into groups for each tool.</p>
                </div>
                <a href="{{ route('admin.seo.keyword-groups.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <i class="bx bx-plus text-lg"></i>
                    Create Group
                </a>
            </div>

            <div class="px-6 pb-6 overflow-x-auto">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider">
                                <th class="px-4 py-3.5 text-left">Name</th>
                                <th class="px-4 py-3.5 text-left">Tool</th>
                                <th class="px-4 py-3.5 text-left">Slug</th>
                                <th class="px-4 py-3.5 text-right">Keywords</th>
                                <th class="px-4 py-3.5 text-right">Pages</th>
                                <th class="px-4 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($groups as $group)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3.5 font-semibold text-gray-900">{{ $group->name }}</td>
                                    <td class="px-4 py-3.5 text-gray-600">{{ $group->tool_slug }}</td>
                                    <td class="px-4 py-3.5 font-mono text-xs text-gray-500">{{ $group->slug }}</td>
                                    <td class="px-4 py-3.5 text-right text-gray-700">{{ $group->keywords_count }}</td>
                                    <td class="px-4 py-3.5 text-right text-gray-700">{{ $group->pages_count }}</td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex justify-end gap-1.5">
                                            <a href="{{ route('admin.seo.keyword-groups.edit', $group) }}"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors">
                                                <i class="bx bx-edit-alt"></i> Edit
                                            </a>
                                            @if($confirmDeleteId === $group->id)
                                                <button wire:click="delete({{ $group->id }})"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                                    Confirm
                                                </button>
                                                <button wire:click="$set('confirmDeleteId', null)"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                                                    Cancel
                                                </button>
                                            @else
                                                <button wire:click="confirmDelete({{ $group->id }})"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors">
                                                    <i class="bx bx-trash"></i> Delete
                                                </button>
                                            @endif
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
                                            <p class="text-gray-600 font-semibold mb-1">No keyword groups yet</p>
                                            <p class="text-sm text-gray-400 mb-4">Create a group to start organizing keywords for a tool.</p>
                                            <a href="{{ route('admin.seo.keyword-groups.create') }}"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                                <i class="bx bx-plus"></i> Create your first group
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
