<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Essential Meta Tags --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? 'ToolsHub - Free online tools for calculators, generators, and converters. Instant results, no ads, no signup required.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'online tools, calculator, generator, converter, free tools' }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="theme-color" content="#4f46e5">

    {{-- Open Graph / Social Media --}}
    <meta property="og:type" content="{{ $og_type ?? 'website' }}">
    <meta property="og:url" content="{{ $og_url ?? url()->current() }}">
    <meta property="og:title" content="{{ $og_title ?? ($title ?? config('app.name')) }}">
    <meta property="og:description" content="{{ $og_description ?? ($description ?? 'Free online tools for calculators, generators, and converters.') }}">
    <meta property="og:image" content="{{ $og_image ?? asset('images/og-image.jpg') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $twitter_title ?? ($title ?? config('app.name')) }}">
    <meta name="twitter:description" content="{{ $twitter_description ?? ($description ?? 'Free online tools for instant results.') }}">
    <meta name="twitter:image" content="{{ $twitter_image ?? asset('images/og-image.jpg') }}">
    <meta name="twitter:creator" content="{{ config('app.twitter') ?? '@toolshub' }}">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $canonical_url ?? url()->current() }}">

    {{-- Alternate Links --}}
    @if(isset($alternate_links))
        @foreach($alternate_links as $lang => $url)
            <link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}">
        @endforeach
    @endif

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Preconnect to Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Structured Data (JSON-LD) --}}
    @php
        $websiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'description' => $description ?? 'Free online tools for calculators, generators, and converters.',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => config('app.url') . '/tools?search={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];

        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => asset('images/logo.png'),
            'description' => $description ?? 'Free online tools',
            'sameAs' => [
                config('app.twitter') ?? '',
                config('app.facebook') ?? ''
            ]
        ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode($websiteSchema) !!}
    </script>

    <script type="application/ld+json">
        {!! json_encode($organizationSchema) !!}
    </script>

    <title>{{ $title ?? config('app.name') }} — Online Tools</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200/50 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                {{-- Logo & Brand --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 font-bold text-2xl hover:opacity-80 transition-opacity">
                        <div class="w-10 h-10 rounded-xl bg-linear-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white">
                            <i class="bx bx-grid-alt text-lg"></i>
                        </div>
                        <span class="bg-linear-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            {{ config('app.name') }}
                        </span>
                    </a>

                    {{-- Nav Links (Desktop) --}}
                    <div class="hidden lg:flex items-center gap-1">
                        <a href="{{ route('tools.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                            All Tools
                        </a>
                        <button class="group relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors flex items-center gap-1">
                            Features <i class="bx bx-chevron-down text-base group-hover:rotate-180 transition-transform"></i>
                            {{-- Dropdown --}}
                            <div class="absolute left-0 top-full mt-0 w-48 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 py-2">
                                <a href="{{ route('tools.index') }}?category=calculator" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50">
                                    <span class="font-medium">Calculators</span>
                                    <p class="text-xs text-gray-400">EMI, tax & more</p>
                                </a>
                                <a href="{{ route('tools.index') }}?category=generator" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50">
                                    <span class="font-medium">Generators</span>
                                    <p class="text-xs text-gray-400">Invoices & quotes</p>
                                </a>
                                <a href="{{ route('tools.index') }}?category=converter" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50">
                                    <span class="font-medium">Converters</span>
                                    <p class="text-xs text-gray-400">Units & formats</p>
                                </a>
                            </div>
                        </button>
                        <a href="/#pricing"
                           class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                            Pricing
                        </a>
                    </div>
                    
                </div>
                {{-- Right Side (Auth & CTA) --}}
                <div class="flex items-center gap-3">
                    @auth
                        {{-- User Section --}}
                        <div class="hidden sm:flex items-center gap-3">
                            @php $planSlug = auth()->user()->activePlan()->slug; @endphp
                            @if($planSlug === 'free')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 border border-amber-200 text-xs font-medium text-amber-700 rounded-full">
                                    <i class="bx bx-star text-sm"></i>
                                    Free Plan
                                </span>
                            @elseif($planSlug === 'pro')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 border border-indigo-200 text-xs font-medium text-indigo-700 rounded-full">
                                    <i class="bx bx-crown text-sm"></i>
                                    Pro
                                </span>
                            @elseif($planSlug === 'enterprise')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 border border-purple-200 text-xs font-medium text-purple-700 rounded-full">
                                    <i class="bx bx-zap text-sm"></i>
                                    Enterprise
                                </span>
                            @endif
                            <a href="{{ auth()->user()->hasRole(['admin', 'superadmin']) ? route('admin.dashboard') : route('dashboard.overview') }}"
                               class="text-sm text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                                Dashboard
                            </a>
                        </div>

                        {{-- Sign Out --}}
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-sm px-4 py-2 text-gray-600 hover:text-indigo-600 border border-gray-200 hover:border-indigo-300 rounded-lg transition-all hover:bg-indigo-50 font-medium">
                                Sign out
                            </button>
                        </form>
                    @else
                        {{-- Guest Auth --}}
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline text-sm text-gray-600 hover:text-indigo-600 font-medium transition-colors">
                            Sign in
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-sm px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all shadow-sm hover:shadow-md">
                            Get started free
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-100 mt-20">
        <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <div class="flex gap-6 text-sm text-gray-400">
                <a href="{{ route('tools.index') }}" class="hover:text-indigo-600">Tools</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
