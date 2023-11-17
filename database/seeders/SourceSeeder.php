<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Filling the database with a default source to begin with
        $sources = [
            ['source' => 'The Guardian']
        ];

        foreach ($sources as $source) {
            Source::create($source);
        }
    }
}
