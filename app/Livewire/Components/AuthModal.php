<?php

namespace App\Livewire\Components;

use Livewire\Component;

class AuthModal extends Component
{
    public bool $isOpen = false;
    public string $toolName = '';

    public function openModal(string $toolName = 'Tool'): void
    {
        $this->toolName = $toolName;
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.components.auth-modal');
    }
}
