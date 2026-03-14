<?php

namespace Database\Seeders;

use App\Models\YoutubeChannel;
use App\Models\YoutubeVideo;
use Illuminate\Database\Seeder;

class YoutubeChannelSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Channel
        $channel = YoutubeChannel::updateOrCreate(
            ['youtube_channel_id' => 'UCHnyfMqiRRG1u-2MsSQLbXA'],
            [
                'title' => 'Veritasium',
                'description' => 'An element of truth - videos about science, education, and anything else.',
                'custom_url' => '@veritasium',
                'published_at' => '2010-07-21 00:00:00',
            ]
        );

        // 2. Add Sample Videos (Physics/Math focus)
        $videos = [
            [
                'youtube_video_id' => '9vkId_A6f8w',
                'title' => 'The Speed of Light is Unmeasurable',
                'description' => 'Why we can\'t actually measure the one-way speed of light.',
                'published_at' => '2020-10-31 15:00:00',
                'duration_seconds' => 1040,
            ],
            [
                'youtube_video_id' => 'p_S-R4-A0I8',
                'title' => 'The Simplest Math Problem No One Can Solve',
                'description' => 'Exploring the Collatz Conjecture.',
                'published_at' => '2021-07-30 14:00:00',
                'duration_seconds' => 1320,
            ],
        ];

        foreach ($videos as $videoData) {
            $video = $channel->videos()->updateOrCreate(
                ['youtube_video_id' => $videoData['youtube_video_id']],
                $videoData
            );

            // 3. Add a "Scraped" metric so the frontend has something to show
            $video->metrics()->create([
                'view_count' => rand(1000000, 50000000),
                'like_count' => rand(50000, 1000000),
                'scraped_at' => now(),
            ]);
        }
    }
}
