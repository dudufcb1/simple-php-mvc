<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitde36a524d5edaf5aeb35a58d955fea79
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitde36a524d5edaf5aeb35a58d955fea79', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitde36a524d5edaf5aeb35a58d955fea79', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitde36a524d5edaf5aeb35a58d955fea79::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}