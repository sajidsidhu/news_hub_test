<?php

namespace App\Console\Commands;

use App\Services\NewsApiService;
use Illuminate\Console\Command;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all news articles from NewsAPI (everything endpoint)';

    /**
     * Execute the console command.
     */
    public function handle(NewsApiService $newsApiService): int
    {
        $this->info('Fetching all news articles from NewsAPI...');
        $articles = $newsApiService->fetchAllArticles();
        if (empty($articles)) {
            $this->error('No articles found or an error occurred.');
            return Command::FAILURE;
        }
        $saved = $newsApiService->saveArticles($articles);
        $this->info("Successfully processed {$saved} articles.");
        return Command::SUCCESS;
    }
}
