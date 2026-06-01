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

    {{-- Danger Zone --}}
    <div class="bg-white rounded-2xl border border-red-100 p-6">
        <h2 class="text-base font-semibold text-red-700 mb-2">Account</h2>
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
