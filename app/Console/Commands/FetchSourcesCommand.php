<?php

namespace App\Console\Commands;

use App\Services\NewsApiService;
use Illuminate\Console\Command;

class FetchSourcesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news-api-sources {--category=} {--language=en} {--country=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store all sources from NewsAPI';

    /**
     * Execute the console command.
     */
    public function handle(NewsApiService $newsApiService): int
    {
        $this->info('Fetching sources from NewsAPI...');
        $catgory = $this->option('category') ?? null;
        $language = $this->option('language') ?? 'en';
        $country = $this->option('country') ?? null;
        $sources = $newsApiService->fetchSources($catgory, $language, $country);
        if (empty($sources)) {
            $this->error('No sources found or an error occurred.');
            return Command::FAILURE;
        }
        $saved = $newsApiService->saveSources($sources);
        $this->info("Successfully processed {$saved} sources.");
        return Command::SUCCESS;
    }
}
