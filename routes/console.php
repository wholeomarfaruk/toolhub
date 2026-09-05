<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Process queued jobs (AI keyword/content generation, etc.) once a minute,
// draining whatever is pending and exiting — suited to shared hosting where
// a persistent `queue:work` process isn't viable. Requires a single cron
// entry running `php artisan schedule:run` every minute.
Schedule::command('queue:work --stop-when-empty --tries=3 --max-time=55')
    ->everyMinute()
    ->withoutOverlapping(5);
