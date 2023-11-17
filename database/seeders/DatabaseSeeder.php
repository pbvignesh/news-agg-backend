<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(APISeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(APICategorySeeder::class);
        $this->call(SourceSeeder::class);
        $this->call(ArticleSeeder::class);
    }
}
