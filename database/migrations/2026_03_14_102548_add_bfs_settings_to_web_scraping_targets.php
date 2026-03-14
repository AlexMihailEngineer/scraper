<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('web_scraping_targets', function (Blueprint $table) {
            // max_depth (n) determines how many steps to follow
            $table->integer('max_depth')->default(1)->after('start_url');

            // crawl_boundary ensures we don't leave the site (e.g., 'example.com')
            $table->string('crawl_boundary')->nullable()->after('max_depth');
        });
    }

    public function down(): void
    {
        Schema::table('web_scraping_targets', function (Blueprint $table) {
            $table->dropColumn(['max_depth', 'crawl_boundary']);
        });
    }
};
