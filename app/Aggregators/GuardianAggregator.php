<?php

namespace App\Aggregators;

use App\Abstracts\AbstractAggregator;
use App\Models\APICategory;
use App\Models\Article;
use App\Models\Category;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class GuardianAggregator extends AbstractAggregator
{
    const GUARDIAN_SOURCE_ID = 1;

    const GUARDIAN_API_ID = 1;

    public function __construct()
    {
        $this->apiKey = env('GUARDIAN_API_KEY');
        $this->baseUrl = env('GUARDIAN_API_BASE_URL');
    }

    public function fetchArticles($fromDate = null, $toDate = null): array
    {
        $url = $this->baseUrl . 'from-date={fromDate}&to-date={toDate}&page-size=50&api-key={apiKey}';
        $url = str_replace('{fromDate}', $fromDate, $url);
        $url = str_replace('{toDate}', $toDate, $url);
        $url = str_replace('{apiKey}', $this->apiKey, $url);
        $request = new Request('GET', $url);

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

    public function save()
    {
        $fromDate = date('Y-m-d', strtotime('-1 day'));
        $toDate = date('Y-m-d');
        $categories = Category::pluck('category', 'id')->all();

        $articles = $this->fetchArticles($fromDate, $toDate);
        if (empty($articles)) {
            return;
        }

        $articles = $articles['response']['results'];

        $categoryMapping = APICategory::where('api_id', self::GUARDIAN_API_ID)
            ->pluck('category_id', 'api_category')
            ->all();

        foreach ($articles as $article) {
            if (Article::where('url', '=', $article['webUrl'])->exists()) {
                continue;
            }

            $articleRow = new Article;
            $articleRow->title = $article['webTitle'];
            $articleRow->url = $article['webUrl'];
            $articleRow->published_date = (new \DateTime($article['webPublicationDate']))->format('Y-m-d H:i:s');
            $articleRow->source_id = self::GUARDIAN_SOURCE_ID; // The Guardian does not provide any other source other than itself
            $articleRow->api_id = self::GUARDIAN_API_ID;
            $articleRow->category_id = $categoryMapping[$article['sectionName']] ?? 1;
            $articleRow->save();
        }
    }
}