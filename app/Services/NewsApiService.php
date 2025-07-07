<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Log;
use jcobhams\NewsApi\NewsApi;

class NewsApiService
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
    }

    /**
     * Fetch all articles from NewsAPI using the everything endpoint.
     * You can adjust the query or parameters as needed.
     */
    public function fetchAllArticles(array $params = []): array
    {
        try {
            $newsapi = new NewsApi($this->apiKey);
            // You can set a default query or allow $params to override
            $query = $params['q'] ?? '';
            $sources = $params['sources'] ?? null;
            $domains = $params['domains'] ?? null;
            $from = $params['from'] ?? null;
            $to = $params['to'] ?? null;
            $language = $params['language'] ?? null;
            $sortBy = $params['sortBy'] ?? null;
            $pageSize = $params['pageSize'] ?? 100;
            $page = $params['page'] ?? 1;

            $response = $newsapi->getEverything($query, $sources, $domains, $from, $to, $language, $sortBy, $pageSize, $page);
            if (isset($response->status) && $response->status === 'ok' && isset($response->articles)) {
                $articles = json_decode(json_encode($response->articles), true);
                return $articles;
            } else {
                Log::error('NewsAPI everything request failed', [
                    'response' => $response
                ]);
                return [];
            }
        } catch (\Exception $e) {
            Log::error('Error fetching all articles from NewsAPI', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function saveArticles(array $articles): int
    {
        $saved = 0;
        foreach ($articles as $article) {
            try {
                if (empty($article['url'])) {
                    Log::warning('Skipping article without URL', ['article' => $article]);
                    continue;
                }
                Article::updateOrCreate(
                    ['url' => $article['url']],
                    [
                        'title' => $article['title'] ?? 'Untitled',
                        'description' => $article['description'] ?? null,
                        'content' => $article['content'] ?? null,
                        'author' => $article['author'] ?? null,
                        'source_name' => $article['source']['name'] ?? ($article['source_name'] ?? 'Unknown Source'),
                        'url_to_image' => $article['urlToImage'] ?? ($article['url_to_image'] ?? null),
                        'category' => $article['category'] ?? null,
                        'published_at' => !empty($article['publishedAt']) 
                            ? date('Y-m-d H:i:s', strtotime($article['publishedAt']))
                            : now(),
                    ]
                );
                $saved++;
            } catch (\Exception $e) {
                Log::error('Error saving article', [
                    'url' => $article['url'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        }
        return $saved;
    }
}
