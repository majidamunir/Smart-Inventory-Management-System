<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\CheckStockLevels;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('stock:check')->daily();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

