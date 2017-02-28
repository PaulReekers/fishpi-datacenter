<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        'App\Console\Commands\DailyTweet',
        'App\Console\Commands\FacebookWebhookTest'
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('twitter:postDailyTweet')->daily();
    }
}
