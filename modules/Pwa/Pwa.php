<?php

namespace Modules\Pwa;

class Pwa
{
    /**
     * Generate manifest file
     *
     * @return array
     */
    public function manifestGenerate()
    {
        $basicManifest = [
            'name' => config('pwa.manifest.name'),
            'short_name' => config('pwa.manifest.short_name'),
            'start_url' => asset(config('pwa.manifest.start_url')),
            'display' => config('pwa.manifest.display'),
            'theme_color' => config('pwa.manifest.theme_color'),
            'background_color' => config('pwa.manifest.background_color'),
            'orientation' => config('pwa.manifest.orientation'),
            'status_bar' => config('pwa.manifest.status_bar'),
        ];
        foreach (config('pwa.manifest.splash') as $size => $item) {
            $basicManifest['splash'][$size] = $item['path'];
        }
        foreach (config('pwa.manifest.icons') as $size => $file) {
            $fileInfo = pathinfo($file['path']);
            $basicManifest['icons'][] = [
                'src' => $file['path'],
                'type' => 'image/'.$fileInfo['extension'],
                'sizes' => $size,
                'purpose' => $file['purpose'],
            ];
        }
        if (config('pwa.manifest.shortcuts')) {
            foreach (config('pwa.manifest.shortcuts') as $shortcut) {
                if (array_key_exists('icons', $shortcut)) {
                    $fileInfo = pathinfo($shortcut['icons']['src']);
                    $icon = [
                        'src' => $shortcut['icons']['src'],
                        'type' => 'image/'.$fileInfo['extension'],
                        'purpose' => $shortcut['icons']['purpose'],
                    ];
                } else {
                    $icon = [];
                }

                $basicManifest['shortcuts'][] = [
                    'name' => trans($shortcut['name']),
                    'description' => trans($shortcut['description']),
                    'url' => $shortcut['url'],
                    'icons' => [
                        $icon,
                    ],
                ];
            }
        }

        foreach (config('pwa.manifest.custom') as $tag => $value) {
            $basicManifest[$tag] = $value;
        }

        return $basicManifest;
    }

    public function serviceWorkerJs()
    {
        $manifest = config('pwa.manifest');
        $cache = [route('pwa.offline')];
        foreach ($manifest['icons'] as $icon) {
            $cache[] = $icon['path'];
        }
        $jsCode = <<<'EOD'
            var staticCacheName = "pwa-v" + new Date().getTime();
            filesToCache = [
        EOD;
        foreach ($cache as $file) {
            $jsCode .= '"'.$file.'",';
        }

        $jsCode .= <<<'EOD'
            ];
            // Cache on install
            self.addEventListener("install", (event) => {
                this.skipWaiting();
                event.waitUntil(
                    caches.open(staticCacheName).then((cache) => {
                        return cache.addAll(filesToCache);
                    })
                );
            });

            // Clear cache on activate
            self.addEventListener("activate", (event) => {
                event.waitUntil(
                    caches.keys().then((cacheNames) => {
                        return Promise.all(
                            cacheNames
                                .filter((cacheName) => cacheName.startsWith("pwa-"))
                                .filter((cacheName) => cacheName !== staticCacheName)
                                .map((cacheName) => caches.delete(cacheName))
                        );
                    })
                );
            });

            // Serve from Cache
            self.addEventListener("fetch", (event) => {
                event.respondWith(
                    caches
                        .match(event.request)
                        .then((response) => {
                            return response || fetch(event.request);
                        })
                        .catch(() => {
                            return caches.match("offline");
                        })
                );
            });
        EOD;

        return $jsCode;
    }

    public function initJs()
    {
        $jsCode = <<<'EOD'
            if ("serviceWorker" in navigator) {
                // get service worker url from meta tag
                var serviceWorkerUrl = document
                    .querySelector('meta[name="service-worker-url"]')
                    .getAttribute("content");
                navigator.serviceWorker.register(serviceWorkerUrl).then(

                    function (registration) {
                        // Registration was successful
                        // console.log(
                            // "Laravel PWA: ServiceWorker registration successful with scope: ",
                            // registration.scope
                        // );
                    },
                    function (err) {
                        // registration failed :(
                        //console.log(
                        //"Laravel PWA: ServiceWorker registration failed: ",
                        //err
                        // );
                    }
                );
            }
    EOD;

        return $jsCode;
    }
}
