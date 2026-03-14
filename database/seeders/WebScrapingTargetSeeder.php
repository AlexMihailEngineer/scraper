<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebScrapingTarget;

class WebScrapingTargetSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Hacker News Target (Uses the specific parser)
        $url = 'https://news.ycombinator.com/news';

        WebScrapingTarget::updateOrCreate(
            ['start_url' => $url],
            [
                'name' => 'Hacker News',
                'domain' => parse_url($url, PHP_URL_HOST),
                'parser_class' => \App\Scrapers\Parsers\HackerNewsParser::class, // Full namespace
                'is_active' => true,
            ]
        );

        // 2. A Generic Site (Uses the DefaultParser because parser_class is null)
        $url = 'https://php.net/';

        WebScrapingTarget::updateOrCreate(
            ['start_url' => $url],
            [
                'name' => 'PHP Official Site',
                'domain' => parse_url($url, PHP_URL_HOST),
                'parser_class' => null,
                'is_active' => true,
            ]
        );
    }
}
