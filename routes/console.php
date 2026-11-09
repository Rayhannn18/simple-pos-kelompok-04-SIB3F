<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\IdempotencyKey;
use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune', [
    '--model' => [IdempotencyKey::class],
])->hourly();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
