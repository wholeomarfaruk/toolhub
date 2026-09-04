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

        {{-- Top Controls: Title and Create Button --}}
        <div class="grid grid-cols-2 gap-4 px-4 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Manage Keyword Groups</h2>
            </div>
            <div class="flex justify-end items-end">
                <a href="{{ route('admin.seo.keyword-groups.create') }}"
                   class="flex items-center gap-2 pb-1 text-gray-700 transition-colors hover:border-gray-400 hover:text-gray-900 cursor-pointer rounded border border-gray-300 px-4 py-2">
                    <i class="bx bx-plus"></i>
                    <span class="text-sm font-medium">Create Group</span>
                </a>
            </div>
        </div>

        <div class="px-4 pb-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <th class="px-4 py-3 text-left font-medium">Name</th>
                        <th class="px-4 py-3 text-left font-medium">Tool</th>
                        <th class="px-4 py-3 text-left font-medium">Slug</th>
                        <th class="px-4 py-3 text-right font-medium">Keywords</th>
                        <th class="px-4 py-3 text-right font-medium">Pages</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($groups as $group)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $group->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $group->tool_slug }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $group->slug }}</td>
                            <td class="px-4 py-3 text-right text-gray-700">{{ $group->keywords_count }}</td>
                            <td class="px-4 py-3 text-right text-gray-700">{{ $group->pages_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.seo.keyword-groups.edit', $group) }}"
                                       class="px-2 py-1 text-xs font-semibold bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded transition-colors">
                                        Edit
                                    </a>
                                    @if($confirmDeleteId === $group->id)
                                        <button wire:click="delete({{ $group->id }})"
                                                class="px-2 py-1 text-xs font-semibold bg-red-600 hover:bg-red-700 text-white rounded transition-colors">
                                            Confirm
                                        </button>
                                        <button wire:click="$set('confirmDeleteId', null)"
                                                class="px-2 py-1 text-xs font-semibold bg-gray-300 hover:bg-gray-400 text-gray-800 rounded transition-colors">
                                            Cancel
                                        </button>
                                    @else
                                        <button wire:click="confirmDelete({{ $group->id }})"
                                                class="px-2 py-1 text-xs font-semibold bg-red-100 hover:bg-red-200 text-red-700 rounded transition-colors">
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                <i class="bx bx-inbox text-3xl mb-2 block"></i>
                                No keyword groups found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
