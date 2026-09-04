<div>
    {{-- Hero --}}
    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white py-12">
        <div class="max-w-4xl mx-auto px-4">
            {{-- Breadcrumb --}}
            <x-breadcrumb :items="[
                ['name' => 'Home', 'url' => route('home'), 'icon' => 'bx bx-home-alt'],
                ['name' => 'Tools', 'url' => route('tools.index')],
                ['name' => $tool->name(), 'url' => route('tools.' . $tool->slug())],
                ['name' => $page->h1 ?: $page->slug, 'url' => null],
            ]" :schema="true" />
            <h1 class="text-3xl font-bold mb-2">{{ $page->h1 ?: $tool->name() }}</h1>
            @if($page->intro)
                <p class="text-indigo-200">{{ $page->intro }}</p>
            @endif
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10 space-y-10">

        {{-- Tool Widget --}}
        <div>
            @livewire($tool->livewireComponent(), ['toolPreset' => $page->tool_preset ?? []])
        </div>

        {{-- Body Content --}}
        @if($page->content)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! $page->content !!}
                </div>
            </div>
        @endif

        {{-- FAQs --}}
        @if(!empty($page->faqs))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <div class="space-y-4">
                    @foreach($page->faqs as $faq)
                        <div class="border-b border-gray-50 pb-4 last:border-b-0 last:pb-0">
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $faq['question'] ?? '' }}</h3>
                            <p class="text-sm text-gray-600">{{ $faq['answer'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Examples --}}
        @if(!empty($page->examples))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Examples</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                                <th class="px-4 py-2 text-left font-medium">Label</th>
                                <th class="px-4 py-2 text-left font-medium">Input</th>
                                <th class="px-4 py-2 text-left font-medium">Output</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($page->examples as $example)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $example['label'] ?? '' }}</td>
                                    <td class="px-4 py-2 text-gray-600">{{ $example['input'] ?? '' }}</td>
                                    <td class="px-4 py-2 text-gray-600">{{ $example['output'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Related Pages --}}
        @if($related->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Related Pages</h2>
                <div class="grid gap-3 md:grid-cols-2">
                    @foreach($related as $relatedPage)
                        <a href="{{ $relatedPage->url() }}"
                           class="block px-4 py-3 border border-gray-100 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                            <span class="text-sm font-medium text-indigo-700">
                                {{ $relatedPage->h1 ?: $relatedPage->slug }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
