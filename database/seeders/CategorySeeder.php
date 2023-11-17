<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // The default set of categories we support right now
        $categories = [
            ['category' => 'General'],
            ['category' => 'Politics'],
            ['category' => 'Sports'],
            ['category' => 'Entertainment'],
            ['category' => 'Science'],
            ['category' => 'Business'],
            ['category' => 'LifeStyle'],
            ['category' => 'Health']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
