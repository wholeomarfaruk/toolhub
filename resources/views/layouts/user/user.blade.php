<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

<div class="flex h-screen overflow-hidden">

    {{-- ── Sidebar ─────────────────────────────────────────────────────── --}}
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col flex-shrink-0 z-20">

        {{-- Logo --}}
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-indigo-600">
                <i class="bx bx-grid-alt text-2xl"></i>
                {{ config('app.name') }}
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

            @php
                $nav = [
                    ['route' => 'dashboard.overview',      'label' => 'Dashboard',      'icon' => 'bx bx-home-circle'],
                    ['route' => 'tools.index',             'label' => 'All Tools',      'icon' => 'bx bx-grid-alt'],
                    ['route' => 'dashboard.history',       'label' => 'Usage History',  'icon' => 'bx bx-history'],
                    ['route' => 'dashboard.subscription',  'label' => 'Subscription',   'icon' => 'bx bx-credit-card'],
                    ['route' => 'dashboard.profile',       'label' => 'Profile',        'icon' => 'bx bx-user-circle'],
                ];
            @endphp

            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ Route::currentRouteName() === $item['route']
                             ? 'bg-indigo-50 text-indigo-700'
                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="{{ $item['icon'] }} text-lg {{ Route::currentRouteName() === $item['route'] ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach

            {{-- Admin Panel link (only for admins) --}}
            @if (auth()->user()->hasRole(['admin', 'superadmin']))
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</p>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors">
                        <i class="bx bx-shield text-lg text-amber-500"></i>
                        Admin Panel
                    </a>
                </div>
            @endif

        </nav>

        {{-- User Card --}}
        <div class="border-t border-gray-100 p-3">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-colors text-left">
                    <img src="{{ auth()->user()->profile_photo_path
                            ? file_path(auth()->user()->profile_photo_path)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff&bold=true' }}"
                         alt="Avatar"
                         class="w-9 h-9 rounded-full object-cover border border-gray-200 flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <i class="bx bx-chevron-up text-gray-400 text-lg transition-transform"
                       :class="open ? '' : 'rotate-180'"></i>
                </button>

                {{-- Dropdown --}}
                <div x-cloak x-show="open" @click.outside="open = false" x-transition
                     class="absolute bottom-full left-0 right-0 mb-1 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden">
                    <a href="{{ route('dashboard.profile') }}"
                       class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="bx bx-user text-gray-400"></i> My Profile
                    </a>
                    <a href="{{ route('dashboard.subscription') }}"
                       class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="bx bx-credit-card text-gray-400"></i> Subscription
                    </a>
                    <div class="border-t border-gray-50"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 text-left">
                            <i class="bx bx-log-out text-red-400"></i> Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </aside>

    {{-- ── Main Column ─────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Header --}}
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 flex-shrink-0">
            <h1 class="text-base font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>

            <div class="flex items-center gap-3">
                {{-- Plan badge --}}
                @php
                    $plan = app(\App\Services\SubscriptionService::class)->planFor(auth()->user());
                @endphp
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $plan->badgeClass() }}">
                    {{ $plan->label() }} Plan
                </span>

                <a href="{{ route('tools.index') }}"
                   class="text-sm px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors font-medium">
                    Use Tools
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>

    </div>

</div>

@livewireScripts
</body>
</html>
