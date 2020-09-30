<?php

JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');

if ($args['show_link'] && $args['link_style']) {
    echo '<span class="uk-' . $args['link_style'] . '">';
}

echo implode($args['separator'], array_map(function ($tag) use ($args) {

    if (empty($args['show_link'])) {
        return $tag->title;
    }

    $route = TagsHelperRoute::getTagRoute("{$tag->tag_id}:{$tag->alias}");

    return "<a href=\"{$route}\">{$tag->title}</a>";

}, $tags));

if ($args['show_link'] && $args['link_style']) {
    echo '</span>';
}
