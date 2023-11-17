<?php

namespace App\Aggregators;

use App\Abstracts\AbstractAggregator;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class NewsAPIAggregator extends AbstractAggregator
{
    const NEWS_API_ID = 3;

    public function __construct()
    {
        $this->apiKey = env('NEWS_API_KEY');
        $this->baseUrl = env('NEWS_API_BASE_URL');
    }

    public function fetchArticles($fromDate = null, $toDate = null, $category = null): array
    {
        $url = $this->baseUrl . 'from={fromDate}&to={toDate}&q={category}';
        $url = str_replace('{fromDate}', $fromDate, $url);
        $url = str_replace('{toDate}', $toDate, $url);
        $url = str_replace('{apiKey}', $this->apiKey, $url);


        $headers = ['X-Api-Key' => $this->apiKey];
        $request = new Request('GET', $url, $headers);

        $client = new Client();
        $response = [];
        try {
            $httpResponse = $client->send($request);
            $content = $httpResponse->getBody()->getContents();
            $response = json_decode($content, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::debug('Client Exception: ' . $e->getMessage());
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::debug('Guzzle Exception: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::debug('General Exception: ' . $e->getMessage());
        }

        return $response;
    }

    public function save(): void
    {
        // Due to Limitations of the Basic Plan API Key we cannot get the latest day's news
        // so we are fetching news from the day before instead
        $fromDate = date('Y-m-d', strtotime('-2 days'));
        $toDate = date('Y-m-d', strtotime('-1 day'));
        $categories = Category::pluck('category', 'id')->all();

        foreach ($categories as $categoryId => $category) {
            $articles = $this->fetchArticles($fromDate, $toDate, $category);
            if (empty($articles)) {
                continue;
            }

            $articles = $articles['articles'];
            $sourceIdMapping = $this->saveSourcesAndGetMapping($articles);
            $authorIdMapping = $this->saveAuthorsAndGetMapping($articles);

            foreach ($articles as $article) {
                if (Article::where('url', '=', $article['url'])->exists()) {
                    continue;
                }

                $articleRow = new Article;
                $articleRow->title = $article['title'];
                $articleRow->description = $article['description'];
                $articleRow->content = $article['content'];
                $articleRow->url = $article['url'];
                $articleRow->thumbnail_url = $article['urlToImage'];
                $articleRow->published_date = (new \DateTime($article['publishedAt']))->format('Y-m-d H:i:s');
                $articleRow->source_id = $sourceIdMapping[$article['source']['name']] ?? null;
                $articleRow->author_id = $authorIdMapping[$article['author']] ?? null;
                $articleRow->api_id = self::NEWS_API_ID;
                $articleRow->category_id = $categoryId;
                $articleRow->save();
            }
        }
    }

    public function saveSourcesAndGetMapping($articles)
    {
        $sources = array_column($articles, 'source');
        $sources = array_column($sources, 'name');
        foreach ($sources as $source) {
            if (empty($source)) {
                continue;
            }
            $source = ['source' => $source];
            Source::firstOrCreate($source);
        }

        return Source::pluck('id', 'source')->all();
    }

    public function saveAuthorsAndGetMapping($articles)
    {
        $authors = array_column($articles, 'author');
        foreach ($authors as $author) {
            if (empty($author)) {
                continue;
            }
            $author = ['author' => $author];
            Author::firstOrCreate($author);
        }

        return Author::pluck('id', 'author')->all();
    }
}