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
        Schema::create('web_raw_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_scrape_execution_id')->constrained()->cascadeOnDelete();

            // Using longText because HTML payloads can be massive
            // In a massive production system, you'd save this to AWS S3 and store the URL here,
            // but a database column is perfect for this portfolio stage.
            $table->longText('raw_html');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_raw_archives');
    }
};
