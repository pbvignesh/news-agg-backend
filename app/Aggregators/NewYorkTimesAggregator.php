<?php

namespace App\Aggregators;

use App\Abstracts\AbstractAggregator;
use App\Models\Article;
use App\Models\Author;
use App\Models\APICategory;
use App\Models\Source;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class NewYorkTimesAggregator extends AbstractAggregator
{
    const NEW_YORK_TIMES_API_ID = 2;

    public function __construct()
    {
        $this->apiKey = env('NEW_YORK_TIMES_API_KEY');
        $this->baseUrl = env('NEW_YORK_TIMES_BASE_URL');
    }

    public function fetchArticles($fromDate = null, $toDate = null, $category = null): array
    {
        $url = $this->baseUrl . 'begin_date={fromDate}&end_date={toDate}&fq=news_desk:({category})&api-key={apiKey}';
        $url = str_replace('{fromDate}', $fromDate, $url);
        $url = str_replace('{toDate}', $toDate, $url);
        $url = str_replace('{apiKey}', $this->apiKey, $url);
        $url = str_replace('{category}', $category, $url);

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

        $categoryMapping = APICategory::where('api_id', self::NEW_YORK_TIMES_API_ID)
            ->pluck('category_id', 'api_category')
            ->all();

        foreach ($categoryMapping as $apiCategory => $categoryId) {
            $articles = $this->fetchArticles($fromDate, $toDate, $apiCategory);
            if (empty($articles)) {
                continue;
            }
            $articles = $articles['response']['docs'];

            foreach ($articles as $article) {
                if (Article::where('url', '=', $article['web_url'])->exists()) {
                    continue;
                }

                $articleRow = new Article;
                $articleRow->title = $article['abstract'];
                $articleRow->description = $article['lead_paragraph'];
                $articleRow->url = $article['web_url'];
                if (isset($article['source'])) {
                    $articleRow->source_id = Source::firstOrCreate(['source' => $article['source']])->id;
                }
                $articleRow->category_id = $categoryMapping[$article['news_desk']] ?? 1;
                if (isset($article['byline']['original'])) {
                    $articleRow->author_id = Author::firstOrCreate(['author' => str_replace('By ', '', $article['byline']['original'])])->id;
                }
                $articleRow->api_id = self::NEW_YORK_TIMES_API_ID;
                $articleRow->published_date = (new \DateTime($article['pub_date']))->format('Y-m-d H:i:s');
                $articleRow->save();
            }

            // A Hacky fix to avoid being rate limited, a better solution can be built by taking a look at
            // the response to see if we are being rate limited and then change our API request duration
            // accordingly
            sleep(10);
        }
    }
}