<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('youtube_channels', function (Blueprint $table) {
            // Rename 'name' to 'title'
            $table->renameColumn('name', 'title');

            // Add 'published_at' after the custom_url column
            $table->timestamp('published_at')->nullable()->after('custom_url');
        });
    }

    public function down(): void
    {
        Schema::table('youtube_channels', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->dropColumn('published_at');
        });
    }
};
