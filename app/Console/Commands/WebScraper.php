<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WebScrapingTarget;
use App\Jobs\ScrapeWebTargetJob;

class WebScraper extends Command
{
    // Ensure your signature matches how you want to call it,
    // e.g., php artisan scrape:web {domain?}
    protected $signature = 'scrape:web {domain?}';
    protected $description = 'Dispatch scraping jobs for registered web targets';

    public function handle()
    {
        $domain = $this->argument('domain');

        // 1. Fetch the targets from the database
        $query = WebScrapingTarget::where('is_active', true);

        if ($domain) {
            $query->where('domain', $domain);
        }

        $targets = $query->get();

        if ($targets->isEmpty()) {
            $this->warn("No active scraping targets found.");
            return;
        }

        $this->info("Found " . $targets->count() . " target(s). Dispatching to queue...");

        // 2. Dispatch each target to the background Job
        foreach ($targets as $target) {
            ScrapeWebTargetJob::dispatch($target);
            $this->line("<info>Dispatched:</info> {$target->name} ({$target->start_url})");
        }

        $this->info("All jobs have been sent to the queue. Run 'php artisan queue:work' to process them.");
    }
}
