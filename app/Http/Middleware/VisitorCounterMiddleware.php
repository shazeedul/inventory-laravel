<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class VisitorCounterMiddleware
{
    private $excludedUrls = [
        '*/manifest.json',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $visitorKey = 'unique_visitor';
        $response = $next($request);

        $path = $request->getPathInfo();
        $paths = explode('/', $path);
        foreach ($this->excludedUrls as $url) {
            // if starts with * then
            if (strpos($url, '*') === 0) {
                // get the rest of the url
                $url = substr($url, 2);
                // now check if the paths array last element is equal to the url
                if (end($paths) === $url) {
                    // if it is then add the pattern to the patterns array
                    return $response;
                }
            } elseif (strpos($url, '*') === strlen($url) - 1) {
                // get the rest of the url without /*
                $url = substr($url, 0, -2);
                // now check if the paths array first element is equal to the url
                if (reset($paths) === $url) {
                    // if it is then add the pattern to the patterns array
                    return $response;
                }
            } elseif (strpos($url, '/*/') !== false) {
                [$prefix, $suffix] = explode('/*/', $url);

                if (count($paths) >= 3) {
                    $firstSegment = $paths[1];
                    $lastSegment = end($paths);
                    // remove / if exists
                    $prefix = str_replace('/', '', $prefix);
                    if ($firstSegment == $prefix && $lastSegment == $suffix) {
                        return $response;
                    }
                }
            } else {
                if ($path == $url) {
                    return $response;
                }
            }

        }

        if (! $request->cookie($visitorKey)) {
            $uniqueIdentifier = $this->generateUniqueIdentifier();
            $response->withCookie(Cookie::make($visitorKey, $uniqueIdentifier, 1440)); // 1440 minutes = 24 hours
            // Set the cookie for one day
            // Increment the visitor count in the database
            Visitor::add(now()->toDateString());
        }

        return $response;
    }

    private function generateUniqueIdentifier()
    {
        // Generate a unique identifier (e.g., UUID)
        return uniqid();
    }
}
