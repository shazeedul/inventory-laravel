<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7d9abac0f3be3305ab6bdb502a616d57
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\User\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\User\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Modules\\User\\DataTables\\UserDataTable' => __DIR__ . '/../..' . '/DataTables/UserDataTable.php',
        'Modules\\User\\Http\\Controllers\\UserController' => __DIR__ . '/../..' . '/Http/Controllers/UserController.php',
        'Modules\\User\\Providers\\RouteServiceProvider' => __DIR__ . '/../..' . '/Providers/RouteServiceProvider.php',
        'Modules\\User\\Providers\\UserServiceProvider' => __DIR__ . '/../..' . '/Providers/UserServiceProvider.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7d9abac0f3be3305ab6bdb502a616d57::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7d9abac0f3be3305ab6bdb502a616d57::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7d9abac0f3be3305ab6bdb502a616d57::$classMap;

        }, null, ClassLoader::class);
    }
}
