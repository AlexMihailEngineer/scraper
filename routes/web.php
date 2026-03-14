<?php

use App\Http\Controllers\WebScrapeController;
use App\Http\Controllers\YoutubeScrapeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
});

Route::get('/web-data', [WebScrapeController::class, 'index'])->name('web.index');
Route::get('/youtube-data', [YoutubeScrapeController::class, 'index'])->name('youtube.index');
