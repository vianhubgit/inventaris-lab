<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backup database harian (opsional, jika queue/scheduler diaktifkan di server).
// Aktifkan dengan menambahkan cron: * * * * * php artisan schedule:run
Schedule::command('inventaris:backup')->dailyAt('23:00');
