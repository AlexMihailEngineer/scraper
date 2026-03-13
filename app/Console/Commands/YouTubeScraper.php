<?php

namespace App\Console\Commands;

use App\Services\YoutubeScraperService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class YouTubeScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:youtube-scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(YoutubeScraperService $scraper)
    {
        $videoId = "2vjPBrBU-TM";
        $video = $scraper->getVideoById($videoId);

        if (!$video) {
            $this->error("Video not found or API error.");
            return;
        }

        $this->info("Title: " . $video->getSnippet()->getTitle());
        $this->comment("Views: " . $video->getStatistics()->getViewCount());
    }
}
