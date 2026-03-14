<?php

namespace App\Jobs;

use App\Models\WebScrapingTarget;
use App\Models\WebScrapeExecution;
use App\Models\WebExtractedData;
use App\Models\WebRawArchive;
use App\Services\WebScraperService;
use App\Scrapers\ParserFactory; //
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ScrapeWebTargetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $target;

    // Resilience Properties
    public int $tries = 3; // Retry up to 3 times
    public int $timeout = 120; // Kill the job if it hangs for more than 2 minutes

    // Exponential backoff: Wait 1 min, then 3 mins, then 10 mins before retrying
    public array $backoff = [60, 180, 600];

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
        // --- RATE LIMITING ---
        // Allow 1 request every 10 seconds per domain.
        // Uses the Database Cache driver.
        $rateLimitKey = 'scrape:' . $this->target->domain;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            // Put the job back on the queue with a delay
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $this->release($seconds);
            return;
        }

        // Register the attempt
        RateLimiter::hit($rateLimitKey, 10);

        $startTime = microtime(true);
        $itemsCount = 0;

        $executionLog = new WebScrapeExecution([
            'web_scraping_target_id' => $this->target->id,
            'url_scraped' => $this->target->start_url,
            'scraped_at' => now(),
        ]);

        try {
            // 1. Perform the scrape using the service
            $crawler = $scraperService->scrapeStaticPage($this->target->start_url);

            $executionLog->response_time_ms = (int) ((microtime(true) - $startTime) * 1000);

            if (!$crawler) {
                throw new \Exception("Crawler returned null. Network or timeout issue.");
            }

            // 2. Use the Parser Strategy
            // This replaces the hardcoded title/links extraction
            $parser = ParserFactory::make($this->target->parser_class);
            $extractedData = $parser->parse($crawler);

            // 3. Log the successful execution
            $executionLog->is_successful = true;
            $executionLog->http_status_code = 200;
            $executionLog->items_extracted = $itemsCount;
            $executionLog->save();

            // 4. Save the extracted data
            // We normalize to an array of items to handle both single-page and collection parsers
            $items = isset($extractedData[0]) ? $extractedData : [$extractedData];

            foreach ($items as $item) {
                $dataHash = md5(json_encode($item));

                // Check if this exact content already exists for this target
                $exists = WebExtractedData::where('data_hash', $dataHash)
                    ->whereIn('web_scrape_execution_id', function ($query) {
                        $query->select('id')
                            ->from('web_scrape_executions')
                            ->where('web_scraping_target_id', $this->target->id);
                    })->exists();

                if (!$exists) {
                    WebExtractedData::create([
                        'web_scrape_execution_id' => $executionLog->id,
                        'payload' => $item,
                        'data_hash' => $dataHash,
                    ]);

                    $itemsCount++;
                }
            }

            // 5. Save the raw HTML for future NLP or ML pipelines
            WebRawArchive::create([
                'web_scrape_execution_id' => $executionLog->id,
                'raw_html' => $crawler->html(),
            ]);
        } catch (\Exception $e) {
            $executionLog->response_time_ms = (int) ((microtime(true) - $startTime) * 1000);
            $executionLog->is_successful = false;
            $executionLog->error_message = substr($e->getMessage(), 0, 500);
            $executionLog->save();

            Log::error("ScrapeJob failed for target {$this->target->id}: " . $e->getMessage());

            throw $e; // Re-throw to allow Laravel to handle retries
        }
    }
}
