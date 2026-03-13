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
        Schema::create('youtube_transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youtube_video_id')->constrained()->cascadeOnDelete();
            $table->string('language_code', 10)->default('en');
            $table->longText('content'); // Transcripts can be massive
            $table->boolean('is_auto_generated')->default(false);
            $table->timestamps();

            $table->unique(['youtube_video_id', 'language_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_transcripts');
    }
};
