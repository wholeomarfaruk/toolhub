<div>
    {{-- Auth Modal Component --}}
    <livewire:components.auth-modal :is-open="$showAuthModal" :tool-name="$authModalToolName" />

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
                Calculate your precise age, days lived, next birthday, and more with just your date of birth.
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

                    {{-- Date of Birth Input with Flatpickr --}}
                    <div x-data="{ localDob: @entangle('dob').defer }">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Date of Birth
                        </label>
                        <div class="relative">
                            <input
                                x-ref="dobInput"
                                x-model="localDob"
                                @blur="if(localDob) @this.set('dob', localDob)"
                                id="dobPicker"
                                type="text"
                                placeholder="Click to select date"
                                class="w-full px-4 py-2.5 pl-10 border {{ $errors->has('dob') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition cursor-pointer"
                                autocomplete="off"
                                readonly>
                            <i class="bx bx-calendar absolute left-3 top-1/2 -translate-y-1/2 text-rose-500 pointer-events-none text-lg"></i>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="bx bx-info-circle align-middle"></i> Select a date between 1900 and today
                        </p>
                        @error('dob')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <style>
                        /* Flatpickr Dark Theme with Rose Accents */
                        .flatpickr-calendar {
                            background: white;
                            border: 1px solid #e5e7eb;
                            border-radius: 12px;
                            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                            font-family: inherit;
                        }

                        .flatpickr-calendar.open {
                            display: block;
                        }

                        .flatpickr-months {
                            background: linear-gradient(135deg, #fdf2f8 0%, #fed7aa 100%);
                            border-radius: 11px 11px 0 0;
                            padding: 12px;
                        }

                        .flatpickr-prev-month,
                        .flatpickr-next-month {
                            color: #7c3aed;
                            font-size: 16px;
                            padding: 8px 12px;
                        }

                        .flatpickr-prev-month:hover,
                        .flatpickr-next-month:hover {
                            background: rgba(124, 58, 237, 0.1);
                            border-radius: 8px;
                        }

                        .flatpickr-month {
                            color: #1f2937;
                            font-weight: 600;
                        }

                        .flatpickr-current-month {
                            font-size: 14px;
                            padding: 0;
                        }

                        .flatpickr-current-month select {
                            background: white;
                            border: 1px solid #d1d5db;
                            border-radius: 6px;
                            padding: 4px 8px;
                            font-size: 13px;
                            color: #374151;
                            cursor: pointer;
                        }

                        .flatpickr-current-month select:hover {
                            border-color: #9333ea;
                        }

                        .flatpickr-weekdays {
                            background: white;
                            padding: 8px;
                        }

                        .flatpickr-weekday {
                            color: #6b7280;
                            font-size: 12px;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 0.05em;
                        }

                        .flatpickr-days {
                            padding: 8px;
                        }

                        .flatpickr-day {
                            color: #374151;
                            border-radius: 8px;
                            font-size: 14px;
                            padding: 8px;
                            margin: 2px;
                            transition: all 0.2s;
                        }

                        .flatpickr-day:hover {
                            background: #f3e8ff;
                            border-color: transparent;
                            cursor: pointer;
                        }

                        .flatpickr-day.selected {
                            background: linear-gradient(135deg, #ec4899 0%, #f97316 100%);
                            border-color: transparent;
                            color: white;
                            font-weight: 600;
                        }

                        .flatpickr-day.selected:hover {
                            background: linear-gradient(135deg, #db2777 0%, #ea580c 100%);
                        }

                        .flatpickr-day.today {
                            border-color: #9333ea;
                            font-weight: 600;
                            color: #9333ea;
                        }

                        .flatpickr-day.inRange {
                            background: #fce7f3;
                            box-shadow: none;
                        }

                        .flatpickr-day.disabled,
                        .flatpickr-day.prevMonthDay,
                        .flatpickr-day.nextMonthDay {
                            color: #d1d5db;
                            background: white;
                        }

                        .flatpickr-day.disabled:hover {
                            cursor: not-allowed;
                            background: white;
                        }

                        .flatpickr-time {
                            border-top: 1px solid #e5e7eb;
                            padding: 10px;
                        }

                        .flatpickr-am-pm {
                            background: white;
                            border: 1px solid #d1d5db;
                            border-radius: 6px;
                            color: #374151;
                        }
                    </style>

                    <script>
                        function initDatePicker() {
                            const dobInput = document.getElementById('dobPicker');
                            if (!dobInput) return;

                            // Destroy existing instance if any
                            if (dobInput._flatpickr) {
                                dobInput._flatpickr.destroy();
                            }

                            // Initialize flatpickr
                            const instance = flatpickr(dobInput, {
                                mode: 'single',
                                maxDate: new Date(),
                                dateFormat: 'Y-m-d',
                                yearRange: [1900, new Date().getFullYear()],
                                monthSelectorType: 'dropdown',
                                showMonths: 1,
                                animateOnInit: true,
                                onClose: function(selectedDates, dateStr) {
                                    if (dateStr) {
                                        // Update the input value directly
                                        dobInput.value = dateStr;
                                        // Dispatch input event for Alpine
                                        dobInput.dispatchEvent(new Event('input', { bubbles: true }));
                                        // Update Livewire after a small delay
                                        setTimeout(() => {
                                            @this.set('dob', dateStr);
                                        }, 100);
                                    }
                                }
                            });

                            // Set initial value if exists
                            const currentValue = dobInput.value || @json($dob);
                            if (currentValue) {
                                instance.setDate(currentValue, false);
                            }
                        }

                        // Initialize on page load
                        document.addEventListener('DOMContentLoaded', initDatePicker);

                        // Reinitialize after Livewire morphs
                        if (typeof Livewire !== 'undefined') {
                            Livewire.hook('morph.updated', () => {
                                setTimeout(initDatePicker, 100);
                            });
                        }
                    </script>

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
                        <button
                            @click="
                                @this.call('exportAgeImage').then(() => {
                                    window.open('{{ route('age-card-image.download') }}', '_blank');
                                });
                            "
                            wire:loading.attr="disabled"
                            class="mt-4 w-full px-4 py-2 bg-gradient-to-r from-rose-500 to-orange-500 hover:from-rose-600 hover:to-orange-600 disabled:opacity-75 text-white font-medium text-sm rounded-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span wire:loading wire:target="exportAgeImage">
                                <i class="bx bx-loader-circle animate-spin text-lg"></i> Creating Image...
                            </span>
                            <span wire:loading.remove wire:target="exportAgeImage" class="flex items-center gap-2">
                                <i class="bx bx-image-add text-lg"></i> Save as Image
                            </span>
                        </button>
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
