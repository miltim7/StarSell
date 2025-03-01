<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd06cb2166e2d08c15a9c10b4444f2d03
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'L' => 
        array (
            'Lava\\Api\\' => 9,
        ),
        'C' => 
        array (
            'Classes\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Lava\\Api\\' => 
        array (
            0 => __DIR__ . '/..' . '/lava-payment/lava/src',
        ),
        'Classes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd06cb2166e2d08c15a9c10b4444f2d03::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd06cb2166e2d08c15a9c10b4444f2d03::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd06cb2166e2d08c15a9c10b4444f2d03::$classMap;

        }, null, ClassLoader::class);
    }
}
