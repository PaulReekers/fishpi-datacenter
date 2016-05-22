<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        'App\Console\Commands\DailyTweet'
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('twitter:postDailyTweet')->dailyAt('08:00');
    }
}
