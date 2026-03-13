<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YoutubeTranscript extends Model
{
    protected $fillable = [
        'youtube_video_id',
        'language_code',
        'content',
        'is_auto_generated',
    ];

    protected function casts(): array
    {
        return [
            'is_auto_generated' => 'boolean',
        ];
    }

    /**
     * Get the video this transcript belongs to.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(YoutubeVideo::class);
    }
}
