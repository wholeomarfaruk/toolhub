<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="/" class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</a>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Forgot your password?</h2>
                <p class="text-sm text-gray-500 mb-6">Enter your email and we'll send you a reset link.</p>

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @session('status')
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            required autofocus autocomplete="username"
                            class="w-full px-3 py-2 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Send reset link
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Back to sign in</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
