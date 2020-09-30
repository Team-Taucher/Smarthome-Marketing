<?php

namespace YOOtheme\Theme\Joomla;

return [

    'events' => [

        'customizer.init' => [
            EditorListener::class => 'initCustomizer',
        ],

    ],

];
