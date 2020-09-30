<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit878ac874030e8db8e16ea71d0be56e5f
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('YOOtheme\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \YOOtheme\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit878ac874030e8db8e16ea71d0be56e5f', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \YOOtheme\Autoload\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInit878ac874030e8db8e16ea71d0be56e5f', 'loadClassLoader'));

        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION') && (!function_exists('zend_loader_file_encoded') || !zend_loader_file_encoded());
        if ($useStaticLoader) {
            require_once __DIR__ . '/autoload_static.php';

            call_user_func(\YOOtheme\Autoload\ComposerStaticInit878ac874030e8db8e16ea71d0be56e5f::getInitializer($loader));
        } else {
            $map = require __DIR__ . '/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }

            $map = require __DIR__ . '/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }

            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        $loader->register(true);

        if ($useStaticLoader) {
            $includeFiles = YOOtheme\Autoload\ComposerStaticInit878ac874030e8db8e16ea71d0be56e5f::$files;
        } else {
            $includeFiles = require __DIR__ . '/autoload_files.php';
        }
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequire878ac874030e8db8e16ea71d0be56e5f($fileIdentifier, $file);
        }

        return $loader;
    }
}

function composerRequire878ac874030e8db8e16ea71d0be56e5f($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}