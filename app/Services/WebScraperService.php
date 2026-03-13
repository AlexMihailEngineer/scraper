<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;

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
}
