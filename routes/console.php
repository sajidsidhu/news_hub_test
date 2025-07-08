<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('fetch:news-articles-news-api')
    ->hourly()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/news-fetch.log'));

Schedule::command('fetch:nytimes-articles')
    ->hourly()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/nytnow .log'));

Schedule::command('fetch:guardian-articles')
    ->hourly()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/guardian.log'));
