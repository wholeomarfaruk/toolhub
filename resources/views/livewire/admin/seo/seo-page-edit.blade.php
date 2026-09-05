{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data x-init="$store.pageName = { name: @json($isCreating ? 'Create SEO Page' : 'Edit SEO Page'), slug: 'seo-pages-edit' }">
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
                        href="{{ route('admin.seo.pages.list') }}">
                        SEO Pages
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
                <h2 class="text-xl font-bold text-gray-900">{{ $isCreating ? 'Create SEO Page' : 'Edit SEO Page' }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Configure content, metadata, and AI generation for this landing page.</p>
            </div>

            <form wire:submit="save" class="space-y-6 max-w-4xl">

                {{-- Basics --}}
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Page Basics</h3>

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
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Slug (URL) *</label>
                            <input type="text" wire:model="slug"
                                   class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                   placeholder="e.g., car-loan-emi-calculator">
                            @error('slug') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 mt-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keyword</label>
                            <select wire:model.live="seoKeywordId"
                                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                <option value="">None</option>
                                @foreach($keywords as $kw)
                                    <option value="{{ $kw->id }}">{{ $kw->keyword }}</option>
                                @endforeach
                            </select>
                            @if($isCreating)
                                <p class="text-xs text-gray-400 mt-1.5">Selecting a keyword auto-fills the slug below.</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keyword Group</label>
                            <select wire:model="seoKeywordGroupId"
                                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                <option value="">None</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 mt-4">
                        <div class="flex items-center p-2.5 border border-gray-200 rounded-lg">
                            <select wire:model="status" class="w-full text-sm border-0 focus:ring-0">
                                <option value="draft">Draft</option>
                                <option value="ai_generated">AI Generated</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                        <div class="flex items-center p-2.5 border border-gray-200 rounded-lg">
                            <input type="checkbox" wire:model="isIndexable" id="isIndexable"
                                   class="w-4 h-4 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                            <label for="isIndexable" class="ml-2 text-sm font-medium text-gray-700">
                                Indexable (included in sitemap)
                            </label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center p-2.5 border border-gray-200 rounded-lg">
                            <input type="checkbox" wire:model="isPrimary" id="isPrimary"
                                   class="w-4 h-4 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                            <label for="isPrimary" class="ml-2 text-sm font-medium text-gray-700">
                                Primary page for this tool (used as the default SEO content on <code class="font-mono text-xs bg-gray-100 px-1 rounded">/tools/{tool}</code>)
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Meta / SEO --}}
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">SEO Meta</h3>

                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Meta Title</label>
                            <input type="text" wire:model="metaTitle"
                                   class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            @error('metaTitle') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Meta Description</label>
                            <textarea wire:model="metaDescription" rows="2"
                                      class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">H1</label>
                            <input type="text" wire:model="h1"
                                   class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Content</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Intro</label>
                            <textarea wire:model="intro" rows="2"
                                      class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Body Content (HTML)</label>
                            <textarea wire:model="content" rows="8"
                                      class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Variables --}}
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Variables</h3>
                    <div class="space-y-2">
                        @foreach($variableRows as $i => $row)
                            <div class="grid grid-cols-5 gap-2 items-center">
                                <input type="text" wire:model="variableRows.{{ $i }}.key" placeholder="key"
                                       class="col-span-2 px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400">
                                <input type="text" wire:model="variableRows.{{ $i }}.value" placeholder="value"
                                       class="col-span-2 px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400">
                                <button type="button" wire:click="removeVariableRow({{ $i }})"
                                        class="inline-flex items-center justify-center px-2.5 py-2.5 text-xs bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        @endforeach
                        <button type="button" wire:click="addVariableRow"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            <i class="bx bx-plus"></i> Add Variable
                        </button>
                    </div>
                </div>

                {{-- Tool Preset --}}
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Tool Preset (JSON)</h3>
                    <textarea wire:model="toolPresetJson" rows="4"
                              placeholder='{"principal": 500000, "annual_rate": 9.5, "tenure_months": 60}'
                              class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"></textarea>
                    @error('toolPresetJson') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- FAQs --}}
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">FAQs</h3>
                    <div class="space-y-3">
                        @foreach($faqRows as $i => $row)
                            <div class="grid grid-cols-1 gap-2 p-3 border border-gray-200 rounded-lg">
                                <div class="flex gap-2 items-start">
                                    <input type="text" wire:model="faqRows.{{ $i }}.question" placeholder="Question"
                                           class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400">
                                    <button type="button" wire:click="removeFaqRow({{ $i }})"
                                            class="inline-flex items-center justify-center px-2.5 py-2.5 text-xs bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                                <textarea wire:model="faqRows.{{ $i }}.answer" rows="2" placeholder="Answer"
                                          class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"></textarea>
                            </div>
                        @endforeach
                        <button type="button" wire:click="addFaqRow"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            <i class="bx bx-plus"></i> Add FAQ
                        </button>
                    </div>
                </div>

                {{-- Examples --}}
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Examples</h3>
                    <div class="space-y-3">
                        @foreach($exampleRows as $i => $row)
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-2 p-3 border border-gray-200 rounded-lg items-center">
                                <input type="text" wire:model="exampleRows.{{ $i }}.label" placeholder="Label"
                                       class="px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400">
                                <input type="text" wire:model="exampleRows.{{ $i }}.input" placeholder="Input"
                                       class="px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400">
                                <input type="text" wire:model="exampleRows.{{ $i }}.output" placeholder="Output"
                                       class="px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400">
                                <button type="button" wire:click="removeExampleRow({{ $i }})"
                                        class="inline-flex items-center justify-center px-2.5 py-2.5 text-xs bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors w-fit">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        @endforeach
                        <button type="button" wire:click="addExampleRow"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            <i class="bx bx-plus"></i> Add Example
                        </button>
                    </div>
                </div>

                @if($seoPage)
                    {{-- AI Generation Controls --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-1.5">
                            <i class="bx bx-bulb text-indigo-600"></i> AI Generation
                        </h3>
                        @if($activeProviders->isEmpty())
                            <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 flex items-center gap-1.5">
                                <i class="bx bx-error-circle"></i>
                                No AI providers configured — set one up in <a href="{{ route('admin.seo.ai-settings') }}" class="font-semibold underline">AI Settings</a>.
                            </p>
                        @else
                            <div class="grid gap-3 md:grid-cols-3 items-end">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">AI Provider</label>
                                    <select wire:model="aiProviderSlug" class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                        <option value="">Select provider…</option>
                                        @foreach($activeProviders as $provider)
                                            <option value="{{ $provider->slug }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Model (optional)</label>
                                    <input type="text" wire:model="aiModel"
                                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow placeholder:text-gray-400"
                                           placeholder="Leave blank for provider default">
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Form Actions --}}
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ route('admin.seo.pages.list') }}"
                       class="px-4 py-2.5 border border-gray-200 text-gray-700 font-semibold rounded-lg text-center hover:bg-gray-50 transition-colors text-sm">
                        Cancel
                    </a>

                    @if($seoPage)
                        <button type="button" wire:click="generateContent" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 disabled:opacity-60 text-white font-semibold rounded-lg shadow-sm transition-colors text-sm">
                            <i class="bx bx-bulb"></i> Generate AI Content
                        </button>

                        @if($seoPage->status !== 'published')
                            <button type="button" wire:click="publish" wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white font-semibold rounded-lg shadow-sm transition-colors text-sm">
                                <i class="bx bx-check-circle"></i> Publish
                            </button>
                        @endif
                    @endif

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2 text-sm">
                        <i class="bx text-sm" :class="$wire.loading ? 'bx-loader-alt animate-spin' : 'bx-save'"></i>
                        <span wire:loading.remove>
                            {{ $isCreating ? 'Create Page' : 'Save Changes' }}
                        </span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ======================== Content End Here ======================== --}}
    </div>
</div>
