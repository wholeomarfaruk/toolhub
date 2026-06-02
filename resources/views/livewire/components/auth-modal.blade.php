<div>
    {{-- Backdrop --}}
    @if($isOpen)
        <div class="fixed inset-0 bg-black/50 z-40 animate-fade-in"
             wire:click="closeModal()"></div>
    @endif

    {{-- Modal Container --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none transition-all duration-300"
         style="display: {{ $isOpen ? 'flex' : 'none' }};">

        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden pointer-events-auto transform transition-all duration-300 animate-scale-in"
             style="transform: {{ $isOpen ? 'scale(1)' : 'scale(0.95)' }}; opacity: {{ $isOpen ? '1' : '0' }};">

            {{-- Header with Gradient --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-6 text-white relative">
                <button wire:click="closeModal()"
                        class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors p-1">
                    <i class="bx bx-x text-2xl"></i>
                </button>
                <h2 class="text-2xl font-bold">{{ $toolName }}</h2>
                <p class="text-indigo-100 text-sm mt-1">Sign in to continue</p>
            </div>

            {{-- Tab Navigation --}}
            <div class="flex border-b border-gray-200">
                <button wire:click="switchTab('signin')"
                        class="flex-1 px-6 py-4 text-center font-semibold transition-all {{ $activeTab === 'signin' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                    Sign In
                </button>
                <button wire:click="switchTab('register')"
                        class="flex-1 px-6 py-4 text-center font-semibold transition-all {{ $activeTab === 'register' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                    Create Account
                </button>
            </div>

            {{-- Content --}}
            <div class="px-8 py-6">

                {{-- SIGN IN TAB --}}
                @if($activeTab === 'signin')
                    <form wire:submit="signIn" class="space-y-4">
                        {{-- Email Input --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email"
                                   wire:model="email"
                                   placeholder="you@example.com"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Input --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password"
                                   wire:model="password"
                                   placeholder="••••••••"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   wire:model="rememberMe"
                                   id="rememberMe"
                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="rememberMe" class="ml-2 text-sm text-gray-600">Remember me</label>
                            <a href="{{ route('password.request') }}" class="ml-auto text-sm text-indigo-600 hover:text-indigo-700">
                                Forgot password?
                            </a>
                        </div>

                        {{-- Sign In Button --}}
                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all active:scale-95 hover:shadow-lg hover:shadow-indigo-200">
                            Sign In
                        </button>
                    </form>

                {{-- REGISTER TAB --}}
                @else
                    <form wire:submit="register" class="space-y-4">
                        {{-- Name Input --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text"
                                   wire:model="name"
                                   placeholder="John Doe"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email Input --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email"
                                   wire:model="email"
                                   placeholder="you@example.com"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Input --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password"
                                   wire:model="password"
                                   placeholder="••••••••"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password"
                                   wire:model="passwordConfirm"
                                   placeholder="••••••••"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        {{-- Terms Agreement --}}
                        <div class="flex items-start">
                            <input type="checkbox"
                                   wire:model="agreeTerms"
                                   id="agreeTerms"
                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-0.5">
                            <label for="agreeTerms" class="ml-2 text-sm text-gray-600">
                                I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-700">Terms & Conditions</a>
                            </label>
                        </div>
                        @error('agreeTerms')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Create Account Button --}}
                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all active:scale-95 hover:shadow-lg hover:shadow-indigo-200">
                            Create Account
                        </button>
                    </form>
                @endif

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>

                {{-- Google OAuth Button --}}
                <a href="{{ route('google.redirect') }}"
                   class="w-full py-2.5 px-4 border border-gray-300 hover:border-indigo-300 rounded-lg font-medium text-gray-700 hover:bg-indigo-50 transition-all flex items-center justify-center gap-2 hover:shadow-md">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Google</span>
                </a>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 px-8 py-4 text-center text-sm text-gray-600 border-t border-gray-200">
                <button wire:click="closeModal()"
                        class="text-indigo-600 hover:text-indigo-700 font-semibold transition-colors">
                    Continue as Guest
                </button>
            </div>
        </div>
    </div>
</div>
