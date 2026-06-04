<div>
    @php
        if (empty($this->title)) {
            $this->title = 'Character Counter | Count Text Characters & Metrics';
            $this->description = 'Free online character counter. Count characters, characters without spaces, words, sentences, and paragraphs instantly.';
            $this->keywords = 'character counter, character count, text characters, count characters, text analysis, seo text tool';
            $this->og_title = 'Character Counter | Free Text Analyzer';
            $this->og_description = 'Count all characters in your text, including no-space counts and word counts, with instant results.';
            $this->og_url = route('tools.character-counter');
            $this->og_image = asset('images/og-character-counter.jpg');
            $this->canonical_url = route('tools.character-counter');
        }
    @endphp

    <livewire:components.auth-modal :is-open="$showAuthModal" :tool-name="$authModalToolName" />

    <div class="bg-gradient-to-br from-fuchsia-600 to-pink-600 text-white py-12">
        <div class="max-w-4xl mx-auto px-4">
            <x-breadcrumb :items="[
                ['name' => 'Home', 'url' => route('home'), 'icon' => 'bx bx-home-alt'],
                ['name' => 'Tools', 'url' => route('tools.index')],
                ['name' => 'Character Counter', 'url' => null],
            ]" :schema="true" />
            <h1 class="text-3xl font-bold mb-2">Character Counter</h1>
            <p class="text-pink-200">
                Count characters, characters without spaces, words, sentences, and paragraphs instantly.
            </p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-10">
        @if ($errors->has('limit'))
            <div class="mb-6 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
                <i class="bx bx-error-circle text-lg text-amber-500"></i>
                {{ $errors->first('limit') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-5">Paste or Type Text</h2>

                    <div class="space-y-4">
                        <textarea
                            wire:model="text"
                            placeholder="Paste your text here..."
                            rows="10"
                            maxlength="100000"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition resize-none"
                        ></textarea>

                        <div class="text-xs text-gray-500">
                            {{ strlen($text) }} / 100,000 characters
                        </div>

                        <div class="space-y-2">
                            <div class="flex gap-2">
                                <button
                                    wire:click="analyze"
                                    wire:loading.attr="disabled"
                                    class="flex-1 py-3 bg-pink-600 hover:bg-pink-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                                    <i class="bx bx-analyse"></i>
                                    Analyze
                                </button>
                                <button
                                    wire:click="clear"
                                    class="flex-1 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold rounded-xl transition-colors">
                                    Clear
                                </button>
                            </div>
                            @if ($hasExportFeature && $result)
                                <button
                                    wire:click="exportPdf"
                                    wire:loading.attr="disabled"
                                    class="w-full py-2 bg-green-600 hover:bg-green-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                                    <i class="bx bx-download"></i>
                                    Export as PDF
                                </button>
                            @elseif (!$hasExportFeature && $result)
                                <button
                                    disabled
                                    title="PDF export requires Pro plan"
                                    class="w-full py-2 bg-gray-300 text-gray-600 text-sm font-semibold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="bx bx-lock"></i>
                                    Export (Pro Plan Required)
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($result)
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Character Metrics</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-pink-50 rounded-lg p-4">
                                <div class="text-3xl font-bold text-pink-600">{{ $result['characters'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Characters</div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="text-3xl font-bold text-purple-600">{{ $result['characters_no_spaces'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Characters (No Spaces)</div>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="text-3xl font-bold text-blue-600">{{ $result['words'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Words</div>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="text-3xl font-bold text-yellow-600">{{ $result['sentences'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Sentences</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Additional Details</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4">
                                <div class="text-sm text-gray-600">Paragraphs</div>
                                <div class="text-2xl font-bold text-gray-900 mt-2">{{ $result['paragraphs'] }}</div>
                            </div>
                            <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-4">
                                <div class="text-sm text-gray-600">Reading Time (200 WPM)</div>
                                <div class="text-2xl font-bold text-pink-600 mt-2">{{ $result['reading_time_minutes'] }} min</div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
                                <div class="text-sm text-gray-600">Avg Word Length</div>
                                <div class="text-2xl font-bold text-purple-600 mt-2">{{ $result['avg_word_length'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
