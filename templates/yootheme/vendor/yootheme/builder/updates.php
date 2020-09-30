<?php

namespace YOOtheme;

/**
 * @var Config $config
 */
$config = app(Config::class);

return [

    '2.1.1.1' => function ($node, array $params) use ($config) {

        list($style) = explode(':', $config('~theme.style'));

        if (in_array($style, ['horizon'])) {

            if ((@$node->props['title_style'] === 'h6' || (@$node->props['title_element'] === 'h6' && empty(@$node->props['title_style']))) && empty(@$node->props['title_color'])) {
                $node->props['title_color'] = 'primary';
            }

        }

        if (in_array($style, ['fjord'])) {

            if ((@$node->props['title_style'] === 'h4' || (@$node->props['title_element'] === 'h4' && empty(@$node->props['title_style']))) && empty(@$node->props['title_color'])) {
                $node->props['title_color'] = 'primary';
            }

        }

    },

    '2.1.0-beta.0.1' => function ($node, array $params) {

        /**
         * @var $type
         */
        extract($params);

        if (@$node->props['maxwidth'] === 'xxlarge') {
            $node->props['maxwidth'] = '2xlarge';
        }

        // move declaration of uk-hidden class to visibility settings
        if ($type->element && empty($node->props['visibility']) && !empty($node->props['class'])) {
            $node->props['class'] = trim(preg_replace_callback('/(^|\s+)uk-hidden@(s|m|l|xl)/', function ($match) use ($node) {
                $node->props['visibility'] = 'hidden-' . $match[2];
                return '';
            }, $node->props['class']));
        }

    },

];
