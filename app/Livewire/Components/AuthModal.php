<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AuthModal extends Component
{
    public bool $isOpen = false;
    public string $toolName = '';
    public string $activeTab = 'signin'; // 'signin' or 'register'

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8')]
    public string $password = '';

    public string $passwordConfirm = '';

    #[Validate('required|min:2')]
    public string $name = '';

    public bool $rememberMe = false;
    public bool $agreeTerms = false;

    #[On('openAuthModal')]
    public function openModal(string $toolName = 'Tool'): void
    {
        $this->toolName = $toolName;
        $this->isOpen = true;
        $this->reset('email', 'password', 'passwordConfirm', 'name', 'rememberMe', 'agreeTerms');
        $this->activeTab = 'signin';
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
        $this->reset('email', 'password', 'passwordConfirm', 'name', 'rememberMe', 'agreeTerms');
    }

    public function signIn()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Redirect to login with email pre-filled
        session(['auth_email' => $this->email]);
        return redirect(route('login'));
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'agreeTerms' => 'accepted',
        ]);

        // Redirect to register with data pre-filled
        session(['register_data' => [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]]);
        return redirect(route('register'));
    }

    public function render()
    {
        return view('livewire.components.auth-modal');
    }
}
