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

    /**
     * Get recent videos for a specific channel.
     * * @return Video[]
     */
    public function getLatestVideosForChannel(string $channelId, int $maxResults = 10): array
    {
        try {
            // First, do a search to get the video IDs for the channel
            $searchResponse = $this->youtube->search->listSearch('id', [
                'channelId' => $channelId,
                'type' => 'video',
                'order' => 'date',
                'maxResults' => $maxResults,
            ]);

            $videoIds = [];
            foreach ($searchResponse->getItems() as $searchResult) {
                $videoIds[] = $searchResult->getId()->getVideoId();
            }

            if (empty($videoIds)) {
                return [];
            }

            // Then, fetch the full statistics and snippets for those IDs
            $videoResponse = $this->youtube->videos->listVideos('snippet,statistics,contentDetails', [
                'id' => implode(',', $videoIds),
            ]);

            return $videoResponse->getItems();
        } catch (GoogleException $e) {
            Log::error("YouTube API Error: " . $e->getMessage());
            return [];
        }
    }
}
