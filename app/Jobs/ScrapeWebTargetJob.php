<?php

namespace App\Jobs;

use App\Models\WebScrapingTarget;
use App\Models\WebScrapeExecution;
use App\Models\WebExtractedData;
use App\Models\WebRawArchive;
use App\Services\WebScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScrapeWebTargetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $target;

    /**
     * Create a new job instance.
     */
    public function __construct(WebScrapingTarget $target)
    {
        $this->target = $target;
    }

    /**
     * Execute the job.
     */
    public function handle(WebScraperService $scraperService): void
    {
        $startTime = microtime(true);
        $executionLog = new WebScrapeExecution([
            'web_scraping_target_id' => $this->target->id,
            'url_scraped' => $this->target->start_url,
            'scraped_at' => now(),
        ]);

        try {
            // 1. Perform the scrape
            $crawler = $scraperService->scrapeStaticPage($this->target->start_url);

            // Calculate response time
            $executionLog->response_time_ms = (int) ((microtime(true) - $startTime) * 1000);

            if (!$crawler) {
                throw new \Exception("Crawler returned null. Network or timeout issue.");
            }

            // For now, we'll do a generic extraction: just grabbing the page title and all links.
            // Later, this will be delegated to the specific parser_class.
            $payload = [
                'title' => $crawler->filter('title')->count() > 0 ? $crawler->filter('title')->text() : 'No Title',
                'links_count' => $crawler->filter('a')->count(),
            ];

            // 2. Hash the payload to check for changes
            $dataHash = md5(json_encode($payload));

            // 3. Log the successful execution
            $executionLog->is_successful = true;
            $executionLog->http_status_code = 200; // Assuming 200 if Crawler succeeded
            $executionLog->save();

            // 4. Save the extracted data
            WebExtractedData::create([
                'web_scrape_execution_id' => $executionLog->id,
                'payload' => $payload,
                'data_hash' => $dataHash,
            ]);

            // 5. Save the raw HTML for future NLP or ML pipelines
            WebRawArchive::create([
                'web_scrape_execution_id' => $executionLog->id,
                'raw_html' => $crawler->html(),
            ]);
        } catch (\Exception $e) {
            // Log the failure gracefully without crashing the queue worker
            $executionLog->response_time_ms = (int) ((microtime(true) - $startTime) * 1000);
            $executionLog->is_successful = false;
            $executionLog->error_message = substr($e->getMessage(), 0, 500); // Truncate just in case
            $executionLog->save();

            Log::error("ScrapeJob failed for target {$this->target->id}: " . $e->getMessage());

            // Re-throw if you want Laravel's queue system to automatically retry this job
            // throw $e;
        }
    }
}
