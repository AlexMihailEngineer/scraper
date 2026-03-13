<?php

namespace App\Scrapers\Parsers;

use App\Scrapers\Contracts\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class HackerNewsParser implements ParserInterface
{
    public function parse(Crawler $crawler): array
    {
        return $crawler->filter('.titleline > a')->each(function (Crawler $node) {
            return [
                'title' => $node->text(),
                'url'   => $node->attr('href'),
                'source' => 'Hacker News',
            ];
        });
    }
}
