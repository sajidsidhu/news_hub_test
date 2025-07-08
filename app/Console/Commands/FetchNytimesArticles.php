<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NytimesArticleService;

class FetchNytimesArticles extends Command
{
    protected $signature = 'fetch:nytimes-articles';
    protected $description = 'Fetch and import all articles from the New York Times API (all pages)';

    public function handle(NytimesArticleService $nytService): int
    {
        $query = '';
        $page = 0;
        $totalSaved = 0;
        $this->info("Fetching all NYT articles for query: $query (all pages)");
        do {
            $docs = $nytService->fetchArticles($query, $page);
            if (empty($docs)) {
                $this->info("No more articles found at page $page. Stopping.");
                break;
            }
            $saved = $nytService->saveArticles($docs);
            $this->info("Page $page: Saved $saved articles from NYT.");
            $totalSaved += $saved;
            $page++;
        } while (!empty($docs));
        $this->info("Total NYT articles saved: $totalSaved");
        return Command::SUCCESS;
    }
}
