<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule invoice reminders to run daily at 9:00 AM
Schedule::command('invoices:send-reminders')->dailyAt('09:00');

// Schedule recurring invoice generation to run daily at 8:00 AM
Schedule::command('invoices:generate-recurring')->dailyAt('08:00');
