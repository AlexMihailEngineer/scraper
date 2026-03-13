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
        Schema::create('web_scrape_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_scraping_target_id')->constrained()->cascadeOnDelete();
            $table->string('url_scraped'); // The specific page hit
            $table->integer('http_status_code')->nullable();
            $table->integer('response_time_ms')->nullable();
            $table->boolean('is_successful')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamp('scraped_at')->useCurrent();
            $table->timestamps();

            // Indexing for quick health dashboard queries
            $table->index(['web_scraping_target_id', 'scraped_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_scrape_executions');
    }
};
