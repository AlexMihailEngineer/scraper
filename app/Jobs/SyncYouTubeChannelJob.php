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
use Carbon\Carbon;

class SyncYouTubeChannelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public YoutubeChannel $channel) {}

    public function handle(YoutubeScraperService $scraper): void
    {
        // Use YOUR service using the Google SDK
        $videos = $scraper->getLatestVideosForChannel($this->channel->youtube_channel_id);

        foreach ($videos as $videoData) {
            // 1. Save or update the Video Metadata
            $video = YoutubeVideo::updateOrCreate(
                [
                    'youtube_channel_id' => $this->channel->id,
                    'youtube_video_id' => $videoData->getId(),
                ],
                [
                    'title' => $videoData->getSnippet()->getTitle(),
                    'description' => $videoData->getSnippet()->getDescription(),
                    'published_at' => Carbon::parse($videoData->getSnippet()->getPublishedAt()),
                ]
            );

            // 2. Insert the Time-Series Metrics
            YoutubeVideoMetric::create([
                'youtube_video_id' => $video->id,
                'view_count' => $videoData->getStatistics()->getViewCount() ?? 0,
                'like_count' => $videoData->getStatistics()->getLikeCount() ?? 0,
                'comment_count' => $videoData->getStatistics()->getCommentCount() ?? 0,
                'scraped_at' => now(),
            ]);
        }
    }
}
