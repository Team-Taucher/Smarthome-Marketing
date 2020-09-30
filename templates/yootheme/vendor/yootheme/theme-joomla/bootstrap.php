<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Language\Text;
use YOOtheme\Application;
use YOOtheme\Config;
use YOOtheme\Path;
use YOOtheme\Theme\SystemCheck as SysCheck;
use YOOtheme\View;

/** @var Application $app */
return [

    'theme' => function (Config $config) {
        return $config->loadFile(Path::get('./config/theme.json'));
    },

    'routes' => [
        ['get', '/customizer', [CustomizerController::class, 'index'], ['customizer' => true]],
        ['post', '/customizer', [CustomizerController::class, 'save']],
    ],

    'events' => [

        'app.error' => [
            SystemListener::class => 'redirectLogin',
        ],

        'app.request' => [
            SystemListener::class => [['expiresHeader'], ['checkPermission']],
        ],

        'url.resolve' => [
            UrlListener::class => 'routeQueryParams',
        ],

        'theme.init' => [
            ChildThemeListener::class => ['initTheme', -10],
            CustomizerListener::class => ['initTheme', -20],
        ],

        'customizer.init' => [
            ChildThemeListener::class => ['initCustomizer', 20],
            CustomizerListener::class => ['initCustomizer', 10],
        ],

        'modules.load' => [
            ChildThemeListener::class => ['loadModules', -5],
        ],

    ],

    'actions' => [

        'onAfterDispatch' => [
            ThemeListener::class => 'afterDispatch',
            ChildThemeListener::class => 'afterDispatch',
        ],

        'onContentPrepareData' => [
            CustomizerListener::class => 'prepareData',
        ],

        'onBeforeCompileHead' => [
            CustomizerListener::class => 'compileHead',
        ],

    ],

    'extend' => [

        View::class => function (View $view, $app) {

            if (!$app->config->get('~theme')) {
                return;
            }

            $view->addLoader([ViewLoader::class, 'loadArticle'], '~theme/templates/article*');
            $view->addLoader([$app(UrlListener::class), 'resolveRelativeUrl']);
            $view->addFunction('trans', [Text::class, '_']);
        },

    ],

    'services' => [

        ThemeLoader::class => '',
        SysCheck::class => SystemCheck::class,

    ],

    'loaders' => [

        'theme' => $app->wrap(ThemeLoader::class, [], false),

    ],

];
