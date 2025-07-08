<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GuardianArticleService;

class FetchGuardianArticles extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:guardian-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and import all articles from The Guardian API (all pages)';

    public function handle(GuardianArticleService $guardianService): int
    {
        $query = '';
        $page = 1;
        $totalSaved = 0;
        $this->info("Fetching all Guardian articles for query: $query (all pages)");
        do {
            $results = $guardianService->fetchArticles($query, $page);
            if (empty($results)) {
                $this->info("No more articles found at page $page. Stopping.");
                break;
            }
            $saved = $guardianService->saveArticles($results);
            $this->info("Page $page: Saved $saved articles from Guardian.");
            $totalSaved += $saved;
            $page++;
        } while (!empty($results));

        $this->info("Total Guardian articles saved: $totalSaved");

        return Command::SUCCESS;
    }
}
