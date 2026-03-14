<?php

namespace App\Http\Controllers;

use App\Models\WebExtractedData;
use Inertia\Inertia;
use Inertia\Response;

class WebScrapeController extends Controller
{
    /**
     * Display a listing of the web scraped data.
     */
    public function index(): Response
    {
        return Inertia::render('WebData/Index', [
            'extractedData' => WebExtractedData::with('execution.target')
                ->latest()
                ->paginate(10)
        ]);
    }
}
