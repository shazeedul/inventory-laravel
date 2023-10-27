<?php

/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'title' => 'SYED SHAZEEDUL ISLAM DEV', // set false to total remove
            'titleBefore' => false, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description' => 'shazeedul.dev is a portfolio and multipurpose website. This website will be used primarily as a portfolio of SYED SHAZEEDUL ISLAM and will be used for public blogging, open source packages, free tutorials and client management through several subdomains."', // set false to total remove
            'separator' => ' - ',
            'keywords' => [
                'shazeedul',
                'shazeedul.dev',
                'shazeedul.dev portfolio',
                'shazeedul.dev blog',
                'shazeedul.dev open source',
                'shazeedul.dev free tutorials',
                'shazeedul.dev client management',
                'shazeedul.dev shazeedul',
                'shazeedul.dev shazeedul.dev',
                'shazeedul.dev shazeedul.dev portfolio',
                'shazeedul.dev shazeedul.dev blog',
                'shazeedul.dev shazeedul.dev open source',
                'shazeedul.dev shazeedul.dev free tutorials',
                'shazeedul.dev shazeedul.dev client management',
                'SYED SHAZEEDUL ISLAM',
                'SYED SHAZEEDUL ISLAM DEV',
                'jannatulportfolio',
                'jannatul.me',
                'jannatul.me portfolio',
            ],
            'canonical' => 'current', // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots' => 'all', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title' => 'SYED SHAZEEDUL ISLAM DEV', // set false to total remove
            'description' => 'shazeedul.dev is a portfolio and multipurpose website. This website will be used primarily as a portfolio of SYED SHAZEEDUL ISLAM and will be used for public blogging, open source packages, free tutorials and client management through several subdomains."', // set false to total remove
            'url' => null, // Set null for using Url::current(), set false to total remove
            'type' => 'WebPage',
            'site_name' => 'shazeedul.dev',
            'images' => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            //'card'        => 'summary',
            //'site'        => '@LuizVinicius73',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title' => 'SYED SHAZEEDUL ISLAM DEV', // set false to total remove
            'description' => 'shazeedul.dev is a portfolio and multipurpose website. This website will be used primarily as a portfolio of SYED SHAZEEDUL ISLAM and will be used for public blogging, open source packages, free tutorials and client management through several subdomains."', // set false to total remove
            'url' => null, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type' => 'WebPage',
            'images' => [],
        ],
    ],
];
