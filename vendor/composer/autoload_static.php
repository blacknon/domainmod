<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb2ab50c1bc2ddce3c955d66d9b575d56
{
    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'Cron' => 
            array (
                0 => __DIR__ . '/..' . '/mtdowling/cron-expression/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitb2ab50c1bc2ddce3c955d66d9b575d56::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
