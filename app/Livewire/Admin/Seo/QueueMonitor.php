<?php

namespace App\Livewire\Admin\Seo;

use App\Models\AiGenerationLog;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QueueMonitor extends Component
{
    public function retryFailed(string $uuid)
    {
        \Illuminate\Support\Facades\Artisan::call('queue:retry', ['id' => [$uuid]]);
        $this->dispatch('toast', message: 'Job re-queued for retry.');
    }

    public function retryAllFailed()
    {
        \Illuminate\Support\Facades\Artisan::call('queue:retry', ['id' => ['all']]);
        $this->dispatch('toast', message: 'All failed jobs re-queued.');
    }

    public function deleteFailed(int $id)
    {
        DB::table('failed_jobs')->where('id', $id)->delete();
        $this->dispatch('toast', message: 'Failed job removed.');
    }

    public function clearAllFailed()
    {
        DB::table('failed_jobs')->truncate();
        $this->dispatch('toast', message: 'All failed jobs cleared.');
    }

    public function render()
    {
        $pendingCount = DB::table('jobs')->count();
        $failedCount = DB::table('failed_jobs')->count();

        $pendingJobs = DB::table('jobs')
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->map(function ($job) {
                $payload = json_decode($job->payload, true);
                return [
                    'id' => $job->id,
                    'job_class' => $payload['displayName'] ?? 'Unknown',
                    'attempts' => $job->attempts,
                    'available_at' => $job->available_at,
                    'created_at' => $job->created_at,
                ];
            });

        $failedJobs = DB::table('failed_jobs')
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->map(function ($job) {
                $payload = json_decode($job->payload, true);
                return [
                    'id' => $job->id,
                    'uuid' => $job->uuid,
                    'job_class' => $payload['displayName'] ?? 'Unknown',
                    'exception' => \Illuminate\Support\Str::limit($job->exception, 400),
                    'failed_at' => $job->failed_at,
                ];
            });

        // Heartbeat: the most recent AI job (success or failed) tells us the worker actually ran recently.
        $lastProcessed = AiGenerationLog::orderByDesc('updated_at')->first();
        $lastProcessedAt = $lastProcessed?->updated_at;
        $workerLikelyRunning = $lastProcessedAt && $lastProcessedAt->gt(now()->subMinutes(10));

        return view('livewire.admin.seo.queue-monitor', [
            'pendingCount' => $pendingCount,
            'failedCount' => $failedCount,
            'pendingJobs' => $pendingJobs,
            'failedJobs' => $failedJobs,
            'lastProcessedAt' => $lastProcessedAt,
            'workerLikelyRunning' => $workerLikelyRunning,
        ])->layout('layouts.admin.admin');
    }
}
