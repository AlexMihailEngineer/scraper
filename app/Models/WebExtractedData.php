<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebExtractedData extends Model
{
    protected $fillable = [
        'web_scrape_execution_id',
        'payload',
        'data_hash',
    ];

    protected function casts(): array
    {
        return [
            // Automatically cast the JSON column to a PHP array
            'payload' => 'array',
        ];
    }

    /**
     * The execution instance that generated this data.
     */
    public function execution(): BelongsTo
    {
        return $this->belongsTo(WebScrapeExecution::class);
    }
}
