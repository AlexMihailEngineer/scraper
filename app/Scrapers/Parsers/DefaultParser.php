<?php

namespace App\Scrapers\Parsers;

use App\Scrapers\Contracts\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class DefaultParser implements ParserInterface
{
    /**
     * Extracts basic metadata common to almost all HTML pages.
     */
    public function parse(Crawler $crawler): array
    {
        return [
            'meta' => [
                'title' => $this->extractTitle($crawler),
                'description' => $this->extractMeta($crawler, 'description'),
                'keywords' => $this->extractMeta($crawler, 'keywords'),
                'canonical' => $crawler->filter('link[rel="canonical"]')->count() > 0
                    ? $crawler->filter('link[rel="canonical"]')->attr('href')
                    : null,
            ],
            'stats' => [
                'link_count' => $crawler->filter('a')->count(),
                'image_count' => $crawler->filter('img')->count(),
                'h1_count' => $crawler->filter('h1')->count(),
            ],
            'text_sample' => substr(strip_tags($crawler->filter('body')->html()), 0, 500),
        ];
    }

    private function extractTitle(Crawler $crawler): string
    {
        return $crawler->filter('title')->count() > 0
            ? $crawler->filter('title')->text()
            : 'No title found';
    }

    private function extractMeta(Crawler $crawler, string $name): ?string
    {
        $selector = "meta[name='{$name}'], meta[property='og:{$name}']";
        return $crawler->filter($selector)->count() > 0
            ? $crawler->filter($selector)->attr('content')
            : null;
    }
}
