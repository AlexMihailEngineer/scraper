<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebScrapingTarget extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'start_url',
        'max_depth',        // Added for BFS
        'crawl_boundary',   // Added for BFS
        'parser_class',
        'is_active',
        'scrape_interval_minutes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'scrape_interval_minutes' => 'integer',
        ];
    }

    /**
     * Get all execution logs for this target.
     */
    public function executions(): HasMany
    {
        return $this->hasMany(WebScrapeExecution::class);
    }

    /**
     * The discovery queue associated with this target.
     */
    public function crawlQueue()
    {
        return $this->hasMany(WebCrawlQueue::class);
    }
}
