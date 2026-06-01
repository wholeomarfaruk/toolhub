<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="/" class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</a>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Verify your email</h2>
                <p class="text-sm text-gray-500 mb-6">
                    Before continuing, please verify your email address by clicking the link we sent you. Didn't receive it? We can send another.
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                <div class="flex items-center justify-between gap-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Resend verification email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
