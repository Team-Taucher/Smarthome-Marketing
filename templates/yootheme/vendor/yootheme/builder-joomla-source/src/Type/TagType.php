<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

class TagType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [

            'fields' => [

                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'filters' => ['limit'],
                    ],
                ],

                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],

                'hits' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Hits',
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Link',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::link',
                    ],
                ],

            ],

            'metadata' => [
                'type' => true,
                'label' => 'Tag',
            ],

        ];
    }

    public static function link($tag)
    {
        \JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');

        return \TagsHelperRoute::getTagRoute("{$tag->tag_id}:{$tag->alias}");
    }
}
