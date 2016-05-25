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
        $from = Carbon::now()->subHour(1);
        $until = Carbon::now();
        $fishDB = FishData::whereBetween('time', array($from, $until));

        $avgWater = $fishDB->avg('water');
        $maxWater = $fishDB->max('water');
        $avgAir = $fishDB->avg('air');
        $maxAir = $fishDB->max('air');

        $tweet = 'The FishTank results from last hour ' .
Carbon::now()->format('d/m/Y H:i:s') . ':
Avg water: ' . number_format($avgWater) . '
Max water: ' . number_format($maxWater) . '
Avg Air: ' . number_format($avgAir) . '
Max air: ' . number_format($maxAir);

        $this->info('The tweet was sent to Twitter!');

        return Twitter::postTweet(['status' => $tweet, 'format' => 'json']);

    }

}
