<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NytimesArticleService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.nytimes.key');
    }

    public function fetchArticles(string $query = '', int $page = 0): array
    {
        $url = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
        try {
            $response = Http::get($url, [
                'q' => $query,
                'api-key' => $this->apiKey,
                'page' => $page,
            ]);
            if (!$response->successful()) {
                Log::error('NYT API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                return [];
            }
            $data = $response->json();
            return $data['response']['docs'] ?? [];
        } catch (\Exception $e) {
            Log::error('NYT API error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function saveArticles(array $docs): int
    {
        $saved = 0;
        foreach ($docs as $doc) {
            try {
                $url = $doc['web_url'] ?? null;
                if (!$url) continue;

                Article::updateOrCreate(
                    ['url' => $url],
                    [
                        'title' => $doc['headline']['main'] ?? 'Untitled',
                        'description' => $doc['lead_paragraph'] ?? null,
                        'content' => $doc['snippet'] ?? null,
                        'author' => $doc['byline']['original'] ?? 'N/A',
                        'source_id' => $doc['source'] ?? 'NYT',
                        'url_to_image' => isset($doc['multimedia'][0]) ? 'https://www.nytimes.com/' . ltrim($doc['multimedia'][0]['url'], '/') : null,
                        'category' => $doc['section_name'] ?? null,
                        'published_at' => $doc['pub_date'] ?? now(),
                    ]
                );
                $saved++;
            } catch (\Exception $e) {
                Log::error('Error saving NYT article', ['url' => $url, 'error' => $e->getMessage()]);
            }
        }
        return $saved;
    }
}
