<?php

namespace App\Console\Commands;

use App\Aggregators\NewsAPIAggregator;
use App\Aggregators\GuardianAggregator;
use App\Aggregators\NewYorkTimesAggregator;
use Illuminate\Console\Command;

class AggregateNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:aggregate-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newsAPIAggregator = new NewsAPIAggregator();
        $newsAPIAggregator->save();

        $guardianAggregator = new GuardianAggregator();
        $guardianAggregator->save();

        $newYorkTimesAggregator = new NewYorkTimesAggregator();
        $newYorkTimesAggregator->save();
    }
}
