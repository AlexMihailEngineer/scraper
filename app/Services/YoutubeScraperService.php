<?php

namespace App\Services;



use Illuminate\Support\Facades\Log;
use Google\Service\Exception as GoogleException;
use Google\Service\YouTube;
use Google\Service\YouTube\Video;

class YoutubeScraperService
{
    public function __construct(protected YouTube $youtube) {}

    /**
     * @return Video|null
     */
    public function getVideoById(string $videoId): ?Video
    {
        try {
            // Fetch video details
            $response = $this->youtube->videos->listVideos('snippet,statistics,contentDetails', [
                'id' => $videoId,
            ]);

            $items = $response->getItems();

            return !empty($items) ? $items[0] : null;

            // Fetch channel details (optional)
            // $channelResponse = $youtube->channels->list('snippet', ['id' => $videoData->getSnippet()->getChannelId()]);

        } catch (GoogleException $e) {
            Log::error("YouTube API Error: " . $e->getMessage());
            return null;
        }
    }
}
