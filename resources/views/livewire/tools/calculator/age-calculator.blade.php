<div>
    {{-- Hero --}}
    <div class="bg-gradient-to-br from-rose-600 to-orange-700 text-white py-12">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex items-center gap-2 text-rose-200 text-sm mb-3">
                <a href="{{ route('tools.index') }}" class="hover:text-white transition-colors">Tools</a>
                <i class="bx bx-chevron-right"></i>
                <span>Age Calculator</span>
            </div>
            <h1 class="text-3xl font-bold mb-2">Age Calculator</h1>
            <p class="text-rose-200">
                Calculate your precise age, days lived, next birthday, zodiac sign, and more with just your date of birth.
            </p>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="max-w-4xl mx-auto px-4 py-10">

        {{-- Rate limit error --}}
        @if ($errors->has('limit'))
            <div class="mb-6 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
                <i class="bx bx-error-circle text-lg text-amber-500"></i>
                {{ $errors->first('limit') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ── Input Card ───────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-5">Your Date of Birth</h2>

                <div class="space-y-5">

                    {{-- Date of Birth Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Date of Birth
                        </label>
                        <input
                            wire:model="dob"
                            type="date"
                            max="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2.5 border {{ $errors->has('dob') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition">
                        @error('dob')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3 pt-2">
                        <button
                            wire:click="calculate"
                            class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-medium text-sm rounded-xl transition-all active:scale-95">
                            Calculate Age
                        </button>
                        <button
                            wire:click="clear"
                            class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium text-sm rounded-xl transition-all">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Results Card ───────────────────────────────────── --}}
            @if ($result)
                <div class="space-y-4">

                    {{-- Age Display --}}
                    <div class="bg-gradient-to-br from-rose-50 to-orange-50 rounded-2xl border border-rose-200 shadow-sm p-6">
                        <p class="text-xs font-semibold text-rose-600 uppercase tracking-wide mb-2">Your Current Age</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $result['years'] }} <span class="text-lg text-gray-600">years</span>
                        </p>
                        <p class="text-base text-gray-700 mt-2">
                            {{ $result['months'] }} months, {{ $result['days'] }} days
                        </p>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Total Days</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($result['total_days']) }}</p>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Total Weeks</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($result['total_weeks']) }}</p>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Total Hours</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($result['total_hours']) }}</p>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Total Months</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($result['total_months']) }}</p>
                        </div>
                    </div>

                    {{-- Next Birthday --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Next Birthday</p>
                        <p class="text-lg font-bold text-gray-900">{{ $result['next_birthday_fmt'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            @if ($result['is_birthday_today'])
                                🎉 Happy Birthday today! 🎉
                            @else
                                {{ $result['days_until_next'] }} days left
                            @endif
                        </p>
                        @if (!$result['is_birthday_today'])
                            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-rose-500 h-2 rounded-full transition-all" style="width: {{ min(100, (365 - $result['days_until_next']) / 365 * 100) }}%"></div>
                            </div>
                        @endif
                    </div>

                    {{-- Birthday Info --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Birthday Information</p>
                        <div class="space-y-2 text-sm">
                            <p class="text-gray-700">
                                <span class="font-medium text-gray-900">Born:</span> {{ $result['dob_formatted'] }} ({{ $result['dob_weekday'] }})
                            </p>
                            <p class="text-gray-700">
                                <span class="font-medium text-gray-900">Next:</span> {{ $result['next_bday_weekday'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Zodiac Sign --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Zodiac Sign</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $result['zodiac_emoji'] }} {{ $result['zodiac_sign'] }}
                        </p>
                    </div>

                    {{-- PDF Export Button --}}
                    <div>
                        @if ($errors->has('export'))
                            <div class="mb-3 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-xs px-3 py-2 rounded-lg">
                                <i class="bx bx-error-circle"></i>
                                {{ $errors->first('export') }}
                            </div>
                        @endif

                        <button
                            wire:click="exportPdf"
                            @disabled(!$hasExportFeature)
                            @class([
                                'w-full px-4 py-2.5 rounded-xl font-medium text-sm transition-all flex items-center justify-center gap-2',
                                'bg-rose-600 hover:bg-rose-700 text-white active:scale-95' => $hasExportFeature,
                                'bg-gray-100 text-gray-400 cursor-not-allowed' => !$hasExportFeature,
                            ])>
                            @if (!$hasExportFeature)
                                <i class="bx bx-lock text-lg"></i>
                            @else
                                <i class="bx bx-download text-lg"></i>
                            @endif
                            {{ $hasExportFeature ? 'Download PDF Report' : 'PDF Export (Pro+)' }}
                        </button>

                        @if (!$hasExportFeature)
                            <p class="mt-2 text-xs text-gray-500 text-center">
                                Available on Pro and Enterprise plans
                            </p>
                        @endif
                    </div>

                </div>
            @else
                <div class="bg-gray-50 rounded-2xl border border-gray-200 border-dashed p-8 flex items-center justify-center min-h-96">
                    <div class="text-center">
                        <i class="bx bx-calendar-event text-5xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500 font-medium">Enter your date of birth to see your age details</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
