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
            'title' => 'IQBAL HASAN DEV', // set false to total remove
            'titleBefore' => false, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description' => 'iqbalhasan.dev is a portfolio and multipurpose website. This website will be used primarily as a portfolio of IQBAL HASAN and will be used for public blogging, open source packages, free tutorials and client management through several subdomains."', // set false to total remove
            'separator' => ' - ',
            'keywords' => [
                'iqbalhasan',
                'iqbalhasan.dev',
                'iqbalhasan.dev portfolio',
                'iqbalhasan.dev blog',
                'iqbalhasan.dev open source',
                'iqbalhasan.dev free tutorials',
                'iqbalhasan.dev client management',
                'iqbalhasan.dev iqbalhasan',
                'iqbalhasan.dev iqbalhasan.dev',
                'iqbalhasan.dev iqbalhasan.dev portfolio',
                'iqbalhasan.dev iqbalhasan.dev blog',
                'iqbalhasan.dev iqbalhasan.dev open source',
                'iqbalhasan.dev iqbalhasan.dev free tutorials',
                'iqbalhasan.dev iqbalhasan.dev client management',
                'IQBAL HASAN',
                'IQBAL HASAN DEV',
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
            'title' => 'IQBAL HASAN DEV', // set false to total remove
            'description' => 'iqbalhasan.dev is a portfolio and multipurpose website. This website will be used primarily as a portfolio of IQBAL HASAN and will be used for public blogging, open source packages, free tutorials and client management through several subdomains."', // set false to total remove
            'url' => null, // Set null for using Url::current(), set false to total remove
            'type' => 'WebPage',
            'site_name' => 'iqbalhasan.dev',
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
            'title' => 'IQBAL HASAN DEV', // set false to total remove
            'description' => 'iqbalhasan.dev is a portfolio and multipurpose website. This website will be used primarily as a portfolio of IQBAL HASAN and will be used for public blogging, open source packages, free tutorials and client management through several subdomains."', // set false to total remove
            'url' => null, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type' => 'WebPage',
            'images' => [],
        ],
    ],
];
