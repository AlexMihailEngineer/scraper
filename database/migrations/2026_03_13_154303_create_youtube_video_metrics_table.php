<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('youtube_video_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youtube_video_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->timestamp('scraped_at')->useCurrent();

            // Indexing the scraped_at column will drastically speed up analytical queries later
            $table->index(['youtube_video_id', 'scraped_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_video_metrics');
    }
};
