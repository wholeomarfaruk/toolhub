<div>
    @php
        use App\Enums\ToolCategory;
        use App\Enums\PlanTier;

        // SEO Configuration
        if (empty($this->title)) {
            $this->title = 'Free Online Tools | ' . config('app.name');
            $this->description = 'Access 50+ free online tools including calculators, generators, converters and more. No signup required. No ads. Instant results.';
            $this->keywords = 'free tools, online calculator, generator, converter, invoice generator, age calculator, word counter, slug generator';
            $this->og_title = 'Free Online Tools | ' . config('app.name');
            $this->og_description = 'Discover 50+ free online tools for instant calculations, document generation, and more. No signup needed.';
            $this->og_url = route('home');
            $this->og_image = asset('images/og-home.jpg');
            $this->canonical_url = route('home');
        }
    @endphp

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- HERO                                                            --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-white">

        {{-- Background decoration with animations --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
            {{-- Floating blobs --}}
            <div class="absolute -top-32 -right-32 w-150 h-150 rounded-full bg-indigo-50 opacity-60 animate-float-slow"></div>

            {{-- Floating particles with orbit effect --}}
            <div class="absolute top-1/4 left-1/4 w-20 h-20" style="perspective: 1000px;">
                <div class="absolute top-10 left-1/3 w-3 h-3 rounded-full bg-indigo-400 opacity-40 animate-orbit" style="animation-delay: 0s;"></div>
                <div class="absolute top-24 right-1/4 w-2 h-2 rounded-full bg-purple-400 opacity-40 animate-orbit-slow" style="animation-delay: 0.5s;"></div>
                <div class="absolute bottom-10 left-1/4 w-4 h-4 rounded-full bg-indigo-300 opacity-30 animate-orbit-reverse" style="animation-delay: 1s;"></div>
            </div>

            {{-- Additional floating elements --}}
            <div class="absolute top-1/3 right-10 w-2 h-2 rounded-full bg-purple-300 opacity-30 animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 right-1/3 w-3 h-3 rounded-full bg-indigo-300 opacity-20 animate-bounce-infinity"></div>
            <div class="absolute top-2/3 left-10 w-2 h-2 rounded-full bg-purple-200 opacity-25 animate-sway"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 text-center">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold px-4 py-1.5 rounded-full mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                {{ count($tools) }}+ Free Tools Available
            </div>

            {{-- Headline --}}
            <h1 class="text-5xl sm:text-6xl font-extrabold text-gray-900 leading-tight tracking-tight mb-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                Every tool you need,<br>
                <span class="bg-linear-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    all in one place
                </span>
            </h1>

            <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-in-up" style="animation-delay: 0.3s;">
                ToolsHub gives you instant access to calculators, generators, converters and more —
                free, fast, and designed for professionals.
            </p>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.4s;">
                <a href="{{ route('tools.index') }}"
                   class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all shadow-sm shadow-indigo-200 text-sm hover:scale-105 hover:shadow-lg hover:shadow-indigo-300 active:scale-95">
                    Browse All Tools
                </a>
                @guest
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto px-8 py-3.5 bg-white border border-gray-200 hover:border-indigo-300 text-gray-700 hover:text-indigo-700 font-semibold rounded-xl transition-all text-sm hover:scale-105 hover:shadow-md active:scale-95">
                        Create Free Account
                    </a>
                @else
                    <a href="{{ auth()->user()->hasRole(['admin','superadmin']) ? route('admin.dashboard') : route('checkout.show', ['plan' => 'pro']) }}"
                       class="w-full sm:w-auto px-8 py-3.5 bg-white border border-gray-200 hover:border-indigo-300 text-gray-700 hover:text-indigo-700 font-semibold rounded-xl transition-all text-sm hover:scale-105 hover:shadow-md active:scale-95">
                        Go to Dashboard
                    </a>
                @endguest
            </div>

            {{-- Trust badges --}}
            <div class="mt-12 flex flex-wrap items-center justify-center gap-6 text-xs text-gray-400 animate-fade-in-up" style="animation-delay: 0.5s;">
                <span class="flex items-center gap-1.5"><i class="bx bx-check-circle text-emerald-500 text-base"></i> No credit card needed</span>
                <span class="flex items-center gap-1.5"><i class="bx bx-check-circle text-emerald-500 text-base"></i> Free tools, always</span>
                <span class="flex items-center gap-1.5"><i class="bx bx-check-circle text-emerald-500 text-base"></i> New tools added regularly</span>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- STATS BAR                                                       --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <section class="border-y border-gray-100 bg-gradient-to-r from-gray-50 via-white to-gray-50 relative overflow-hidden">
        {{-- Animated background elements --}}
        <div class="absolute inset-0 pointer-events-none opacity-30">
            <div class="absolute top-0 left-1/4 w-72 h-72 bg-indigo-100 rounded-full filter blur-3xl animate-float-slow" style="animation-delay: 0s;"></div>
            <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-purple-100 rounded-full filter blur-3xl animate-float-slow" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-4 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
            @php
                $stats = [
                    ['value' => count($tools) . '+', 'label' => 'Free Tools'],
                    ['value' => count($grouped) . '+', 'label' => 'Categories'],
                    ['value' => '100%',   'label' => 'Free to Use'],
                    ['value' => 'No Card', 'label' => 'Required'],
                ];
            @endphp
            @foreach ($stats as $i => $s)
                <div class="animate-fade-in-up group cursor-pointer" style="animation-delay: {{ 0.6 + ($i * 0.1) }}s;" data-scroll-animation="animate-zoom-in-out" data-scroll-delay="{{ 0.1 + ($i * 0.05) }}s">
                    <p class="text-3xl font-extrabold text-indigo-700 group-hover:scale-110 group-hover:text-purple-600 transition-all duration-300">{{ $s['value'] }}</p>
                    <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- TOOL SHOWCASE                                                   --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <div class="text-center mb-12">
            <p class="text-xs font-semibold text-indigo-500 uppercase tracking-widest mb-3 animate-fade-in-up" style="animation-delay: 0.7s;">What's inside</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 animate-fade-in-up" style="animation-delay: 0.8s;">Tools built for productivity</h2>
            <p class="text-gray-400 mt-3 max-w-lg mx-auto text-sm animate-fade-in-up" style="animation-delay: 0.9s;">
                Each tool is purpose-built — clean input, instant result, no clutter.
            </p>
        </div>

        {{-- Category tabs / tool grid --}}
        @foreach ($grouped as $categorySlug => $categoryTools)
            @php $category = ToolCategory::from($categorySlug); @endphp

            <div class="mb-12" data-scroll-animation="animate-fade-in-up">
                {{-- Category heading --}}
                <div class="flex items-center gap-3 mb-5 group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors group-hover:rotate-circle group-hover:animate-spin-slow">
                        <i class="{{ $category->icon() }} text-indigo-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $category->label() }}</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-all">
                        {{ count($categoryTools) }} {{ Str::plural('tool', count($categoryTools)) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" data-stagger="0.08">
                    @foreach ($categoryTools as $tool)
                        <a href="{{ route('tools.' . $tool->slug()) }}"
                           class="group relative bg-white border border-gray-100 rounded-2xl p-5
                                  hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-50 hover:-translate-y-1
                                  transition-all duration-200 animate-fade-in-up"
                           data-stagger-item
                           style="animation-delay: {{ 1 + (array_search($tool, $categoryTools) * 0.05) }}s;">

                            {{-- Plan badge --}}
                            @if ($tool->requiredPlan() !== PlanTier::Free)
                                <span class="absolute top-4 right-4 text-xs font-semibold px-2 py-0.5 rounded-full {{ $tool->requiredPlan()->badgeClass() }}">
                                    {{ $tool->requiredPlan()->label() }}
                                </span>
                            @endif

                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-11 h-11 rounded-xl bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center transition-all shrink-0 group-hover:scale-110 group-hover:rotate-6">
                                    <i class="{{ $tool->icon() }} text-xl text-indigo-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors">
                                        {{ $tool->name() }}
                                    </h4>
                                </div>
                            </div>

                            <p class="text-sm text-gray-500 leading-relaxed mb-4">
                                {{ $tool->description() }}
                            </p>

                            <div class="flex items-center text-indigo-600 text-xs font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                                Open tool <i class="bx bx-right-arrow-alt ml-1 text-sm"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="text-center mt-4">
            <a href="{{ route('tools.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                View all tools <i class="bx bx-right-arrow-alt text-base"></i>
            </a>
        </div>

    </section>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- FEATURES                                                        --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <section class="bg-gray-50 border-y border-gray-100 py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <p class="text-xs font-semibold text-indigo-500 uppercase tracking-widest mb-3 animate-fade-in-up" style="animation-delay: 0.1s;" data-scroll-animation="animate-fade-in-up">Why ToolsHub</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 animate-fade-in-up" style="animation-delay: 0.2s;" data-scroll-animation="animate-fade-in-up">Built different</h2>
            </div>

            @php
                $features = [
                    [
                        'icon'  => 'bx bx-zap',
                        'color' => 'bg-amber-50 text-amber-600',
                        'title' => 'Instant Results',
                        'desc'  => 'No page reloads. Results appear the moment you click — powered by Livewire reactive components.',
                    ],
                    [
                        'icon'  => 'bx bx-lock-open',
                        'color' => 'bg-emerald-50 text-emerald-600',
                        'title' => 'Free Core Tools',
                        'desc'  => 'The most useful tools are completely free, forever. No paywalls, no ads, no tricks.',
                    ],
                    [
                        'icon'  => 'bx bx-devices',
                        'color' => 'bg-indigo-50 text-indigo-600',
                        'title' => 'Works Everywhere',
                        'desc'  => 'Fully responsive on any screen. Use ToolsHub from your phone, tablet, or desktop.',
                    ],
                    [
                        'icon'  => 'bx bx-history',
                        'color' => 'bg-purple-50 text-purple-600',
                        'title' => 'Usage History',
                        'desc'  => 'Sign in once and your activity is tracked. Jump back to recent tools with one click.',
                    ],
                    [
                        'icon'  => 'bx bx-shield-check',
                        'color' => 'bg-teal-50 text-teal-600',
                        'title' => 'Private by Default',
                        'desc'  => 'Your inputs are processed server-side and never stored. We don\'t track your calculations.',
                    ],
                    [
                        'icon'  => 'bx bx-line-chart-down',
                        'color' => 'bg-rose-50 text-rose-600',
                        'title' => 'Growing Library',
                        'desc'  => 'New tools are added regularly. Suggest a tool and we\'ll prioritise it for the next release.',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-stagger="0.1">
                @foreach ($features as $i => $f)
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group cursor-pointer animate-fade-in-up"
                         data-stagger-item
                         style="animation-delay: {{ 0.3 + ($i * 0.05) }}s;"
                         data-scroll-animation="animate-fade-in-up"
                         data-scroll-delay="{{ 0.3 + ($i * 0.05) }}s">
                        <div class="w-11 h-11 rounded-xl {{ $f['color'] }} flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 group-hover:shadow-lg transition-all {{ str_contains($f['color'], 'text-amber') ? 'group-hover:animate-spin-slow' : 'group-hover:animate-bounce-subtle' }}">
                            <i class="{{ $f['icon'] }} text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $f['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed group-hover:text-gray-700 transition-colors">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- HOW IT WORKS                                                    --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold text-indigo-500 uppercase tracking-widest mb-3 animate-fade-in-up" style="animation-delay: 0.1s;" data-scroll-animation="animate-fade-in-up">Simple as it gets</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 animate-fade-in-up" style="animation-delay: 0.2s;" data-scroll-animation="animate-fade-in-up">How it works</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 relative" data-stagger="0.15">

            {{-- connecting line (desktop only) with animation --}}
            <div class="hidden sm:block absolute top-8 left-1/6 right-1/6 h-px bg-gradient-to-r from-transparent via-indigo-300 to-transparent"
                 style="animation: gradient-shift 3s ease infinite;"
                 aria-hidden="true"></div>

            @php
                $steps = [
                    ['step' => '1', 'icon' => 'bx bx-search', 'title' => 'Pick a tool', 'desc' => 'Browse by category or search for the exact tool you need.'],
                    ['step' => '2', 'icon' => 'bx bx-edit',   'title' => 'Enter your data', 'desc' => 'Fill in the inputs. Clean form, no distractions.'],
                    ['step' => '3', 'icon' => 'bx bx-download','title' => 'Get your result', 'desc' => 'Instant output. Copy, download, or share as needed.'],
                ];
            @endphp

            @foreach ($steps as $i => $s)
                <div class="relative flex flex-col items-center text-center group hover:scale-105 transition-transform duration-300 animate-fade-in-up"
                     data-stagger-item
                     style="animation-delay: {{ 0.3 + ($i * 0.1) }}s;"
                     data-scroll-animation="animate-fade-in-up"
                     data-scroll-delay="{{ 0.3 + ($i * 0.1) }}s">
                    <div class="relative w-16 h-16 rounded-2xl bg-linear-to-br from-indigo-600 to-purple-600 flex items-center justify-center mb-5 shadow-lg shadow-indigo-200 group-hover:shadow-2xl group-hover:shadow-indigo-400 group-hover:scale-110 transition-all {{ $i === 1 ? 'animate-pulse-ring' : 'animate-bounce-subtle' }}">
                        <i class="{{ $s['icon'] }} text-2xl text-white group-hover:animate-bounce-infinity"></i>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white border-2 border-indigo-600 text-indigo-700 text-xs font-extrabold flex items-center justify-center group-hover:scale-125 transition-transform">
                            {{ $s['step'] }}
                        </span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $s['title'] }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-55 group-hover:text-gray-700 transition-colors">{{ $s['desc'] }}</p>
                </div>
            @endforeach
        </div>

    </section>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- PRICING                                                         --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <section id="pricing" class="bg-gray-50 border-y border-gray-100 py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @php
                use App\Models\Plan;
                $userPlanSlug = auth()->check() ? auth()->user()->activePlan()->slug : null;
                $freePlan = Plan::where('slug', 'free')->first();
                $proPlan = Plan::where('slug', 'pro')->first();
                $enterprisePlan = Plan::where('slug', 'enterprise')->first();
            @endphp

            <div class="text-center mb-14">
                <p class="text-xs font-semibold text-indigo-500 uppercase tracking-widest mb-3 animate-fade-in-up" style="animation-delay: 0.1s;" data-scroll-animation="animate-fade-in-up">Pricing</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3 animate-fade-in-up" style="animation-delay: 0.2s;" data-scroll-animation="animate-fade-in-up">Simple, transparent pricing</h2>
                <p class="text-gray-500 max-w-lg mx-auto animate-fade-in-up" style="animation-delay: 0.3s;" data-scroll-animation="animate-fade-in-up">Choose the plan that fits your needs. All plans include core features.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-stagger="0.12">

                {{-- FREE PLAN --}}
                <div class="relative bg-white rounded-2xl border border-gray-200 p-8 hover:border-gray-300 hover:shadow-lg transition-all animate-fade-in-up group"
                     data-stagger-item
                     style="animation-delay: 0.4s;"
                     data-scroll-animation="animate-fade-in-up"
                     data-scroll-delay="0.4s">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Free</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-gray-900">$0</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Perfect for trying out tools</p>
                    </div>

                    <button class="w-full py-3 px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors mb-8">
                        Current Plan
                    </button>

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>5 PDF exports/month</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>All basic tools</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>2 templates for invoices</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>3 tools per day limit</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>Free plan watermark</span>
                        </div>
                    </div>
                </div>

                {{-- PRO PLAN --}}
                <div class="relative bg-white rounded-2xl border-2 border-indigo-500 p-8 shadow-lg hover:shadow-xl transition-all scale-105">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span class="bg-indigo-600 text-white text-xs font-bold px-4 py-1.5 rounded-full">MOST POPULAR</span>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $proPlan->name ?? 'Pro' }}</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-gray-900">{{ $proPlan->priceMonthlyFormatted() ?? '$2.99' }}</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">{{ $proPlan->description ?? 'Best for professionals' }}</p>
                    </div>

                    @auth
                        @if($userPlanSlug === 'pro')
                            <button class="w-full py-3 px-6 bg-indigo-100 text-indigo-700 font-semibold rounded-xl transition-colors mb-8">
                                Current Plan
                            </button>
                        @else
                            <a href="{{ route('checkout.show', ['plan' => 'pro']) }}"
                               class="block w-full py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors mb-8 text-center">
                                Upgrade Now
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}"
                           class="block w-full py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors mb-8 text-center">
                            Get Started
                        </a>
                    @endauth

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>250 PDF exports/month</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>All tools included</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>All 3 invoice templates</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>20 tools per day limit</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>No watermark</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>Priority support</span>
                        </div>
                    </div>
                </div>

                {{-- ENTERPRISE PLAN --}}
                <div class="relative bg-white rounded-2xl border border-gray-200 p-8 hover:border-gray-300 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $enterprisePlan->name ?? 'Enterprise' }}</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-gray-900">{{ $enterprisePlan->priceMonthlyFormatted() ?? '$7.99' }}</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">{{ $enterprisePlan->description ?? 'For power users & teams' }}</p>
                    </div>

                    @auth
                        @if($userPlanSlug === 'enterprise')
                            <button class="w-full py-3 px-6 bg-purple-100 text-purple-700 font-semibold rounded-xl transition-colors mb-8">
                                Current Plan
                            </button>
                        @else
                            <a href="{{ route('checkout.show', ['plan' => 'enterprise']) }}"
                               class="block w-full py-3 px-6 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors mb-8 text-center">
                                Upgrade Now
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}"
                           class="block w-full py-3 px-6 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors mb-8 text-center">
                            Get Started
                        </a>
                    @endauth

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>Unlimited PDF exports</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>All tools + beta features</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>All templates unlimited</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>Unlimited daily usage</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>API access</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bx bx-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                            <span>24/7 priority support</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-12">
                <p class="text-gray-500 text-sm">All prices in USD. Billed monthly. Cancel anytime.</p>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- CTA BANNER                                                      --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <section class="pb-20 px-4">
        <div class="max-w-4xl mx-auto bg-linear-to-br from-indigo-600 to-purple-700 rounded-3xl px-8 py-14 text-center relative overflow-hidden">

            {{-- Decoration --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2" aria-hidden="true"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2" aria-hidden="true"></div>

            <div class="relative">
                <p class="text-indigo-200 text-sm font-semibold uppercase tracking-widest mb-4">Start for free</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Ready to save hours every week?
                </h2>
                <p class="text-indigo-200 text-base mb-8 max-w-lg mx-auto">
                    Create your free account and get instant access to every tool,
                    usage history, and more. No credit card required.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}"
                           class="px-8 py-3.5 bg-white text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 transition-colors text-sm shadow-lg">
                            Create Free Account
                        </a>
                        <a href="{{ route('tools.index') }}"
                           class="px-8 py-3.5 border border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-colors text-sm">
                            Explore Tools First
                        </a>
                    @else
                        <a href="{{ route('tools.index') }}"
                           class="px-8 py-3.5 bg-white text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 transition-colors text-sm shadow-lg">
                            Browse All Tools
                        </a>
                        <a href="{{ auth()->user()->hasRole(['admin','superadmin']) ? route('admin.dashboard') : route('checkout.show', ['plan' => 'pro']) }}"
                           class="px-8 py-3.5 border border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-colors text-sm">
                            Go to Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

</div>
