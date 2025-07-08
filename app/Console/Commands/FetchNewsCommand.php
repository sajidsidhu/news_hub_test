<?php

namespace App\Console\Commands;

use App\Services\NewsApiService;
use Illuminate\Console\Command;
use App\Models\Source;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news-articles-news-api {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all news articles from NewsAPI (everything endpoint) for all sources in DB';

    /**
     * Execute the console command.
     */
    public function handle(NewsApiService $newsApiService): int
    {
        $this->info('Fetching all news articles from NewsAPI for all sources in DB...');
        $sources = Source::getSourceIds();
        if (empty($sources)) {
            $this->error('No sources found in the database. Please run fetch:news-api-sources first.');
            return Command::FAILURE;
        }

        $fromOption = $this->option('from');
        $toOption = $this->option('to');
        $from = $fromOption ? date('Y-m-d', strtotime($fromOption)) : date('Y-m-d');
        $to = $toOption ? date('Y-m-d', strtotime($toOption)) : date('Y-m-d');
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            $this->error('The --from and --to options must be in YYYY-MM-DD format or a valid date string.');
            return Command::FAILURE;
        }
        
        $totalSaved = 0;
        foreach ($sources as $sourceId) {
            
            $page = 1; // Initialize page number
            do{

                $params = [
                    'sources' => $sourceId,
                    'language' => 'en',
                    'from' => $from,
                    'to' => $to,
                    'page_size' => 100,
                    'page' => $page, 
                ];
                
                $articles = $newsApiService->fetchAllArticles($params);// Fetch articles from NewsAPI
               
                $saved = $newsApiService->saveArticles($articles); // Save articles to the database
                $totalSaved += $saved;
                $page++;

            }while(count($articles) > 0);

        }
        $this->info("Total articles processed: {$totalSaved}");
        return Command::SUCCESS;
    }
}
