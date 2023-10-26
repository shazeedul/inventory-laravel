<?php

return [

    /**
     * The default domain
     */
    'default_domain' => config('app.url'),

    /**
     * The Sitemap Store path and filename
     */
    'store_path' => public_path('sitemap.xml'),

    /**
     * The Sitemap Ignore Url
     */
    'except_url' => [
        'admin/*',
        'api/*',
    ],

    /**
     * The Sitemap except Routes Method
     */
    'except_method' => [
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
    ],

];
