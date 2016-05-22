<?php

namespace App\Console\Commands;

use App\FishData;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Thujohn\Twitter\Facades\Twitter;

class DailyTweet extends Command
{

    protected $signature = 'twitter:postDailyTweet';
    protected $description = 'Send a summary of air and water temperature via twitter';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $from = Carbon::now()->subDay();
        $until = Carbon::now();
        $fishDB = FishData::whereBetween('time', array($from, $until));

        $avgWater = $fishDB->avg('water');
        $maxWater = $fishDB->max('water');
        $avgAir = $fishDB->avg('air');
        $maxAir = $fishDB->max('air');

        $tweet = 'Hi, here are the results from the office fishTank for ' .
Carbon::today()->format('d/m/Y') . ':
Avg water: ' . number_format($avgWater) . '
Max water: ' . number_format($maxWater) . '
Avg Air: ' . number_format($avgAir) . '
Max air: ' . number_format($maxAir);

        $this->info('The daily tweet was sent to Twitter!');

        return Twitter::postTweet(['status' => $tweet, 'format' => 'json']);

    }

}
