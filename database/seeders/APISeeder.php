<?php

namespace Database\Seeders;

use App\Models\API;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class APISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fill it with the list of default APIs that we have
        $apis = [
            ['api' => 'The Guardian'],
            ['api' => 'The New York Times'],
            ['api' => 'NewsAPI'],
        ];

        foreach ($apis as $api) {
            API::create($api);
        }
    }
}
