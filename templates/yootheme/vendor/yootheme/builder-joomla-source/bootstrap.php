<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use YOOtheme\Builder;
use YOOtheme\Builder\Source\SourceTransform;
use YOOtheme\Path;

return [

    'routes' => [
        ['get', '/joomla/articles', [SourceController::class, 'articles']],
    ],

    'actions' => [

        'onContentPrepare' => [
            TemplateListener::class => ['onPrepareContent', -10],
        ],

    ],

    'events' => [

        'source.init' => [
            SourceListener::class => 'initSource',
        ],

        'customizer.init' => [
            SourceListener::class => 'initCustomizer',
        ],

        'builder.template' => [
            TemplateListener::class => ['onTemplate', 5],
        ],

    ],

    'extend' => [

        Builder::class => function (Builder $builder) {
            $builder->addTypePath(Path::get('./elements/*/element.json'));
        },

        SourceTransform::class => function (SourceTransform $transform, $app) {

            $transform->addFilter('date', function ($value, $format) {
                return HTMLHelper::_('date', $value, $format ?: Text::_('DATE_FORMAT_LC3'));
            });

        },

    ],

];
