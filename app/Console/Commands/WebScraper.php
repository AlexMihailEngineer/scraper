<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WebScraperService;
use Symfony\Component\DomCrawler\Crawler;

class WebScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:web-scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(WebScraperService $scraper)
    {
        $url = 'https://news.ycombinator.com/';
        $crawler = $scraper->scrapeStaticPage($url);

        if (!$crawler) {
            $this->error("Could not reach the page.");
            return;
        }

        // Example: Extract Hacker News headlines
        $crawler = $crawler->filter('.titleline > a')->each(function (Crawler $node) {
            $this->info($node->text() . " [" . $node->attr('href') . "]");
        });

        foreach ($crawler as $domElement) {
            var_dump($domElement?->nodeName);
        }
    }
}
