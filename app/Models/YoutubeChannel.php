<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YoutubeChannel extends Model
{
    // Protect against mass assignment vulnerabilities
    protected $fillable = [
        'youtube_channel_id',
        'title',
        'description',
        'custom_url',
    ];

    /**
     * Get the videos associated with this channel.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(YoutubeVideo::class);
    }
}
