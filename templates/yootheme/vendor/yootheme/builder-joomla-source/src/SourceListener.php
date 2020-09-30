<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\HTML\HTMLHelper;
use YOOtheme\Builder\Source\Type\SiteType;
use YOOtheme\Config;

class SourceListener
{
    public static function initSource($source)
    {
        $query = [
            Type\SiteQueryType::config(),
            Type\ArticleQueryType::config(),
            Type\CategoryQueryType::config(),
            Type\ArticlesQueryType::config(),
            Type\CustomArticleQueryType::config(),
            Type\CustomArticlesQueryType::config(),
            Type\CustomCategoryQueryType::config(),
            Type\CustomCategoriesQueryType::config(),
        ];

        $types = [
            ['Article', Type\ArticleType::config()],
            ['ArticleEvent', Type\ArticleEventType::config()],
            ['ArticleImages', Type\ArticleImagesType::config()],
            ['ArticleUrls', Type\ArticleUrlsType::config()],
            ['Category', Type\CategoryType::config()],
            ['CategoryParams', Type\CategoryParamsType::config()],
            ['Site', SiteType::config()],
            ['Tag', Type\TagType::config()],
            ['User', Type\UserType::config()],
        ];

        foreach ($query as $args) {
            $source->queryType($args);
        }

        foreach ($types as $args) {
            $source->objectType(...$args);
        }
    }

    public static function initCustomizer(Config $config)
    {
        $templates = [

            'com_content.article' => [
                'label' => 'Single Article',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'catid' => $category = [
                                'label' => 'Limit by Categories',
                                'description' => 'The template is only assigned to articles from the selected categories. Articles from child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.',
                                'type' => 'select-category',
                                'default' => [],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                            ],
                            'tag' => $tag = [
                                'label' => 'Limit by Tags',
                                'description' => 'The template is only assigned to articles with the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.',
                                'type' => 'select-tag',
                                'default' => [],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            'com_content.category' => [
                'label' => 'Category Blog',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'catid' => [
                                'label' => 'Limit by Categories',
                                'description' => 'The template is only assigned to the selected categories. Child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.',
                            ] + $category,
                            'tag' => ['description' => 'The template is only assigned to categories with the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.'] + $tag,
                        ],
                    ],
                ],
            ],

            'com_content.featured' => [
                'label' => 'Featured Articles',
            ],

        ];

        $config->add('customizer.templates', $templates);

        $config->add('customizer.categories', array_map(function ($category) {
            return [$category->value, $category->text];
        }, HTMLHelper::_('category.options', 'com_content')));

        $config->add('customizer.tags', array_map(function ($tag) {
            return [$tag->value, $tag->text];
        }, HTMLHelper::_('tag.options')));

        $config->add(
            'customizer.sections.builder-templates.fieldset.default.fields.type.options',
            array_combine(array_column($templates, 'label'), array_keys($templates))
        );

    }
}
