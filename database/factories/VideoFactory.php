<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'youtube_video_id' => fake()->regexify('[A-Za-z0-9_-]{11}'),
            'title' => fake()->sentence(6, true),
            'description' => fake()->paragraphs(2, true),
            'published_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'view_count' => fake()->numberBetween(0, 2000000),
            'like_count' => fake()->numberBetween(0, 1000000),
            'comment_count' => fake()->numberBetween(0, 50000),
            'channel_id' => fake()->regexify('[A-Za-z0-9_-]{11}'),
            'thumbnail_url' => fake()->imageUrl(320, 180, 'video', true, 'abcdef'),
            'duration' => fake()->numberBetween(0, 7200),
            'crawled_at' => fake()->dateTimeThisYear(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
