<div class="max-w-2xl space-y-6">

    {{-- Profile Info --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-5">Personal Information</h2>

        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-50 border border-green-100 text-green-700 text-sm rounded-xl">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="updateProfile" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                <input wire:model="name" type="text"
                    class="w-full px-4 py-2.5 border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input wire:model="email" type="email"
                    class="w-full px-4 py-2.5 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="pt-1">
                <button type="submit"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors disabled:opacity-60">
                    <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                    <span wire:loading wire:target="updateProfile">Saving…</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-5">Change Password</h2>

        <form wire:submit="updatePassword" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                <input wire:model="current_password" type="password"
                    class="w-full px-4 py-2.5 border {{ $errors->has('current_password') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('current_password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                <input wire:model="password" type="password"
                    class="w-full px-4 py-2.5 border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                <input wire:model="password_confirmation" type="password"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="pt-1">
                <button type="submit"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-xl transition-colors disabled:opacity-60">
                    <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                    <span wire:loading wire:target="updatePassword">Updating…</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Connected Accounts --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-5">Connected Accounts</h2>

        {{-- Google OAuth Status --}}
        <div class="flex items-center justify-between p-4 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-100">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Google Account</h3>
                    @if ($googleConnected)
                        <p class="text-xs text-green-600 flex items-center gap-1 mt-1">
                            <i class="bx bx-check-circle"></i>
                            Connected
                        </p>
                    @else
                        <p class="text-xs text-gray-600 mt-1">Not connected</p>
                    @endif
                </div>
            </div>

            <div class="flex gap-2">
                @if ($googleConnected)
                    <button wire:click="disconnectGoogle"
                        wire:confirm="Are you sure you want to disconnect your Google account?"
                        class="px-4 py-2 border border-red-200 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors">
                        Disconnect
                    </button>
                @else
                    <a href="{{ route('google.redirect') }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                        </svg>
                        Connect Google
                    </a>
                @endif
            </div>
        </div>

        <p class="text-xs text-gray-500 mt-4">
            @if ($googleConnected)
                Your Google account is connected. You can sign in using your Google credentials. Click "Disconnect" to remove this connection.
            @else
                Connect your Google account to sign in more securely. You'll be able to use your Google credentials to log in.
            @endif
        </p>
    </div>

    {{-- Account & Security --}}
    <div class="bg-white rounded-2xl border border-red-100 p-6">
        <h2 class="text-base font-semibold text-red-700 mb-2">Account & Security</h2>
        <p class="text-sm text-gray-500 mb-4">
            Signed in as <span class="font-medium text-gray-900">{{ auth()->user()->email }}</span>
            with role <span class="font-medium text-gray-900">{{ auth()->user()->roleName() ?? 'user' }}</span>.
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="px-4 py-2 border border-red-200 text-red-600 text-sm font-medium rounded-xl hover:bg-red-50 transition-colors">
                Sign out of all devices
            </button>
        </form>
    </div>

</div>
