<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Check rate alerts every 15 minutes and fire push notifications when thresholds are hit
Schedule::command('alerts:check')->everyFifteenMinutes();
