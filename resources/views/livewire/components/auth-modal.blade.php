<div>
    {{-- Modal Backdrop --}}
    @if($isOpen)
        <div class="fixed inset-0 bg-black/50 z-40" @click="$wire.closeModal()"></div>
    @endif

    {{-- Modal --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: {{ $isOpen ? 'flex' : 'none' }};">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-rose-500 to-orange-500 px-6 py-8 text-white">
                <button
                    @click="$wire.closeModal()"
                    class="absolute top-4 right-4 text-white/80 hover:text-white transition">
                    <i class="bx bx-x text-2xl"></i>
                </button>
                <h2 class="text-2xl font-bold mb-2">Sign In Required</h2>
                <p class="text-rose-100">Please sign in to use {{ $toolName }}</p>
            </div>

            {{-- Body --}}
            <div class="px-6 py-8">
                <p class="text-gray-600 text-sm mb-6">
                    Create a free account or sign in to access all tools and features.
                </p>

                {{-- Sign In Button --}}
                <a href="{{ route('login') }}"
                   class="block w-full px-4 py-3 bg-gradient-to-r from-rose-500 to-orange-500 hover:from-rose-600 hover:to-orange-600 text-white font-semibold rounded-lg transition-all text-center mb-3">
                    <i class="bx bx-log-in align-middle mr-2"></i> Sign In
                </a>

                {{-- Register Button --}}
                <a href="{{ route('register') }}"
                   class="block w-full px-4 py-3 border-2 border-gray-300 hover:border-rose-500 text-gray-700 hover:text-rose-600 font-semibold rounded-lg transition-all text-center">
                    <i class="bx bx-user-plus align-middle mr-2"></i> Create Account
                </a>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 px-6 py-4 text-center text-sm text-gray-600 border-t border-gray-200">
                <button
                    @click="$wire.closeModal()"
                    class="text-rose-600 hover:text-rose-700 font-semibold">
                    Continue as Guest
                </button>
            </div>
        </div>
    </div>
</div>
