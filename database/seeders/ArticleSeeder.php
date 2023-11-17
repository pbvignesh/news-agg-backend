<?php

namespace Database\Seeders;

use App\Aggregators\NewsAPIAggregator;
use App\Aggregators\GuardianAggregator;
use App\Aggregators\NewYorkTimesAggregator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Populate the database with some news articles for the past one day
        $newsAPIAggregator = new NewsAPIAggregator();
        $newsAPIAggregator->save();

        $guardianAggregator = new GuardianAggregator();
        $guardianAggregator->save();

        $newYorkTimesAggregator = new NewYorkTimesAggregator();
        $newYorkTimesAggregator->save();
    }
}
