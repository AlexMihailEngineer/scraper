<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('web_crawl_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_scraping_target_id')->constrained()->cascadeOnDelete();

            // The URL and a hash for fast unique lookups
            $table->text('url');
            $table->string('url_hash', 64)->index();

            // BFS Depth tracking
            $table->integer('depth')->default(0);

            // Status for job management
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');

            $table->timestamps();

            // Ensure we don't crawl the same URL twice for the same target
            $table->unique(['web_scraping_target_id', 'url_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_crawl_queue');
    }
};
