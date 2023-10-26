<?php

// 'short_url' => preg_replace('#^https?://#', '', rtrim(env('APP_URL', 'http://localhost'), '/')),
return [
    'portfolio' => config('app.short_url'),
    'www' => 'www'.'.'.config('app.short_url'),
    'admin' => 'admin'.'.'.config('app.short_url'),
    'apps' => 'app'.'.'.config('app.short_url'),
    'telescope' => 'telescope'.'.'.config('app.short_url'),
    'bteb' => env('BTEB_DOMAIN', 'bteb'.'.'.config('app.short_url')),
    'api' => 'api'.'.'.config('app.short_url'),
    'jannatul' => env('JANNATUL_DOMAIN', 'jannatul.test'),
];
