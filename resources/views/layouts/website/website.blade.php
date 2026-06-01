<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} — {{ config('app.name') }}</title>
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
