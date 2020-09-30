<?php

use Joomla\CMS\Factory;

return [

    'transforms' => [

        'render' => function ($node) {

            $document = Factory::getDocument();
            $renderer = $document->loadRenderer('modules');
            $position = isset($node->props['content']) ? $node->props['content'] : '';

            // render module position
            if ($position && $document->countModules($position)) {
                $node->content = $renderer->render($position, [
                    'name' => $position,
                    'style' => 'grid' . ($node->props['layout'] === 'stack' ? '-stack' : ''),
                    'position' => $node->props, // pass grid settings to templates/position.php
                ]);
            }

            // return false, if no module position content was found
            if (empty($node->content)) {
                return false;
            }

        },

    ],

    'updates' => [

        '1.22.0-beta.0.1' => function ($node) {

            if (isset($node->props['grid_gutter'])) {
                $node->props['column_gap'] = $node->props['grid_gutter'];
                $node->props['row_gap'] = $node->props['grid_gutter'];
                unset($node->props['grid_gutter']);
            }

            if (isset($node->props['grid_divider'])) {
                $node->props['divider'] = $node->props['grid_divider'];
                unset($node->props['grid_divider']);
            }

        },

        '1.20.0-beta.1.1' => function ($node) {

            if (isset($node->props['maxwidth_align'])) {
                $node->props['block_align'] = $node->props['maxwidth_align'];
                unset($node->props['maxwidth_align']);
            }

        },

    ],

];
