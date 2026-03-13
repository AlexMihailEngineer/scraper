<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class YoutubeVideo extends Model
{
    protected $fillable = [
        'youtube_channel_id',
        'youtube_video_id',
        'title',
        'description',
        'duration_seconds',
        'published_at',
    ];

    // Cast the published_at column to a Carbon instance automatically
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'duration_seconds' => 'integer',
        ];
    }

    /**
     * Get the channel that owns this video.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(YoutubeChannel::class);
    }

    /**
     * Get the time-series metrics for this video.
     */
    public function metrics(): HasMany
    {
        return $this->hasMany(YoutubeVideoMetric::class);
    }

    /**
     * Get all transcripts (different languages) for this video.
     */
    public function transcripts(): HasMany
    {
        return $this->hasMany(YoutubeTranscript::class);
    }

    /**
     * Helper: Get only the most recently scraped metrics.
     */
    public function latestMetric(): HasOne
    {
        return $this->hasOne(YoutubeVideoMetric::class)->latestOfMany('scraped_at');
    }
}
