<?php

namespace App\Livewire\Website\Home;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $registry = app(\App\Services\ToolRegistry::class);

        return view('livewire.website.home.home', [
            'tools'   => array_values($registry->all()),
            'grouped' => $registry->groupedByCategory(),
        ])->layout('layouts.website.website', ['title' => 'Free Online Tools']);
    }
}
