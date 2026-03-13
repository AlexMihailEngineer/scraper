<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class YouTubeScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:you-tube-scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new \Google\Client();
        $client->setApplicationName("Laravel Scraper");
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $youtube = new \Google\Service\YouTube($client);
        $videoId = "2vjPBrBU-TMalex";

        try {
            // Fetch video details
            $videoResponse = $youtube->videos->listVideos('snippet,statistics,contentDetails', [
                'id' => $videoId,
            ]);

            if (!empty($videoResponse->getItems())) {
                $videoData = $videoResponse->getItems()[0];
                // ... process and save videoData to your Video model
            }

            // Fetch channel details (optional)
            // $channelResponse = $youtube->channels->list('snippet', ['id' => $videoData->getSnippet()->getChannelId()]);

        } catch (\Google\Service\Exception $e) {
            // Log error, Telescope will capture this
            Log::error("YouTube API Error: " . $e->getMessage());
        }
    }
}
