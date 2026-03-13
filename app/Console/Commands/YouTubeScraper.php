<?php

namespace App\Console\Commands;

use App\Models\YoutubeChannel;
use App\Jobs\SyncYouTubeChannelJob;
use Illuminate\Console\Command;

class YouTubeScraper extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'scrape:youtube'; // Renamed to match the scrape:web convention

    /**
     * The console command description.
     */
    protected $description = 'Dispatch jobs to sync latest videos and metrics for all active YouTube channels';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting YouTube channel synchronization...");

        // 1. Fetch all channels from the database
        // (You might want to add an 'is_active' boolean to the youtube_channels table later)
        $channels = YoutubeChannel::all();

        if ($channels->isEmpty()) {
            $this->warn("No YouTube channels found in the database. Please seed some first.");
            return;
        }

        $this->info("Found " . $channels->count() . " channel(s). Dispatching to queue...");

        // 2. Dispatch a job for each channel
        foreach ($channels as $channel) {
            SyncYouTubeChannelJob::dispatch($channel);
            $this->line("<info>Dispatched:</info> {$channel->name} ({$channel->youtube_channel_id})");
        }

        $this->info("All YouTube sync jobs have been sent to the queue. Run 'php artisan queue:work' to process them.");
    }
}
