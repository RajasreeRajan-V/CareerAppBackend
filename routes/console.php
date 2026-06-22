<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; 

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command('report:daily-viewers')
//     ->dailyAt('12:10')
//     ->withoutOverlapping();
Schedule::command('report:daily-viewers') ->dailyAt('10:30');


Schedule::command('colleges:send-renewal-reminders')->dailyAt('10:00');

// >withoutOverlapping(10)
//     ->appendOutputTo(storage_path('logs/reminder.log'))