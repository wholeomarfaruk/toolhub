{{-- ======================== Page Layout Start From Here ======================== --}}
<div x-data="{ copied: null }" x-init="$store.pageName = { name: 'Queue Setup Guide', slug: 'seo-queue-setup' }">
    {{-- ======================== Page Header Start From Here ======================== --}}
    <div class="flex flex-wrap justify-between gap-6">
        <h1 class="text-gray-500 text-lg font-bold" x-cloak x-text="$store.pageName?.name ?? ''"></h1>
        <nav>
            <ol class="flex items-center gap-1.5">
                <li>
                    <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                        href="{{ route('admin.dashboard') }}">
                        Dashboard
                        <svg class="stroke-current" width="17" height="16" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
                <li class="text-sm text-gray-800 dark:text-white/90" x-text="$store.pageName?.name ?? ''"></li>
            </ol>
        </nav>
    </div>
    {{-- ======================== Page Header End Here ======================== --}}

    <div class="flex-1 w-full bg-white rounded-lg min-h-[80vh]">
        <div class="px-4 py-4 max-w-3xl">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Queue Worker Setup (Hostinger hPanel)</h2>
            <p class="text-sm text-gray-500 mb-6">
                AI keyword/content generation runs as a background job. Without a queue worker
                running, clicking "Generate" will queue the job but nothing will ever process it.
                This page walks through setting that up on Hostinger's hPanel.
            </p>

            {{-- Current status --}}
            <div class="mb-6 grid gap-4 grid-cols-2">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="text-xs text-gray-500">Pending Jobs Right Now</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</div>
                </div>
                <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                    <div class="text-xs text-red-700">Failed Jobs</div>
                    <div class="text-2xl font-bold text-red-800">{{ $failedCount }}</div>
                </div>
            </div>
            <p class="text-xs text-gray-500 mb-6">
                See <a href="{{ route('admin.seo.queue-monitor') }}" class="text-indigo-600 hover:underline font-medium">Queue Monitor</a>
                for a live view (auto-refreshing worker heartbeat, pending/failed job lists, retry actions).
            </p>

            {{-- Command generator --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-8">
                <h3 class="text-sm font-bold text-gray-900 mb-3">1. Generate your exact cron command</h3>
                <div class="grid gap-3 md:grid-cols-2 mb-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Hostinger Username</label>
                        <input type="text" wire:model.live="hostingUsername"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                               placeholder="e.g. u566023680">
                        <p class="text-xs text-gray-500 mt-1">Found in hPanel &rarr; Advanced &rarr; Cron Jobs (visible in any existing cron entry's path), or in File Manager's root path.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Domain Folder Name</label>
                        <input type="text" wire:model.live="domainFolder"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                               placeholder="e.g. toolshub.software">
                        <p class="text-xs text-gray-500 mt-1">The folder under <code class="bg-white px-1 rounded">domains/</code> matching your live domain.</p>
                    </div>
                </div>

                @if($cronCommand)
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Paste this into hPanel's "Command to Run" field (the editable part after the pre-filled <code class="bg-white px-1 rounded">/usr/bin/php /home/{{ $hostingUsername }}/</code> prefix):</label>
                    <div class="flex items-center gap-2">
                        <code id="cron-command" class="flex-1 block bg-white border border-indigo-200 rounded-lg px-3 py-2 text-sm font-mono text-gray-800 overflow-x-auto whitespace-nowrap">{{ $cronCommand }}</code>
                        <button type="button"
                                @click="navigator.clipboard.writeText(document.getElementById('cron-command').innerText); copied = 'cron'; setTimeout(() => copied = null, 2000)"
                                class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shrink-0">
                            <span x-show="copied !== 'cron'">Copy</span>
                            <span x-show="copied === 'cron'">Copied!</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Full command will be: <code class="bg-white px-1 rounded">/usr/bin/php /home/{{ $hostingUsername }}/{{ $cronCommand }}</code>
                    </p>
                @else
                    <p class="text-sm text-gray-500 italic">Fill in both fields above to generate your exact command.</p>
                @endif
            </div>

            {{-- Step by step --}}
            <div class="space-y-5">
                <div class="flex gap-4">
                    <div class="shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center">2</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Open hPanel Cron Jobs</h4>
                        <p class="text-sm text-gray-600">hPanel &rarr; <strong>Advanced &rarr; Cron Jobs</strong>.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center">3</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Create a new cron job</h4>
                        <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                            <li>Type: <strong>PHP</strong></li>
                            <li>Common Options: <strong>Every Minute</strong></li>
                            <li>Command to Run (editable field): paste the generated command above</li>
                        </ul>
                        <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mt-2">
                            Do not add <code class="bg-white px-1 rounded">&gt;/dev/null 2&gt;&amp;1</code> or any other shell
                            redirection — hPanel's cron field does not support those characters and will reject or mangle the command.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center">4</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Save, then verify</h4>
                        <p class="text-sm text-gray-600">
                            Wait 1–2 minutes, then check the
                            <a href="{{ route('admin.seo.queue-monitor') }}" class="text-indigo-600 hover:underline font-medium">Queue Monitor</a>
                            page — the pending job count should drop and the heartbeat indicator should turn green.
                        </p>
                    </div>
                </div>
            </div>

            {{-- How it works --}}
            <div class="mt-8 bg-gray-50 border border-gray-200 rounded-xl p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-2">How this works</h3>
                <p class="text-sm text-gray-600 mb-2">
                    This single cron entry runs Laravel's own scheduler (<code class="bg-white px-1 rounded">schedule:run</code>) every minute.
                    The scheduler is configured (in <code class="bg-white px-1 rounded">routes/console.php</code>) to run
                    <code class="bg-white px-1 rounded">queue:work --stop-when-empty</code> — it drains whatever AI generation
                    jobs are pending and exits, rather than running forever (which shared hosting doesn't allow).
                </p>
                <p class="text-sm text-gray-600">
                    A <code class="bg-white px-1 rounded">withoutOverlapping</code> lock prevents two overlapping runs if a job
                    takes longer than a minute to process. Only one cron entry is ever needed, even if more scheduled tasks are
                    added later.
                </p>
            </div>
        </div>
    </div>
</div>
