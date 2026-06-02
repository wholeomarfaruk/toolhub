<div>
    @php
        use App\Enums\ToolCategory;
        use App\Enums\PlanTier;

        // SEO Configuration
        if (empty($this->title)) {
            $this->title = 'All Free Online Tools | ' . config('app.name');
            $this->description = 'Browse our complete collection of free online tools. Calculators, generators, converters, and more. All free. No signup required.';
            $this->keywords = 'free online tools, calculator, generator, converter, utilities, free tools online';
            $this->og_title = 'All Free Online Tools | ' . config('app.name');
            $this->og_description = 'Complete collection of 50+ free online tools for instant results.';
            $this->og_url = route('tools.index');
            $this->canonical_url = route('tools.index');
        }
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-linear-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white py-16">
        {{-- Animated background elements --}}
        <div class="absolute inset-0 pointer-events-none opacity-20">
            <div class="absolute top-10 right-10 w-40 h-40 bg-white rounded-full animate-pulse-ring"></div>
            <div class="absolute bottom-10 left-20 w-32 h-32 bg-purple-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-indigo-300 rounded-full animate-bounce-infinity"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-3 animate-fade-in-up" style="animation-delay: 0.1s;">Free Online Tools</h1>
            <p class="text-indigo-200 text-lg max-w-xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
                A growing collection of calculators, generators, converters and formatters.
            </p>
        </div>
    </section>

    {{-- Tool Grid --}}
    <section class="max-w-6xl mx-auto px-4 py-14">

        @forelse ($grouped as $categorySlug => $tools)
            @php $category = ToolCategory::from($categorySlug); @endphp

            <div class="mb-12" data-scroll-animation="animate-fade-in-up">
                <div class="flex items-center gap-2 mb-6 animate-fade-in-up group" style="animation-delay: 0.3s;" data-hover-animation="jiggle">
                    <i class="{{ $category->icon() }} text-2xl text-indigo-600 group-hover:text-purple-600 group-hover:rotate-circle transition-all group-hover:animate-spin-slow"></i>
                    <h2 class="text-xl font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $category->label() }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-stagger="0.06">
                    @foreach ($tools as $tool)
                        <a href="{{ route('tools.' . $tool->slug()) }}"
                           class="group bg-white border border-gray-100 rounded-2xl p-5 hover:border-indigo-200 hover:shadow-md hover:-translate-y-1 transition-all duration-200 animate-fade-in-up"
                           data-stagger-item
                           style="animation-delay: {{ 0.4 + (array_search($tool, $tools) * 0.05) }}s;">

                            <div class="flex items-start justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center group-hover:bg-linear-to-br group-hover:from-indigo-500 group-hover:to-purple-600 transition-all group-hover:scale-125 group-hover:shadow-lg group-hover:shadow-indigo-300">
                                    <i class="{{ $tool->icon() }} text-xl text-indigo-600 group-hover:text-white group-hover:rotate-12 transition-all group-hover:animate-bounce-subtle"></i>
                                </div>
                                @if ($tool->requiredPlan() !== PlanTier::Free)
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $tool->requiredPlan()->badgeClass() }}">
                                        {{ $tool->requiredPlan()->label() }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">
                                {{ $tool->name() }}
                            </h3>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                {{ $tool->description() }}
                            </p>

                            <div class="mt-4 flex items-center text-indigo-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                                Use tool <i class="bx bx-right-arrow-alt ml-1 text-base"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 py-20">No tools registered yet.</p>
        @endforelse

    </section>
</div>
