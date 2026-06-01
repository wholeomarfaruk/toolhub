<div>
    {{-- Hero --}}
    <div class="bg-gradient-to-br from-blue-600 to-cyan-700 text-white py-12">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex items-center gap-2 text-blue-200 text-sm mb-3">
                <a href="{{ route('tools.index') }}" class="hover:text-white transition-colors">Tools</a>
                <i class="bx bx-chevron-right"></i>
                <span>Word Counter</span>
            </div>
            <h1 class="text-3xl font-bold mb-2">Word Counter</h1>
            <p class="text-blue-200">
                Analyze your text instantly. Get word count, character count, reading time, and detailed metrics.
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── Input Card ───────────────────────────────────── --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-5">Paste or Type Text</h2>

                    <div class="space-y-4">
                        <textarea
                            wire:model="text"
                            placeholder="Paste your text here..."
                            rows="10"
                            maxlength="100000"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        ></textarea>

                        <div class="text-xs text-gray-500">
                            {{ strlen($text) }} / 100,000 characters
                        </div>

                        <div class="space-y-2">
                            <div class="flex gap-2">
                                <button
                                    wire:click="analyze"
                                    wire:loading.attr="disabled"
                                    class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
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

            {{-- ── Results ───────────────────────────────────── --}}
            @if ($result)
                <div class="lg:col-span-2 space-y-6">

                    {{-- Basic Metrics --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Basic Metrics</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-600">{{ $result['words'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Words</div>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4">
                                <div class="text-2xl font-bold text-green-600">{{ $result['characters'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Characters</div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
                                <div class="text-2xl font-bold text-purple-600">{{ $result['characters_no_spaces'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">No Spaces</div>
                            </div>
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4">
                                <div class="text-2xl font-bold text-yellow-600">{{ $result['sentences'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Sentences</div>
                            </div>
                            <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-4">
                                <div class="text-2xl font-bold text-pink-600">{{ $result['paragraphs'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Paragraphs</div>
                            </div>
                            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4">
                                <div class="text-2xl font-bold text-red-600">{{ $result['reading_time_minutes'] }}</div>
                                <div class="text-xs text-gray-600 mt-1">Min Read</div>
                            </div>
                        </div>
                    </div>

                    {{-- Time & Averages --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Time Estimates</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Reading Time (200 WPM)</span>
                                    <span class="font-semibold text-gray-900">{{ $result['reading_time_minutes'] }} min</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Speaking Time (130 WPM)</span>
                                    <span class="font-semibold text-gray-900">{{ $result['speaking_time_minutes'] }} min</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Averages</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Avg Word Length</span>
                                    <span class="font-semibold text-gray-900">{{ $result['avg_word_length'] }} chars</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Avg Sentence Length</span>
                                    <span class="font-semibold text-gray-900">{{ $result['avg_sentence_length'] }} words</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Readability Analysis --}}
                    @if ($result['readability_score'])
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Readability Analysis</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4">
                                    <div class="text-sm text-gray-600">Flesch Reading Ease</div>
                                    <div class="text-2xl font-bold text-indigo-600 mt-2">{{ $result['readability_score']['flesch_reading_ease'] }}</div>
                                    <div class="text-xs text-gray-600 mt-1">{{ $result['readability_score']['difficulty'] }}</div>
                                </div>
                                <div class="bg-gradient-to-br from-violet-50 to-violet-100 rounded-lg p-4">
                                    <div class="text-sm text-gray-600">Flesch-Kincaid Grade</div>
                                    <div class="text-2xl font-bold text-violet-600 mt-2">{{ $result['readability_score']['flesch_kincaid_grade'] }}</div>
                                    <div class="text-xs text-gray-600 mt-1">Grade Level</div>
                                </div>
                                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4">
                                    <div class="text-sm text-gray-600">Difficulty</div>
                                    <div class="text-lg font-bold text-orange-600 mt-2">{{ $result['readability_score']['difficulty'] }}</div>
                                    <div class="text-xs text-gray-600 mt-1">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Top Keywords --}}
                    @if ($result['top_keywords'] && count($result['top_keywords']) > 0)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Top Keywords</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($result['top_keywords'] as $keyword)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ ucfirst($keyword) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Keyword Density --}}
                    @if ($result['keyword_density'] && count($result['keyword_density']) > 0)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Keyword Density</h3>
                            <div class="space-y-3">
                                @foreach ($result['keyword_density'] as $keyword => $data)
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ ucfirst($keyword) }}</div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                                            </div>
                                        </div>
                                        <div class="ml-4 text-right">
                                            <div class="text-sm font-semibold text-gray-900">{{ $data['percentage'] }}%</div>
                                            <div class="text-xs text-gray-500">{{ $data['count'] }}x</div>
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
                        <i class="bx bx-file text-4xl text-gray-400 block mb-4"></i>
                        <p class="text-gray-500 text-sm">
                            Paste or type your text to analyze it
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
