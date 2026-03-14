<?php

namespace App\Jobs;

use App\Models\YoutubeChannel;
use App\Models\YoutubeVideo;
use App\Models\YoutubeVideoMetric;
use App\Services\YoutubeScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;

class SyncYouTubeChannelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60; // APIs should respond quickly
    public int $backoff = 300; // Wait 5 minutes before retrying API failures

    public function __construct(public YoutubeChannel $channel) {}

    public function handle(YoutubeScraperService $scraper): void
    {
        try {
            $videos = $scraper->getLatestVideosForChannel($this->channel->youtube_channel_id);

            foreach ($videos as $videoData) {
                // 1. Get the ISO 8601 duration string (e.g., "PT17M20S")
                $isoDuration = $videoData->getContentDetails()->getDuration();

                // 2. Convert ISO 8601 to seconds
                $durationInSeconds = 0;
                try {
                    $interval = new \DateInterval($isoDuration);
                    $durationInSeconds = ($interval->d * 86400) + ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
                } catch (\Exception $e) {
                    Log::warning("Could not parse duration for video {$videoData->getId()}: " . $e->getMessage());
                }

                $video = YoutubeVideo::updateOrCreate(
                    [
                        'youtube_channel_id' => $this->channel->id,
                        'youtube_video_id' => $videoData->getId(),
                    ],
                    [
                        'title' => $videoData->getSnippet()->getTitle(),
                        'description' => $videoData->getSnippet()->getDescription(),
                        'published_at' => Carbon::parse($videoData->getSnippet()->getPublishedAt()),
                        'duration_seconds' => $durationInSeconds,
                    ]
                );

                YoutubeVideoMetric::create([
                    'youtube_video_id' => $video->id,
                    'view_count' => $videoData->getStatistics()->getViewCount() ?? 0,
                    'like_count' => $videoData->getStatistics()->getLikeCount() ?? 0,
                    'comment_count' => $videoData->getStatistics()->getCommentCount() ?? 0,
                    'scraped_at' => now(),
                ]);
            }
        } catch (Throwable $e) {
            Log::error("YouTube Sync Failed for Channel {$this->channel->youtube_channel_id}: " . $e->getMessage());

            // If it's a quota error, you might want to fail the job permanently instead of retrying
            if (str_contains(strtolower($e->getMessage()), 'quota')) {
                $this->fail($e);
                return;
            }

            throw $e;
        }
    }
}
