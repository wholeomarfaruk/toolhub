<?php

namespace App\Livewire\Admin\Seo;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QueueSetupGuide extends Component
{
    public string $hostingUsername = '';
    public string $domainFolder = '';

    public function mount()
    {
        $this->domainFolder = parse_url(config('app.url'), PHP_URL_HOST) ?: '';
    }

    public function render()
    {
        $pendingCount = DB::table('jobs')->count();
        $failedCount = DB::table('failed_jobs')->count();

        $cronCommand = null;
        if ($this->hostingUsername && $this->domainFolder) {
            $cronCommand = "domains/{$this->domainFolder}/artisan schedule:run";
        }

        return view('livewire.admin.seo.queue-setup-guide', [
            'pendingCount' => $pendingCount,
            'failedCount' => $failedCount,
            'cronCommand' => $cronCommand,
        ])->layout('layouts.admin.admin');
    }
}
