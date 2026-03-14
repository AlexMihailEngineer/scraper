<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebCrawlQueue extends Model
{
    protected $table = 'web_crawl_queue';

    protected $fillable = [
        'web_scraping_target_id',
        'url',
        'url_hash',
        'depth',
        'status',
    ];

    public function target(): BelongsTo
    {
        return $this->belongsTo(WebScrapingTarget::class, 'web_scraping_target_id');
    }
}
