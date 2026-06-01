<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="/" class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</a>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-8" x-data="{ recovery: false }">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Two-factor authentication</h2>

                <p class="text-sm text-gray-500 mb-6" x-show="!recovery">
                    Enter the code from your authenticator app.
                </p>
                <p class="text-sm text-gray-500 mb-6" x-show="recovery" x-cloak>
                    Enter one of your emergency recovery codes.
                </p>

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-5">
                    @csrf

                    <div x-show="!recovery">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Authentication Code</label>
                        <input id="code" type="text" inputmode="numeric" name="code"
                            autofocus x-ref="code" autocomplete="one-time-code"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div x-show="recovery" x-cloak>
                        <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-1">Recovery Code</label>
                        <input id="recovery_code" type="text" name="recovery_code"
                            x-ref="recovery_code" autocomplete="one-time-code"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button" class="text-sm text-indigo-600 hover:text-indigo-800 underline"
                            x-show="!recovery"
                            x-on:click="recovery = true; $nextTick(() => $refs.recovery_code.focus())">
                            Use a recovery code
                        </button>
                        <button type="button" class="text-sm text-indigo-600 hover:text-indigo-800 underline"
                            x-show="recovery" x-cloak
                            x-on:click="recovery = false; $nextTick(() => $refs.code.focus())">
                            Use authenticator code
                        </button>

                        <button type="submit"
                            class="py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Verify
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
