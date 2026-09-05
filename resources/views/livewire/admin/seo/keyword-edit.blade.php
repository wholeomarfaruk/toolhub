{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: @json($isCreating ? 'Add Keyword' : 'Edit Keyword'), slug: 'seo-keywords-edit' }">
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
                        href="{{ route('admin.seo.keywords.list') }}">
                        Keywords
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
                <h2 class="text-xl font-bold text-gray-900">{{ $isCreating ? 'Add Keyword' : 'Edit Keyword' }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Configure keyword details, intent, and targeting.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">

                {{-- Main Form --}}
                <div class="lg:col-span-2">
                    <form wire:submit="save" class="space-y-6">
                        <div class="bg-white rounded-xl border border-gray-100 p-6">
                            <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Keyword Details</h3>

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
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Group</label>
                                    <select wire:model="seoKeywordGroupId"
                                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                        <option value="">No group</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keyword *</label>
                                <input type="text" wire:model="keywordText"
                                       class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                       placeholder="e.g., car loan emi calculator">
                                @error('keywordText') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid gap-4 md:grid-cols-2 mt-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Search Intent *</label>
                                    <select wire:model="searchIntent"
                                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                        <option value="informational">Informational</option>
                                        <option value="transactional">Transactional</option>
                                        <option value="navigational">Navigational</option>
                                        <option value="commercial">Commercial</option>
                                    </select>
                                    @error('searchIntent') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status *</label>
                                    <select wire:model="status"
                                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-3 mt-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Country (ISO-2)</label>
                                    <input type="text" wire:model="country" maxlength="2"
                                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm uppercase focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                           placeholder="US">
                                    @error('country') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Language *</label>
                                    <input type="text" wire:model="language" maxlength="5"
                                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                           placeholder="en">
                                    @error('language') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Priority (1-5) *</label>
                                    <input type="number" wire:model="priority" min="1" max="5"
                                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                    @error('priority') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notes</label>
                                <textarea wire:model="notes" rows="2"
                                          class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                          placeholder="Optional notes..."></textarea>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex gap-3 pt-2">
                            <a href="{{ route('admin.seo.keywords.list') }}"
                               class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 font-semibold rounded-lg text-center hover:bg-gray-50 transition-colors text-sm">
                                Cancel
                            </a>
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2 text-sm">
                                <i class="bx text-sm" :class="$wire.loading ? 'bx-loader-alt animate-spin' : 'bx-save'"></i>
                                <span wire:loading.remove>
                                    {{ $isCreating ? 'Add Keyword' : 'Save Changes' }}
                                </span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Bulk CSV Import Panel --}}
                <div>
                    <div class="bg-white rounded-xl border border-gray-100 p-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-1.5">
                            <i class="bx bx-upload text-indigo-600"></i> Bulk Import (CSV)
                        </h3>
                        <p class="text-xs text-gray-500 mb-3">
                            Columns: <code class="bg-gray-100 px-1.5 py-0.5 rounded">keyword,search_intent,country,language,priority</code>.
                            The tool selected above is used for all imported rows.
                        </p>

                        <input type="file" wire:model="csvFile" accept=".csv,.txt"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3.5 py-2.5 mb-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                        @error('csvFile') <span class="text-red-600 text-xs block mb-2">{{ $message }}</span> @enderror

                        <button wire:click="importCsv" wire:loading.attr="disabled" wire:target="importCsv,csvFile"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                            <span wire:loading.remove wire:target="importCsv">Import CSV</span>
                            <span wire:loading wire:target="importCsv">Importing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
