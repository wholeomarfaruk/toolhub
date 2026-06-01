<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortBy = 'created_at';
    public $sortDir = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        $query = Payment::with(['user', 'plan', 'subscription'])
            ->when($this->search, function ($q) {
                return $q->whereHas('user', function ($user) {
                    $user->where('name', 'like', "%{$this->search}%")
                         ->orWhere('email', 'like', "%{$this->search}%");
                })
                ->orWhereHas('plan', function ($plan) {
                    $plan->where('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, function ($q) {
                return $q->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        return view('livewire.admin.payments.payment-list', [
            'payments' => $query,
            'statuses' => ['pending', 'completed', 'failed', 'expired'],
        ])->layout('layouts.admin.admin');
    }
}
