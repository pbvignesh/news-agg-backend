<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class Feed extends Model
{
    use HasFactory;

    protected $table = 'feeds';

    public static function getUserNewsFeed($userId, $filters)
    {
        // Check if the user is requesting a custom filter on the news feed page
        if (
            !empty($filters['searchTerm']) 
            || !empty($filters['sources'])
            || !empty($filters['categories'])
            || !empty($filters['authors'])
            || !empty($filters['dateRange']['start'])
            || !empty($filters['dateRange']['end'])
        ) {
            return self::generateFeed(
                $filters['sources'] ?? [],
                $filters['categories'] ?? [],
                $filters['authors'] ?? [],
                $filters['searchTerm'] ?? null,
                $filters['dateRange']['start'] ?? null ,
                $filters['dateRange']['end'] ?? null
            );
        }

        // If not then check if the user has a news feed that they have created.
        // Users who don't have a preferred news feed will have a default
        // news feed that will be shown to them
        $userFeed = Feed::where('user_id', $userId)
            ->where('is_default', true)
            ->first();

        if (!$userFeed) {
            return self::generateFeed();
        }

        // If the user has a preferred news feed, then we will be generating
        // the news feed from the users' preferences
        $sourceIds = DB::table('feed_sources')
            ->where('feed_id', $userFeed->id)
            ->pluck('source_id');

        $categoryIds = DB::table('category_feeds')
            ->where('feed_id', $userFeed->id)
            ->pluck('category_id');
        $authorIds = DB::table('author_feeds')
            ->where('feed_id', $userFeed->id)
            ->pluck('author_id');

        return self::generateFeed($sourceIds, $categoryIds, $authorIds);
    }

    public static function generateFeed($sourceIds = [], $categoryIds = [], $authorIds = [], $searchTerm = null, $fromDate = null, $toDate = null)
    {
        $feedQuery = Article::select(
                'articles.id as id',
                'articles.title as title',
                'articles.description as description',
                'articles.url as url',
                'authors.author as author',
                'sources.source as source',
                'categories.category as category',
                'articles.published_date as raw_date'
            )
            ->leftJoin('sources', 'articles.source_id', '=', 'sources.id')
            ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
            ->leftJoin('authors', 'articles.author_id', '=', 'authors.id');

        if (!empty($sourceIds)) {
            $feedQuery = $feedQuery->orWhereIn('articles.source_id', $sourceIds);
        }

        if (!empty($categoryIds)) {
            $feedQuery = $feedQuery->orWhereIn('articles.category_id', $categoryIds);
        }

        if (!empty($authorIds)) {
            $feedQuery = $feedQuery->orWhereIn('articles.author_id', $authorIds);
        }

        if (!empty($searchTerm)) {
            $feedQuery = $feedQuery->orWhere('articles.title', 'like', '%' . $searchTerm . '%')
                ->orWhere('articles.description', 'like', '%' . $searchTerm . '%');
        }

        if (!empty($fromDate)) {
            $feedQuery = $feedQuery->orWhere('articles.published_date', '>=', $fromDate);
        }

        if (!empty($toDate)) {
            $feedQuery = $feedQuery->orWhere('articles.published_date', '<=', $toDate);
        }

        $feed = $feedQuery->orderBy('articles.published_date', 'desc')
            ->limit(100)
            ->get()
            ->map(function ($item) {
                $rawDate = Carbon::parse($item->raw_date);
                $item->publishedDate = $rawDate->diffForHumans();
                return $item;
            });

        return $feed;
    }
}
