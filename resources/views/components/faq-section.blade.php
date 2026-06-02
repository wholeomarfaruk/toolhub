@props(['faqs' => [], 'title' => 'Frequently Asked Questions', 'schema' => true])

@php
    use App\Helpers\SeoHelper;

    // Generate schema if enabled
    $faqSchema = $schema && !empty($faqs) ? SeoHelper::generateFaqSchema($faqs) : null;
@endphp

<section class="bg-gray-50 border-y border-gray-100 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Find answers to common questions about our tools and services.
            </p>
        </div>

        @if(!empty($faqs))
            <div class="space-y-4 max-w-3xl mx-auto">
                @foreach($faqs as $index => $faq)
                    <div class="group bg-white rounded-lg border border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all"
                         x-data="{ open: false }"
                         @click="open = !open">

                        {{-- FAQ Header (Clickable) --}}
                        <button class="w-full flex items-center justify-between px-6 py-4 text-left font-semibold text-gray-900 hover:text-indigo-600 transition-colors"
                                @click.stop="open = !open"
                                :aria-expanded="open">
                            <span>{{ $faq['question'] ?? '' }}</span>
                            <i class="bx bx-chevron-down transition-transform"
                               :class="{ 'rotate-180': open }"></i>
                        </button>

                        {{-- FAQ Content (Collapsed/Expanded) --}}
                        <div class="overflow-hidden transition-all duration-300"
                             :style="open ? 'max-height: 500px; opacity: 1;' : 'max-height: 0; opacity: 0;'">
                            <div class="px-6 pb-4 border-t border-gray-100 text-gray-600 text-sm leading-relaxed">
                                {!! $faq['answer'] ?? '' !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500">
                <p>No FAQs available yet.</p>
            </div>
        @endif
    </div>
</section>

{{-- FAQ Schema (JSON-LD) --}}
@if($faqSchema)
    <script type="application/ld+json">
        {!! $faqSchema !!}
    </script>
@endif
