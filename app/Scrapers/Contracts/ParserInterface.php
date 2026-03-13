<?php

namespace App\Scrapers\Contracts;

use Symfony\Component\DomCrawler\Crawler;

interface ParserInterface
{
    /**
     * Transform raw HTML (via Crawler) into a structured array.
     */
    public function parse(Crawler $crawler): array;
}
