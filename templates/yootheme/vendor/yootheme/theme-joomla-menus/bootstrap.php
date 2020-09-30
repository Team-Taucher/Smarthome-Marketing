<?php

namespace YOOtheme\Theme\Joomla;

return [

    'routes' => [
        ['get', '/items', [MenusController::class, 'getItems']],
    ],

    'events' => [

        'modules.load' => [
            MenusListener::class => 'loadModules',
        ],

        'customizer.init' => [
            MenusListener::class => 'initCustomizer',
        ],

    ],

];
