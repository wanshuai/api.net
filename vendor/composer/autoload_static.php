<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit43162180e3835728658ec6655ac2e3a1
{
    public static $files = array (
        'ad3464811fed6675248087dc696f0dce' => __DIR__ . '/../..' . '/app/common/defines.php',
        '79801d9789eccbf69bc39ffa7b16225f' => __DIR__ . '/../..' . '/app/common/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'v' => 
        array (
            'vendor\\' => 7,
        ),
        'm' => 
        array (
            'models\\' => 7,
        ),
        'c' => 
        array (
            'controllers\\' => 12,
        ),
        'W' => 
        array (
            'Wechat\\' => 7,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'N' => 
        array (
            'NoahBuscher\\Macaw\\' => 18,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
            'Medoo\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'vendor\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/common/vendor',
        ),
        'models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/models',
        ),
        'controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/controllers',
        ),
        'Wechat\\' => 
        array (
            0 => __DIR__ . '/..' . '/zoujingli/wechat-php-sdk/Wechat',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'NoahBuscher\\Macaw\\' => 
        array (
            0 => __DIR__ . '/..' . '/noahbuscher/macaw',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'Medoo\\' => 
        array (
            0 => __DIR__ . '/..' . '/catfan/medoo/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit43162180e3835728658ec6655ac2e3a1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit43162180e3835728658ec6655ac2e3a1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}