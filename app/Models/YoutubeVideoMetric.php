<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YoutubeVideoMetric extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'youtube_video_id',
        'view_count',
        'like_count',
        'comment_count',
        'scraped_at',
    ];

    protected function casts(): array
    {
        return [
            'view_count' => 'integer',
            'like_count' => 'integer',
            'comment_count' => 'integer',
            'scraped_at' => 'datetime',
        ];
    }

    /**
     * Get the video these metrics belong to.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(YoutubeVideo::class);
    }
}
