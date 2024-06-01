<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitde36a524d5edaf5aeb35a58d955fea79
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Model\\' => 6,
            'MVC\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Model\\' => 
        array (
            0 => __DIR__ . '/../..' . '/models',
        ),
        'MVC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitde36a524d5edaf5aeb35a58d955fea79::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitde36a524d5edaf5aeb35a58d955fea79::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitde36a524d5edaf5aeb35a58d955fea79::$classMap;

        }, null, ClassLoader::class);
    }
}
