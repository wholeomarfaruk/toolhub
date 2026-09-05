@props(['items' => [], 'schema' => true])

@php
    use App\Helpers\SeoHelper;

    // Default breadcrumbs: Home > Current page
    if (empty($items)) {
        $items = [
            ['name' => 'Home', 'url' => url('/'), 'icon' => 'bx bx-home-alt'],
            ['name' => request()->path(), 'url' => null, 'icon' => null],
        ];
    }

    // Generate schema if enabled
    $breadcrumbSchema = $schema ? SeoHelper::generateBreadcrumbSchema($items) : null;
@endphp

{{-- Breadcrumb Navigation --}}
<nav aria-label="Breadcrumb" class="flex items-center gap-2 flex-wrap text-sm text-white/80 mb-6">
    @foreach($items as $index => $item)
        @if($item['url'])
            <a href="{{ $item['url'] }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                @if(isset($item['icon']))
                    <i class="{{ $item['icon'] }} text-base"></i>
                @endif
                <span>{{ $item['name'] }}</span>
            </a>
        @else
            <span class="text-white flex items-center gap-1.5 font-medium">
                @if(isset($item['icon']))
                    <i class="{{ $item['icon'] }} text-base"></i>
                @endif
                <span>{{ $item['name'] }}</span>
            </span>
        @endif

        @if($index < count($items) - 1)
            <i class="bx bx-chevron-right text-white/50"></i>
        @endif
    @endforeach
</nav>

{{-- Breadcrumb Schema (JSON-LD) --}}
@if($breadcrumbSchema)
    <script type="application/ld+json">
        {!! $breadcrumbSchema !!}
    </script>
@endif
