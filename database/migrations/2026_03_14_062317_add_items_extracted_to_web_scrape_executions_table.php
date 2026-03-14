<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('web_scrape_executions', function (Blueprint $table) {
            // Adding the column to track how many unique items were saved
            $table->integer('items_extracted')->default(0)->after('is_successful');
        });
    }

    public function down(): void
    {
        Schema::table('web_scrape_executions', function (Blueprint $table) {
            $table->dropColumn('items_extracted');
        });
    }
};
