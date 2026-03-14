<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebScraperService
{
    protected $client;

    public function __construct()
    {
        // We configure the client with a real User-Agent to avoid being blocked immediately
        $this->client = new Client([
            'timeout'         => 30.0,
            'connect_timeout' => 15.0,
            'force_ip_resolve' => 'v4', // Crucial: Skip the IPv6 "unreachable" wait
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; Fedora; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            ]
        ]);
    }

    public function scrapeStaticPage(string $url)
    {
        try {
            $response = $this->client->get($url);
            $html = (string) $response->getBody();

            return new Crawler($html);
        } catch (\Exception $e) {
            Log::error("Failed to scrape $url: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extracts, normalizes, and filters links from a Crawler object.
     */
    public function discoverLinks(Crawler $crawler, string $baseUrl, ?string $boundary = null): array
    {
        // 1. Extract all href attributes and convert them to absolute URLs
        // Symfony's Crawler::links() automatically handles relative to absolute conversion
        // using the base URL of the document.
        $links = collect($crawler->filter('a')->links())->map(function ($link) {
            $url = $link->getUri();

            // 2. Normalize: Remove URL fragments (#section-1)
            return preg_replace('/#.*$/', '', $url);
        });

        return $links
            ->unique()
            ->filter(function ($url) use ($boundary) {
                // Remove empty strings or non-web links
                if (empty($url) || !Str::startsWith($url, ['http://', 'https://'])) {
                    return false;
                }

                // 3. Filter: Stay within the crawl boundary if one is provided
                if ($boundary) {
                    $host = parse_url($url, PHP_URL_HOST);
                    return $host === $boundary || Str::endsWith($host, '.' . $boundary);
                }

                return true;
            })
            ->values()
            ->toArray();
    }
}
