<?php

namespace App\Livewire\User\Profile;

use App\Models\ConnectedAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserProfile extends Component
{
    public string $name  = '';
    public string $email = '';

    public string $current_password    = '';
    public string $password            = '';
    public string $password_confirmation = '';

    public bool $googleConnected = false;

    public function mount(): void
    {
        $user        = Auth::user();
        $this->name  = $user->name;
        $this->email = $user->email;
        $this->googleConnected = $user->hasConnectedAccount('google');
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $this->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update([
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('toast', ['type' => 'success', 'message' => 'Profile updated.']);
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($this->current_password, Auth::user()->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        Auth::user()->update(['password' => Hash::make($this->password)]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Password updated.']);
    }

    public function disconnectGoogle(): void
    {
        ConnectedAccount::where('user_id', Auth::id())
            ->where('provider', 'google')
            ->delete();

        $this->googleConnected = false;

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Google account disconnected successfully.',
        ]);
    }

    public function render()
    {
        return view('livewire.user.profile.user-profile')
            ->layout('layouts.user.user', ['title' => 'Profile Settings']);
    }
}
