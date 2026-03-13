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
        Schema::create('web_extracted_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_scrape_execution_id')->constrained()->cascadeOnDelete();

            // We store the data as a JSON document. In MySQL/PostgreSQL, you can query directly inside this JSON later.
            $table->json('payload');

            // A hash of the payload to easily detect if the data actually changed since the last scrape
            $table->string('data_hash', 64)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_extracted_data');
    }
};
