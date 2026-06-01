<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="/" class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</a>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Confirm your password</h2>
                <p class="text-sm text-gray-500 mb-6">This is a secure area. Please confirm your password to continue.</p>

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password"
                            required autofocus autocomplete="current-password"
                            class="w-full px-3 py-2 border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
