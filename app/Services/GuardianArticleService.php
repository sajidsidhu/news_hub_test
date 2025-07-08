<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GuardianArticleService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
    }

    public function fetchArticles(string $query = '', int $page = 1): array
    {
        $url = 'https://content.guardianapis.com/search';
        try {
            $response = Http::get($url, [
                'q' => $query,
                'api-key' => $this->apiKey,
                'show-fields' => 'headline,byline,trailText,standfirst,bodyText,thumbnail,firstPublicationDate',
                'page' => $page,
                'page-size' => 50,
            ]);
            if (!$response->successful()) {
                Log::error('Guardian API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                return [];
            }
            $data = $response->json();
            return $data['response']['results'] ?? [];
        } catch (\Exception $e) {
            Log::error('Guardian API error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function saveArticles(array $results): int
    {
        $saved = 0;
        foreach ($results as $item) {
            try {
                $url = $item['fields']['shortUrl'] ?? $item['webUrl'] ?? null;
                if (!$url) continue;
                $fields = $item['fields'] ?? [];
                Article::updateOrCreate(
                    ['url' => $url],
                    [
                        'title'         => $fields['headline'] ?? $item['webTitle'] ?? null,
                        'description'   => $fields['trailText'] ?: ($fields['standfirst'] ?? null),
                        'content'       => $fields['bodyText'] ?? null,
                        'author'        => $fields['byline'] ?? 'N/A',
                        'source_id'     => 'the-guardians',
                        'url_to_image'  => $fields['thumbnail'] ?? null,
                        'category'      => $item['sectionName'] ?? $item['pillarName'] ?? null,
                        'published_at'  => isset($fields['firstPublicationDate']) 
                                            ? Carbon::parse($fields['firstPublicationDate']) 
                                            : Carbon::parse($item['webPublicationDate'] ?? now()),
                    ]
                );
                $saved++;
            } catch (\Exception $e) {
                Log::error('Error saving Guardian article', ['url' => $url, 'error' => $e->getMessage()]);
            }
        }
        return $saved;
    }
}
