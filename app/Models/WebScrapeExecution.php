<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WebScrapeExecution extends Model
{
    // Disable standard timestamps if you rely strictly on 'scraped_at'
    // public $timestamps = false; // Uncomment if you removed them from the migration

    protected $fillable = [
        'web_scraping_target_id',
        'url_scraped',
        'http_status_code',
        'response_time_ms',
        'is_successful',
        'error_message',
        'scraped_at',
    ];

    protected function casts(): array
    {
        return [
            'http_status_code' => 'integer',
            'response_time_ms' => 'integer',
            'is_successful' => 'boolean',
            'scraped_at' => 'datetime',
        ];
    }

    /**
     * The target configuration that initiated this execution.
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(WebScrapingTarget::class, 'web_scraping_target_id');
    }

    /**
     * The structured data extracted during this execution.
     */
    public function extractedData(): HasOne
    {
        return $this->hasOne(WebExtractedData::class);
    }

    /**
     * The raw HTML stored during this execution.
     */
    public function rawArchive(): HasOne
    {
        return $this->hasOne(WebRawArchive::class);
    }
}
