<?php

namespace Database\Seeders;

use App\Models\APICategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class APICategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This contains a mapping of the categories defined by the API to our
        // own internal categories
        $apiCategories = [
            ['api_id' => 1, 'api_category' => 'Politics', 'category_id' => '2'],
            ['api_id' => 1, 'api_category' => 'Football', 'category_id' => '3'],
            ['api_id' => 1, 'api_category' => 'Games', 'category_id' => '3'],
            ['api_id' => 1, 'api_category' => 'Sport', 'category_id' => '3'],
            ['api_id' => 1, 'api_category' => 'Art and Design', 'category_id' => '4'],
            ['api_id' => 1, 'api_category' => 'Books', 'category_id' => '4'],
            ['api_id' => 1, 'api_category' => 'Crosswords', 'category_id' => '4'],
            ['api_id' => 1, 'api_category' => 'Films', 'category_id' => '4'],
            ['api_id' => 1, 'api_category' => 'Music', 'category_id' => '4'],
            ['api_id' => 1, 'api_category' => 'Television & Radio', 'category_id' => '4'],
            ['api_id' => 1, 'api_category' => 'Children\'s books', 'category_id' => '4'],
            ['api_id' => 1, 'api_category' => 'Environment', 'category_id' => '5'],
            ['api_id' => 1, 'api_category' => 'Guardian Government Computing', 'category_id' => '5'],
            ['api_id' => 1, 'api_category' => 'Science', 'category_id' => '5'],
            ['api_id' => 1, 'api_category' => 'Technology', 'category_id' => '5'],
            ['api_id' => 1, 'api_category' => 'Better Business', 'category_id' => '6'],
            ['api_id' => 1, 'api_category' => 'Business', 'category_id' => '6'],
            ['api_id' => 1, 'api_category' => 'Business to business', 'category_id' => '6'],
            ['api_id' => 1, 'api_category' => 'Money', 'category_id' => '6'],
            ['api_id' => 1, 'api_category' => 'Culture', 'category_id' => '7'],
            ['api_id' => 1, 'api_category' => 'Culture Network', 'category_id' => '7'],
            ['api_id' => 1, 'api_category' => 'Culture professionals network', 'category_id' => '7'],
            ['api_id' => 1, 'api_category' => 'Fashion', 'category_id' => '7'],
            ['api_id' => 1, 'api_category' => 'Life and style', 'category_id' => '7'],
            ['api_id' => 1, 'api_category' => 'Travel', 'category_id' => '7'],
            ['api_id' => 1, 'api_category' => 'Guardian holiday offers', 'category_id' => '7'],
            ['api_id' => 1, 'api_category' => 'Food', 'category_id' => '8'],
            ['api_id' => 1, 'api_category' => 'Healthcare Professionals Network', 'category_id' => '8'],
            ['api_id' => 2, 'api_category' => 'Politics', 'category_id' => '2'],
            ['api_id' => 2, 'api_category' => 'Sports', 'category_id' => '3'],
            ['api_id' => 2, 'api_category' => 'Adventure Sports', 'category_id' => '3'],
            ['api_id' => 2, 'api_category' => 'Books', 'category_id' => '4'],
            ['api_id' => 2, 'api_category' => 'Magazine', 'category_id' => '4'],
            ['api_id' => 2, 'api_category' => 'Movies', 'category_id' => '4'],
            ['api_id' => 2, 'api_category' => 'Play', 'category_id' => '4'],
            ['api_id' => 2, 'api_category' => 'Teens', 'category_id' => '4'],
            ['api_id' => 2, 'api_category' => 'Television', 'category_id' => '4'],
            ['api_id' => 2, 'api_category' => 'Environment', 'category_id' => '5'],
            ['api_id' => 2, 'api_category' => 'Personal Tech', 'category_id' => '5'],
            ['api_id' => 2, 'api_category' => 'Science', 'category_id' => '5'],
            ['api_id' => 2, 'api_category' => 'Technology', 'category_id' => '5'],
            ['api_id' => 2, 'api_category' => 'Business Day', 'category_id' => '6'],
            ['api_id' => 2, 'api_category' => 'Business', 'category_id' => '6'],
            ['api_id' => 2, 'api_category' => 'Entrepreneurs', 'category_id' => '6'],
            ['api_id' => 2, 'api_category' => 'Financial', 'category_id' => '6'],
            ['api_id' => 2, 'api_category' => 'Personal Investing', 'category_id' => '6'],
            ['api_id' => 2, 'api_category' => 'Small Business', 'category_id' => '6'],
            ['api_id' => 2, 'api_category' => 'Wealth', 'category_id' => '6'],
            ['api_id' => 2, 'api_category' => 'Your Money', 'category_id' => '6'],
            ['api_id' => 2, 'api_category' => 'Arts', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Blogs', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Culture', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Fashion & Style', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Fashion', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Flight', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Food', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Home', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Metro', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Metropolitan', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Museums', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Style', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Sunday Styles', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'T Style', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'The Arts', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Theater', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Travel', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Vacation', 'category_id' => '7'],
            ['api_id' => 2, 'api_category' => 'Dining', 'category_id' => '8'],
            ['api_id' => 2, 'api_category' => 'Health', 'category_id' => '8'],
            ['api_id' => 2, 'api_category' => 'Men\'s Health', 'category_id' => '8'],
            ['api_id' => 2, 'api_category' => 'Women\'s Health', 'category_id' => '8'],
        ];

        foreach ($apiCategories as $apiCategory) {
            APICategory::create($apiCategory);
        }
    }
}
