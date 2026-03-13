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
        Schema::create('web_scraping_targets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Hacker News Frontpage"
            $table->string('domain')->index(); // e.g., "news.ycombinator.com"
            $table->string('start_url');
            $table->string('parser_class')->nullable(); // e.g., 'App\Scrapers\Parsers\HackerNewsParser'
            $table->boolean('is_active')->default(true);
            $table->integer('scrape_interval_minutes')->nullable(); // For scheduling
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_scraping_targets');
    }
};
