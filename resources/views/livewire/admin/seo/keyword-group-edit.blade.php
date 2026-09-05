{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: @json($isCreating ? 'Create Keyword Group' : 'Edit Keyword Group'), slug: 'seo-keyword-groups-edit' }">
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
                <li>
                    <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                        href="{{ route('admin.seo.keyword-groups.list') }}">
                        Keyword Groups
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

        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">{{ $isCreating ? 'Create Keyword Group' : 'Edit Keyword Group' }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Define how keywords are grouped and linked to a tool.</p>
            </div>

            <form wire:submit="save" class="space-y-6 max-w-2xl">

                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Group Details</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tool *</label>
                            <select wire:model="toolSlug"
                                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                <option value="">Select a tool...</option>
                                @foreach($tools as $tool)
                                    <option value="{{ $tool->slug() }}">{{ $tool->name() }}</option>
                                @endforeach
                            </select>
                            @error('toolSlug') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name *</label>
                            <input type="text" wire:model="name" wire:change="updatedName"
                                   class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                   placeholder="e.g., Car Loan Keywords">
                            @error('name') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Slug (URL) *</label>
                        <input type="text" wire:model="slug"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                               placeholder="e.g., car-loan-keywords">
                        @error('slug') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                  placeholder="Brief description..."></textarea>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.seo.keyword-groups.list') }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 font-semibold rounded-lg text-center hover:bg-gray-50 transition-colors text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2 text-sm">
                        <i class="bx text-sm" :class="$wire.loading ? 'bx-loader-alt animate-spin' : 'bx-save'"></i>
                        <span wire:loading.remove>
                            {{ $isCreating ? 'Create Group' : 'Save Changes' }}
                        </span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
