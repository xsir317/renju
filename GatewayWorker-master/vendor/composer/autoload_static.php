<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita6600bcdd1349710b4399dbdda5c7cdb
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
        'G' => 
        array (
            'GatewayWorker\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
        ),
        'GatewayWorker\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita6600bcdd1349710b4399dbdda5c7cdb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita6600bcdd1349710b4399dbdda5c7cdb::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
