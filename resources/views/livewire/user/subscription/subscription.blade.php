<div class="max-w-3xl space-y-6">

    {{-- Current Plan --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Current Plan</p>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $plan->label() }}</h2>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $plan->badgeClass() }}">
                        Active
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    Access to {{ $toolCount }} {{ Str::plural('tool', $toolCount) }}
                </p>
            </div>
            @if ($plan->value === 'free')
                <div class="text-right">
                    <p class="text-xs text-gray-400 mb-1">Upgrade anytime</p>
                    <button disabled
                        class="px-4 py-2 bg-indigo-100 text-indigo-400 text-sm font-semibold rounded-xl cursor-not-allowed">
                        Coming Soon
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Plan Comparison --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50">
            <h3 class="font-semibold text-gray-900">Plan Comparison</h3>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide w-1/2">Feature</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide">Free</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-600 uppercase tracking-wide">Pro</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-amber-600 uppercase tracking-wide">Enterprise</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                    $features = [
                        ['name' => 'All Free Tools',          'free' => true,  'pro' => true,  'ent' => true],
                        ['name' => 'Daily Limit per Tool',    'free' => '50',  'pro' => '500', 'ent' => '∞'],
                        ['name' => 'Usage History',           'free' => true,  'pro' => true,  'ent' => true],
                        ['name' => 'Pro Tools Access',        'free' => false, 'pro' => true,  'ent' => true],
                        ['name' => 'Priority Support',        'free' => false, 'pro' => true,  'ent' => true],
                        ['name' => 'API Access',              'free' => false, 'pro' => false, 'ent' => true],
                        ['name' => 'Custom Integrations',     'free' => false, 'pro' => false, 'ent' => true],
                    ];
                @endphp

                @foreach ($features as $f)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-700">{{ $f['name'] }}</td>
                        @foreach (['free', 'pro', 'ent'] as $tier)
                            <td class="px-6 py-3 text-center">
                                @if ($f[$tier] === true)
                                    <i class="bx bx-check-circle text-emerald-500 text-lg"></i>
                                @elseif ($f[$tier] === false)
                                    <i class="bx bx-x-circle text-gray-200 text-lg"></i>
                                @else
                                    <span class="text-xs font-semibold text-gray-700">{{ $f[$tier] }}</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Coming Soon Banner --}}
    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 rounded-2xl p-6 text-center">
        <i class="bx bx-rocket text-4xl text-indigo-400 mb-3"></i>
        <h3 class="font-semibold text-gray-900 mb-1">Paid plans coming soon</h3>
        <p class="text-sm text-gray-500">
            Pro and Enterprise plans are in development. You'll be notified when they launch.
        </p>
    </div>

</div>
