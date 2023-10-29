<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit56829e06f8b043348b35e4940c5f6b16
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\Language\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\Language\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Modules\\Language\\Database\\Seeders\\LanguageTableSeeder' => __DIR__ . '/../..' . '/Database/Seeders/LanguageTableSeeder.php',
        'Modules\\Language\\Entities\\Language' => __DIR__ . '/../..' . '/Entities/Language.php',
        'Modules\\Language\\Http\\Controllers\\LanguageController' => __DIR__ . '/../..' . '/Http/Controllers/LanguageController.php',
        'Modules\\Language\\Http\\Requests\\LanguageRequest' => __DIR__ . '/../..' . '/Http/Requests/LanguageRequest.php',
        'Modules\\Language\\Providers\\LanguageServiceProvider' => __DIR__ . '/../..' . '/Providers/LanguageServiceProvider.php',
        'Modules\\Language\\Providers\\RouteServiceProvider' => __DIR__ . '/../..' . '/Providers/RouteServiceProvider.php',
        'Modules\\Language\\Transformers\\LanguageResource' => __DIR__ . '/../..' . '/Transformers/LanguageResource.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit56829e06f8b043348b35e4940c5f6b16::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit56829e06f8b043348b35e4940c5f6b16::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit56829e06f8b043348b35e4940c5f6b16::$classMap;

        }, null, ClassLoader::class);
    }
}