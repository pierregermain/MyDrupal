<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfe34df13f9fff10b11c61659ae66b556
{
    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Symfony\\Component\\HttpFoundation' => 
            array (
                0 => __DIR__ . '/..',
            ),
            'SessionHandlerInterface' => 
            array (
                0 => __DIR__ . '/..',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitfe34df13f9fff10b11c61659ae66b556::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
