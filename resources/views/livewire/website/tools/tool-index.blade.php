<div>
    @php
        use App\Enums\ToolCategory;
        use App\Enums\PlanTier;
    @endphp

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-3">Free Online Tools</h1>
            <p class="text-indigo-200 text-lg max-w-xl mx-auto">
                A growing collection of calculators, generators, converters and formatters.
            </p>
        </div>
    </section>

    {{-- Tool Grid --}}
    <section class="max-w-6xl mx-auto px-4 py-14">

        @forelse ($grouped as $categorySlug => $tools)
            @php $category = ToolCategory::from($categorySlug); @endphp

            <div class="mb-12">
                <div class="flex items-center gap-2 mb-6">
                    <i class="{{ $category->icon() }} text-2xl text-indigo-600"></i>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $category->label() }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($tools as $tool)
                        <a href="{{ route('tools.' . $tool->slug()) }}"
                           class="group bg-white border border-gray-100 rounded-2xl p-5 hover:border-indigo-200 hover:shadow-md transition-all duration-200">

                            <div class="flex items-start justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                                    <i class="{{ $tool->icon() }} text-xl text-indigo-600"></i>
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
