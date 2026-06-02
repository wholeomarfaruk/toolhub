<div>
    @php
        // SEO Configuration for Slug Generator
        if (empty($this->title)) {
            $this->title = 'Slug Generator | Create SEO-Friendly URL Slugs';
            $this->description = 'Free online slug generator. Convert text to SEO-friendly URL slugs instantly. Support for separators, stop words removal, and Unicode characters.';
            $this->keywords = 'slug generator, URL slug creator, SEO slug, slug maker, permalink generator, url generator, slug converter';
            $this->og_title = 'Slug Generator | Free URL Slug Creator';
            $this->og_description = 'Create SEO-friendly URL slugs instantly from text with customization options.';
            $this->og_url = route('tools.slug-generator');
            $this->og_image = asset('images/og-slug-generator.jpg');
            $this->canonical_url = route('tools.slug-generator');
        }
    @endphp

    {{-- Auth Modal Component --}}
    <livewire:components.auth-modal :is-open="$showAuthModal" :tool-name="$authModalToolName" />

    {{-- Hero --}}
    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white py-12">
        <div class="max-w-4xl mx-auto px-4">
            {{-- Breadcrumb with Schema --}}
            <x-breadcrumb :items="[
                ['name' => 'Home', 'url' => route('home'), 'icon' => 'bx bx-home-alt'],
                ['name' => 'Tools', 'url' => route('tools.index')],
                ['name' => 'Slug Generator', 'url' => null],
            ]" :schema="true" />
            <h1 class="text-3xl font-bold mb-2">Slug Generator</h1>
            <p class="text-emerald-200">
                Convert text into SEO-friendly URL slugs instantly. Perfect for blog posts, articles, and web content.
            </p>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="max-w-6xl mx-auto px-4 py-10">

        {{-- Rate limit error --}}
        @if ($errors->has('limit'))
            <div class="mb-6 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
                <i class="bx bx-error-circle text-lg text-amber-500"></i>
                {{ $errors->first('limit') }}
            </div>
        @endif

        {{-- Bulk mode error --}}
        @if ($errors->has('bulk'))
            <div class="mb-6 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
                <i class="bx bx-error-circle text-lg text-amber-500"></i>
                {{ $errors->first('bulk') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── Input Card ───────────────────────────────────── --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-5">Convert Text to Slug</h2>

                    <div class="space-y-4">
                        {{-- Mode Tabs --}}
                        <div class="flex gap-2 mb-4">
                            <button
                                wire:click="$set('bulkMode', false)"
                                class="flex-1 py-2 text-sm font-semibold rounded-lg transition-colors {{ !$bulkMode ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                Single
                            </button>
                            @if ($hasBulkMode)
                                <button
                                    wire:click="$set('bulkMode', true)"
                                    class="flex-1 py-2 text-sm font-semibold rounded-lg transition-colors {{ $bulkMode ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    Bulk
                                </button>
                            @else
                                <button
                                    disabled
                                    title="Bulk mode requires Pro plan"
                                    class="flex-1 py-2 text-sm font-semibold rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                                    Bulk <i class="bx bx-lock-alt text-xs ml-1"></i>
                                </button>
                            @endif
                        </div>

                        {{-- Text Input --}}
                        @if (!$bulkMode)
                            <textarea
                                wire:model="text"
                                placeholder="Enter text to convert to slug..."
                                rows="6"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none"
                            ></textarea>
                        @else
                            <textarea
                                wire:model="bulkText"
                                placeholder="Enter one text per line (max 10 lines)..."
                                rows="8"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none font-mono text-xs"
                            ></textarea>
                        @endif

                        {{-- Separator --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">Separator</label>
                            @if ($hasCustomSeparator)
                                <div class="flex gap-2">
                                    @foreach (['-', '_', '.'] as $sep)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" wire:model="separator" value="{{ $sep }}" class="w-4 h-4">
                                            <span class="text-sm font-medium text-gray-700">{{ $sep === '-' ? 'Hyphen (-)' : ($sep === '_' ? 'Underscore (_)' : 'Dot (.)') }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex gap-2 opacity-50 pointer-events-none">
                                    <label class="flex items-center gap-2 cursor-not-allowed">
                                        <input type="radio" checked disabled class="w-4 h-4">
                                        <span class="text-sm font-medium text-gray-700">Hyphen (-)</span>
                                    </label>
                                    <span class="text-xs text-gray-500 ml-auto flex items-center gap-1"><i class="bx bx-lock-alt"></i>Pro</span>
                                </div>
                            @endif
                        </div>

                        {{-- Stop Words Toggle --}}
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                @if ($hasStopWords)
                                    <input type="checkbox" wire:model="useStopWords" class="w-5 h-5 rounded border-gray-300 text-emerald-600">
                                    <span class="text-sm font-medium text-gray-700">Remove Stop Words</span>
                                @else
                                    <input type="checkbox" disabled class="w-5 h-5 rounded border-gray-300 text-gray-300 opacity-50">
                                    <span class="text-sm font-medium text-gray-500">Remove Stop Words</span>
                                    <span class="text-xs text-gray-400 ml-auto flex items-center gap-1"><i class="bx bx-lock-alt"></i>Pro</span>
                                @endif
                            </label>
                            @if ($hasStopWords)
                                <p class="text-xs text-gray-500 mt-1">Removes common words like "the", "and", "is"</p>
                            @endif
                        </div>

                        {{-- Unicode Toggle --}}
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                @if ($hasUnicode)
                                    <input type="checkbox" wire:model="useUnicode" class="w-5 h-5 rounded border-gray-300 text-emerald-600">
                                    <span class="text-sm font-medium text-gray-700">Unicode Support</span>
                                @else
                                    <input type="checkbox" disabled class="w-5 h-5 rounded border-gray-300 text-gray-300 opacity-50">
                                    <span class="text-sm font-medium text-gray-500">Unicode Support</span>
                                    <span class="text-xs text-gray-400 ml-auto flex items-center gap-1"><i class="bx bx-lock-alt"></i>Enterprise</span>
                                @endif
                            </label>
                            @if ($hasUnicode)
                                <p class="text-xs text-gray-500 mt-1">Transliterates special characters</p>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 pt-2">
                            <button
                                wire:click="{{ $bulkMode ? 'generateBulk' : 'generate' }}"
                                wire:loading.attr="disabled"
                                class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                                <i class="bx bx-link"></i>
                                {{ $bulkMode ? 'Generate All' : 'Generate' }}
                            </button>
                            <button
                                wire:click="clear"
                                class="flex-1 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold rounded-xl transition-colors">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Results ───────────────────────────────────── --}}
            @if ($result)
                <div class="lg:col-span-2 space-y-6">

                    @if (!$bulkMode && isset($result['slug']))
                        {{-- Single Result --}}
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Generated Slug</h3>

                            {{-- Slug Display --}}
                            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 p-6 mb-4">
                                <div class="text-sm text-gray-600 mb-2">Your Slug</div>
                                <div class="flex items-center gap-3">
                                    <code class="flex-1 text-2xl font-bold text-emerald-600 break-all">{{ $result['slug'] }}</code>
                                    <button
                                        x-data="{ copying: false }"
                                        @click="async () => {
                                            await navigator.clipboard.writeText('{{ $result['slug'] }}');
                                            copying = true;
                                            $wire.copyToClipboard('{{ $result['slug'] }}');
                                            setTimeout(() => copying = false, 2000);
                                        }"
                                        :class="copying ? 'bg-emerald-500' : 'bg-emerald-600 hover:bg-emerald-700'"
                                        class="px-3 py-2 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-1 whitespace-nowrap">
                                        <i :class="copying ? 'bx bx-check' : 'bx bx-copy'"></i>
                                        <span x-text="copying ? 'Copied!' : 'Copy'"></span>
                                    </button>
                                </div>
                            </div>

                            {{-- Stats --}}
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="text-xs text-gray-600">Original Length</div>
                                    <div class="text-lg font-bold text-gray-900">{{ $result['char_count'] }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="text-xs text-gray-600">Slug Length</div>
                                    <div class="text-lg font-bold text-gray-900">{{ strlen($result['slug']) }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="text-xs text-gray-600">Separator</div>
                                    <div class="text-lg font-bold text-gray-900">{{ $result['separator'] }}</div>
                                </div>
                            </div>
                        </div>
                    @elseif ($bulkMode && isset($result['bulk']))
                        {{-- Bulk Results --}}
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Generated Slugs ({{ $result['count'] }})</h3>

                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @foreach ($result['bulk'] as $idx => $item)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="text-xs text-gray-600 mb-1">{{ $idx + 1 }}. Original</div>
                                        <div class="text-sm text-gray-900 mb-2 font-medium">{{ $item['original'] }}</div>
                                        <div class="flex items-center gap-2">
                                            <code class="flex-1 text-sm font-bold text-emerald-600 break-all">{{ $item['slug'] }}</code>
                                            <button
                                                x-data="{ copying: false }"
                                                @click="async () => {
                                                    await navigator.clipboard.writeText('{{ $item['slug'] }}');
                                                    copying = true;
                                                    $wire.copyToClipboard('{{ $item['slug'] }}');
                                                    setTimeout(() => copying = false, 2000);
                                                }"
                                                :class="copying ? 'bg-emerald-300' : 'bg-emerald-100 hover:bg-emerald-200'"
                                                class="px-2 py-1 text-emerald-700 text-xs font-semibold rounded transition-colors">
                                                <i :class="copying ? 'bx bx-check' : 'bx bx-copy'"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            @else
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border border-gray-200 border-dashed p-12 text-center">
                        <i class="bx bx-link text-4xl text-gray-400 block mb-4"></i>
                        <p class="text-gray-500 text-sm">
                            Enter text and click generate to create your SEO-friendly slug
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
