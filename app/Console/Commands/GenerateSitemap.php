<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate a sitemap for your website.';

    public function handle()
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return ! $this->shouldExcludeRoute($route);
        })->map(function ($route) {
            return $this->prepareUrl($route->uri, $route->getDomain());
        });
        $sitemap = $this->generateSitemapXML($routes);

        // Format the XML content for better readability
        $formattedSitemap = $this->formatXML($sitemap);

        $storePath = Config::get('sitemap.store_path');
        file_put_contents($storePath, $formattedSitemap);

        $this->info('Sitemap generated and saved to '.$storePath);
    }

    private function shouldExcludeRoute($route)
    {
        $exceptUrls = Config::get('sitemap.except_url');
        $exceptMethods = Config::get('sitemap.except_method');

        $uri = $route->uri();
        $methods = $route->methods();

        foreach ($exceptUrls as $pattern) {
            if (Str::is($pattern, $uri)) {
                return true;
            }
        }

        foreach ($methods as $method) {
            if (in_array($method, $exceptMethods)) {
                return true;
            }
        }

        return false;
    }

    private function prepareUrl($url, $default_domain = null)
    {
        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }
        // check if begins with a forward slash if not add one
        if (! Str::startsWith($url, '/')) {
            $url = '/'.$url;
        }

        return $default_domain ? $default_domain.$url : config('app.url');
    }

    private function generateSitemapXML($routes)
    {
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $urlset = $xml->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $urlset->setAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
        $urlset->setAttribute('xmlns:video', 'http://www.google.com/schemas/sitemap-video/1.1');
        $xml->appendChild($urlset);

        foreach ($routes as $route) {
            $url = $xml->createElement('url');
            $loc = $xml->createElement('loc', $route);
            $changefreq = $xml->createElement('changefreq', 'daily');
            $priority = $xml->createElement('priority', '1.0');
            $url->appendChild($loc);
            $url->appendChild($changefreq);
            $url->appendChild($priority);
            $urlset->appendChild($url);
        }

        return $xml->saveXML();
    }

    private function formatXML($xmlString)
    {
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlString);

        return $dom->saveXML();
    }
}
