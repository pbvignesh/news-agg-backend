<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeds = Feed::where('user_id', Auth::id())->get();
        foreach ($feeds as &$feed) {
            $categories = Category::select('categories.id', 'category')
                ->join('category_feeds', 'category_feeds.category_id', '=', 'categories.id')
                ->where('category_feeds.feed_id', '=', $feed->id)
                ->get();

            $feed->categories = $categories;
        }

        return $feeds;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'feedName' => 'required|string',
            'isDefault' => 'required',
        ]);

        $feed = new Feed;
        $feed->name = $request->input('feedName');
        $feed->is_default = $request->input('isDefault');
        $feed->user_id = Auth::id();
        $feed->save();

        $authors = [];
        foreach ($request->input('authors') as $authorId) {
            $authors[] = [
                'author_id' => $authorId,
                'feed_id' => $feed->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        DB::table('author_feeds')->insert($authors);

        $categories = [];
        foreach ($request->input('categories') as $categoryId) {
            $categories[] = [
                'category_id' => $categoryId,
                'feed_id' => $feed->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        DB::table('category_feeds')->insert($categories);

        $sources = [];
        foreach ($request->input('sources') as $sourceId) {
            $sources[] = [
                'source_id' => $sourceId,
                'feed_id' => $feed->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        DB::table('feed_sources')->insert($sources);

        $categories = Category::whereIn('id', $request->input('categories'))->get();
        $feed->categories = $categories;

        return $feed;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Feed::where('id', $id)->andWhere('user_id', Auth::id())->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // As of now we only support making the default value of a feed so we automatically
        // remove previous defaults if any and set the new feed to be the default
        Feed::where('user_id', Auth::id())
            ->where('is_default', true)
            ->update(['is_default' => false, 'updated_at' => Carbon::now()]);

        Feed::where('user_id', Auth::id())
            ->where('id', $id)
            ->update(['is_default' => true, 'updated_at' => Carbon::now()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Feed::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();
    }
}
