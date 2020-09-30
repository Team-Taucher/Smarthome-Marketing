<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\MVC\Controller\BaseController;
use YOOtheme\Config;
use YOOtheme\Event;
use YOOtheme\File;

class ChildThemeListener
{
    public static function initTheme(Config $config)
    {
        if (!$child = $config('~theme.child_theme')) {
            return;
        }

        if (!file_exists($childDir = "{$config('theme.rootDir')}_{$child}") ? $childDir : null) {
            return;
        }

        // add childDir to config
        $config->set('theme.childDir', $childDir);

        // add ~theme alias resolver
        Event::on('path ~theme', function ($path, $file) use ($childDir) {
            return $file && File::find($childDir . $file) ? $childDir . $file : $path;
        });

        $views = (new \ReflectionClass(BaseController::class))->getProperty('views');
        $views->setAccessible(true);
        $views->setValue(new ViewsObject($config('theme.template'), basename($childDir)));
    }

    public static function initCustomizer(Config $config)
    {
        $config->set('theme.child_themes', array_merge(['None' => ''], static::getChildThemes($config('theme.rootDir'))));
    }

    public static function loadModules(Config $config, array $modules)
    {
        if (!$config('theme.childDir')) {
            return $modules;
        }

        foreach ($modules as $module) {

            $params = json_decode($module->params);
            $layout = isset($params->layout) && is_string($params->layout) ? str_replace('_:', '', $params->layout) : 'default';

            if (file_exists("{$config('theme.childDir')}/html/{$module->module}/{$layout}.php")) {
                $params->layout = basename($config('theme.childDir')) . ":{$layout}";
                $module->params = json_encode($params);
            }
        }

        return $modules;
    }

    public static function afterDispatch(Config $config)
    {
        if (!$config('app.isAdmin') && $config('theme.childDir') && file_exists($file = "{$config('theme.childDir')}/{$config('joomla.config.themeFile', 'index.php')}")) {
            $config('joomla.config')->set('theme', basename(dirname($file)));
        }
    }

    public static function getChildThemes($root)
    {
        $dir = dirname($root);
        $name = basename($root);
        $themes = [];

        foreach (glob("{$dir}/{$name}_*") as $child) {
            $child = str_replace("{$name}_", '', basename($child));
            $themes[ucfirst($child)] = $child;
        }

        return $themes;
    }
}
