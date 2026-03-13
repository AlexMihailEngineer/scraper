<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebRawArchive extends Model
{
    protected $fillable = [
        'web_scrape_execution_id',
        'raw_html',
    ];

    /**
     * The execution instance that generated this archive.
     */
    public function execution(): BelongsTo
    {
        return $this->belongsTo(WebScrapeExecution::class);
    }
}
