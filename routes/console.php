<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// جدولة إرسال التذكيرات التلقائية يومياً في الساعة 6:15 مساءً
Schedule::command('reminders:send-due-date')
    ->dailyAt('18:20')
    ->timezone('Asia/Riyadh')
    ->withoutOverlapping()
    ->runInBackground();
