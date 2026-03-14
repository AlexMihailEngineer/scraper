<?php

namespace App\Http\Controllers;

use App\Models\YoutubeVideo;
use Inertia\Inertia;
use Inertia\Response;

class YoutubeScrapeController extends Controller
{
    /**
     * Display a listing of the scraped YouTube videos.
     */
    public function index(): Response
    {
        return Inertia::render('Youtube/Index', [
            'videos' => YoutubeVideo::with(['channel', 'latestMetric'])
                ->orderBy('published_at', 'desc')
                ->paginate(12)
        ]);
    }
}
